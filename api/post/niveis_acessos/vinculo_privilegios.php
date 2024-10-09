<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "resources" . DIRECTORY_SEPARATOR . "system_functions.php";
unset($_SESSION["ref_log"]);

use System\Controller\NiveisPrivilegios;

$niveis_privilegios_handler = new NiveisPrivilegios();

if (!empty($_POST)) {
  $nivel    = $_POST["nivel"];
  $pagina   = $_POST["privilegio"];
  $tipo     = $_POST["tipo_vinculacao"];
  $acoes    = @$_POST["acoes-privilegios"];

  if(empty($acoes)){
    $acoes = ' ';
  } else {
    $acoes = implode("|", $acoes);
  }
  

  $data_insert = [
    "nivel" => $nivel,
    "pagina" => $pagina,
    "tipo" => $tipo,
    "permissoes" => $acoes
  ];

  $insert = $niveis_privilegios_handler->criar($data_insert);

  if ($insert !== false) {
    $_SESSION["form_action_status"] = true;
    $_SESSION["status_msg"] = "Aplicação de privilégio realizada com sucesso";
  } else if ($insert == false && !empty($_SESSION["ref_log"])) {
    $_SESSION["form_action_status"] = false;
    $_SESSION["status_msg"] = "Ação não realizada, erro: " . @$_SESSION["ref_log"];
  }
} else {
  $_SESSION["form_action_status"] = false;
  $_SESSION["status_msg"] = "Dados para cadastro não informados";
}
unset($_SESSION["ref_log"]);

header("Location: /gerenciamento/cadastros/niveis_acessos/");