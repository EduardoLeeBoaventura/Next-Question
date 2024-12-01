<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "resources" . DIRECTORY_SEPARATOR . "system_functions.php";
?>
<div class="modal fade" id="modal-edit" tabindex="-1" aria-labelledby="modal-editLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="modal-editLabel">Editar pergunta</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" style="border: 1px solid #0005;border-width: 1px 0px 1px 0px;">
        <form id="form-modal-edit" action="/api/post/perguntas/edicao.php" method="post">
          <input type="hidden" name="ref_registro" id="ref_registro">
          <div class="form-group">
            <label for="pergunta-edit">Pergunta</label>
            <input type="text" name="pergunta" id="pergunta-edit" class="form-control border border-dark">
          </div>
          
          <div class="form-group">
            <label for="categorias">Categoria</label>
            <select name="categorias[]" multiple id="categoria-add" class="select-select multiple form-control" onchange="searchAcoesPrivilegio(this.value)">
              <option class="fixed" value="0">Selecione</option>

              <?php
              $lista_categorias = $categorias_handler->listar();
              if ($lista_categorias !== false) {
                foreach ($lista_categorias as $info_categoria) {
              ?>

                  <option class="fixed" value="<?= $info_categoria['ref'] ?>"> <?= $info_categoria['nome'] ?></option>
              <?php
                }
              }
              ?>
            </select>
          </div>

          <div class="form-group">
            <label for="situacao-edit">Situação da Questão</label>
            <select name="situacao" id="situacao-edit" class="select-select form-control">
                <option class="fixed" value="ATIVO">Ativo</option>
                <option class="fixed" value="INATIVO">Inativo</option>
            </select>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
        <button type="button" class="btn btn-warning" onclick="document.querySelector('#form-modal-edit').submit()">Editar</button>
      </div>
    </div>
  </div>
</div>