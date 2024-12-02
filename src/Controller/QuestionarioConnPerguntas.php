<?php

namespace System\Controller;

require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "resources" . DIRECTORY_SEPARATOR . "system_functions.php";

use System\Model\QuestionarioConnPerguntas as ModelQuestionarioConnPerguntas;

use System\Controller\Questionario;
use System\Controller\Perguntas;
use System\Controller\Alternativas;
use System\Controller\VinculoQuestionario;
use System\Controller\PerguntasConnCategorias;

class QuestionarioConnPerguntas
{
  private $model = null;

  public function __construct()
  {
    $this->model = new ModelQuestionarioConnPerguntas(DB_HOST, DB_USER, DB_PASS, DB_NAME);
  }

  public function criar($questionario, $perguntas)
  {
    $perguntas_handler       = new Perguntas();
    $questionario_handler             = new Questionario();
    $vinculo_handler = new VinculoQuestionario();
    $categorias_perguntas_handler = new PerguntasConnCategorias();


    $id_pergunta = $perguntas_handler->returnsIdByRef($perguntas);
    $id_questionario = $questionario_handler->returnsIdByRef($questionario);
    $condicoes = [
      ['id_pergunta', $id_pergunta]
    ];
    $id_categoria_pergunta = $categorias_perguntas_handler->listar(null, $condicoes);
    $conditions = [
      ["id_questionario", $id_questionario],
      ["id_categoria", $id_categoria_pergunta['0']['id']],
    ];
    $id_vinculo = $vinculo_handler->listar(null, $conditions);

    $data = [
      "id_pergunta"   => $perguntas_handler->returnsIdByRef($perguntas),
      "id_vinculo" => $id_vinculo[0]['id']
      
    ];

    $response = $this->model->insert($data);
    return $response->result;
  }

  public function atualizar($data, $conditions)
  {
    $response = $this->model->update($data, $conditions);

    return $response;
  }

  public function excluir($pergunta, $questionario)
  {
    $conditions = [
      "EXISTS(SELECT * FROM perguntas P  WHERE P.id = questionario_conn_perguntas.id_pergunta AND P.ref = '$pergunta') ",
      "EXISTS(SELECT * FROM vinculo_questionario VQ WHERE VQ.id = questionario_conn_perguntas.id_vinculo) ",
      "EXISTS(SELECT * FROM questionario Q WHERE Q.ref = '$questionario') "
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
      "P.pergunta",
      "P.id",
      ];

      
      // dumpDie(true, $result);
    $where = [
      ["P.visibilidade", 1]
    ];

    if(!empty($conditions)){
      array_push($where, ...$conditions);
    }

    $response = $this->model->select($columns, $where, null, null, null, $limit_min, $limit_max);
    $resultado = $response->result;
    
    // dumpDie(true, $resultado);
    foreach ($resultado as $k => $v) {
      $alternativas_handler = new Alternativas();
      $conditions_alternativas = [
        ['id_pergunta', $resultado[$k]['id']]
      ];
  
      $alternativas_result = $alternativas_handler->listar($conditions_alternativas);
      
      $resultado[$k]['alternativas'] = $alternativas_result;
    }


    return $resultado;
  }
}
