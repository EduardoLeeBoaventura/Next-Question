<?php
require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "resources" . DIRECTORY_SEPARATOR . "system_functions.php";

  use System\Controller\Categoria;

  if(!empty($_POST)){
    $categoria = new Categoria();

    $data = [
        "nome"   => $_POST['nome']
    ];
    $response = $categoria->criar($data);
    var_dump($response);
    if($response !== false){
        $_SESSION['status_msg'] = "Ação concluida com sucesso";
    }
  } else{
        $_SESSION['status_msg'] = "Dados inválidos ou insuficientes";
  }