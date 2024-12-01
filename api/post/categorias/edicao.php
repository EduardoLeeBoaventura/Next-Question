<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "resources" . DIRECTORY_SEPARATOR . "system_functions.php";

  use System\Controller\Categorias;
  $categorias_handler = new Categorias();

  if (!empty($_POST)) {
    $dados_update = [
      "nome"             => $_POST['nome'],
      "descricao"        => $_POST['descricao'],
      "id_superior"      => $categorias_handler->returnsIdByRef($_POST['categoria_superior']),
      "usuario_edicao"   => $_SESSION['id_usuario']
    ];

  if(!empty($_POST)){
    $categoria = new Categorias();
    $ref_registro = $categoria->returnsIdByRef($_POST['ref_registro']);

    $conditions = [
        ['ref', $_POST['ref_registro']]
    ];

    $update = $categorias_handler->atualizar($dados_update, $conditions);

    if($update !== false){
        $_SESSION['status_msg'] = "Categoria atualizada com sucesso";
    }
  } else{
    $_SESSION['status_msg'] = "Dados inválidos ou não inseridos";
  }
  header("Location: /gerenciamento/cadastros/categorias/");
}