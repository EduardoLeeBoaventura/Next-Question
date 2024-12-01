<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "resources" . DIRECTORY_SEPARATOR . "system_functions.php";
unset($_SESSION["ref_log"]);

use System\Controller\CategoriasConnQuestionario;

if (!empty($_POST)){
    $CategoriasCQuestionario = new CategoriasConnQuestionario();

    $categoria = $_POST['categoria_ref'];
    $questionario = $_POST['questionario_ref'];
    $response = $CategoriasCQuestionario->criar($questionario, $categoria);
    
    if($response !== false){
        $_SESSION['status_msg'] = "Ação concluida com sucesso";
    } else {
        $_SESSION['status_msg'] = "Dados inválidos ou insuficientes";
    }
} else {
    $_SESSION['status_msg'] = "Dados inválidos ou insuficientes";
}