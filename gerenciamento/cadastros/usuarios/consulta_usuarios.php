<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "resources" . DIRECTORY_SEPARATOR . "system_functions.php";
?>
<div class="modal fade" id="modal-consulta" tabindex="-1" aria-labelledby="modal-consulta-usuariosLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="modal-consulta-usuariosLabel">Consultar Usuario(a)</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" style="border: 1px solid #0005;border-width: 1px 0px 1px 0px;">
        <form id="form-modal-consulta" action="/api/get/usuarios/consulta_usuarios.php" method="get" onsubmit="sendForm(event, this, addSearchedUsers)">
          <div class="form-group">
            <label for="cpf-consulta">CPF</label>
            <input type="text" name="cpf-consulta" id="cpf-consulta" class="form-control border border-dark" data-format="###.###.###-##" oninput="mask(this)" placeholder="000.000.000-00">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
        <button type="button" class="btn btn-warning" onclick="sendForm(event, document.querySelector('#form-modal-consulta'), addSearchedUsers)">Consultar</button>
      </div>
    </div>
  </div>
</div>