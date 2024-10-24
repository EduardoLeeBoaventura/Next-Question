<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "resources" . DIRECTORY_SEPARATOR . "system_functions.php";

  // definições para importação dos módulos da página
    $includes = [];
    if(havePrivilegeToDo(@returnsConstData('ROUTE_INFO')['title'], 'C')){
      array_push($includes, returnsPathFromHost("gerenciamento", "cadastros", "perguntas", "cadastro.php"));
    }

    if(havePrivilegeToDo(@returnsConstData('ROUTE_INFO')['title'], 'R')){
      array_push($includes, returnsPathFromHost("gerenciamento", "cadastros", "perguntas", "listagem.php"));
    }

    if(havePrivilegeToDo(@returnsConstData('ROUTE_INFO')['title'], 'U')){
      array_push($includes, returnsPathFromHost("gerenciamento", "cadastros", "perguntas", "edicao.php"));
      array_push($includes, returnsPathFromHost("gerenciamento", "cadastros", "perguntas", "vinculo_privilegios.php"));
    }
    
    define("PAINEL_CONTENT", $includes);
  // definições para importação dos módulos da página

  use System\Controller\NiveisAcessos;
  
  $niveis_acessos_handler = new NiveisAcessos();
  
  $GLOBALS['item-sidebar'] = "niveis_acessos";
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>SYSTEM - Privilégios</title>

  <?php include_once returnsPathFromHost("src", "Componentes", "head-imports.php"); ?>
</head>
<body>
  <?php
    include_once returnsPathFromHost("src", "Componentes", "modules", "painel-body.php");
   
    include_once returnsPathFromHost("src", "Componentes", "footer-imports.php");
  ?>
</body>
</html>