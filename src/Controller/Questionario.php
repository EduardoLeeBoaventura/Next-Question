<?php
namespace System\Controller;

require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "resources" . DIRECTORY_SEPARATOR . "system_functions.php";
require_once returnsPathFromHost("src", "Model", "database-handler-php", "Handlers", "SQL_CRUD.php");

use System\Model\Questionario as ModelQuestionario;
use System\Controller\QuestionarioConnPerguntas;
use System\Controller\VinculoQuestionario;

class Questionario 
{
    private $model = null;

    public function __construct()
    {
        $this->model = new ModelQuestionario(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    }

    public function criar($data)
    {
      $response = $this->model->insert($data);
      return $response;
    }
    
    public function listar($conditions = null, $limit_min = null, $limit_max = null){
      $columns = [
        "questionario.id",
        "questionario.nome",
        "questionario.descricao"
      ];



      
      $questionario = $this->model->select($columns, $conditions, null, null, null, $limit_min, $limit_max);
      $response = $questionario->result; 

      // dumpdie(true, $response);

      foreach ($response as $k => $v) {
        $vinculo_conditions = [
          ['id_questionario', $response[$k]['id']]
        ];

        $vinculo_handler = new VinculoQuestionario();
        $vinculo_resultado = $vinculo_handler->listar($vinculo_conditions);
        // dumpDie(true, $vinculo_resultado);
        array_push($response[$k], $vinculo_resultado);
        // $response[$k] += $vinculo_resultado;


        $QConnPerg_handler = new QuestionarioConnPerguntas();
          $conditions = [
            ["id_vinculo", $v['id']]
          ];
          $QConnPerg_resultado = $QConnPerg_handler->listar($conditions);
          // dumpDie(true, $QConnPerg_resultado);
          array_push($response[$k], $QConnPerg_resultado);
          // $response[$k] += $QConnPerg_resultado;
          
        
      }
      // dumpDie(true, $response[0][0]);

      $result = $response;

      return $result;
    }

    public function atualizar($data, $conditions)
    {
      $response = $this->model->update($data, $conditions);
  
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