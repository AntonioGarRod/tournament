<?php
/**
 * Created by PhpStorm.
 * User: antoniogarciarodriguez
 * Date: 04/01/2017
 * Time: 08:49
 */

function show_fixtures($names) {
  $teams = sizeof($names);

    // If odd number of teams add a "ghost".
  $ghost = false;
  if ($teams%2 == 1) {
    $teams++;
    $ghost = true;
  }

  // Generate the fixtures using the cyclic algorithm.
  $totalRounds = $teams - 1;
  $matchesPerRound = $teams / 2;
  $rounds = array();
  for ($i = 0; $i < $totalRounds; $i++) {
    $rounds[$i] = array();
  }

  for ($round = 0; $round < $totalRounds; $round++) {
    for ($match = 0; $match < $matchesPerRound; $match++) {
      $home = ($round + $match) % ($teams - 1);
      $away = ($teams - 1 - $match + $round) % ($teams - 1);
      // Last team stays in the same place while the others
      // rotate around it.
      if ($match == 0) {
        $away = $teams - 1;
      }
      $rounds[$round][$match][0] = team_name($home + 1, $names);
      $rounds[$round][$match][1] = team_name($away + 1, $names);
    }
  }

  // Interleave so that home and away games are fairly evenly dispersed.
  $interleaved = array();
  for ($i = 0; $i < $totalRounds; $i++) {
    $interleaved[$i] = array();
  }

  $evn = 0;
  $odd = ($teams / 2);
  for ($i = 0; $i < sizeof($rounds); $i++) {
    if ($i % 2 == 0) {
      $interleaved[$i] = $rounds[$evn++];
    } else {
      $interleaved[$i] = $rounds[$odd++];
    }
  }

  $rounds = $interleaved;

  // Last team can't be away for every game so flip them
  // to home on odd rounds.
  //for ($round = 0; $round < sizeof($rounds); $round++) {
  //  if ($round % 2 == 1) {
  //    $rounds[$round][0] = flip($rounds[$round][0]);
  //  }
  //}

  return $rounds;
}

function flip($match) {
  $components = str_split(' v ', $match);
  return $components[1] . " v " . $components[0];
}

function team_name($num, $names) {
  $i = $num - 1;
  if (sizeof($names) > $i && strlen(trim($names[$i])) > 0) {
    return trim($names[$i]);
  } else {
    return $num;
  }
}

function generar_partidos(&$prepare_partidos){
  $jornadas = count($prepare_partidos) - 1;

  for ($i = 0; $i<$jornadas; $i++){
    if ($i <= ($jornadas/2)) {
      $prepare_partidos['jornada'] = $i;
      $partidos_jornada[$i] = generar_jornada($prepare_partidos);
      array_push($prepare_partidos['jugadores_prepared'], array_shift($prepare_partidos['jugadores_prepared']));
    } else {
      $prepare_partidos['jornada'] = $i;
      $index = $prepare_partidos['count_jugadores'] / 2;
      $jugadores = array_chunk($prepare_partidos['jugadores_prepared'], $index);
      if (count($jugadores[0])==2) {
        $partidos_jornada[$i][0] = array($jugadores[0][0], $jugadores[1][0]);
        $partidos_jornada[$i][1] = array($jugadores[0][1], $jugadores[1][1]);
        break;
      } if (count($jugadores[0])==3) {
        $partidos_jornada[$i][0] = array($jugadores[1][0], $jugadores[1][2]);
        $partidos_jornada[$i+1][0] = array($jugadores[0][2], $jugadores[1][1]);
        $partidos_jornada[$i][1] = array($jugadores[0][1], $jugadores[1][0]);
        $partidos_jornada[$i+1][1] = array($jugadores[0][0], $jugadores[1][1]);
        $partidos_jornada[$i+2][0] = array($jugadores[0][1], $jugadores[1][2]);
        $partidos_jornada[$i+2][1] = array($jugadores[0][2], $jugadores[0][0]);
        break;
      } else {

        $prepare_partidos['count_jugadores'] = $prepare_partidos['count_jugadores'] / 2;
        $prepare_partidos['jugadores_prepared'] = $jugadores[0];
        $partidos_jornada[$i] = generar_partidos($prepare_partidos);
        $prepare_partidos['jugadores_prepared'] = $jugadores[1];
        $partidos_jornada[$i] = generar_partidos($prepare_partidos);
      }
    }
  }

  return $partidos_jornada;
}

/**
 * @param $prepare_partidos
 *
 * $prepare_partidos['grupo'] = $grupo;
 * $prepare_partidos['torneo_id'] = $torneo_id;
 * $prepare_partidos['jugadores_prepared'] = $jugadores_prepared;
 * $prepare_partidos['count_jugadores'] = número de jugadores
 * $prepare_partidos['jornada'] = Número jornada
 */
function generar_jornada(&$prepare_partidos) {
  $index = (count($prepare_partidos)-1) / 2;
  $jugadores = array_chunk($prepare_partidos['jugadores_prepared'], $index);

  for ($i = 0; $i<$index; $i++){
    $j = $index-$i;
    $partidos[] = array($jugadores[0][$i], $jugadores[1][$j]);
  }

  return $partidos;
}

/**
 * @param $prepare_partidos
 *
 * $prepare_partidos['grupo'] = $grupo;
 * $prepare_partidos['torneo_id'] = $torneo_id;
 * $prepare_partidos['jugadores_prepared'] = $jugadores_prepared;
 * $prepare_partidos['count_jugadores'] = número de jugadores
 * $prepare_partidos['jornada'] = Número jornada
 */
function generar_resto_jornada(&$prepare_partidos) {
  $index = $prepare_partidos['count_jugadores'] / 2;
  $jugadores = array_chunk($prepare_partidos['jugadores_prepared'], $index);

  for ($i = 0; $i<$index; $i++){
    $partidos[] = array($jugadores[0][$i], $jugadores[1][$i]);
  }

  return $partidos;
}
