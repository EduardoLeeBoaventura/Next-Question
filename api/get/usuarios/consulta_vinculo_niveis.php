<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "resources" . DIRECTORY_SEPARATOR . "system_functions.php";

  use System\Controller\UsuariosConnNiveis;

  $usuarios_conn_niveis_handler = new UsuariosConnNiveis();
  
  if(!empty($_GET)){
    $usuario = @$_GET['usuario'];

    $conditions = [
      ["U.ref", $usuario]
    ];

    $vinculos = $usuarios_conn_niveis_handler->listar($conditions);

    
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