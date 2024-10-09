<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "resources" . DIRECTORY_SEPARATOR . "system_functions.php";

use System\Controller\NiveisAcessos;

$niveis_acessos_handler = new NiveisAcessos();

if (!empty($_POST)) {
  $dados_update = [
    "nome"             => $_POST['nome'],
    "situacao"         => $_POST['situacao'],
    "usuario_edicao"   => $_SESSION['id_usuario']
  ];

  $conditions = [
    ["ref", $_POST['ref_registro']]
  ];
    
  $update = $niveis_acessos_handler->atualizar($dados_update, $conditions);

  if ($update->result !== false) {
    $_SESSION["form_action_status"] = true;
    $_SESSION["status_msg"] = "Edição realizada com sucesso";
  } else if ($update->result == false && !empty($update->result_error)) {
    $_SESSION["form_action_status"] = false;
    $_SESSION["status_msg"] = "Ação não realizada, erro: " . $update->ref_log;
  }
} else {
  $_SESSION["form_action_status"] = false;
  $_SESSION["status_msg"] = "Dados para cadastro não informados";
}

header("Location: /gerenciamento/cadastros/niveis_acessos/");