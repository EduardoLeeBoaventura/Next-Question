<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "resources" . DIRECTORY_SEPARATOR . "system_functions.php";

  use System\Controller\UsuariosPrivilegios;

  $usuarios_privilegios_handler = new UsuariosPrivilegios();
  
  if(!empty($_GET)){
    $privilegio = @$_GET['privilegio'];

    $acoes = $usuarios_privilegios_handler->listarAcoesPrivilegio($privilegio);

    
    if($acoes !== false && arrayLength($acoes) > 0){
      $response  = [
        "response" => $acoes,
        "status" => true,
        "status_msg" => "Ações encontradas."
      ];
    } else {
      $response  = [
        "response" => [],
        "status" => false,
        "status_msg" => "Nenhuma ação encontrada"
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