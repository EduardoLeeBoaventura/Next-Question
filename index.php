<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "resources" . DIRECTORY_SEPARATOR . "system_functions.php";

  define("PAINEL_CONTENT", returnsPathFromHost("gerenciamento", "dashboard.php"));
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>SYSTEM - Painel</title>

  <?php include_once returnsPathFromHost("src", "Componentes", "head-imports.php"); ?>
</head>
<body>
  <?php include_once returnsPathFromHost("src", "Componentes", "modules", "painel-body.php"); ?>

  <?php include_once returnsPathFromHost("src", "Componentes", "footer-imports.php"); ?>
</body>
</html>