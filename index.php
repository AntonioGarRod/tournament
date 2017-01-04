<?php
/**
 * Created by PhpStorm.
 * User: antoniogarciarodriguez
 * Date: 04/01/2017
 * Time: 08:49
 */
include 'tournament.php';

for ($i=0; $i<100; $i++) {
  $prepare_partidos[$i] = $i;
}

$prepare_partidos['jugadores_prepared'] = $prepare_partidos;

$partidos_jornada = show_fixtures($prepare_partidos);

foreach ($partidos_jornada as $jornada) {
  print_r($jornada);
  print('<br>');
}

