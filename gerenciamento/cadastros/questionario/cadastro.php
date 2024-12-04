<?php
require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "resources" . DIRECTORY_SEPARATOR . "system_functions.php";
?>
<div class="modal fade" id="modal-add" tabindex="-1" aria-labelledby="modal-add-usuariosLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="modal-add-usuariosLabel">Questionarios</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" style="border: 1px solid #0005;border-width: 1px 0px 1px 0px;">
        <form id="form-modal-add" action="/api/post/questionario/cadastro.php" method="post">
          <div class="form-group">
            <label for="nome-add">Questionarios</label>
            <input type="text" name="nome" id="nome-add" class="form-control border border-dark" required>
          </div>

          <div class="form-group">
            <label for="questionarios-add">Questionarios</label>
            <select name="questionarios" id="questionarios-add" class="select-select form-control" onchange="searchAcoesPrivilegio(this.value)">
              <option class="fixed" value="0">Selecione</option>
              <?php
              $questionario = $questionario_handler->listar();

              foreach ($questionario as $info_questionario) {
              ?>
                <option value="<?= $info_questionario['ref'] ?>"> <?= $info_questionario['nome'] ?></option>
              <?php
              }
              ?>
            </select>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
        <button type="button" class="btn btn-warning" onclick="document.querySelector('#form-modal-add').submit()">Cadastrar</button>
      </div>
    </div>
  </div>
</div>