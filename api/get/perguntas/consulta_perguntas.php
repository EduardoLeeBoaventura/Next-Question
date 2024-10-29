<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "resources" . DIRECTORY_SEPARATOR . "system_functions.php";

  use System\Controller\Perguntas;

  $perguntas = new Perguntas();
  
  if(!empty($_GET)){
    $ref = @$_GET['ref'];

    $conditions = [
      "ref = '$ref'"
    ];
    
    $perguntas = $perguntas->listar($conditions);

    if($perguntas !== false && arrayLength($perguntas) > 0){
        $response = [
            "response" => $perguntas,
            "status" => true,
            "status_msg" => "Pergunta(s) encontradas."
        ];
    } else {
        $response = [
            "response" => [],
            "status" => false,
            "status_msg" => "Nenhuma pergunta(s) encontrada."
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