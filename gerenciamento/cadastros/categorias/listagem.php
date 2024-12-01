<?php
require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "resources" . DIRECTORY_SEPARATOR . "system_functions.php";

$listagem_categorias = $categorias_handler->listar();
?>
<div class="form-search bg-secondary rounded p-3 text-light">
  <form method="get">
    <div class="row">
      <div class="col">
        <label for="nome">Nome</label>
        <input type="text" class="form-control" name="nome" id="nome" placeholder="Nome da Categoria" value="<?= @$_GET['nome'] ?>">
      </div>

      <div class="col">
        <label for="descricao">Descrição</label>
        <input type="text" class="form-control" name="descricao" id="descricao" placeholder="Descrição da Categoria" value="<?= @$_GET['descricao'] ?>">
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
      Nova Categoria
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
      <th>Descrição</th>
      <th>Categoria Superior</th>
      <th>Ações</th>
    </tr>
  </thead>
  <tbody id="listagem-categorias">
    <?php
    if ( $lista_categorias !== false) {
      foreach($listagem_categorias as $k => $categorias_info){
        ?>
          <tr>
            <td><?= ++$k ?></td>
            <td><?= $categorias_info['nome'] ?></td>
            <td><?= $categorias_info['descricao'] ?></td>
            <td><?= $categorias_info['id_superior'] ?></td>
            <td class="d-flex justify-content-center">
              <?php
                if(havePrivilegeToDo(@returnsConstData('ROUTE_INFO')['title'], 'U')){
                  ?>
                    <button type="button" class="btn btn-default d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#modal-edit" onclick="setValues('<?= $categorias_info['ref'] ?>','<?= $categorias_info['nome'] ?>','<?= $categorias_info['descricao'] ?>')">
                      <span class="material-symbols-outlined">edit_square</span>
                    </button>
                  <?php
                }

                if(havePrivilegeToDo(@returnsConstData('ROUTE_INFO')['title'], 'D')){
                  ?>
                    <button type="button" class="btn btn-default d-flex align-items-center" onclick="removeCategorias('<?= $categorias_info['ref'] ?>')">
                      <span class="material-symbols-outlined">delete</span> 
                    </button>
                  <?php
                }
              ?>
            </td>
          </tr>
        <?php
      }
    }
    ?>
  </tbody>
</table>
<script>
  function setValues(ref, nome, descricao){
    const data = {
      "ref_registro": ref,
      "nome-edit": nome,
      "descricao-edit": descricao,
    }

    sendValuesToEditForm(data);
  }

  function setValuesToAlterPass(ref){
    const data = {
      "ref_registro_alter_pass": ref
    }

    sendValuesToEditForm(data);
  }

  
  // onclick="removeCategorias('< ?= $categoria_info['ref'] ?>')"
  async function removeCategorias(categorias){
    const url = "/api/get/categorias/excluir_categorias.php";

    const data_consulta = {
      categorias
    };

    const options = {
      method: "get",
      headers: {
        'Content-Type': 'application/json'
      }
    };

    if(confirm("Tem certeza que deseja remover essa categoria?")){
      const response = await fetchData(url, data_consulta, options);

      //setValuesToJoinPrivilegios(categorias);
      location.reload();
    }
  }
</script>