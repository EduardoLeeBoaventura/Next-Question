<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "resources" . DIRECTORY_SEPARATOR . "system_functions.php";

  $listagem_usuarios = $usuarios_handler->listar();
?>
<div class="form-search bg-secondary rounded p-3 text-light">
  <form method="get">
    <div class="row">
      <div class="col">
        <label for="nome">Nome</label>
        <input type="text" class="form-control" name="nome" id="nome" placeholder="Nome do usuário" value="<?= @$_GET['nome'] ?>">
      </div>

      <div class="col">
        <label for="email">Email</label>
        <input type="text" class="form-control" name="email" id="email" placeholder="Email do usuário" value="<?= @$_GET['email'] ?>">
      </div>
      
      <div class="col">
        <label for="cpf">CPF</label>
        <input type="text" class="form-control" name="cpf" id="cpf" placeholder="CPF do usuário" value="<?= @$_GET['cpf'] ?>">
      </div>
    </div>
    <div class="row">
      <div class="col">
        <label for="tipo">Tipo de usuário</label>
        <select class="select-select form-control" name="tipo" id="tipo">
          <option class="fixed" value="">Todos</option>
          <option class="fixed" value="">Adminstrador</option>
          <option class="fixed" value="">Funcionario</option>
          <option class="fixed" value="">Filiado</option>
        </select>
      </div>
    </div>
    <div class="row">
      <div class="col">
        <label for="nivel">Nível de usuário</label>
        <select class="select-select form-control" name="nivel" id="nivel">
          <option class="fixed" value="">Todos</option>
          <option class="fixed" value="">Editar</option>
          <option class="fixed" value="">Ver</option>
        </select>
      </div>
      
      <div class="col">
        <label for="situacao">Situação do usuário</label>
        <select class="select-select form-control" name="situacao" id="situacao">
          <option class="fixed" value="">Todos</option>
          <option class="fixed" value="">Ativos</option>
          <option class="fixed" value="">Pendentes</option>
          <option class="fixed" value="">Inativos</option>
        </select>
      </div>

      <div class="col">
        <label for=""></label>
        <input type="submit" class="form-control btn btn-light" value="Pesquisar">
      </div>
    </div>
  </form>
</div>

<hr>

<div class="row">
  <?php
    if(havePrivilegeToDo(@returnsConstData('ROUTE_INFO')['title'], 'C')){
      ?>
        <button type="button" class="col-sm-2 btn btn-default text-primary d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#modal-add">
            <span class="material-symbols-outlined">add</span>
            Novo Usuario(a)
        </button>
      <?php
    }

    if(havePrivilegeToDo(@returnsConstData('ROUTE_INFO')['title'], 'R+')){
      ?>
        <button type="button" class="col-sm-2 btn btn-default text-primary d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#modal-consulta">
            <span class="material-symbols-outlined">search</span>
            Consultar Usuário
        </button>
      <?php
    }
  ?>
</div>

<table class="table table-secondary table-striped">
  <thead>
    <tr>
      <th>##</th>
      <th>Nome</th>
      <th>Email</th>
      <th>CPF</th>
      <th>Situação</th>
      <th>Ações</th>
    </tr>
  </thead>
  <tbody id="listagem-usuarios">
    <?php
      foreach($listagem_usuarios as $k => $usuarios_info){
        ?>
          <tr>
            <td><?= ++$k ?></td>
            <td><?= $usuarios_info['nome'] ?></td>
            <td><?= $usuarios_info['email'] ?></td>
            <td><?= "***.***.*" . $usuarios_handler->hideData($usuarios_info['cpf']) ?></td>
            <td><?= $usuarios_info['situacao'] ?></td>
            <td class="d-flex justify-content-center">

            <div class="dropdown">
              <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                Opções
              </button>
              <ul class="dropdown-menu">
                <?php
                  if(havePrivilegeToDo(@returnsConstData('ROUTE_INFO')['title'], 'U')){
                    ?>
                      <li>
                        <button type="button" class="btn btn-default d-flex align-items-center" style="gap: 10px;" data-bs-toggle="modal" data-bs-target="#modal-edit" onclick="setValues('<?= $usuarios_info['ref'] ?>','<?= $usuarios_info['nome'] ?>','<?= $usuarios_info['email'] ?>','<?= $usuarios_info['telefone'] ?>','<?= $usuarios_info['cpf'] ?>','<?= $usuarios_info['desenvolvedor'] ?>','<?= $usuarios_info['geral'] ?>')">
                          <span class="material-symbols-outlined">edit_square</span>
                          Editar
                        </button>
                      </li>

                      <li>
                        <button type="button" class="btn btn-default d-flex align-items-center" style="gap: 10px;" data-bs-toggle="modal" data-bs-target="#modal-alter-pass" title="Alterar Senha"  onclick="setValuesToAlterPass('<?= $usuarios_info['ref'] ?>')">
                          <span class="material-symbols-outlined">vpn_key</span>
                          Senha
                        </button>
                      </li>
                    <?php
                  }
                  if(havePrivilegeToDo(@returnsConstData('ROUTE_INFO')['title'], 'U+')){
                    ?>
                      <li>
                        <button type="button" class="btn btn-default d-flex align-items-center" style="gap: 10px;" data-bs-toggle="modal" data-bs-target="#modal-vinculo-niveis" title="Níveis"  onclick="setValuesToJoinNiveis('<?= $usuarios_info['ref'] ?>')">
                          <span class="material-symbols-outlined">badge</span>
                          Níveis
                        </button>
                      </li>
                      
                      <li>
                        <button type="button" class="btn btn-default d-flex align-items-center" style="gap: 10px;" data-bs-toggle="modal" data-bs-target="#modal-vinculo-privilegios" title="Privilégios"  onclick="setValuesToJoinPrivilegios('<?= $usuarios_info['ref'] ?>')">
                          <span class="material-symbols-outlined">military_tech</span>
                          Privilégios
                        </button>
                      </li>
                    <?php
                  }
                ?>
              </ul>
            </div>
            </td>
          </tr>
        <?php
      }
    ?>
  </tbody>
