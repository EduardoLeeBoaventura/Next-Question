<?php

namespace System\Controller;

require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "resources" . DIRECTORY_SEPARATOR . "system_functions.php";
require_once returnsPathFromHost("src", "Model", "database-handler-php", "Handlers", "SQL_CRUD.php");

use System\Model\Perguntas as ModelPerguntas;
use Handlers\SQL_CRUD;

use System\Controller\Tipos;
use System\Controller\PerguntasConnTipos;

class Perguntas
{
  private $model = null;

  public function __construct()
  {
    $this->model = new ModelPerguntas(DB_HOST, DB_USER, DB_PASS, DB_NAME);
  }

  public function criar($data)
  {
    $response = $this->model->insert($data);
    return $response;
  }

  public function atualizar($data, $conditions)
  {
    $response = $this->model->update($data, $conditions);

    return $response;
  }

  public function excluir($ref_registro)
  {
    $conditions = [
      ["ref", $ref_registro]
    ];

    return $this->model->delete($conditions);
  }

  public function listar($conditions = null, $limit_min = 100, $limit_max = null)
  {
    $columns = [
      "id",
      "ref",
      "pergunta",
      "tipo",
      "opcoes",
      "coption",
      "visibilidade",
    ];

    $conditions_usuarios = [
      ["visibilidade", 1]
    ];

    if(!empty($conditions)){
      array_push($conditions_usuarios, ...$conditions);
    }

    $usuarios = $this->model->select($columns, $conditions, null, null, null, $limit_min, $limit_max);

    $response = false;
    if($usuarios->result !== false && arrayLength($usuarios->result) > 0){
      $response = $usuarios->result;

      foreach ($response as $key => $value) {
        $tipos = new Tipos();
        $conditions = [
          ["id_pergunta", $value['id']]
        ];
        $privilegios = $tipos->listar($conditions);

        $perguntas_conn_tipos = new PerguntasConnTipos();
        $conditions = [
          ["perguntas_conn_tipos.id_tipo", $value['id']],
          ["N.situacao", "ATIVO"]
        ];
        $niveis_acessos = $perguntas_conn_tipos->listar($conditions);
        $niveis_privilegios = $this->returnsNiveisPrivilegios($niveis_acessos);

        $privilegios = $this->mergePrivileges($niveis_privilegios, $privilegios);
        
        $response[$key]['privilegios'] = $privilegios;
      }
    }
    
    return $response;
  }

  public function listarParaConsulta($conditions = null, $limit_min = 100, $limit_max = null)
  {
    $usuarios = $this->listar($conditions, $limit_min, $limit_max);

    $response = false;
    if(is_array($usuarios) && arrayLength($usuarios) > 0){
      $response = $usuarios[0];
    }

    return $response;
  }

  public function hideData($data)
  {
    $sub_data = @substr(@$data, -5);

    return $sub_data;
  }

  public function listarParaCertificado($usuario){
    $conditions = [
      ["ref", $usuario]
    ];

    $columns = [
      "nome",
      "cpf",
      "telefone"
    ];
    
    $response = $this->model->select($columns, $conditions);

    return $response->result !== false ? $response->result : false;
  }

  public function alterarSenha($senha, $senha_autorizacao, $conditions){
    if(password_verify($senha_autorizacao, USER_INFO['senha'])){
      $data = [
        "senha" => password_hash($senha, PASSWORD_DEFAULT)
      ];

      $response = $this->model->update($data, $conditions);

      if(!empty($response->ref_log)){
        $_SESSION['ref_log'] = $response->ref_log;
        return false;
      } else {
        return true;
      }

    } else {
      $_SESSION['ref_log'] = "senha de autorização invalida";

      return false;
    }
  }

  public function returnsIdByRef($ref)
  {
    $where = [
      ["ref", $ref],
      ["visibilidade", 1]
    ];

    $response = $this->model->select('id', $where);

    return $response->result != false ? $response->result[0]['id'] : false;
  }
}
