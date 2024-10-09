<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "resources" . DIRECTORY_SEPARATOR . "system_functions.php";

use System\Controller\Usuarios;

$usuarios_handler = new Usuarios();

if (!empty($_POST)) {
  $dados_update = [
    "nome"             => $_POST['nome'],
    "email"            => $_POST['email'],
    "telefone"         => $_POST['telefone'],
    "cpf"              => $_POST['cpf'],
    "usuario_edicao"   => $_SESSION['id_usuario']
  ];

  if(!empty($_POST['situacao'])) {
    $dados_update['situacao'] = $_POST['situacao'];
  }

  if(USER_INFO['desenvolvedor'] && !empty($_POST['usuario-especial'])) {
    $dados_update['desenvolvedor'] = $_POST['usuario-especial'] == 'desenvolvedor' ? 1 : 0;
    $dados_update['geral'] = $_POST['usuario-especial'] == 'geral' ? 1 : 0;
  }

  $conditions = [
    ["ref", $_POST['ref_registro']]
  ];
    
  $update = $usuarios_handler->atualizar($dados_update, $conditions);

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

if(isset($_POST['from']) && $_POST['from'] == 'edit-account') {
  header("Location: /gerenciamento/editar_conta.php");
} else {
  header("Location: /gerenciamento/cadastros/usuarios/");
}