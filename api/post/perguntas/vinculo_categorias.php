<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "resources" . DIRECTORY_SEPARATOR . "system_functions.php";
unset($_SESSION["ref_log"]);

use System\Controller\PerguntasConnCategorias;

if (!empty($_POST)){
    $PerguntasCCategorias = new PerguntasConnCategorias();

    $categorias = $_POST['categorias'];
    $perguntas = $_POST['perguntas'];
    $response = $PerguntasCTipos->criar($perguntas, $categorias);
    // var_dump($response);
    
    if($response !== false){
        $_SESSION['status_msg'] = "Ação concluida com sucesso";
    } else {
        $_SESSION['status_msg'] = "Dados inválidos ou insuficientes";
    }
} else {
    $_SESSION['status_msg'] = "Dados inválidos ou insuficientes";
}