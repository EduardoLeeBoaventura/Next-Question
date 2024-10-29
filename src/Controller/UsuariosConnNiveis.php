<?php

namespace System\Controller;

require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "resources" . DIRECTORY_SEPARATOR . "system_functions.php";

use System\Model\UsuariosConnNiveis as ModelUsuariosConnNiveis;
use System\Controller\NiveisAcessos;
use System\Controller\Usuarios;

class UsuariosConnNiveis
{
  private $model = null;

  public function __construct()
  {
    $this->model = new ModelUsuariosConnNiveis(DB_HOST, DB_USER, DB_PASS, DB_NAME);
  }

  public function criar($usuario, $nivel)
  {
    $niveis_acessos_handler       = new NiveisAcessos();
    $usuarios_handler             = new Usuarios();

    $data = [
      "id_nivel"   => $niveis_acessos_handler->returnsIdByRef($nivel),
      "id_usuario" => $usuarios_handler->returnsIdByRef($usuario)
    ];
    $response = $this->model->insert($data);
    return $response->result;
  }

  public function atualizar($data, $conditions)
  {
    $response = $this->model->update($data, $conditions);

    return $response;
  }

  public function excluir($nivel, $usuario)
  {
    $conditions = [
      " EXISTS(SELECT * FROM niveis_acessos NA WHERE NA.id = usuarios_conn_niveis.id_nivel AND NA.ref = '$nivel') ",
      " EXISTS(SELECT * FROM usuarios U WHERE U.id = usuarios_conn_niveis.id_usuario AND U.ref = '$usuario') "
    ];

    $response = $this->model->delete($conditions);

    if($response->result === false){
      $_SESSION["ref_log"] = false;
    }

    return $response->result;
  }

  public function listar($conditions = null, $limit_min = 100, $limit_max = null)
  {
    $columns = [
      "N.id",
      "N.ref",
      "N.nome nivel"
    ];

    $where = [
      ["N.visibilidade", 1]
    ];

    if(!empty($conditions)){
      array_push($where, ...$conditions);
    }

    $response = $this->model->select($columns, $where, null, null, null, $limit_min, $limit_max);

    if($response->result === false){
      $_SESSION["ref_log"] = false;
    } else {
      foreach($response->result as $k => $v){
        $niveis_acessos_handler = new NiveisAcessos();
        $nivel = $niveis_acessos_handler->listar([["ref", $v['ref']]]);

        $response->result[$k] = $nivel[0];
      }
    }

    return $response->result;
  }
}
