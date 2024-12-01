<?php

use System\Controller\PerguntasConnCategorias;

require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "resources" . DIRECTORY_SEPARATOR . "system_functions.php";

$listagem_perguntas = $perguntas_handler->listar();
?>
<div class="form-search bg-secondary rounded p-3 text-light">
  <form method="get">
    <div class="row">
      <div class="col">
        <label for="pergunta">Pergunta</label>
        <input type="text" class="form-control" name="pergunta" id="pergunta" placeholder="Enunciado da Pergunta" value="<?= @$_GET['pergunta'] ?>">
      </div>

      <div class="col">
        <label for="descricao">Descrição</label>
        <input type="text" class="form-control" name="descricao" id="descricao" placeholder="Descrição da pergunta" value="<?= @$_GET['descricao'] ?>">
      </div>

      <div class="col">
        <label for="situacao">Situação da pergunta</label>
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
      Nova Pergunta
    </button>
  <?php
  }
?>
</div>

<table class="table table-secondary table-striped">
  <thead>
    <tr>
      <th>##</th>
      <th>Categoria</th>
      <th>Enunciado</th>
      <th>Alternativas</th>
      <th>Gabarito</th>
      <th>Ações</th>
    </tr>
  </thead>
  <tbody id="listagem-perguntas">
    <?php
      foreach($listagem_perguntas as $k => $perguntas_info){
        $lista_perguntas_conn_categorias = $perguntas_conn_categorias_handler->listar(null, [['P.ref', $perguntas_info['ref']]]);

        $perguntas_categorias = array_map(function ($v){
          return $v['nome'];
        }, $lista_perguntas_conn_categorias);

        $perguntas_categorias_txt = implode(', ', $perguntas_categorias);
        ?>
          <tr>
            <td><?= ++$k ?></td>
            <td><?= $perguntas_categorias_txt ?></td> 
            <td><?= $perguntas_info['pergunta'] ?></td>
            <td></td>
            <td></td>
            <td class="d-flex justify-content-center">
              <?php
                if(havePrivilegeToDo(@returnsConstData('ROUTE_INFO')['title'], 'U')){
                  ?>
                    <button type="button" class="btn btn-default d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#modal-edit" onclick="setValues('<?= $perguntas_info['ref'] ?>','<?= $perguntas_info['pergunta'] ?>')">
                      <span class="material-symbols-outlined">edit_square</span>
                    </button>
                  <?php
                }

                if(havePrivilegeToDo(@returnsConstData('ROUTE_INFO')['title'], 'D')){
                  ?>
                    <button type="button" class="btn btn-default d-flex align-items-center" onclick="removePerguntas('<?= $perguntas_info['ref'] ?>')">
                      <span class="material-symbols-outlined">delete</span> 
                    </button>
                  <?php
                }
              ?>
            </td>
          </tr>
        <?php
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

  
  // onclick="removeperguntas('< ?= $pergunta_info['ref'] ?>')"
  async function removePerguntas(perguntas){
    const url = "/api/get/perguntas/excluir_perguntas.php";

    const data_consulta = {
      perguntas
    };

    const options = {
      method: "get",
      headers: {
        'Content-Type': 'application/json'
      }
    };

    if(confirm("Tem certeza que deseja remover essa Pergunta?")){
      const response = await fetchData(url, data_consulta, options);

      //setValuesToJoinPrivilegios(perguntas);
      location.reload();
    }
  }
</script>