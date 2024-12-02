<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "resources" . DIRECTORY_SEPARATOR . "system_functions.php";
  
  use System\Controller\Perguntas;

  if(!empty($_POST)){
    $perguntas = new Perguntas();

    $dados_insert = [
        "pergunta"         => $_POST['pergunta'],
        "categorias"       => $_POST['categorias'],
        "alternativa"     => $_POST['alternativa'],
        "gabarito"         => $_POST['gabarito'],
        "usuario_cadastro" => $_SESSION['id_usuario'],
        "usuario_edicao"   => $_SESSION['id_usuario']
    ];

  $insert = $perguntas->criar($dados_insert);

  if($insert->result !== false){
    $_SESSION["form_action_status"] = true;
    $_SESSION["status_msg"] = "Cadastro realizado com sucesso";
  }else if($insert->result == false && !empty($insert->result_error)){
    $query_param = "";

    $_SESSION["form_action_status"] = false;
    $_SESSION["status_msg"] = "Ação não realizada, erro: " .$insert->ref_log;
  }
} else {
    $_SESSION["form_action_status"] = false;
    $_SESSION["status_msg"] = "Dados para cadastro não informados";
}

header('Location: /gerenciamento/cadastros/perguntas');