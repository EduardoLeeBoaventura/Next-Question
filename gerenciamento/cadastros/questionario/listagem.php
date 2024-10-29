<?php
require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "resources" . DIRECTORY_SEPARATOR . "system_functions.php";

$listagem_questionario = $questionario_handler->listar();
?>
<div class="form-search bg-secondary rounded p-3 text-light">
  <form method="get">
    <div class="row">
      <div class="col">
        <label for="nome">Questionario</label>
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
        <label for="situacao">Situação da categoria</label>
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
  if (havePrivilegeToDo(@returnsConstData('ROUTE_INFO')['title'], 'C')) {
  ?>
    <button type="button" class="col-sm-2 btn btn-default text-primary d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#modal-add">
      <span class="material-symbols-outlined">add</span>
      Novo Nível
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
      <th>Privilégios Associados</th>
      <th>Situação</th>
      <th>Ações</th>
    </tr>
  </thead>

  <tbody>

    <?php


    foreach ($lista_categorias as $info_categoria) {
    ?>
      <tr>

        <td>Nome</td> 
        <td>Descrição</td> 
      </tr>
    <?php
    }
    ?>

  </tbody>
</table>

<small>
  C = Cadastrar | R = Ler / Ver | U = Editar | D = Excluir | E = Emitir.
  <br>O '+' é pra indicar que possui alguma ação naquele sentido que é especial (deve ser mais restrita).
</small>

<script>
  function setValues(ref, nome, situacao) {
    const data = {
      "ref_registro": ref,
      "nome-edit": nome,
      "situacao-edit": situacao
    }

    sendValuesToEditForm(data);
  }

  async function searchAcoesPrivilegio(privilegio) {
    const data = {
      privilegio
    }

    const url = "/api/get/niveis_acessos/consulta_acoes_privilegios.php";

    const options = {
      method: "get"
    };

    const json = await fetchData(url, data, options);

    const form_group = document.querySelector("#acoes-privilegios");
    form_group.innerHTML = "";
    if (json.status) {
      const response = json.response;

      response.forEach(v => {
        let val = returnsAction(v);
        let normal = "";
        if (v.indexOf('+') != -1) {
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

  function returnsAction(v) {
    let val = '';
    switch (v) {
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

  async function setValuesToJoinPrivilegios(nivel) {
    const data = {
      "ref_nivel-vinculo-privilegios": nivel
    }

    const url = "/api/get/niveis_acessos/consulta_vinculo_privilegios.php";

    const data_consulta = {
      nivel
    };

    const options = {
      method: "get"
    };

    const json = await fetchData(url, data_consulta, options);

    const table = document.querySelector("#table-vinculos-niveis-privilegios");
    table.innerHTML = "";
    if (json.status) {
      const response = json.response;

      response.forEach(v => {
        const tr = `
            <tr>
              <td>${v.pagina}</td>
              <td>${v.permissoes}</td>
              <td>${v.tipo}</td>
              <td class="d-flex align-items-center justify-content-center">
                <button class="btn btn-default" onclick="removeVinculo('${v.ref}', '${nivel}')">
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

  async function removeVinculo(privilegio, nivel) {
    const url = "/api/get/niveis_acessos/excluir_vinculo_privilegios.php";

    const data_consulta = {
      privilegio
    };

    const options = {
      method: "get",
      headers: {
        'Content-Type': 'application/json'
      }
    };

    if (confirm("Tem certeza que deseja remover este vínculo?")) {
      const response = await fetchData(url, data_consulta, options);

      setValuesToJoinPrivilegios(nivel);
      location.reload();
    }
  }
</script>