<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "resources" . DIRECTORY_SEPARATOR . "system_functions.php";

  use System\Controller\Usuarios;

  $usuarios_handler = new Usuarios();
  
  if(!empty($_GET)){
    $cpf = @$_GET['cpf-consulta'];

    $conditions = [
      "cpf = '$cpf'"
    ];

    $usuario = $usuarios_handler->listarParaConsulta($conditions);

    
    if($usuario !== false && arrayLength($usuario) > 0){
      $usuario['cpf'] = "***.***.*" . $usuarios_handler->hideData($usuario['cpf']);
      
      $response  = [
        "response" => $usuario,
        "status" => true,
        "status_msg" => "Filiado(s) encontrados."
      ];
    } else {
      $response  = [
        "response" => [],
        "status" => false,
        "status_msg" => "Nenhum filiado encontrado"
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