<?php
/* 
 * bushes
 * in the forest you have to be careful not to lose your goldbag
 *
 * region: forest
 *
 * v.1.0 040415(yymmdd)basis erstellt
 * v.1.1 040416(yymmdd)eingefaerbt und debuglog zugefuegt
 *
 * by bibir
 */
 
if (!isset($session)) exit();

output("`2Als du durch den Wald gehst, um nach Monstern Ausschau zu halten, kommst du zu einer Stelle, ");
output("an der dir viele B�sche den Weg erschweren.`n");
output("Du bemerkst ein Ziehen und Zerren an deinem Goldbeutel.`n`n`n`0");
$gold_lost=getsetting("bushesgold","0");
//output("`n`$`n gold_lost1 : $gold_lost`0");

switch(e_rand(1,3)){
	case 1: //goldbeutel ist weg
		output("`2Doch bevor du dich darum k�mmern kannst, ihn festzuhalten, ist er dir auch schon abgerissen und hoffnungslos im Dickicht verloren.`n`n`0");
		output("`6Du verlierst all dein Gold.`n`0");
		//donationpoints als entschaedigung
		if ($session[user][gold] > 1000) {
			$session[user][donation]+= 3;
		} else if ($session[user][gold] > 100) {
			$session[user][donation]+= 2;
		} else {
			$session[user][donation]+= 1;
		}
		$gold_lost += $session[user][gold];
		$log_lost = $session[user][gold];
		//debuglog("verlor all sein gold ($log_lost) im gebuesch");
		$session[user][gold]=0;
		break;
	case 2: //goldbeutel festgehalten
		output("`2Gerade rechtzeitig genug bemerkst du, wie sich dein Goldbeutel schon etwas gel�st hat.`n");
		output("Hastig machst du ihn wieder fest und bei deinem weiteren Weg h�ltst du ihn aufmerksam fest.`n`n`0");
		output("`@Der wird dir so schnell nicht abhanden kommen.`0");
		break;
	case 3: //goldbeutel gefunden
		output("`2Gerade rechtzeitig genug bemerkst du, wie sich dein Goldbeutel schon etwas gel�st hat.`n");
		output("Schnell machst du ihn wieder fest und kontrollierst, ob noch alles drin ist.`n`n");
		output("Als du deinen Weg fortsetzen willst, entdeckst du einen Goldbeutel im Geb�sch h�ngen.");
		output("Du mu�t einiges an Kraft aufbringen, um den Beutel aus dem Geb�sch zu befreien.`n`n`0");
		output("`@Da war wohl jemand nicht so vorsichtig, wie du selbst.`0`n`n");
		if($gold_lost == 0) {
			output("`6Leider ist der Goldbeutel leer.`n`0");
			output("`2Da hat aber jemand mehr Gl�ck gehabt, als Verstand.`0`n");
		} else {
			output("`6Als du den Goldbeutel aufmachst, z�hlst du `$ $gold_lost `6Goldst�cke, die nun dein sind.`n`n`0");
		}
		$session[user][gold]+=$gold_lost;
		//debuglog("fand $gold_lost in einem gebuesch");
		$gold_lost="0";	
		break;
		
	default:
		ouput("was war denn das?");
}

savesetting("bushesgold",$gold_lost);
//output("`n`$`n gold_lost2 : $gold_lost`0");


?>