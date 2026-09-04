<?php
$f = "assets/traveland/js/main.js";
$c = file_get_contents($f);
$c = preg_replace("/\/\/===== Sticky.*?\}\);/s", "", $c);
file_put_contents($f, $c);
echo "OK\n";
?>
