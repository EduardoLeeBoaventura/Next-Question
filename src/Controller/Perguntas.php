<?php
namespace System\Controller;

require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "resources" . DIRECTORY_SEPARATOR . "system_functions.php";
require_once returnsPathFromHost("src", "Model", "database-handler-php", "Handlers", "SQL_CRUD.php");

use System\Model\Perguntas as ModelPergunta;
use System\Controller\PerguntasConnCategorias;
use System\Controller\Categorias;
use System\Controller\Alternativas;

class Perguntas
{
  private $model = null;

  public function __construct()
  {
    $this->model = new ModelPergunta(DB_HOST, DB_USER, DB_PASS, DB_NAME);
  }

  public function criar($data)
  {
    $categorias = $data['categorias'];
    unset($data['categorias']);

    $response = $this->model->insert($data);
    if($response->result !== false){
      $id_pergunta = $response->result;

      $categorias_handler                = new Categorias();
      $perguntas_conn_categorias_handler = new PerguntasConnCategorias();

      foreach($categorias as $ref_categorias){
        $id_categoria = $categorias_handler->returnsIdByRef($ref_categorias);

        $response_perguntas_conn_categorias = $perguntas_conn_categorias_handler->criar($id_pergunta, $id_categoria);

        if($response_perguntas_conn_categorias === false){
          $response->result = false;
          break;
        }
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
      "ref",
      "pergunta"
    ];

    $where = [
      ["visibilidade", 1],
    ];

    if (!empty($conditions)) {
      array_push($where, ...$conditions);
    }

     //$response = $this->model->select($columns, $where, null, null, null, $limit_min, $limit_max);

     $perguntas = $this->model->select($columns, $where, null, null, null, $limit_min, $limit_max);
     $response = $perguntas->result; 
     return  $response;


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
      
    
  
    // dumpdie(true, $response);

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

