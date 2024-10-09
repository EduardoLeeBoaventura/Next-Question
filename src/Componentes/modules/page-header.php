<nav id="page-header" class="navbar navbar-expand-lg bg-light border-bottom border-dark" data-bs-theme="dark">
  <div class="container-fluid" style="height: 100%;">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <div class="navbar-nav me-auto mb-2 mb-lg-0">
        <span class="display-6 fs-3">
          <?php
            if(defined("ROUTE_INFO") && !empty(ROUTE_INFO['title']))
              echo ROUTE_INFO['title'];
          ?>
        </span>
      </div>
      
      <div class="d-flex">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link text-dark" aria-current="page" href="/gerenciamento/editar_conta.php"><span class="material-symbols-outlined">person</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-dark" href="/sair.php"><span class="material-symbols-outlined">logout</span></a>
          </li>
        </ul>
      </div>
    </div>
  </div>
</nav>