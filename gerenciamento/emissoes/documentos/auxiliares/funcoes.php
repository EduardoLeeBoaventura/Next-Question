<?php
require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "resources" . DIRECTORY_SEPARATOR . "system_functions.php";

use System\Controller\Usuarios;

$GLOBALS['usuarios_handler']       = new Usuarios();

function retornaDadosEmissao($emissao){
  if(!empty($emissao)){
    return [
      "page_title" => pegarTitulo($emissao),
      "conteudo_pagina" => str_replace("-","_", $emissao) . '.php'
    ];
  } else {
    return [
      "page_title" => false,
      "conteudo_pagina" => false
    ];
  }
}

function pegarTitulo($emissao){
  if(!empty($emissao)){
    $json = file_get_contents(__DIR__ . '/titulo_paginas.json');
    $dados = json_decode($json);
  
    return $dados->$emissao ?? false;
  } else {
    return false;
  }
}

function retornaDataAgora(){
  $meses = [
    'Janeiro',
    'Fevereiro',
    'Março',
    'Abril',
    'Maio',
    'Junho',
    'Julho',
    'Agosto',
    'Setembro',
    'Outubro',
    'Novembro',
    'Dezembro',
  ];

  return date('d') .' '. $meses[date('n')-1] .' '. date('Y');
}

function getInformacaoDoBanco(){
  switch(@$GLOBALS['document']){
    case 'certificado':
    break;
    
    default:
      return false;
    break;
  }
}

function retornaMes($index){
  $meses = [
    "Janeiro",
    "Fevereiro",
    "Março",
    "Abril",
    "Maio",
    "Junho",
    "Julho",
    "Agosto",
    "Setembro",
    "Outubro",
    "Novembro",
    "Dezembro"
  ];

  return $meses[$index];
}

function formataDinheiro($valor){
  $valor_arr     = explode(".", $valor);
  $valor_int     = @$valor_arr[0];
  $valor_decimal = @$valor_arr[1];

  $valor_int_arr = str_split($valor_int);
  
  $valor_int_arr_ordenado = reverterOrdemArray($valor_int_arr);
  $count = arrayLength($valor_int_arr);

  for($i = 0;$i < $count;$i++){
    $posicao = $i + 1;
    
    if($posicao % 4 == 0){
      $valor_int_arr_ordenado[$i] = $valor_int_arr_ordenado[$i] . '.';
    }
  }

  $valor_int_arr_reordenado = reverterOrdemArray($valor_int_arr_ordenado);

  if(empty($valor_decimal)){
    $valor_decimal = '00';
  }

  return implode('', $valor_int_arr_reordenado) . ',' . $valor_decimal;
}

function reverterOrdemArray($array){
  $array_ordenado = [];
  
  $count = arrayLength($array);
  for($i = $count-1;$i >= 0;$i--){
    array_push($array_ordenado, $array[$i]);
  }

  return $array_ordenado;
}