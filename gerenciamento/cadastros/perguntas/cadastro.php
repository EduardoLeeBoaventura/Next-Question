<?php
require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "resources" . DIRECTORY_SEPARATOR . "system_functions.php";
?>
<div class="modal fade" id="modal-add" tabindex="-1" aria-labelledby="modal-add-usuariosLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="modal-add-usuariosLabel">Cadastrar Perguntas</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" style="border: 1px solid #0005;border-width: 1px 0px 1px 0px;">
        <form id="form-modal-add" action="/api/post/perguntas/cadastro.php" method="post">
          <div class="form-group">
            <label for="enunciado-add">Enunciado</label>
            <input type="text" name="enunciado" id="enunciado-add" class="form-control border border-dark" required>
          </div>

          <div class="form-group">
            <label for="descricao-add">Descrição</label>
            <textarea type="text" name="descricao" id="descricao-add" class="form-control border border-dark" required></textarea>
          </div>

          <div class="form-group">
            <label for="categoria_superior-add">Categoria Superior</label>
            <select name="categoria_superior" id="categoria_superior-add" class="select-select form-control" onchange="searchAcoesPrivilegio(this.value)">
              <option class="fixed" value="0">Selecione</option>
              <?php
              if ( $lista_categorias !== false) {
                $lista_categorias = $categorias_handler->listar();

                foreach ($lista_categorias as $info_categoria) {
                  ?>
                    <option value="<?= $info_categoria['ref'] ?>"> <?= $info_categoria['nome'] ?></option>
                  <?php
                }
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