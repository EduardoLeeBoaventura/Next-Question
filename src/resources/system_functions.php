<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "vendor" . DIRECTORY_SEPARATOR . "autoload.php";

date_default_timezone_set("America/Bahia");

use System\Controller\Usuarios;

// if(ENVIRONMENT != "development"){
//   die('<h1>Sistema em manutenção. Tente novamente mais tarde.</h1>');
// }

session_start();
checkAssetsFolderName();
checkRoute();

function checkLogin($route_info)
{
  $logado = (bool)@$_SESSION['id_usuario'];

  $redirect_url = "";
  if (!$logado && array_search('auth', $route_info['requirements']) !== false) {
    $redirect_url = $route_info['alternative']['auth'];
  } else if ($logado && array_search('not_auth', $route_info['requirements']) !== false) {
    $redirect_url = $route_info['alternative']['not_auth'];
  }

  if($logado){
    userInfo();
  }

  if(!empty($redirect_url)){
    header("Location: $redirect_url");
    exit;
  }
}

function userInfo(){
  $usuarios_handler = new Usuarios();

  $usuario_logado = $usuarios_handler->listar([["id", $_SESSION['id_usuario']]]);

  if($usuario_logado !== false && !empty($usuario_logado)){
    define("USER_INFO", $usuario_logado[0]);
  } else {
    header("Location: /sair.php");
    exit;
  }
}

function returnsConstData($CONST){
  $user_info = false;
  if(defined($CONST)){
    $user_info = constant($CONST);
  }

  return $user_info;
}

function returnsPathFromHost(...$parts): String
{
  $path = implode(DIRECTORY_SEPARATOR, $parts);

  return $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . $path;
}

function returnsArrayConditions($conditions)
{
  if (is_array($conditions)) {
    $new_conditions = array_filter($conditions, function ($v) {
      $verify_1 = is_array($v) && !empty($v[0]) && !empty($v[1]);
      $verify_2 = !is_array($v) && !empty($v);
      
      if ($verify_1 || $verify_2) {
        return $v;
      }
    });

    return $new_conditions;
  } else {
    return null;
  }
}

function printHide($val, $id)
{
  echo '<div id="' . $id . '" style="display:none;">';
  var_dump($val);
  echo '</div>';
}

function dumpDie($pre_format, ...$val)
{
  foreach ($val as $v) {
    if ($pre_format) {
      echo "<pre>";
    }

    var_dump($v);

    if ($pre_format) {
      echo "</pre>";
    } else {
      echo "\n";
    }
  }

  die('');
}

function arrayLength($array){
  if(is_array($array)){
    $count = count($array);
  } else {
    $count = 0;
  }

  return $count;
}

// ROUTES CONTROL

function checkRoute()
{
  $path = normalizePath($_SERVER['SCRIPT_NAME']);
  $routes = readRoutesJson();

  $route_info = returnsRouteInfo($routes, $path);

  define("ROUTE_INFO", $route_info);
  
  if($route_info === false){
    $is_related = searchForRelated($routes, $path);
    if($is_related === false){
      header("Location: /");
    } else {
      header("Location: $is_related");
    }
    exit;
  }
  verifyRequirements($route_info);
}

function normalizePath($path){
  $path = removeIndex($path);
  if (mb_strpos($path, '.php') === false) {
    $last_char = mb_substr($path, -1);
    if($last_char != '/'){
      $path .= '/';
    }
  }

  return $path;
}

function removeIndex($path)
{
  if(mb_strpos($path, '/index.php') !== false){
    $path = mb_substr($path, 0, mb_strpos($path, '/index.php'));
    
    if(mb_strpos($_SERVER['REQUEST_URI'], '/index.php') !== false){
      $path = empty($path) ? '/' : $path;
      header("Location: $path");
      exit;
    }
  }

  return $path;
}

function readRoutesJson(){
  $json = file_get_contents(returnsPathFromHost('routes.json'));
  return json_decode($json, true);
}

function returnsRouteInfo($routes, $path){
  return @$routes[$path] ?? false;
}

function searchForRelated($routes, $path){
  $route = false;
  foreach($routes as $k => $v){
    if(in_array($path, $v['related'])){
      $route = $k;
      break;
    }
  }

  return $route;
}

function verifyRequirements($route_info){
  checkLogin($route_info);
  
  if((bool)@$route_info['title']){
    if(!haveAccessToPage($route_info['title'])){
      header("Location: /");
    }
  }
  
  checkMethod($route_info);
  
  checkEnvironment($route_info);
}

function checkMethod($route_info){
  $method = strtolower($_SERVER['REQUEST_METHOD']);
  $requirements = $route_info['requirements'];

  $redirect_url = "";
  if(in_array("get", $requirements) && $method != "get"){
    $redirect_url = $route_info['alternative']['get'];
  } else if(in_array("post", $requirements) && $method != "post"){
    $redirect_url = $route_info['alternative']['post'];
  }

  if(!empty($redirect_url)){
    header("Location: $redirect_url");
    exit;
  }
}

