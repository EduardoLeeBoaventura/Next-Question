<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "resources" . DIRECTORY_SEPARATOR . "system_functions.php";

  use System\Controller\Questionario;

  $questionario = new Questionario();

  if(!empty($_GET)){
    $questionarios = $questionario->listar(1, 20);

    
    if($questionarios !== false && arrayLength($questionarios) > 0){
      $response  = [
        "response" => $questionarios,
        "status" => true,
        "status_msg" => "Questionário(s) encontrados."
      ];
    } else {
      $response  = [
        "response" => [],
        "status" => false,
        "status_msg" => "Nenhum questionário encontrado"
      ];
    }
  } else {
    $response  = [
      "response" => [],
      "status" => false,
      "status_msg" => "Nada para buscar."
    ];
  }

  echo json_encode($response);