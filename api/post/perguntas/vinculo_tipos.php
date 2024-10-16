<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "resources" . DIRECTORY_SEPARATOR . "system_functions.php";
unset($_SESSION["ref_log"]);

use System\Controller\PerguntasConnTipos;

if (!empty($_POST)){
    $PerguntasCTipos = new PerguntasConnTipos();

    $tipo = $_POST['tipo'];
    $pergunta = $_POST['pergunta'];
    $response = $PerguntasCTipos->criar($pergunta, $tipo);
    // var_dump($response);
    
    if($response !== false){
        $_SESSION['status_msg'] = "Ação concluida com sucesso";
    } else {
        $_SESSION['status_msg'] = "Dados inválidos ou insuficientes";
    }
} else {
    $_SESSION['status_msg'] = "Dados inválidos ou insuficientes";
}