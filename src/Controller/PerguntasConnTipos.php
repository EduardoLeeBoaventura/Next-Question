<?php

namespace System\Controller;

require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "resources" . DIRECTORY_SEPARATOR . "system_functions.php";
require_once returnsPathFromHost("src", "Model", "database-handler-php", "Handlers", "SQL_CRUD.php");

use System\Model\TiposConnPerguntas as ModelPerguntasConnTipos;
use System\Controller\Tipos;
use System\Controller\Pergunta;

class PerguntasConnTipos
{
  private $model = null;

  public function __construct()
  {
    $this->model = new ModelPerguntasConnTipos(DB_HOST, DB_USER, DB_PASS, DB_NAME);
  }

  public function criar($perguntas, $tipos)
  {
    $tipos      = new Tipos();
    $perguntas  = new Pergunta();
    $data = [
      "id_tipo"   => $tipos->returnsIdByRef($tipos),
      "id_pergunta" => $perguntas->returnsIdByRef($perguntas)
    ];
    $response = $this->model->insert($data);
    return $response->result;
  }

  public function atualizar($data, $conditions)
  {
    $response = $this->model->update($data, $conditions);

    return $response;
  }

  public function excluir($tipo, $pergunta)
  {
    $conditions = [
      " EXISTS(SELECT * FROM tipos TP WHERE TP.id = tipos_conn_perguntas.id_tipo AND TP.ref = '$tipo') ",
      " EXISTS(SELECT * FROM perguntas P WHERE P.id = tipos_conn_perguntas.id_pergunta AND P.ref = '$pergunta') "
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
      "T.nome",
      "P.pergunta"
    ];    

    $response = $this->model->select($columns, null, null, null, null, $limit_min, $limit_max);

    return $response->result;
  }
}
