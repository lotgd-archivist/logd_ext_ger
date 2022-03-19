<?php

// 22062004

/* ******************* 
The Golden Egg
A "capture the flag"-like extension for PvP in LoGD
V 1.1 Written by anpera 2004-02-26 

Additional changes in common.php, configuration.php, darkhorse.php, healer.php, newday.php, prefs.php, pvp.php, shades.php required.
See http://www.anpera.net/forum/viewforum.php?f=27 for details.
******************* */  

$session[user][specialinc] = "goldenegg.php";
if ($HTTP_GET_VARS[op]=="takeit") {
	output("`3Du bist dir sicher, dass es sich bei dem Ei um das legendäre goldene Ei handelt und schwingst dich an den zahlreichnen Astlöchern und niedrigen Ästen nach oben.`n`n");
	if (e_rand(1,5)==4){
		output("Pech gehabt! Gerade als du nach dem Ei greifen willst, pickt dir ein Greif von hinten auf die Schulter und macht kurzen Prozess mit dir...");
		$lvl = $session[user][level];
		$hurt = e_rand(5*$lvl,10*$lvl);
		$session[user][hitpoints]-=$hurt;
		output("`n`n`^Du verlierst $hurt Lebenspunkte!`n");
		if ($session[user][hitpoints]<=0) {
    			output("`4Du bist `bTOT`b!!!`nDu verlierst glücklicherweise weder Gold noch Erfahrungspunkte.`nDu kannst morgen wieder kämpfen.");
    			addnav("Zu den Schatten","shades.php");
    			addnav("Zu den News","news.php");
			addnews($session[user][name]." `0starb beim Versuch, sich das `^goldene Ei`0 zu schnappen.");
    		}
	} else if (getsetting("hasegg",0)!=0){
		output("Pech gehabt! Gerade als du nach dem Ei greifen willst, stellst du fest, daß es verschwunden ist. Im Augenwinkel siehst du unten gerade noch jemanden mit `bdeinem`b Ei weglaufen...");
	} else {
		output("`3Die Luft ist rein und du greifst dir das Ei. Sofort spürst du, dass dir dieses Ei einigen Türen öffnen wird und magische Fähigkeiten hat, die sogar den Tod ");
		output("besiegen können. Aber dir ist auch klar, dass dieses Ei den Neid vieler anderer Krieger auf sich ziehen wird.");
		addnews("`^".$session[user][name]."`^ hat das goldene Ei im Wald gefunden!");
		$session[user][reputation]++;
		savesetting("hasegg",stripslashes($session[user][acctid]));
	}
	$session[user][specialinc]="";
} elseif ($HTTP_GET_VARS[op]=="abhaun") {
	output("`3Soll sich doch jemand anderes Opfern und das Ei holen. Du hältst es für ungefährlicher, das Ei jemandem in den Feldern abzunehmen, als selbst den Baum hochzuklettern und eine Begegnung mit einem unbekannten  Tier zu haben.");
	$session[user][specialinc]="";
} else {
	if (getsetting("pvp",1)==0) {
		output("`3Mitten im dichten Wald entdeckst du auf einer starken Astgabel eines gewaltigen Baumes ein riesiges Nest.`n");
		output("Du bemerkst, dass es leer ist und schenkst ihm keine weitere Aufmerksamkeit.`n`n ");
		$session[user][specialinc]="";
	} else if (getsetting("hasegg",0)==0){
	  	output("`3Mitten im dichten Wald bemerkst du etwas von oben durch die Blätter funkeln. Es ist nicht die Sonne! In deiner Neugier kommst du näher.");
	  	output(" Du siehst auf einer starken Astgabel eines gewaltigen Baumes ein riesiges Nest - in dem ein großes goldenes Ei funkelt!`n");
	  	output("Da du keine Ahnung hast, welches Tier solche Eier legt und so riesige Nester baut, bist du unschlüssig, ob du schnell nach oben klettern und das Ei mitnehmen sollst, ");
	  	output("oder es doch lieber dort lassen solltest. Einerseits könnte das Ei magische Kräfte haben, oder zumindest eine reichliche Mahlzeit bieten - andererseits aber könntest du ");
		output(" ebenso verlockend und lecker auf die Mutter dieses Eis wirken...");
		addnav("Nimm das Ei mit","forest.php?op=takeit");
		addnav("Lieber nicht","forest.php?op=abhaun");
	} else {
		$sql = "SELECT acctid,name,sex FROM accounts WHERE acctid = '".getsetting("hasegg",0)."'";
		$result = db_query($sql) or die(db_error(LINK));
		$row = db_fetch_assoc($result);
		$owner = $session[user][acctid];
		if ($owner == $row[acctid]) {
			output("`3Du kommst an eine Stelle im Wald, die dir merkwürdig bekannt vorkommt. Plötzlich hörst du ein lautes Kreischen über dir in der Luft und ein schnell lauter werdendes Rauschen.");
			output(" Es ist die Greifenmutter, die ihr Ei bei dir wittert!! Du schaffst es gerade noch, ihr das Ei entgegenzustrecken, bevor sie über dich herfällt.");
			$lvl = $session[user][level];
			$hurt = e_rand(4*$lvl,9*$lvl);
			$session[user][hitpoints]-=$hurt;
			output("`n`n`^Du verlierst $hurt Lebenspunkte!`n");
			$text ="`^".$session[user][name]."`^ hat das goldene Ei im Wald verloren";
			if ($session[user][hitpoints]<=0) {
    				output("`4Du bist `bTOT`b!!!`nDu verlierst glücklicherweise weder Gold noch Erfahrungspunkte.`nDu kannst morgen wieder kämpfen.");
    				addnav("Zu den Schatten","shades.php");
    				addnav("Zu den News","news.php");
				$text = $text." und starb dabei";
    			}
			addnews($text.".");
			savesetting("hasegg",stripslashes(0));
		} else {
			output("`3Mitten im dichten Wald entdeckst du auf einer starken Astgabel eines gewaltigen Baumes ein riesiges Nest.`n");
		  	output("Du bemerkst, dass es leer ist und schenkst ihm keine weitere Aufmerksamkeit.`n`n ");
			output("`^Das goldene Ei befindet sich zur Zeit im Besitz von $row[name]`^!`n`3Willst du es ".($row[sex]?"ihr":"ihm")." nicht mal abnehmen?`n");
		}
		$session[user][specialinc]="";
	}
}
?>
