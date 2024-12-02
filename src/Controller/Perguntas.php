<?php

namespace System\Controller;

require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "resources" . DIRECTORY_SEPARATOR . "system_functions.php";

use System\Model\Perguntas as ModelPerguntas;

use System\Controller\PerguntasConnCategorias;
use System\Controller\Categorias;
use System\Controller\Alternativas;

class Perguntas
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
      "visibilidade"
    ];


    $conditions_perguntas = [
      ["visibilidade", 1]
    ];

    if(!empty($conditions)){
      array_push($conditions_perguntas, ...$conditions);
    }

    $perguntas = $this->model->select($columns, $conditions, null, null, null, $limit_min, $limit_max);
    $response = $perguntas->result;

    $response = false;
    if($perguntas->result !== false && arrayLength($perguntas->result) > 0){
      $response = $perguntas->result;

      foreach ($response as $key => $value) {
        $alternativas_pergunta = new Alternativas();
        $conditionsalt = [
          ['id_pergunta', $response[$key]['id']]
        ];
        $alternativas = $alternativas_pergunta->listar($conditionsalt);
  
        
        $response[$key]['alternativas'] = $alternativas;


        $perguntas_conn_categorias_handler = new PerguntasConnCategorias();
        $conditions_categorias = [
          ["id_pergunta", $response[$key]['id']]
        ];
        $categorias_pergunta = $perguntas_conn_categorias_handler->listar(null, $conditions_categorias);
        // dumpdie(true, $response);
        $response[$key]['categorias'] = $categorias_pergunta;
      }
      
    }
    dumpdie(true, $response);

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