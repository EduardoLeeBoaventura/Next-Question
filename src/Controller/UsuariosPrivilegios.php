<?php

namespace System\Controller;

require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "resources" . DIRECTORY_SEPARATOR . "system_functions.php";
require_once returnsPathFromHost("src", "Model", "database-handler-php", "Handlers", "SQL_CRUD.php");

use System\Model\UsuariosPrivilegios as ModelUsuariosPrivilegios;
use System\Controller\Usuarios;

class UsuariosPrivilegios
{
  private $model = null;

  public function __construct()
  {
    $this->model = new ModelUsuariosPrivilegios(DB_HOST, DB_USER, DB_PASS, DB_NAME);
  }

  public function criar($data)
  {
    $usuarios_handler = new Usuarios();

    $data_insert = [
      "id_usuario" => $usuarios_handler->returnsIdByRef($data['usuario']),
      "pagina" => $data['pagina'],
      "tipo" => $data['tipo'],
      "permissoes" => $data['permissoes']
    ];
  
    $response = $this->model->insert($data_insert);
    return $response;
  }

  public function atualizar($data, $conditions)
  {
    $response = $this->model->update($data, $conditions);

    return $response;
  }

  public function excluir($ref_registro)
  {
    $data = [
      "visibilidade" => 0
    ];

    $conditions = [
      ["ref", $ref_registro]
    ];

    $response = $this->model->update($data, $conditions);
    if($response->result === false){
      $_SESSION["ref_log"] = $response->ref_log;
    }

    return $response->result;
  }

  public function listar($conditions = null, $limit_min = 100, $limit_max = null)
  {
    $where = [
      ["UP.visibilidade", 1],
    ];

    if (!empty($conditions)) {
      array_push($where, ...$conditions);
    }

    $response = $this->model->select("UP.*, U.ref usuario", $where, null, null, null, $limit_min, $limit_max);

    if($response->result === false){
      $_SESSION['ref_log'] = $response->ref_log;
    }
    
    $response = $response->result;

    return $response;
  }

  public function listarAcoesPrivilegio($privilegio){
    $routes = readRoutesJson();
    $route_info = returnsRouteInfoByPage($routes, $privilegio);
    
    if(!empty($route_info['privilege'])){
      if(mb_strpos($route_info['privilege'], '|') !== false){
        $response = explode("|", $route_info['privilege']);
      } else {
        $response = [$route_info['privilege']];
      }
    }

    return $response;
  }
}
