<?php

// gefunden auf http://www.lotgd.de
//
// Modifikationen von warchild & anpera
// mit einer Idee von Manwe

if (!isset($session)) exit();

$specialinc_file = "findtreasure.php";

if ( $HTTP_GET_VARS[op] == "baum"){
	output("`n`2Als du den Baum n�her untersuchst, merkst du, dass der Baum innen hohl ist. ");
	output("Du fa�t in den hohlen Baum und ");
	$baumsuche = e_rand(1,12);
	$session['user']['specialinc'] = ""; 
	switch($baumsuche){
		case 1:
		case 2: 
		output("holst ein zusammengerolltes Pergament heraus.`n`n"); 
		output("`2Du kannst es lesen oder es wegwerfen und deinen Weg weitergehen`0");
		addnav("Pergament lesen","forest.php?op=lesen");
		addnav("Zur�ck in den Wald","forest.php?op=back");
		$session['user']['specialinc'] = $specialinc_file; 
		break;
		case 3:
		output("du streifst dabei einen der `2weissen Pilze`7, der daraufhin mit einem leisen 'Plopp!' eine riesige Menge Sporen auspustet, die Du ungl�cklicherweise einatmest! Ausgestreckt auf dem Baum liegend siehst du grelle Farben und h�rst leise Musik w�hrend die Zaubersporen deine Erinnerungen vernichten und du dich beim Aufwachen fragst, wie du hier hergekommen bist.`n`n");
		output("`^Du verlierst einen Waldkampf und 5% Deiner Erfahrung!");
		$session[user][experience] = $session[user][experience] * 0.95;
		$session[user][turns]--;
		break;
		case 4:
		case 5: 
		output("traust deinen Augen nicht. Du ziehst die Hand heraus und es liegt `#ein Edelstein`2 darin!");
		$session['user']['gems']++;
		break;
		case 6:
		case 7:
		case 8:
		$amt=e_rand(10,90)*$session[user][level];
		output("findest nichts. Du gehst rechts um den Baumstamm herum und findest in einem kleinen Versteck `^$amt `2Gold.");
		$session[user][gold]+=$amt;
		break;
		case 9:
		case 10:
		case 11:
		output("sp�rst einen brennenden Schmerz in deiner Hand! Eine Schlange bewohnt diesen Baumstamm und hat dir in die Hand gebissen. Dir ");
		output("wird schwarz vor Augen. Das Gift wirkt.`n`nAls du wieder zu dir kommst, f�hlst du dich schwach. Aber du hast �berlebt.`n");
		if ($session[user][hitpoints]>2){
			output("`4Du hast die meisten deiner Lebenspunkte verloren.");
			$session[user][hitpoints]=2;
		}
		break;
		case 12:
		output("du fasst direkt mitten in ein `4Wespennest! `7Zornig aufgeweckt st�rzen sich die Wespen wutentbrannt auf dich und zerstechen Deine Arme, Dein Gesicht und Deinen Hals!`n`n");
		output("`^Du verlierst einen Charmpunkt und einige Lebenspunkte!");
		if ($session[user][charm] >0) $session[user][charm]--;
		$session[user][hitpoints] = $session[user][hitpoints]* 0.6;
		break;


		
	}
	// addnav("Weg weitergehen","forest.php");
}else if ( $HTTP_GET_VARS[op] == "lesen"){
	output("`n`2Auf dem Pergament siehst du eine Karte dieser Gegend. Auf der Karte ist ");
	output("ein '`4X`2' markiert. Es scheint eine Schatzkarte zu sein!`n`n");
	output("Am markierten Weg erkennst du, dass dich die Schatzsuche fast den gesamten restlichen Tag kosten w�rde.");
	$session['user']['specialinc'] = $specialinc_file; 
	addnav("Schatz suchen","forest.php?op=schatzsuche");
	addnav("Weg weitergehen","forest.php?op=back");
}else if ( $HTTP_GET_VARS[op] == "schatzsuche"){
	if ($session[user][turns]<1){
		output("`n`2Leider hast du heute nicht mehr genug Zeit �brig, die du zum Suchen nach dem Schatz verwenden k�nntest.");
	}else{
		$runden=e_rand(1,$session[user][turns]);
		output("`n`2Auf der Suche nach dem `4X`2 auf der Karte verbrauchst du `@$runden `2Waldk�mpfe. Schliesslich findest du die Stelle ");
		output("und f�ngst auch sofort an, mit ".$session[user][weapon]."`2 nach dem hier vermuteten Schatz zu graben. ");
		output("Schon nach kurzer Zeit st��t du auf eine gro�e beschlagene ");
		output("Holzkiste. Als du die Kiste �ffnest, l�chelt dich ein kleines Verm�gen an:`n`n");
		$foundgold = 100 * e_rand(1,$runden) * $session['user']['level'];
		$foundgold = round(e_rand($foundgold/2 , $foundgold));
		if ( e_rand(1,4) == 1 ) $foundgold *= 10;
		output("`2In der Schatztruhe findest du $foundgold Goldst�cke. Nach einem kleinen Freudentanz machst du dich zur�ck auf deinen Weg.");
		addnews("`0In einer st�rmischen Nacht findet ".$session['user']['name']." einen riesigen Schatz von `^$foundgold`0 Goldst�cken.");
		$session['user']['gold'] += $foundgold;
		$session[user][turns]-=$runden;
		$session[user][reputation]++;
	}
	$session['user']['specialinc'] = $specialinc_file; 
	addnav("Zur�ck in den Wald","forest.php?op=back");
}else if ( $HTTP_GET_VARS[op] == "back"){
	output("`n`2Du begibst dich wieder auf den Weg von dem du gekommen bist und gehst weiter auf die Suche nach Abenteuern.");
	$seesion['user']['specialinc'] = "";
	forest(true);
	// addnav("Zur�ck in den Wald","forest.php");
}else{
	output("`n");
	output("`2Am Rande des Weges f�llt dir ein umgefallener hohler Baum auf, der irgendwie nach einem Abenteuer riecht.`n`n");
	output("Du kannst den Baum n�her untersuchen oder weiter deines Weges gehen.`0");

	$session['user']['specialinc'] = $specialinc_file;
	addnav("u?Baum untersuchen","forest.php?op=baum");
	addnav("Weg weitergehen","forest.php?op=back");
}

?>