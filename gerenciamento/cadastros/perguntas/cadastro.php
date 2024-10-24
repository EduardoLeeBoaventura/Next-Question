<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "resources" . DIRECTORY_SEPARATOR . "system_functions.php";
?>
<div class="modal fade" id="modal-add" tabindex="-1" aria-labelledby="modal-add-usuariosLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="modal-add-usuariosLabel">Cadastrar Questões</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" style="border: 1px solid #0005;border-width: 1px 0px 1px 0px;">
        <form id="form-modal-add" action="/api/post/usuarios/cadastro.php" method="post">
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
            <small>A senha, por padrão, corresponde aos 6 primeiros dígitos do CPF.</small>
          </div>

          <?php
            if(USER_INFO['desenvolvedor']){
              ?>
                <div class="form-group mt-3">
                  <label for="desenvolvedor-add">
                    <input type="radio" name="usuario-especial" value="desenvolvedor" id="desenvolvedor-add">
                    Desenvolvedor
                  </label>
                  
                  <label for="geral-add" style="margin-left: 10px;">
                    <input type="radio" name="usuario-especial" value="geral" id="geral-add">
                    Geral
                  </label>
                </div>
              <?php
            }
          ?>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
        <button type="button" class="btn btn-warning" onclick="document.querySelector('#form-modal-add').submit()">Cadastrar</button>
      </div>
    </div>
  </div>
</div>