<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "resources" . DIRECTORY_SEPARATOR . "system_functions.php";

  use System\Controller\Perguntas;

  if(!empty($_POST)){
    $perguntas = new Perguntas();
    $id = $perguntas->returnsIdByRef($_POST[$ref]);

    $conditions = [
        ['id', $id]
    ];

    $response = $pergunta->atualizar($_POST['atualizacao'], $conditions);

    if($response !== false){
        $_SESSION['status_msg'] = "Pergunta atualizada com sucesso";
    }

    var_dump($response);
  } else{
    $_SESSION['status_msg'] = "Dados inválidos ou não inseridos";
  }