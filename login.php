<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "resources" . DIRECTORY_SEPARATOR . "system_functions.php";
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>System</title>

  <?php include_once returnsPathFromHost("src", "Componentes", "head-imports.php"); ?>
  <link rel="stylesheet" href="<?= ASSETS_PATH ?>css/login.css">
</head>

<body>
  <div class="container mt-5 border border-dark rounded">
    <header class="text-center m-3">
      <img src="<?= ASSETS_PATH ?>imagens/logo-sistema.png" id="logo">
    </header>
    <form id="form-login" method="post" action="/api/post/usuarios/login.php">
      <h5 class="text-center">SYSTEM</h5>
      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input class="form-control border border-dark" type="email" name="email">
      </div>

      <div class="mb-3">
        <label for="senha" class="form-label">Senha</label>
        <input class="form-control border border-dark" type="password" name="senha" id="senha">
      </div>

      <div class="mb-3">
        <input class="btn btn-warning d-block w-100" type="submit" value="Enviar">
      </div>

      <div class="text-center mb-3">
        <p class="m-0">Ainda não possui uma conta?</p>
        <a href="/cadastro_livre/usuarios">Cadastrar</a>
      </div>
    </form>

  </div>
  <?php
    include_once returnsPathFromHost("src", "Componentes", "footer-imports.php");
    include_once returnsPathFromHost("src", "Componentes", "modules", "page-footer.php");
  ?>
</body>

</html>