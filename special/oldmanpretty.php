<?
if (!isset($session)) exit();
output("`^Ein alter Mann schlägt dich mit einem schönen Stock, kichert und rennt davon!`n`nDu `%bekommst einen`^ Charmepunkt!`0");
$session[user][charm]++;
?>