</table>
<script>
  function setValues(ref, nome, email, telefone, cpf, desenvolvedor, geral){
    const data = {
      "ref_registro": ref,
      "nome-edit": nome,
      "telefone-edit": telefone,
      "email-edit": email,
      "cpf-edit": cpf,
      "desenvolvedor-edit": desenvolvedor,
      "geral-edit": geral
    }

    sendValuesToEditForm(data);
  }

  function setValuesToAlterPass(ref){
    const data = {
      "ref_registro_alter_pass": ref
    }

    sendValuesToEditForm(data);
  }

  
  async function setValuesToJoinNiveis(ref){
    const data = {
      "ref_usuario-vinculo-niveis": ref
    }

    const url = "/api/get/usuarios/consulta_vinculo_niveis.php";

    const data_consulta = {
      usuario: ref
    };

    const options = {
      method: "get"
    };

    const json = await fetchData(url, data_consulta, options);

    const table = document.querySelector("#table-vinculos-usuario-niveis");
    table.innerHTML = "";
    if(json.status){
      const response = json.response;
      
      response.forEach(v=>{
        const tr = `
            <tr>
              <td>${v.nome}</td>
              <td class="d-flex align-items-center justify-content-center">
                <button class="btn btn-default" onclick="removeVinculoNiveis('${v.ref}', '${ref}')">
                  <span class="material-symbols-outlined">delete</span>
                </button>
              </td>
            </tr>
        `;

        table.innerHTML += tr;

        removeOption(v.ref, 'nivel-vinculo-niveis');
      });
    }

    sendValuesToEditForm(data);
  }
  async function removeVinculoNiveis(nivel, usuario){
    const url = "/api/get/usuarios/excluir_vinculo_niveis.php";

    const data_consulta = {
      nivel,
      usuario
    };

    const options = {
      method: "get"
    };

    if(confirm("Tem certeza que deseja remover este vínculo?")){
      const json = await fetchData(url, data_consulta, options);
  
      setValuesToJoinNiveis(usuario);
    }
  }

  async function searchAcoesPrivilegio(privilegio){
    const data = {
      privilegio
    }

    const url = "/api/get/usuarios/consulta_acoes_privilegios.php";

    const options = {
      method: "get"
    };

    const json = await fetchData(url, data, options);

    const form_group = document.querySelector("#acoes-privilegios");
    form_group.innerHTML = "";
    if(json.status){
      const response = json.response;

      response.forEach(v=>{
        let val = returnsAction(v);
        let normal = "";
        if(v.indexOf('+') != -1){
          normal_val = returnsAction(v.replace('+', ''));
          normal = `
            <label for="${normal_val}-add" style="margin-right: 10px;">
              <input type="checkbox" name="acoes-privilegios[]" multiple value="${v.replace('+', '')}" id="${normal_val}-add">
              ${normal_val}
            </label>
          `;
        }

        const input = `
            ${normal}
            <label for="${val}-add" style="margin-right: 10px;">
              <input type="checkbox" name="acoes-privilegios[]" multiple value="${v}" id="${val}-add">
              ${val}
            </label>
        `;

        form_group.innerHTML += input;
      });
    }
  }

  function returnsAction(v){
    let val = '';
    switch(v){
          case 'C':
            val = 'Cadastrar';
            break;
          case 'C+':
            val = 'Cadastrar +';
            break;
          case 'R':
            val = 'Ler';
            break;
          case 'R+':
            val = 'Ler +';
            break;
          case 'U':
            val = 'Editar';
            break;
          case 'U+':
            val = 'Editar +';
            break;
          case 'D':
            val = 'Excluir';
            break;
          case 'D+':
            val = 'Excluir +';
            break;
          case 'E':
            val = 'Emitir';
            break;
          case 'E+':
            val = 'Emitir +';
            break;
        }

        return val;
  }

  async function setValuesToJoinPrivilegios(usuario){
    const data = {
      "ref_usuario-vinculo-privilegios": usuario
    }

    const url = "/api/get/usuarios/consulta_vinculo_privilegios.php";

    const data_consulta = {
      usuario
    };

    const options = {
      method: "get"
    };

    const json = await fetchData(url, data_consulta, options);

    const table = document.querySelector("#table-vinculos-usuarios-privilegios");
    table.innerHTML = "";
    if(json.status){
      const response = json.response;

      response.forEach(v=>{
        const tr = `
            <tr>
              <td>${v.pagina}</td>
              <td>${v.permissoes}</td>
              <td>${v.tipo}</td>
              <td class="d-flex align-items-center justify-content-center">
                <button class="btn btn-default" onclick="removeVinculoPrivilegios('${v.ref}', '${usuario}')">
                  <span class="material-symbols-outlined">delete</span>
                </button>
              </td>
            </tr>
        `;

        table.innerHTML += tr;
      });
    }

    sendValuesToEditForm(data);
  }

  async function removeVinculoPrivilegios(privilegio, usuario){
    const url = "/api/get/usuarios/excluir_vinculo_privilegios.php";

    const data_consulta = {
      privilegio
    };

    const options = {
      method: "get",
      headers: {
        'Content-Type': 'application/json'
      }
    };

    if(confirm("Tem certeza que deseja remover este vínculo?")){
      const response = await fetchData(url, data_consulta, options);

      setValuesToJoinPrivilegios(usuario);
      location.reload();
    }
  }
</script>