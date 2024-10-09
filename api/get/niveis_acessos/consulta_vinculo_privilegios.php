<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "resources" . DIRECTORY_SEPARATOR . "system_functions.php";

  use System\Controller\NiveisPrivilegios;

  $niveis_privilegios_handler = new NiveisPrivilegios();
  
  if(!empty($_GET)){
    $nivel = @$_GET['nivel'];

    $conditions = [
      ["NA.ref", $nivel]
    ];

    $vinculos = $niveis_privilegios_handler->listar($conditions);
    
    if($vinculos !== false && arrayLength($vinculos) > 0){      
      $response  = [
        "response" => $vinculos,
        "status" => true,
        "status_msg" => "Vinculo(s) encontrado(s)."
      ];
    } else {
      $response  = [
        "response" => [],
        "status" => false,
        "status_msg" => "Nenhum registro encontrado"
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