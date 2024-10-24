<div id="sidebar" class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark justify-content-between">
  <div>
    <header>
      <a href="/" class="text-light navbar-brand" style="height: 100%;position: relative;">
        <img id="logomarca" src="<?= ASSETS_PATH ?>imagens/logo-sistema.png">
      </a>
    </header>
    <br>
    <ul class="nav nav-pills flex-column mb-auto">
      <?php
        if(haveAccessToPage("Cadastros de Usuários")){
          ?>
          
            <!-- USUÁRIOS -->
            <li class="nav-item <?= @$GLOBALS['item-sidebar'] == "usuarios" ? "active" : "" ?>" title="Usuários">
              <a href="/gerenciamento/cadastros/usuarios/" class="nav-link text-white d-flex align-items-center" style="gap: 10px;">
                <span class="material-symbols-outlined">group</span>
                <span class="text">Usuários</span>
              </a>
            </li>
            <!-- USUÁRIOS \. -->
          <?php
        }

        if(haveAccessToPage("Cadastros de Níveis de Acessos")){
          ?>
          
            <!-- NÍVEIS DE ACESSOS -->
            <li class="nav-item <?= @$GLOBALS['item-sidebar'] == "niveis_acessos" ? "active" : "" ?>" title="Níveis de Acessos">
              <a href="/gerenciamento/cadastros/niveis_acessos/" class="nav-link text-white d-flex align-items-center" style="gap: 10px;">
                <span class="material-symbols-outlined">badge</span>
                <span class="text">Níveis de Acessos</span>
              </a>
            </li>
            <!-- NÍVEIS DE ACESSOS \. -->
          <?php
        }
        if(haveAccessToPage("Categorias")){
          ?>
          
            <!-- CATEGORIAS -->
            <li class="nav-item <?= @$GLOBALS['item-sidebar'] == "categoria" ? "active" : "" ?>" title="Categorias">
              <a href="/gerenciamento/cadastros/categorias/" class="nav-link text-white d-flex align-items-center" style="gap: 10px;">
                <span class="material-symbols-outlined">category</span>
                <span class="text">Categorias</span>
              </a>
            </li>
            <!-- CATEGORIAS \. -->
          <?php 
        } 

if(haveAccessToPage("Questões")){
  ?>
  
    <!-- QUESTÕES -->
    <li class="nav-item <?= @$GLOBALS['item-sidebar'] == "perguntas" ? "active" : "" ?>" title="Questões">
      <a href="/gerenciamento/cadastros/perguntas/" class="nav-link text-white d-flex align-items-center" style="gap: 10px;">
        <span class="material-symbols-outlined">forms_add_on</span>
        <span class="text">Questões</span>
      </a>
    </li>
    <!-- QUESTÔES \. -->
  <?php
        }
        
        if(haveAccessToPage("Painel de Emissões")){
          ?>
            <!-- EMISSÕES -->
            <li class="nav-item <?= @$GLOBALS['item-sidebar'] == "emissoes" ? "active" : "" ?> sidebar-collapse" data-sub-list="sl-emissoes" title="Emissões">
              <a href="/gerenciamento/emissoes/" class="nav-link text-white d-flex align-items-center" style="gap: 10px;">
                <span class="material-symbols-outlined">summarize</span>
                <span class="text">Emissões</span>
              </a>
            </li>
            <!-- EMISSÕES \. -->
          <?php
        }
      ?>
       

    </ul>
  </div>

  <div>
    <button class="showside-barr btn btn-sm btn-outline-light d-flex align-items-center" onclick="toggleVisibilitSidebarr()">
      <span class="material-symbols-outlined" id="toggle-sidebarr">arrow_forward</span>
    </button>
  </div>
</div>