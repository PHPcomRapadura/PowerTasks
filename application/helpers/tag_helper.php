<?php

  function tag_label_color($label){
    $colors = array(
      'default' => 'Cinza',
      'primary' => 'Azul Escuro',
      'info' => 'Azul Claro',
      'warning' => 'Amarelo',
      'danger' => 'Vermelho',
      'success' => 'Verde',
    );

    if(!isset($colors[$label])){
      return 'Sem Cor';
    }

    return $colors[$label];
  }
