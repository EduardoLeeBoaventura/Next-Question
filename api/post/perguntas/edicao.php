<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "resources" . DIRECTORY_SEPARATOR . "system_functions.php";

  use System\Controller\Perguntas;
  $perguntas_handler = new Perguntas();

  if (!empty($_POST)) {
    $dados_update = [
      "pergunta" => $_POST['pergunta'],
      "opcoes"   => $_POST['opcoes'],
      "coption"  => $_POST['coption']
    ];

  if(!empty($_POST)){
    $perguntas = new Perguntas();
    $ref_registro = $categoria->returnsIdByRef($_POST['ref_registro']);

    $conditions = [
        ['ref', $_POST['ref_registro']]
    ];

    $update = $perguntas_handler->atualizar($dados_update, $conditions);

    if($update !== false){
        $_SESSION['status_msg'] = "Pergunta atualizada com sucesso";
    }
  } else{
    $_SESSION['status_msg'] = "Dados inválidos ou não inseridos";
  }
  header("Location: /gerenciamento/cadastros/perguntas/");
}