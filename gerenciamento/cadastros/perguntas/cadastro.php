<?php
require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "resources" . DIRECTORY_SEPARATOR . "system_functions.php";
?>
<div class="modal fade" id="modal-add" tabindex="-1" aria-labelledby="modal-add-usuariosLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="modal-add-usuariosLabel">Cadastrar Pergunta</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" style="border: 1px solid #0005;border-width: 1px 0px 1px 0px;">
        <form id="form-modal-add" action="/api/post/perguntas/cadastro.php" method="post">
          <div class="form-group">
            <label for="pergunta-add">Pergunta</label>
            <textarea type="text" name="pergunta" id="pergunta-add" class="form-control border border-dark" required></textarea>
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
            <label>Alternativas</label>
            <ol type="a">
              <li>
                <input type="text" id="alternativa1" name="alternativa[]" multiple>
                <input type="radio" id="alternativa1" name="gabarito" value="0">
              </li>
              <br>
              <li>
                <input type="text" id="alternativa2" name="alternativa[]" multiple>
                <input type="radio" id="alternativa2" name="gabarito" value="1">
              </li>
              <br>
              <li>
                <input type="text" id="alternativa3" name="alternativa[]" multiple>
                <input type="radio" id="alternativa3" name="gabarito" value="2">
              </li>
              <br>
              <li>
                <input type="text" id="alternativa4" name="alternativa[]" multiple>
                <input type="radio" id="alternativa4" name="gabarito" value="3">
              </li>
            </ol>
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