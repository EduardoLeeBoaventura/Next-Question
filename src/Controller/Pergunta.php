<?php

namespace System\Controller;

require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "resources" . DIRECTORY_SEPARATOR . "system_functions.php";
require_once returnsPathFromHost("src", "Model", "database-handler-php", "Handlers", "SQL_CRUD.php");

use System\Model\Perguntas as ModelPerguntas;
use Handlers\SQL_CRUD;

use System\Controller\Tipos;
use System\Controller\PerguntasConnTipos;

class Pergunta
{
  private $model = null;

  public function __construct()
  {
    $this->model = new ModelPerguntas(DB_HOST, DB_USER, DB_PASS, DB_NAME);
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

  public function listar($conditions = null, $limit_min = 100, $limit_max = null)
  {
    $columns = [
      "id",
      "ref",
      "pergunta",
      "tipo",
      "opcoes",
      "coption",
      "visibilidade"
    ];

    $perguntas = $this->model->select($columns, $conditions, null, null, null, $limit_min, $limit_max);
    $response = $perguntas->result;
    
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