function checkEnvironment($route_info){
  $requirements = $route_info['requirements'];

  $redirect_url = "";
  if(in_array("development", $requirements) && ENVIRONMENT != "development"){
    $redirect_url = $route_info['alternative']['development'];
  } else if(in_array("production", $requirements) && ENVIRONMENT != "production"){
    $redirect_url = $route_info['alternative']['production'];
  }

  if(!empty($redirect_url)){
    header("Location: $redirect_url");
    exit;
  }
}

function haveAccessToPage($page){
  $user_info = returnsConstData('USER_INFO');
  $geral = (bool)@$user_info['geral'];
  $desenvolvedor = (bool)@$user_info['desenvolvedor'];

  $privilegios = @$user_info['privilegios'];
  $privilegios_da_pagina = returnsSpecificPrivileges($page, $privilegios);

  $routes = readRoutesJson();
  
  $route_info = returnsRouteInfoByPage($routes, $page);
  if(!empty($route_info['privilege'])){
    $route_info['privilege'] = explode('|', $route_info['privilege']);
  }

  if(!$desenvolvedor && array_search("developer", $route_info['requirements']) !== false){
    $have_access_to_page = false;
  } else if ($desenvolvedor || ($geral && $page != "Cadastros de Níveis de Acessos")){
    if(isset($route_info['privilege']) && arrayLength(@$route_info['privilege']) <= arrayLength(@$privilegios_da_pagina['BLOQUEAR'])){
      $have_access_to_page = false;
    }

    $have_access_to_page = true;
  } else if(!isset($route_info['privilege'])) {
    $have_access_to_page = true;
  } else {
    $have_access_to_page = arrayLength(@$privilegios_da_pagina['AUTORIZAR']) > 0;
  }

  return $have_access_to_page;
}

function havePrivilegeToDo($page, ...$privilege){
  $user_info = returnsConstData('USER_INFO');
  $geral = (bool)@$user_info['geral'];
  $desenvolvedor = (bool)@$user_info['desenvolvedor'];
  $privilegios = @$user_info['privilegios'];

  $privilegios_da_pagina = returnsSpecificPrivileges($page, $privilegios);

  if($desenvolvedor || ($geral && $page != "Cadastros de Níveis de Acessos")){
    $have_privilege_to_do = true;

  } else if (haveAccessToPage($page)){
    $have_privilege_to_do = false;

    foreach($privilegios_da_pagina['AUTORIZAR'] as $k => $v){
      if(in_array($v, $privilege)){
        $have_privilege_to_do = true;
        break;
      }
    }
  } else {
    $have_privilege_to_do = false;
  }

  return $have_privilege_to_do;
}

function returnsSpecificPrivileges($page, $privileges){
  if(!empty(@$privileges[$page])){
    $privileges = $privileges[$page];
  } else {
    $privileges = [];
  }

  return $privileges;
}

function returnsRouteInfoByPage($routes, $page){
  foreach($routes as $k => $v){
    if(!empty($v['title']) && $v['title'] == $page){
      return $routes[$k];
      break;
    }
  }
}
// ROUTES CONTROL

function checkAssetsFolderName(){
  $assets_folder_name = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . str_replace(DIRECTORY_SEPARATOR, '', ASSETS_PATH);

  if(is_dir($assets_folder_name) === false){
    $actual_assets_folder_name = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . returnsActualAssetsFolderName();
    
    rename($actual_assets_folder_name, $assets_folder_name);
  }
}

function returnsActualAssetsFolderName(){
  $directories = scandir($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR);

  $actual_assets_folder_name = "";
  foreach($directories as $directory){
    $path = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . $directory;
    if(is_dir($path) && mb_strpos($directory, 'assets_') !== false){
      $actual_assets_folder_name = $directory;
    }
  }

  return $actual_assets_folder_name;
}

function readAndWriteJsonParameters(){
  $json_parameters_file = returnsPathFromHost('parameters.json');

  $assets_name = false;
  if(is_file($json_parameters_file)){
    $json = file_get_contents($json_parameters_file);
    $parameters = json_decode($json, true);
    
    if($parameters['update_assets']){
      $parameters['update_assets'] = false;
      
      $assets_name = "assets_" . date("YmdHis");
  
      $json = json_encode($parameters);
      file_put_contents($json_parameters_file, $json);
    }
  
  }
  
  return $assets_name;
}

// ============================ \\

function returnsUsersTypesTitles($type){
  $title = "";
  switch($type){
    case 'ADMINISTRATIVO':
      $title = "Administrativo";
      break;
    case 'FUNCIONARIO':
      $title = "Funcionário";
      break;
    case 'CLIENTE':
      $title = "Cliente";
      break;
    case 'VISITANTE':
      $title = "Visitante";
      break;
  }

  return $title;
}