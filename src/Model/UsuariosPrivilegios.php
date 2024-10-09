<?php
    namespace System\Model;
    
  require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "resources" . DIRECTORY_SEPARATOR . "system_functions.php";
    require_once returnsPathFromHost("src", "Model", "database-handler-php", "Handlers", "SQL_CRUD.php");

    use Handlers\SQL_CRUD;

    class UsuariosPrivilegios extends SQL_CRUD{
        private $table = "usuarios_privilegios";

        public function insert(Array $data) : Object{
            $response = parent::execInsert($this->table, $data);

            return $response;
        }
      
        public function select(String|Array|Null $columns = "UP.*", Array|Null $conditions = null, Array|String|Null $group_by = null, Array|String|Null $order_by = null, String|Null $order_direction = "<", String|Int|Null $limit_min = 100, String|Int|Null $limit_max = null) : Object{

            $tables =[
                "$this->table UP" => [],
                "usuarios U" => [["U.id", "UP.id_usuario"]],
            ];

            $response = parent::execSelect($tables, $columns, $conditions, $group_by, $order_by, $order_direction, $limit_min, $limit_max);

            return $response;
        }
      
        public function update(Array $data, Array|String|Null $conditions = null) : Object{
            $response = parent::execUpdate($this->table, $data, $conditions);

            return $response;
        }
      
        public function delete(Array|String|Null $conditions) : Object{
            $data = [
                "visibilidade" => 0
            ];

            $response = $this->update($data, $conditions);
      
            return $response;
        }
    }