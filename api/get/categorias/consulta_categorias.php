<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "resources" . DIRECTORY_SEPARATOR . "system_functions.php";

  use System\Controller\categorias;

  $categorias = new categorias();
  
  if(!empty($_GET)){
    $ref = $_GET['ref'];

    $conditions = [
      "ref = '$ref'"
    ];
    
    $categorias = $categorias->listar($conditions);

    if($categorias !== false && arrayLength($categorias) > 0){
        $response = [
            "response" => $categorias,
            "status" => true,
            "status_msg" => "Categoria(s) encontradas."
        ];
    } else {
        $response = [
            "response" => [],
            "status" => false,
            "status_msg" => "Nenhuma categoria(s) encontrada."
        ];
    }
  } else {
    $response = [
        "response" => [],
        "status" => false,
        "status_msg" => "Dados incorretos ou insuficientes"
    ];
  }
  echo json_encode($response);