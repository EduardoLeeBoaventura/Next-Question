<?php

  require_once __DIR__ . DIRECTORY_SEPARATOR . "/resources" . DIRECTORY_SEPARATOR . "constantes.php";

  $assets_name = returnsActualAssetsFolderName();

  $change_name = readAndWriteJsonParameters();

  if($change_name !== false){
    $assets_name = $change_name;
  }
    
  define("ASSETS_PATH", DIRECTORY_SEPARATOR . $assets_name . DIRECTORY_SEPARATOR);