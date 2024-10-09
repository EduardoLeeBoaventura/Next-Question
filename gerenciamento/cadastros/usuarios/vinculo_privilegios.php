<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "resources" . DIRECTORY_SEPARATOR . "system_functions.php";
?>
<div class="modal fade" id="modal-vinculo-privilegios" tabindex="-1" aria-labelledby="modal-vinculo-privilegios-usuariosLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="modal-vinculo-privilegios-usuariosLabel">Cadastrar Usuario(a)</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" style="border: 1px solid #0005;border-width: 1px 0px 1px 0px;">
        <div class="border border-black rounded" style="height: 300px;overflow: auto;" id="vinculos-usuarios-privilegios">
          <table class="table table-bordered border border-black" id="table-vinculos-usuarios-privilegios">
          </table>
        </div>

        <form id="form-modal-vinculo-privilegios" action="/api/post/usuarios/vinculo_privilegios.php" method="post">
          <input type="hidden" name="usuario" id="ref_usuario-vinculo-privilegios">

          <div class="form-group">
            <label for="privilegios-vinculo-privilegios">Privilégios</label>
            <select name="privilegio" id="privilegios-vinculo-privilegios" class="select-select form-control" onchange="searchAcoesPrivilegio(this.value)">
              <option class="fixed" value="0">Selecione</option>
              <?php
                $privilegios = readRoutesJson();
                foreach($privilegios as $privilegio){
                  $user_info = returnsConstData('USER_INFO');
                  $desenvolvedor = (bool)@$user_info['desenvolvedor'];
                  $liberar_privilegio = $privilegio['title'] != 'Cadastros de Níveis de Acessos' || $desenvolvedor;

                  if(!empty($privilegio['title']) && array_search('privilege', $privilegio['requirements']) !== false && $liberar_privilegio){
                    ?>
                      <option class="fixed" value="<?= $privilegio['title'] ?>"><?= $privilegio['title'] ?></option>
                    <?php
                  }
                }
              ?>
            </select>
          </div>

          <div class="form-group mt-3" id="acoes-privilegios">
          </div>

          <div class="form-group">
            <label for="tipo_vinculacao-vinculo-privilegios">Tipo Vínculo</label>
            <select name="tipo_vinculacao" id="tipo_vinculacao-vinculo-privilegios" class="select-select form-control">
              <option class="fixed" value="AUTORIZAR">Autorizar</option>
              <option class="fixed" value="BLOQUEAR">Bloquear</option>
            </select>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
        <button type="button" class="btn btn-warning" onclick="document.querySelector('#form-modal-vinculo-privilegios').submit()">Cadastrar</button>
      </div>
    </div>
  </div>
</div>