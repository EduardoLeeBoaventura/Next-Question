<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "resources" . DIRECTORY_SEPARATOR . "system_functions.php";
?>
<div class="modal fade" id="modal-vinculo-niveis" tabindex="-1" aria-labelledby="modal-vinculo-niveis-competicaoLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="modal-vinculo-niveis-competicaoLabel">Cadastrar Vínculo Usuário - Níveis</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" style="border: 1px solid #0005;border-width: 1px 0px 1px 0px;">
        <div class="border border-black rounded" style="height: 300px;overflow: auto;" id="vinculos-usuario-niveis">
          <table class="table table-bordered border border-black" id="table-vinculos-usuario-niveis">
          </table>
        </div>

        <form id="form-modal-vinculo-niveis" action="/api/post/usuarios/vinculo_niveis.php" method="post">
          <input type="hidden" name="usuario" id="ref_usuario-vinculo-niveis">

          <div class="form-group">
            <label for="nivel-vinculo-niveis">Nível</label>
            <select name="nivel" id="nivel-vinculo-niveis" class="select-select form-control">
              <option class="fixed" value="0">Selecione</option>
              <?php
                foreach($niveis as $nivel){
                  ?>
                    <option class="fixed" value="<?= $nivel['ref'] ?>"><?= $nivel['nome'] ?></option>
                  <?php
                }
              ?>
            </select>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
        <button type="button" class="btn btn-warning" onclick="document.querySelector('#form-modal-vinculo-niveis').submit()">Cadastrar</button>
      </div>
    </div>
  </div>
</div>