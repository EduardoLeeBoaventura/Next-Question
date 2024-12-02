<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "resources" . DIRECTORY_SEPARATOR . "system_functions.php";
  unset($_SESSION["ref_log"]);

  use System\Controller\Categorias;

  $categorias_handler = new Categorias();

  if (!empty($_GET)) {
  $categorias = $_GET['categorias'];

  $delete = $categorias_handler->excluir($categorias);

  if ($delete !== false) {
    $response  = [
      "response" => $delete,
      "status" => true,
      "status_msg" => "Categoria removida com sucesso."
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