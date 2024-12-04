<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "resources" . DIRECTORY_SEPARATOR . "system_functions.php";

  use System\Controller\Questionario;

  if(!empty($_POST)){
    $questionario = new Questionario();

    $data = [
        "nome" => $_POST['nome'],
        "desciricao" => $_POST['desciricao'],
        "quantidade_questoes" => $_POST['quantidade_questoes'],
      ];
      $response = $questionario->criar($data);
      // var_dump($response);
    if($response !== false){
        $_SESSION['status_msg'] = "Ação concluida com sucesso";
    } else {
      $_SESSION['status_msg'] = "Dados inválidos ou insuficientes";
  }
  } else {
    $_SESSION['status_msg'] = "Dados inválidos ou insuficientes";
  }