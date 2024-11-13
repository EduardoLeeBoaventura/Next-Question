<?php
namespace System\Controller;

require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "resources" . DIRECTORY_SEPARATOR . "system_functions.php";
require_once returnsPathFromHost("src", "Model", "database-handler-php", "Handlers", "SQL_CRUD.php");

use System\Model\Questionario as ModelQuestionario;
use System\Controller\Categorias;
use System\Controller\PerguntasConnCategorias;
use System\Controller\CategoriasConnQuestionario;

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

    public function iniciar($questionario, $categoria, $qtd_perguntas)
    {
      $perguntas = new PerguntasConnCategorias();
      $categoria_conn = new CategoriasConnQuestionario();
      $conn = $categoria_conn->criar($questionario, $categoria);
      $conn['last_id_categoria'];

      $columns = [
        "p.pergunta"
      ];

      $perguntas->listar($columns, 0, $qtd_perguntas);
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