<?
if (!isset($session)) exit();
if ($session[user][charm]>0){
	output("`^Ein alter Mann schl�gt dich mit einem h�sslichen Stock, kichert und rennt davon!`n`nDu `%verlierst einen`^ Charmepunkt!`0");
	$session[user][charm]--;
}else{
  output("`^Ein alter Mann trifft dich mit einem h�sslichen Stock und schnappt nach Luft, als der Stock `%einen Charmepunkt verliert`^.  Du bist noch h�sslicher als dieser h�ssliche Stock!`0");
}
?>
