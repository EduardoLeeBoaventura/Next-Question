<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "resources" . DIRECTORY_SEPARATOR . "system_functions.php";
?>
<div class="container-forms">
  <div class="edit-account">
    <form id="form-modal-edit" action="/api/post/usuarios/edicao.php" method="post">
      <input type="hidden" name="ref_registro" value="<?= @returnsConstData('USER_INFO')['ref'] ?>">
      <input type="hidden" name="from" value="edit-account">
      
      <div class="form-group">
        <label for="nome-edit">Nome</label>
        <input type="text" name="nome" id="nome-edit" class="form-control border border-dark" value="<?= @returnsConstData('USER_INFO')['nome'] ?>">
      </div>
      
      <div class="form-group">
        <label for="email-edit">Email</label>
        <input type="text" name="email" id="email-edit" class="form-control border border-dark" value="<?= @returnsConstData('USER_INFO')['email'] ?>">
      </div>
      
      <div class="form-group">
        <label for="telefone-edit">Telefone</label>
        <input type="text" name="telefone" id="telefone-edit" class="form-control border border-dark" data-format="(##) ####-####" oninput="mask(this)" value="<?= @returnsConstData('USER_INFO')['telefone'] ?>">
      </div>
      
      <div class="form-group">
        <label for="cpf-edit">CPF</label>
        <input type="text" name="cpf" id="cpf-edit" class="form-control border border-dark" data-format="###.###.###-##" oninput="mask(this)" value="<?= @returnsConstData('USER_INFO')['cpf'] ?>">
      </div>
    </form>
    <br>
    <button type="button" class="btn btn-warning" onclick="document.querySelector('#form-modal-edit').submit()">Editar</button>
  </div>
  
  <div class="alterar-senha">
    <form id="form-modal-alter-pass" action="/api/post/usuarios/alterar_senha.php" method="post">
      <input type="hidden" name="ref_registro" value="<?= @returnsConstData('USER_INFO')['ref'] ?>">
      <input type="hidden" name="from" value="edit-account">
      
      <div class="form-group">
        <label for="senha-autorizacao-alter-pass">Senha Atual</label>
        <input type="password" name="senha-autorizacao" id="senha-autorizacao-alter-pass" class="form-control border border-dark">
        <small>Escreva sua senha de atual.</small>
      </div>

      <div class="form-group">
        <label for="senha-alter-pass">Nova Senha</label>
        <input type="password" name="senha" id="senha-alter-pass" class="form-control border border-dark">
        <small>Escreva a nova senha para a sua conta.</small>
      </div>
    </form>
    <br>
    <button type="button" class="btn btn-warning" onclick="document.querySelector('#form-modal-alter-pass').submit()">Alterar Senha</button>
  </div>
</div>