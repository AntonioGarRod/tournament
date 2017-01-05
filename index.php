<?php
/**
 * Created by AntonioGarRod
 * Date: 04/01/2017
 */

include 'tournament.php';

$num_team = 18;

for ($i=1; $i<$num_team; $i++) {
  $prepare_partidos[$i] = $i;
}

$prepare_partidos['jugadores_prepared'] = $prepare_partidos;

$partidos_jornada = show_fixtures($prepare_partidos);
$num_jornada = 0;
echo "<table border='1'><tr>";
foreach ($partidos_jornada as $jornada) {
  $num_jornada++;
  echo("<tr><td>Jornada " . $num_jornada . ":</td>");
    echo "<td>";
    foreach ($jornada as $partidos) {
      echo($partidos[0] . " vs " . $partidos[1] . "<br>");
    }
    echo "</td>";
  echo "</tr>";
}
echo "</table>";

