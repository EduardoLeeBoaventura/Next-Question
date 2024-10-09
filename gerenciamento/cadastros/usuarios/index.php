<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "resources" . DIRECTORY_SEPARATOR . "system_functions.php";

  // definições para importação dos módulos da página
    $includes = [];

    if(havePrivilegeToDo(@returnsConstData('ROUTE_INFO')['title'], 'C')){
      array_push($includes, returnsPathFromHost("gerenciamento", "cadastros", "usuarios", "cadastro.php"));
    }

    if(havePrivilegeToDo(@returnsConstData('ROUTE_INFO')['title'], 'R')){
      array_push($includes, returnsPathFromHost("gerenciamento", "cadastros", "usuarios", "listagem.php"));
    }

    if(havePrivilegeToDo(@returnsConstData('ROUTE_INFO')['title'], 'R+')){
      array_push($includes, returnsPathFromHost("gerenciamento", "cadastros", "usuarios", "consulta_usuarios.php"));
    }

    if(havePrivilegeToDo(@returnsConstData('ROUTE_INFO')['title'], 'U')){
      array_push($includes, returnsPathFromHost("gerenciamento", "cadastros", "usuarios", "edicao.php"));
      array_push($includes, returnsPathFromHost("gerenciamento", "cadastros", "usuarios", "alterar_senha.php"));
    }

    if(havePrivilegeToDo(@returnsConstData('ROUTE_INFO')['title'], 'U+')){
      array_push($includes, returnsPathFromHost("gerenciamento", "cadastros", "usuarios", "vinculo_niveis.php"));
      array_push($includes, returnsPathFromHost("gerenciamento", "cadastros", "usuarios", "vinculo_privilegios.php"));
    }
    
    define("PAINEL_CONTENT", $includes);
  // definições para importação dos módulos da página

  $user_info = returnsConstData("USER_INFO");

  use System\Controller\Usuarios;
  use System\Controller\NiveisAcessos;
  
  $usuarios_handler = new Usuarios();

  $niveis_acessos_handler = new NiveisAcessos();
  $niveis = $niveis_acessos_handler->listar();

  $usuario_logado = $usuarios_handler->listar([["ref", $user_info['ref']]])[0];
  
  $GLOBALS['item-sidebar'] = "usuarios";
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>SYSTEM - Usuários</title>

  <?php include_once returnsPathFromHost("src", "Componentes", "head-imports.php"); ?>
</head>
<body>
  <?php
    include_once returnsPathFromHost("src", "Componentes", "modules", "painel-body.php");
   
    include_once returnsPathFromHost("src", "Componentes", "footer-imports.php");
  ?>
  
  <script>
    function addSearchedUsers(users){
      if(users.status){
        user = users.response;

        const tr = `
          <tr>
            <td><?= "#" ?></td>
            <td>${user.nome}</td>
            <td>${user.email}</td>
            <td>${user.cpf}</td>
            <td>${user.situacao}</td>
            <td> -- -- </td>
          </tr>
        `;

        const tbody = document.querySelector('#listagem-usuarios');
        tbody.innerHTML = tr + tbody.innerHTML;
      } else {
        console.log(users.status_msg);
      }
    }
  </script>
</body>
</html>