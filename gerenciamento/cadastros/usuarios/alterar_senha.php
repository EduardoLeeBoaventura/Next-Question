<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "resources" . DIRECTORY_SEPARATOR . "system_functions.php";
?>
<div class="modal fade" id="modal-alter-pass" tabindex="-1" aria-labelledby="modal-alter-passLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="modal-alter-passLabel">Editar usuário</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" style="border: 1px solid #0005;border-width: 1px 0px 1px 0px;">
        <form id="form-modal-alter-pass" action="/api/post/usuarios/alterar_senha.php" method="post">
          <input type="hidden" name="ref_registro" id="ref_registro_alter_pass">
          <div class="form-group">
            <label for="senha-alter-pass">Nova Senha</label>
            <input type="password" name="senha" id="senha-alter-pass" class="form-control border border-dark">
            <small>Escreva a nova senha para este usuário.</small>
          </div>

          <div class="form-group">
            <label for="senha-autorizacao-alter-pass">Senha de Autorização</label>
            <input type="password" name="senha-autorizacao" id="senha-autorizacao-alter-pass" class="form-control border border-dark">
            <small>Escreva sua senha de autorização.</small>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
        <button type="button" class="btn btn-warning" onclick="document.querySelector('#form-modal-alter-pass').submit()">Alterar Senha</button>
      </div>
    </div>
  </div>
</div>