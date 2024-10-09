<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "resources" . DIRECTORY_SEPARATOR . "system_functions.php";
unset($_SESSION["ref_log"]);

use System\Controller\Usuarios;

$usuarios_handler = new Usuarios();

if (!empty($_POST)) {
  $senha = $_POST['senha'];
  $senha_autorizacao = $_POST['senha-autorizacao'];

  $conditions = [
    ["ref", $_POST['ref_registro']]
  ];
    
  $update = $usuarios_handler->alterarSenha($senha, $senha_autorizacao, $conditions);

  if ($update !== false) {
    $_SESSION["form_action_status"] = true;
    $_SESSION["status_msg"] = "Edição realizada com sucesso";
    } else if ($update == false && !empty($_SESSION["ref_log"])) {
      $_SESSION["form_action_status"] = false;
      $_SESSION["status_msg"] = "Ação não realizada, erro: " . @$_SESSION["ref_log"];
    }
} else {
  $_SESSION["form_action_status"] = false;
  $_SESSION["status_msg"] = "Dados para cadastro não informados";
}
unset($_SESSION["ref_log"]);

if(isset($_POST['from']) && $_POST['from'] == 'edit-account') {
  header("Location: /gerenciamento/editar_conta.php");
} else {
  header("Location: /gerenciamento/cadastros/usuarios/");
}