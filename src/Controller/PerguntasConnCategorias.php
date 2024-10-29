<?php

namespace System\Controller;

require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "resources" . DIRECTORY_SEPARATOR . "system_functions.php";
require_once returnsPathFromHost("src", "Model", "database-handler-php", "Handlers", "SQL_CRUD.php");

use System\Model\CategoriasConnPerguntas as ModelPerguntasConnCategorias;
use System\Controller\Categorias;
use System\Controller\Pergunta;

class PerguntasConnCategorias
{
  private $model = null;

  public function __construct()
  {
    $this->model = new ModelPerguntasConnCategorias(DB_HOST, DB_USER, DB_PASS, DB_NAME);
  }

  public function criar($perguntas, $categorias)
  {
    $categoria = new Categorias();
    $pergunta  = new Pergunta();
    $data = [
      "id_categoria"   => $categoria->returnsIdByRef($categorias),
      "id_pergunta" => $pergunta->returnsIdByRef($perguntas)
    ];
    $response = $this->model->insert($data);
    return $response->result;
  }

  public function atualizar($data, $conditions)
  {
    $response = $this->model->update($data, $conditions);

    return $response;
  }

  public function excluir($categoria, $pergunta)
  {
    $conditions = [
      " EXISTS(SELECT * FROM categorias C WHERE C.id = categorias_conn_perguntas.id_tipo AND C.ref = '$categoria') ",
      " EXISTS(SELECT * FROM perguntas P WHERE P.id = tipos_conn_perguntas.id_pergunta AND P.ref = '$pergunta') "
    ];

    $response = $this->model->delete($conditions);

    if($response->result === false){
      $_SESSION["ref_log"] = false;
    }

    return $response->result;
  }

  public function listar($columns = null, $limit_min = 100, $limit_max = null)
  {
    if(empty($columns)) {
      $columns = [
        "T.nome",
        "P.pergunta"
      ];
    }

    $response = $this->model->select($columns, null, null, null, null, $limit_min, $limit_max);

    return $response->result;
  }
}
