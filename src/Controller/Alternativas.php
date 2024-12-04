<?php

namespace System\Controller;

require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "resources" . DIRECTORY_SEPARATOR . "system_functions.php";

use System\Model\Alternativas as ModelAlternativas;

class Alternativas
{
  private $model = null;

  public function __construct()
  {
    $this->model = new ModelAlternativas(DB_HOST, DB_USER, DB_PASS, DB_NAME);
  }

  public function criar($data)
{
    $alternativas = $data["alternativa"];
    foreach ($alternativas as $alternativa) {
        $final_data = [
            "id_pergunta" => $data["id_pergunta"],
            "alternativa" => $alternativa,
            "gabarito" => $data["gabarito"]
        ];
        $response = $this->model->insert($final_data);

        if($response === false){
          $response->result = false;
          break;
        }
    }

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
      "alternativa",
      "numero_alternativa",
      "gabarito",
    ];


    $conditions_alternativas = [
      ["visibilidade", 1]
    ];

    if(!empty($conditions)){
      array_push($conditions_alternativas, ...$conditions);
    }

    $alternativas = $this->model->select($columns, $conditions, null, null, null, $limit_min, $limit_max);
    // dumpDie(true, $alternativas);
    $response = $alternativas->result;


    if($alternativas->result !== false && arrayLength($alternativas->result) > 0){
      $response = $alternativas->result;

    } else{
      $response = false;
    }
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