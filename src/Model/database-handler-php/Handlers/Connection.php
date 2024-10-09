<?php

namespace Handlers;

use Exception;
use Stringable;

class ReturnFormat
{
  public $result;
  public String|Null $result_error;
  public String|Bool|Null $ref_log;
  public String|Bool|Null $sql;

  function __construct($result, String|Null $result_error, String|Null $ref_log, String|Bool|Null $sql)
  {
    $this->result       = $result;
    $this->result_error = $result_error;
    $this->ref_log       = $ref_log;
    $this->sql          = $sql;
  }
}

class Connection
{
  public $connection = null;
  public $connection_error = null;

  public $sql_exec_result = null;
  public $sql_exec_result_error = null;

  protected String $db_host;
  protected String $db_user;
  protected String $db_pass;
  protected String $db_name;
  protected String $db_drive;

  public function __construct(String $host = null, String $db_user = null, String $db_pass = null, String $db_name = null, String $db_drive = 'mysql')
  {
    $this->db_host = $host;
    $this->db_user = $db_user;
    $this->db_pass = $db_pass;
    $this->db_name = $db_name;
    $this->db_drive = $db_drive;
  }

  private function connect()
  {
    try {
      $PDO = new \PDO("$this->db_drive:host=$this->db_host;dbname=$this->db_name;charset=utf8mb4", $this->db_user, $this->db_pass);

      $this->connection = $PDO;
    } catch (Exception $e) {
      $this->connection = false;
      $this->connection_error = $e->getMessage();
    }

    return $this->connection;
  }

  private function closeConnection()
  {
    $this->connection = null;
    $this->connection_error = null;
  }

  public function executeSQL(String $sql, ...$values): Object
  {
    $sql_exec_result = null;
    $sql_exec_result_error = null;

    $values = array_values($values);
    $this->connect();
    
    $sql_comand = strtolower(explode(" ", $sql)[0]);
    
    if ($this->connection) {
      try {
        
        $stmt = $this->connection->prepare($sql);
        foreach ($values as $k => $v) {
          $stmt->bindParam($k+1, $values[$k]);
        }
        $stmt->execute();
        
        if ($sql_comand == 'select') {
          $sql_exec_result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } else if($sql_comand == 'insert'){
          $sql_exec_result = $this->connection->lastInsertId();
        } else {
          $sql_exec_result = $stmt->rowCount();
        }
        $sql_exec_result_error = null;
      } catch (Exception $e) {
        $sql_exec_result = false;
        $sql_exec_result_error = $e->getMessage();
      }
    } else {
      $sql_exec_result = false;
      $sql_exec_result_error = "Connection is null.";
    }

    $this->sql_exec_result = $sql_exec_result;
    $this->sql_exec_result_error = $sql_exec_result_error;

    $ref_log = null;
    if ($this->sql_exec_result === false) {
      $SQL = "INSERT INTO logs (ref, erro, auxiliar) VALUES (?,?,?)";
      $stmt = $this->connection->prepare($SQL);

      $error = !empty($this->sql_exec_result_error) ? $this->sql_exec_result_error : "undefined";
      
      $ref = $this->returnsItemRef("logs");
      $values_log = [$ref, $error, $sql . " --- user - " . @$_SESSION['id_usuario']];
      foreach ($values_log as $k => $v) {
        $stmt->bindParam($k+1, $values_log[$k]);
      }

      $stmt->execute();

      $ref_log = $ref;
    }

    $this->closeConnection();

    return new ReturnFormat($sql_exec_result, $sql_exec_result_error, $ref_log, $sql);
  }


  /* REF HANLER */
  public function returnsItemRef($table){
    $SQL = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ?";
    $response = $this->executeSQL($SQL, $this->db_name, $table);
    $columns = array_map(function($v){
      return $v['COLUMN_NAME'];
    }, $response->result);

    if(array_search("ref", $columns) !== false){
      $characters = str_split("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ");
      $ref_format = str_split('#####-####-#####-########');
  
      $ref = false;
      $find_ref = false;
      $count = 0;
      while(!$find_ref && $count<10000){
        $ref = '';
        foreach($ref_format as $ref_format_char){
          if($ref_format_char == '#'){
            $ref .= $characters[array_rand($characters, 1)];
          }else{
            $ref .= $ref_format_char;
          }
        }
  
        $find_ref = $this->checkIfRefCanBeUsed($table, $ref);
        if($find_ref === false){
          $ref = false;
        }
        $count++;
      }
      return $ref;
    } else {
      return false;
    }
  }

  private function checkIfRefCanBeUsed($table, $ref){
    $SQL = "SELECT * FROM $table WHERE ref = ?";
    $response = $this->executeSQL($SQL, $ref);

    return !(bool)arrayLength($response->result);
  }
  /* REF HANLER \. */
}
