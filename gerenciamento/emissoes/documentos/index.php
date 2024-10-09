<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "resources" . DIRECTORY_SEPARATOR . "system_functions.php";
  
  require_once returnsPathFromHost("gerenciamento", "emissoes", 'documentos', 'auxiliares', 'definicoes_chaves_get_emissoes.php');
  require_once returnsPathFromHost("gerenciamento", "emissoes", 'documentos', 'auxiliares', 'funcoes.php');

  $dados_emissao = retornaDadosEmissao(@$GLOBALS['document']);
  $page_title = $dados_emissao['page_title'];
  $arquivo_pagina = $dados_emissao['conteudo_pagina'] ?? '';
  
  $dados_do_banco_para_emissao = getInformacaoDoBanco();
  if($dados_do_banco_para_emissao === false || empty($dados_do_banco_para_emissao)){
    $page_title = false;
  }
  
  // INFORMAÇÕES UNIDADE
    $nome_fantasia    = $dados_do_banco_para_emissao['nome_unidade'];
    // DADOS DA AGÊNCIA / UNIDADE
  // FIM \. INFORMAÇÕES UNIDADE
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="robots" content="noindex"/>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="shortcut icon" href="<?= ASSETS_PATH ?>imagens/logo-sistema.png" type="image/x-icon" />
  
  <title>SYSTEM | Emissão - <?= ($page_title === false) ? 'Documento não encontrado' : $page_title  ?></title>

  <link rel="stylesheet" href="<?= ASSETS_PATH ?>css/emissoes/style.css">
</head>
<body>
  <div id="conteudo-impressao">
    <div class="pagina">
      <div class="bg"></div>
      <div class="conteudo">
        <header class="cabecalho complemento-da-pagina">
          <img class="logo" src="<?= ASSETS_PATH ?>imagens/logo-sistema.png">
          <h1><?= ($page_title === false) ? 'Documento não encontrado' : $page_title  ?></h1>
        </header>

        <section class="corpo">
          <?php
            if($arquivo_pagina && file_exists(__DIR__ . DIRECTORY_SEPARATOR . 'conteudos' . DIRECTORY_SEPARATOR . $arquivo_pagina) && $page_title !== false){
              include(__DIR__ . DIRECTORY_SEPARATOR . 'conteudos' . DIRECTORY_SEPARATOR . $arquivo_pagina);
            }
          ?>
        </section>

        <footer class="rodape complemento-da-pagina">
          <p>
            <? $nome_fantasia ?>
            <br>
            <?= $endereco_unidade ?>, <?= $numero_unidade ?>, <?= $bairro_unidade ?>, <?= $cidade_unidade ?> - <?= $uf_unidade ?>
            <br>
            emissão: <?= date('d/m/Y') ?>
          </p>
        </footer>
      </div>
    </div>
  </div>

  <script>
    onload = ()=>{
      const estrutura_pagina = setPaginaInicial();

      setHeight();

      dividirEmPaginas(estrutura_pagina);
      
      preparaElementosEditaveis();
    }

    function preparaElementosEditaveis(){
      const editaveis = document.querySelectorAll('.editavel');
      for(let editavel of editaveis){
        editavel.addEventListener('dblclick', ()=>{
          preparaEdicao(editavel);
        });
      }
    }

    function preparaEdicao(elemento){
      const input = retornaInputParaEditavel();

      const elemento_inner = elemento.innerText;
      input.value = elemento_inner;

      elemento.innerHTML = '';
      elemento.appendChild(input);

      input.focus();
    }

    function aplicarEdicao(input){
      const pai = input.parentElement;
      const valor = input.value;

      pai.innerText = valor;
    }

    function retornaInputParaEditavel(){
      const input = document.createElement('input');
      input.type = 'text';
      input.addEventListener('blur', ()=>{aplicarEdicao(input)});

      return input;
    }

    function setPaginaInicial(){
      const pagina_inicial = document.querySelector('.pagina');

      const conteudo = document.querySelector('#conteudo-impressao');
      
      pagina_inicial.id = 'inicial';
      const corpo_pagina_inicial = document.querySelector('#inicial .corpo');

      conteudo.innerHTML += pagina_inicial.outerHTML;

      const corpo_segunda_pagina = document.querySelectorAll('#conteudo-impressao .pagina .corpo')[1];
      corpo_segunda_pagina.innerHTML = '';
      
      const segunda_pagina = document.querySelectorAll('#conteudo-impressao .pagina')[1];
      segunda_pagina.id = '';
      const estrutura_pagina = segunda_pagina.outerHTML;
      segunda_pagina.remove();

      return estrutura_pagina;
    }

    function setHeight(){
      const corpo_inicial = document.querySelector('#inicial .corpo');
      for(let child of corpo_inicial.children){
        child.dataset.height = child.clientHeight;
      }
    }

    function dividirEmPaginas(estrutura_pagina){
      const corpo_pagina_inicial = document.querySelector('#inicial .corpo');
      const corpo_height = corpo_pagina_inicial.clientHeight;
      const conteudo     = document.querySelector('#conteudo-impressao');
      conteudo.innerHTML += estrutura_pagina;


      let pagina = 1;
      let height_atual = 0;
      for(let elemento of corpo_pagina_inicial.children){
        const corpo_pagina = document.querySelectorAll('#conteudo-impressao .pagina .corpo')[pagina];
        const height_elemento = parseInt(elemento.dataset.height);
        const height_futuro   = height_atual + height_elemento;

        if(height_futuro > corpo_height){
          conteudo.innerHTML += estrutura_pagina;

          pagina++;
          
          const proximo_corpo_pagina = document.querySelectorAll('#conteudo-impressao .pagina .corpo')[pagina];
          proximo_corpo_pagina.innerHTML = elemento.outerHTML;

          height_atual = height_elemento;
        } else {
          corpo_pagina.innerHTML += elemento.outerHTML;

          height_atual = height_futuro;
        }
      }

      const pagina_inicial = document.querySelector('#inicial');
      pagina_inicial.style.display = 'none';
    }

    function visibilidadeDoProtetorDeCarregamento(acao){
      const protetor_carregamento = document.querySelector('#protetor-carregamento');
      if(acao == 'esconder'){
        protetor_carregamento.style.display = 'none';
      } else {
        protetor_carregamento.style.display = 'block';
      }
    }
  </script>
</body>
</html>