<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "resources" . DIRECTORY_SEPARATOR . "system_functions.php";

  $options = returnsPathFromHost("gerenciamento", "emissoes", 'options.php');
  
  $includes = [$options];
  define("PAINEL_CONTENT", $includes);
  // definições para importação dos módulos da página

  $GLOBALS['item-sidebar'] = "emissoes";
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <title>SYSTEM - Emissoes</title>

  <?php
    require_once returnsPathFromHost("src", "Componentes", "head-imports.php");
  ?>
</head>
<body>
  <?php
    require_once returnsPathFromHost("src", "Componentes", "modules", "painel-body.php");

    require_once returnsPathFromHost("src", "Componentes", "footer-imports.php");
  ?>
</body>
</html>