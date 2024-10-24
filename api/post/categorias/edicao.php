<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "resources" . DIRECTORY_SEPARATOR . "system_functions.php";

  use System\Controller\Categorias;

  if(!empty($_POST)){
    $categoria = new Categorias();
    $id = $categoria->returnsIdByRef($_POST[$ref]);

    $conditions = [
        ['id', $id]
    ];

    $response = $categoria->atualizar($_POST['atualizacao'], $conditions);

    var_dump($response);
    if($response !== false){
        $_SESSION['status_msg'] = "Categoria atualizada com sucesso";
    }
  } else{
    $_SESSION['status_msg'] = "Dados inválidos ou não inseridos";
  }