<?php
    namespace System\Model;
    
  require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "resources" . DIRECTORY_SEPARATOR . "system_functions.php";
    require_once returnsPathFromHost("src", "Model", "database-handler-php", "Handlers", "SQL_CRUD.php");

    use Handlers\SQL_CRUD;

    class UsuariosConnNiveis extends SQL_CRUD{
        private $table = "usuarios_conn_niveis";

        public function insert(Array $data) : Object{
            $response = parent::execInsert($this->table, $data);

            return $response;
        }
      
        public function select(String|Array|Null $columns = "UCN.*", Array|Null $conditions = null, Array|String|Null $group_by = null, Array|String|Null $order_by = null, String|Null $order_direction = "<", String|Int|Null $limit_min = 100, String|Int|Null $limit_max = null) : Object{

            $tables = [
                "usuarios U" => [],
                "usuarios_conn_niveis UCN" => ["UCN.id_usuario = U.id"],
                "niveis_acessos N" => [["N.id", "UCN.id_nivel"]],
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