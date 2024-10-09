<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "resources" . DIRECTORY_SEPARATOR . "system_functions.php";
unset($_SESSION["ref_log"]);

use System\Controller\UsuariosConnNiveis;

$usuarios_conn_niveis_handler = new UsuariosConnNiveis();

if (!empty($_POST)) {
  $nivel       = $_POST["nivel"];
  $ref_usuario = $_POST["usuario"];
    
  $insert = $usuarios_conn_niveis_handler->criar($ref_usuario, $nivel);

  if ($insert !== false) {
    $_SESSION["form_action_status"] = true;
    $_SESSION["status_msg"] = "Cadastro de nível realizado com sucesso";
  } else if ($insert == false && !empty($_SESSION["ref_log"])) {
    $_SESSION["form_action_status"] = false;
    $_SESSION["status_msg"] = "Ação não realizada, erro: " . @$_SESSION["ref_log"];
  }
} else {
  $_SESSION["form_action_status"] = false;
  $_SESSION["status_msg"] = "Dados para cadastro não informados";
}
unset($_SESSION["ref_log"]);

header("Location: /gerenciamento/cadastros/usuarios/");