<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "resources" . DIRECTORY_SEPARATOR . "system_functions.php";

  use System\Controller\Tipos;

  if(!empty($_POST)){
    $tipos = new Tipos();
    $id = $tipos->returnsIdByRef($_POST[$ref]);

    $conditions = [
        ['id', $id]
    ];

    $response = $tipos->atualizar($_POST['atualizacao'], $conditions);

    var_dump($response);
    if($response !== false){
        $_SESSION['status_msg'] = "Tipo atualizado com sucesso";
    }
  } else{
    $_SESSION['status_msg'] = "Dados inválidos ou não inseridos";
  }