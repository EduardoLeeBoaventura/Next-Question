<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "resources" . DIRECTORY_SEPARATOR . "system_functions.php";

  use System\Controller\Usuarios;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>COLT - Cadastre seu Clube</title>

  <?php include_once returnsPathFromHost("src", "Componentes", "head-imports.php"); ?>
  <link rel="stylesheet" href="<?= ASSETS_PATH ?>css/login.css">
</head>
<body>
  <div class="container mt-5 border border-dark rounded">
    <header class="text-center m-3">
      <img src="<?= ASSETS_PATH ?>imagens/logo-sistema.png" id="logo">
    </header>

    
    <?php
      if(!empty($_SESSION['form_action_status']) && $_SESSION['form_action_status'] !== false){
        $usuarios_handler = new Usuarios();
        $usuario = $usuarios_handler->listar([['U.ref', $_SESSION['form_action_status']]])->result[0];
        ?>
          <div class="border border-dark rounded">
            <p>
              Após a aprovação do seu usuário, <a href="/">faça login</a> utilizando o email <em style="text-decoration: underline;font-weight:bold;" onclick="navigator.clipboard.writeText(this.innerText);alert('copiado')"><?= $usuario['email'] ?></em> e os 6 primeiros dígitos do CPF.
            </p>
          </div>
        <?php
      } else {
        ?>
          <form class="border border-dark rounded p-3" method="post" action="/api/post/usuarios/cadastro.php">
            <input type="hidden" name="nivel_acesso" value="1">
            <input type="hidden" name="tipo" value="FUNCIONARIO_CLUBE">

            <div class="form-group">
              <label for="nome-add">Nome</label>
              <input type="text" name="nome" id="nome-add" class="form-control border border-dark" required>
            </div>

            <div class="form-group">
              <label for="telefone-add">Telefone</label>
              <input type="text" name="telefone" id="telefone-add" class="form-control border border-dark" data-format="(##) # ####-####||(##) ####-####" oninput="mask(this)" required placeholder="(00) 0000-0000 / (00) 9 0000-0000">
            </div>

            <div class="form-group">
              <label for="email-add">Email</label>
              <input type="email" name="email" id="email-add" class="form-control border border-dark" required>
            </div>

            <div class="form-group">
              <label for="cpf-add">CPF</label>
              <input type="text" name="cpf" id="cpf-add" class="form-control border border-dark" data-format="###.###.###-##" oninput="mask(this)" required placeholder="000.000.000-00">
            </div>

            <small>A senha, por padrão, corresponde aos 6 primeiros dígitos do CPF.</small>
      
            <div class="mb-3 mt-3">
              <input class="btn btn-warning d-block w-100" type="submit" value="Enviar">
            </div>

            <div class="text-center mb-3">
              <p class="m-0">Já possui uma conta?</p>
              <a href="/">Entrar</a>
            </div>
          </form>
        <?php
      }
    ?>
  </div>
  <?php
    include_once returnsPathFromHost("src", "Componentes", "footer-imports.php");
    include_once returnsPathFromHost("src", "Componentes", "modules", "page-footer.php");
  ?>
</body>
</html>