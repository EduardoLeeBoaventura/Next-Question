<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "resources" . DIRECTORY_SEPARATOR . "system_functions.php";

  use System\Controller\Pergunta;

  $_POST['pergunta'] = "Como sabemos o que sabemos?";
  $_POST['opcoes'] = "typ";
  $_POST['opcao_correta'] = "Nao a resposta certa";

  if(!empty($_POST)){
    $pergunta = new Pergunta();

    $data = [
        "pergunta" => $_POST['pergunta'],
        "opcoes" => $_POST['opcoes'],
        "coption" => $_POST['opcao_correta'],
    ];
    $response = $pergunta->criar($data);
    if($response !== false){
        $_SESSION['status_msg'] = "Ação concluida com sucesso";
        var_dump($response);
    }
  } else{
        $_SESSION['status_msg'] = "Dados inválidos ou insuficientes";
  }