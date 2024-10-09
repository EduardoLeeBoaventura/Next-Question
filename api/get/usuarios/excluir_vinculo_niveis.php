<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "resources" . DIRECTORY_SEPARATOR . "system_functions.php";
  unset($_SESSION["ref_log"]);

  use System\Controller\UsuariosConnNiveis;

  $usuarios_conn_niveis_handler = new UsuariosConnNiveis();

  if (!empty($_GET)) {
    $nivel   = $_GET['nivel'];
    $usuario = $_GET['usuario'];

    $delete = $usuarios_conn_niveis_handler->excluir($nivel, $usuario);

    if ($delete !== false) {
      $response  = [
        "response" => $delete,
        "status" => true,
        "status_msg" => "Vínculo removido com sucesso."
      ];
    } else if ($delete == false && !empty($_SESSION["ref_log"])) {
      $response  = [
        "response" => [],
        "status" => false,
        "status_msg" => "Ação não realizada, erro: " . $_SESSION["ref_log"]
      ];
    }
  } else {
    $response  = [
      "response" => [],
      "status" => false,
      "status_msg" => "Nada para buscar."
    ];
  }
  unset($_SESSION["ref_log"]);

  echo json_encode($response);