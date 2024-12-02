<?php

namespace System\Controller;

require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "resources" . DIRECTORY_SEPARATOR . "system_functions.php";
require_once returnsPathFromHost("src", "Model", "database-handler-php", "Handlers", "SQL_CRUD.php");

use System\Model\CategoriasConnPerguntas as ModelCategoriasConnPerguntas;
use System\Controller\Categorias;
use System\Controller\Questionario;

class CategoriasConnQuestionario
{
  private $model = null;

  public function __construct()
  {
    $this->model = new ModelCategoriasConnPerguntas(DB_HOST, DB_USER, DB_PASS, DB_NAME);
  }

  public function criar($questionario_id_ref, $categoria_id_ref, $ID = true)
  {
    if(!$ID){
      $categoria = new Categorias();
      $questionario  = new Questionario();
      $data = [
        "id_categoria"   => $categoria->returnsIdByRef($categoria_id_ref),
        "id_questionario" => $questionario->returnsIdByRef($questionario_id_ref)
      ];
    } else{
      $data = [
        "id_categoria"   => $categoria_id_ref,
        "id_questionario" => $questionario_id_ref
      ];
    }
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
      " EXISTS(SELECT * FROM categorias C WHERE C.id = categorias_conn_questionario.id_categoria AND C.ref = '$categoria') ",
      " EXISTS(SELECT * FROM questionario Q WHERE Q.id = categorias_conn_questionario.id_questionario AND Q.ref = '$questionario') "
    ];

    $response = $this->model->delete($conditions);

    if($response->result === false){
      $_SESSION["ref_log"] = false;
    }

    return $response->result;
  }

  public function listar($limit_min = 100, $limit_max = null)
  {
    $columns = [
      "Q.id",
      "Q.nome",
      "C.id",
      "C.nome",
    ];

    $response = $this->model->select($columns, null, null, null, null, $limit_min, $limit_max);

    return $response->result;
  }
}
