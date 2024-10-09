<div class="painel-body-container">
  <?php include_once returnsPathFromHost("src", "Componentes", "modules", "sidebarr.php"); ?>
  
  <div class="painel-body-content">
    <?php
      include_once returnsPathFromHost("src", "Componentes", "modules", "page-header.php");
      ?>
        <div>
          <div id="page-alert-success" class="alert alert-success d-flex justify-content-between" role="alert" style="display: none !important;">
            <span></span> <button class="btn btn-close color-success" onclick="this.parentElement.style = 'display: none !important;'"></button>
          </div>
          <div id="page-alert-danger" class="alert alert-danger d-flex justify-content-between" role="alert" style="display: none !important;">
            <span></span> <button class="btn btn-close color-danger" onclick="this.parentElement.style = 'display: none !important;'"></button>
          </div>
      <?php
      
      if(@$_SESSION["form_action_status"] === true){
        ?>
          <div class="alert alert-success d-flex justify-content-between" role="alert">
            <span><?= $_SESSION["status_msg"] ?></span> <button class="btn btn-close color-success" onclick="this.parentElement.remove()"></button>
          </div>
        <?php

        $_SESSION["form_action_status"] = null;
        $_SESSION["status_msg"] = null;
      } else if(@$_SESSION["form_action_status"] === false){
        ?>
          <div class="alert alert-danger d-flex justify-content-between" role="alert">
            <span><?= $_SESSION["status_msg"] ?></span> <button class="btn btn-close color-danger" onclick="this.parentElement.remove()"></button>
          </div>
        <?php

        $_SESSION["form_action_status"] = null;
        $_SESSION["status_msg"] = null;
      }

      if(is_array(PAINEL_CONTENT)){
        foreach(PAINEL_CONTENT as $value){
          include_once $value;
        }
      } else if (!empty(PAINEL_CONTENT)) {
        include_once PAINEL_CONTENT;
      }

      ?>
        </div>
      <?php

      include_once returnsPathFromHost("src", "Componentes", "modules", "page-footer.php");
    ?>
  </div>
</div>