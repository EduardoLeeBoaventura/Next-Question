<?php
    namespace System\Model;
    
  require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "resources" . DIRECTORY_SEPARATOR . "system_functions.php";
    require_once returnsPathFromHost("src", "Model", "database-handler-php", "Handlers", "SQL_CRUD.php");

    use Handlers\SQL_CRUD;

    class QuestionarioConnPerguntas extends SQL_CRUD{
        private $table = "questionario_conn_perguntas";

        public function insert(Array $data) : Object{
            $response = parent::execInsert($this->table, $data);

            return $response;
        }
      
        public function select(String|Array|Null $columns = "QCP.*", Array|Null $conditions = null, Array|String|Null $group_by = null, Array|String|Null $order_by = null, String|Null $order_direction = "<", String|Int|Null $limit_min = 100, String|Int|Null $limit_max = null) : Object{

            $tables = [
                "perguntas P" => [],
                "questionario_conn_pergunta QCP" => ["QCP.id_pergunta = P.id"],
                "questionario Q" => [["Q.id", "QCP.id_questionario"]],
            ];

            $response = parent::execSelect($tables, $columns, $conditions, $group_by, $order_by, $order_direction, $limit_min, $limit_max);

            return $response;
        }
      
        public function update(Array $data, Array|String|Null $conditions = null) : Object{
            $response = parent::execUpdate($this->table, $data, $conditions);

            return $response;
        }
      
        public function delete(Array|String|Null $conditions) : Object{
            $response = parent::execDelete($this->table, $conditions);
      
            return $response;
        }
    }