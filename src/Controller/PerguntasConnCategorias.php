<?php

namespace System\Controller;

require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "resources" . DIRECTORY_SEPARATOR . "system_functions.php";
require_once returnsPathFromHost("src", "Model", "database-handler-php", "Handlers", "SQL_CRUD.php");

use System\Model\CategoriasConnPerguntas as ModelPerguntasConnCategorias;

class PerguntasConnCategorias
{
  private $model = null;

  public function __construct()
  {
    $this->model = new ModelPerguntasConnCategorias(DB_HOST, DB_USER, DB_PASS, DB_NAME);
  }

  public function criar($pergunta, $categoria)
  {
    $data = [
      "id_categoria" => $categoria,
      "id_pergunta"  => $pergunta
    ];
    $response = $this->model->insert($data);
    return $response->result;
  }

  public function atualizar($data, $conditions)
  {
    $response = $this->model->update($data, $conditions);

    return $response;
  }

  public function excluir($categorias, $perguntas)
  {
    $conditions = [
      " EXISTS(SELECT * FROM tipos TP WHERE TP.id = tipos_conn_perguntas.id_tipo AND TP.ref = '$categorias') ",
      " EXISTS(SELECT * FROM perguntas P WHERE P.id = tipos_conn_perguntas.id_pergunta AND P.ref = '$perguntas') "
    ];

    $response = $this->model->delete($conditions);

    if($response->result === false){
      $_SESSION["ref_log"] = false;
    }

    return $response->result;
  }

  public function listar($columns = null, $conditions = null, $limit_min = 100, $limit_max = null)
  {
    if(empty($columns)) {
      $columns = [
        "C.nome",
        "P.pergunta"
      ];
    }

    $response = $this->model->select($columns, $conditions, null, null, null, $limit_min, $limit_max);

    return $response->result;
  }
}
