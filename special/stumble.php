<?php
$lvl=$session[user][level];
$what=e_rand(1,4);
switch($what){
	case 1:
	output("`2Du stapfst durch den Wald, nur geringf�gig unvorsichtiger als sonst. Es kommt, wie es kommen muss: Du trittst in eine B�renfalle. Zwar kannst du dich daraus befreien, aber die Wunde ist tief.`n`n");
	break;
	case 2:
	output("`^Ein alter Mann schl�gt dich kr�ftig mit einem Stock, kichert und rennt davon!`n`n`0");
	break;
	default:
	output("`2Was f�r ein Pech! Du rutscht auf etwas aus und verletzt dich im Fallen. Du bemerkst einen d�nger�hnlichen Gestank.`n`nPferdemist!`n`n");
}
$hurt = e_rand($lvl,3*$lvl);
$session[user][hitpoints]-=$hurt;
output("`^Du verlierst $hurt Lebenspunkte!`n");
if ($session[user][hitpoints]<=0) {
	$session[user][alive]=0;
	output("`4Du bist `bTOT`b!!!`nDu verlierst gl�cklicherweise weder Gold noch Erfahrungspunkte.");
	addnav("Zu den Schatten","shades.php");
	addnav("Zu den News","news.php");
	addnews($session[user][name]." verletzte sich schwer und starb im Wald bei einem Missgeschick.");
}
?>
