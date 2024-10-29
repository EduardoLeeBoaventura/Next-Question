<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "resources" . DIRECTORY_SEPARATOR . "system_functions.php";

  use System\Controller\categorias;

  $categoria = new categorias();
  
  if(!empty($_GET)){
    $ref = @$_GET['ref'];

    $conditions = [
      "ref = '$ref'"
    ];
    
    $categoria = $categoria->listar($conditions);

    if($categoria !== false && arrayLength($categoria) > 0){
        $response = [
            "response" => $categoria,
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