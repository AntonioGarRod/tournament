<?php
/**
 * Created by AntonioGarRod
 * Date: 04/01/2017
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

  return $rounds;
}

function flip($match) {
  $components = str_split(' v ', $match);
  return $components[1] . " v " . $components[0];
}

function team_name($num, $names) {
  $i = $num;
  if (sizeof($names) > $i && strlen(trim($names[$i])) > 0) {
    return trim($names[$i]);
  } else {
    return $num;
  }
}

function generar_partidos(&$prepare_partidos){
  return show_fixtures($prepare_partidos);
}
