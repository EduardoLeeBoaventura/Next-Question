<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "resources" . DIRECTORY_SEPARATOR . "system_functions.php";

  use System\Controller\Usuarios;
  if(!empty($_POST)){
    $usuarios_handler = new Usuarios();
    
    
    $pass = substr(str_replace('-','', str_replace('.','',$_POST['cpf'])), 0, 6);
    $pass_default = password_hash($pass, PASSWORD_DEFAULT);
    //a senha padrão são os primeiros 6 digitos do campo cpf
    
    $dados_insert = [
      "nome"             => $_POST['nome'],
      "email"            => $_POST['email'],
      "senha"            => $pass_default,
      "telefone"         => $_POST['telefone'],
      "cpf"              => $_POST['cpf'],
      "usuario_cadastro" => @$_SESSION['id_usuario'],
      "usuario_edicao"   => @$_SESSION['id_usuario']
    ];

    if(@returnsConstData('USER_INFO') && !empty($_POST['usuario-especial'])) {
      $dados_insert['desenvolvedor'] = $_POST['usuario-especial'] == 'desenvolvedor' ? 1 : 0;
      $dados_insert['geral'] = $_POST['usuario-especial'] == 'geral' ? 1 : 0;
    }
    
    $insert = $usuarios_handler->criar($dados_insert);

    if($insert->result !== false){
      $usuario = $usuarios_handler->listar([['U.email', $_POST['email']]])->result[0]['ref'];

      $_SESSION["form_action_status"] = $usuario;
      if(!empty($_SESSION['id_usuario'])){
        $_SESSION["form_action_status"] = true;
      }
      $_SESSION["status_msg"] = "Cadastro realizado com sucesso";
    }else if($insert->result == false && !empty($insert->result_error)){
      $query_param = "";

      $_SESSION["form_action_status"] = false;
      $_SESSION["status_msg"] = "Ação não realizada, erro: " .$insert->ref_log;
    }
  } else {
      $_SESSION["form_action_status"] = false;
      $_SESSION["status_msg"] = "Dados para cadastro não informados";
  }

  if(!empty($_SESSION['id_usuario'])){
      header("Location: /gerenciamento/cadastros/usuarios/");
  } else {
      header("Location: /cadastro_livre/usuarios/");
  }