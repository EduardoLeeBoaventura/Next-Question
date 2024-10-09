<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "resources" . DIRECTORY_SEPARATOR . "system_functions.php";
  
  use System\Controller\Usuarios;
    
  if (!empty($_POST)) {
    $usuarios_handler = new Usuarios();

    $get_user = $usuarios_handler->login($_POST['email'], $_POST['senha']);
  }

  header("Location: /");
  exit;