<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "resources" . DIRECTORY_SEPARATOR . "system_functions.php";

  use System\Controller\categorias;

  $tipos_handler = new categorias();
  
  if(!empty($_GET)){
    $ref = @$_GET['ref'];

    $conditions = [
      "ref = '$ref'"
    ];
    
    $tipos = $tipos_handler->listar($conditions);

    if($tipos !== false && arrayLength($tipos) > 0){
        $response = [
            "response" => $tipos,
            "status" => true,
            "status_msg" => "Tipo(s) encontrados."
        ];
    } else {
        $response = [
            "response" => [],
            "status" => false,
            "status_msg" => "Nenhuma Tipo(s) encontrado."
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