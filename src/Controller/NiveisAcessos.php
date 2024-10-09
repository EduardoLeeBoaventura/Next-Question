<?php

namespace System\Controller;

require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "resources" . DIRECTORY_SEPARATOR . "system_functions.php";
require_once returnsPathFromHost("src", "Model", "database-handler-php", "Handlers", "SQL_CRUD.php");

use System\Controller\NiveisPrivilegios;

use System\Model\NiveisAcessos as ModelNiveisAcessos;

class NiveisAcessos
{
  private $model = null;

  public function __construct()
  {
    $this->model = new ModelNiveisAcessos(DB_HOST, DB_USER, DB_PASS, DB_NAME);
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
      ["visibilidade", 1],
    ];

    if (!empty($conditions)) {
      array_push($where, ...$conditions);
    }

    $response = $this->model->select("*", $where, null, null, null, $limit_min, $limit_max);

    if($response->result !== false){
      $privileges_handler = new NiveisPrivilegios();

      foreach($response->result as $k => $value){
        $privileges = $privileges_handler->listar([["id_nivel", $value['id']]]);

        if($privileges === false){
          $privileges = [];
        }

        $response->result[$k]['privilegios'] = $privileges;
      }
    } else {
      $_SESSION['ref_log'] = $response->ref_log;
    }
    
    $response = $response->result;

    return $response;
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
