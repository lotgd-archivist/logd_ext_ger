<?
if (!isset($session)) exit();
if ($session[user][charm]>0){
	output("`^Ein alter Mann schlägt dich mit einem hässlichen Stock, kichert und rennt davon!`n`nDu `%verlierst einen`^ Charmepunkt!`0");
	$session[user][charm]--;
}else{
  output("`^Ein alter Mann trifft dich mit einem hässlichen Stock und schnappt nach Luft, als der Stock `%einen Charmepunkt verliert`^.  Du bist noch hässlicher als dieser hässliche Stock!`0");
}
?>
