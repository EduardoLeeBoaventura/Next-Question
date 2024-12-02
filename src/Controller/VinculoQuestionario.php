<?php

namespace System\Controller;

require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "resources" . DIRECTORY_SEPARATOR . "system_functions.php";

use System\Model\VinculoQuestionario as ModelVinculoQuestionario;

use System\Controller\Questionario;
use System\Controller\Categorias;

class VinculoQuestionario
{
  private $model = null;

  public function __construct()
  {
    $this->model = new ModelVinculoQuestionario(DB_HOST, DB_USER, DB_PASS, DB_NAME);
  }

  public function criar($questionario, $categoria)
  {
    $categorias_handler       = new Categorias();
    $questionario_handler             = new Questionario();

    $data = [
      "id_categoria"   => $categorias_handler->returnsIdByRef($categoria),
      "id_questionario" => $questionario_handler->returnsIdByRef($questionario)
    ];
    $response = $this->model->insert($data);
    return $response->result;
  }

  public function atualizar($data, $conditions)
  {
    $response = $this->model->update($data, $conditions);

    return $response;
  }

  public function excluir($categoria, $questionario)
  {
    $conditions = [
      " EXISTS(SELECT * FROM categorias C WHERE C.id = vinculo_questionario.id_categoria AND C.ref = '$categoria') ",
      " EXISTS(SELECT * FROM usuarios Q WHERE U.id = vinculo_questionario.id_questionario AND Q.ref = '$questionario') "
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
      "c.nome",
    ];

    $where = [
      ["C.visibilidade", 1]
    ];

    if(!empty($conditions)){
      array_push($where, ...$conditions);
    }

    $response = $this->model->select($columns, $where, null, null, null, $limit_min, $limit_max);
    // dumpDie(true, $response);
    if($response->result === false){
      $_SESSION["ref_log"] = false;
    }

    return $response->result;
  }

}

