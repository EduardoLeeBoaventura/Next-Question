<?php

namespace System\Controller;

require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "resources" . DIRECTORY_SEPARATOR . "system_functions.php";
require_once returnsPathFromHost("src", "Model", "database-handler-php", "Handlers", "SQL_CRUD.php");

use System\Model\Usuarios as ModelUsuarios;
use Handlers\SQL_CRUD;

use System\Controller\UsuariosPrivilegios;
use System\Controller\UsuariosConnNiveis;

class Usuarios
{
  private $model = null;

  public function __construct()
  {
    $this->model = new ModelUsuarios(DB_HOST, DB_USER, DB_PASS, DB_NAME);
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

  public function login($email, $senha)
  {
    $columns = ["id", "ref", "nome", "email", "senha"];
    $where   = [
      ["email", $email],
      ["situacao", "ATIVO"]
    ];

    $response = $this->model->select($columns, $where);

    if ($response->result !== false && arrayLength($response->result) > 0 && password_verify($senha, $response->result[0]['senha'])) {
      $get_user = $response->result[0];

      $_SESSION['id_usuario']   = $get_user['id'];
      $_SESSION['usuario']      = $get_user['nome'];
      $_SESSION['email']        = $get_user['email'];

      return true;
    } else {
      return false;
    }
  }
  
  public function autorizarAcao($senha)
  {
    if(empty($_SESSION['id_usuario']) || empty($senha)){
      return false;
    }

    $columns = ["id", "ref", "nome", "email", "senha"];
    $where   = [
      ["id", @$_SESSION['id_usuario']],
      ["situacao", "ATIVO"],
      ["visibilidade", 1]
    ];

    $response = $this->model->select($columns, $where);

    if ($response->result !== false && arrayLength($response->result) > 0 && password_verify($senha, $response->result[0]['senha'])) {
      return true;
    } else {
      return false;
    }
  }

  private function returnsNiveisPrivilegios($niveis_info){
    $niveis_privilegios = [];
    foreach($niveis_info as $nivel_info){
      $privilegios = $this->handlePrivilegios($nivel_info['privilegios']);
      $niveis_privilegios = $this->mergePrivileges($niveis_privilegios, $privilegios);
    }
    return $niveis_privilegios;
  }

  private function handlePrivilegios($privilegios){
    $new_privilegios = [];
    if(is_array($privilegios)){
      foreach($privilegios as $v){
        $organized_privilege = $this->organizePrivilege($v);
        $new_privilegios     = $this->mergePrivileges($new_privilegios, $organized_privilege);
      }
    } else {
      $new_privilegios = [];
    }

    return $new_privilegios;
  }

  private function returnsTipoPrivilegioOposto($tipo){
    return $tipo == 'BLOQUEAR' ? 'AUTORIZAR' : 'BLOQUEAR';
  }

  private function organizePrivilege($new_privilege){
    $pagina = $new_privilege['pagina'];
    $tipo   = $new_privilege['tipo'];
    
    $organized_privilege[$pagina] = [
      $tipo => explode("|", $new_privilege['permissoes']),
    ];

    return $organized_privilege;
  }

  private function mergePrivileges($privilegios, $privilegios_final){
    foreach($privilegios as $pagina => $privilege){
      if(empty($privilegios_final[$pagina])){
        $privilegios_final[$pagina] =  $privilege;

      } else {
        foreach($privilegios[$pagina] as $tipo => $permissoes){
          $tipo_oposto = $this->returnsTipoPrivilegioOposto($tipo);

          if(empty($privilegios_final[$pagina][$tipo])){
            $privilegios_final[$pagina][$tipo] = [];

          }

          foreach($privilegios[$pagina][$tipo] as $permissao){
            $nao_tem_permissao   = !in_array($permissao, $privilegios_final[$pagina][$tipo]);
            $existe_tipo_oposto  = !empty($privilegios_final[$pagina][$tipo_oposto]) && is_array($privilegios_final[$pagina][$tipo_oposto]);
            $tem_adversativa     = $existe_tipo_oposto && in_array($permissao, $privilegios_final[$pagina][$tipo_oposto]);
            $nao_tem_adversativa = !$existe_tipo_oposto || !$tem_adversativa;

            if($nao_tem_permissao && $nao_tem_adversativa){
              array_push($privilegios_final[$pagina][$tipo], $permissao);
            }
          }
        }
      }

      foreach($privilegios_final[$pagina] as $tipo => $permissoes){
        if(empty($privilegios_final[$pagina][$tipo])){
          unset($privilegios_final[$pagina][$tipo]);
        }
      }
    }

    return $privilegios_final;
  }

  public function listar($conditions = null, $limit_min = 100, $limit_max = null)
  {
    $columns = [
      "id",
      "ref",
      "nome",
      "email",
      "senha",
      "telefone",
      "cpf",
      "geral",
      "desenvolvedor",
      "situacao",
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
        $usuarios_privilegios_handler = new UsuariosPrivilegios();
        $conditions = [
          ["id_usuario", $value['id']],
          ["UP.situacao", "ATIVO"]
        ];
        $privilegios = $usuarios_privilegios_handler->listar($conditions);
        $privilegios = $this->handlePrivilegios($privilegios);

        $usuarios_nivel_handler = new UsuariosConnNiveis();
        $conditions = [
          ["UCN.id_usuario", $value['id']],
          ["N.situacao", "ATIVO"]
        ];
        $niveis_acessos = $usuarios_nivel_handler->listar($conditions);
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
