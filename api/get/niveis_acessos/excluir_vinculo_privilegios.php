<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "resources" . DIRECTORY_SEPARATOR . "system_functions.php";
unset($_SESSION["ref_log"]);

use System\Controller\NiveisPrivilegios;

$niveis_privilegios_handler = new NiveisPrivilegios();

if (!empty($_GET)) {
  $privilegio = $_GET['privilegio'];

  $delete = $niveis_privilegios_handler->excluir($privilegio);

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