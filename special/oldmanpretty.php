<?
if (!isset($session)) exit();
output("`^Ein alter Mann schl�gt dich mit einem sch�nen Stock, kichert und rennt davon!`n`nDu `%bekommst einen`^ Charmepunkt!`0");
$session[user][charm]++;
?>
