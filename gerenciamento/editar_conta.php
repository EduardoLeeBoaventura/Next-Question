<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "resources" . DIRECTORY_SEPARATOR . "system_functions.php";

  define("PAINEL_CONTENT", returnsPathFromHost("src", "Componentes", "modules", "editar_conta.php"));
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>SYSTEM - Perfil</title>

  <style>
    .container-forms{
      display: flex;
      justify-content: space-between;
    }

    .container-forms > *{
      padding: 0px 20px;
      width: 100%;
    }
    .container-forms > .edit-account{
      border-right: 1px solid;
    }
  </style>

  <?php include_once returnsPathFromHost("src", "Componentes", "head-imports.php"); ?>
</head>
<body>
  <?php include_once returnsPathFromHost("src", "Componentes", "modules", "painel-body.php"); ?>

  <?php include_once returnsPathFromHost("src", "Componentes", "footer-imports.php"); ?>
</body>
</html>