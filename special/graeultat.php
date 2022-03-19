<?php

// 21072004

// Graeultat - written by Joerg Ledergerber
// 18072004

// some bugs removed, a little balancing

if ($_GET['op']=="return") {
	switch(e_rand(1,6)){
		case 1:
		case 2:
		output("`n`2Erleichtert gehst du weg von den Leichen und fühlst dich nun reicher an Erfahrung.`n`n");
		$xp=$session['user']['level']*20;
		output("`^Du erhälst $xp Erfahrungspunkte.`0");
		$session[user][experience]+=$xp; 
		break;
		case 3:
		case 4:
		if ($session[user][turns] > 0) {
			output("`n`2Du fürchtest, dass du durch diesen Schock ersteinmal nicht kämpfen kannst und ruhst dich lieber noch einen Moment lang aus.`n`n");
			output("`^Du verlierst einen Waldkampf.`0");
			$session[user][turns]-=1;
		}else{
			output("`n`2Du hältst diesen Schwächeanfall für ein gutes Zeichen, heute besser keine weiteren Gegner im Wald zu erschlagen.`n`");
			$session['user']['reputation']++;
		}
		break;
		case 5:
		case 6:
		if($session[user][hitpoints] > 1) {
			output("`n`2Du bekommst starke Würgekrämpfe und gehst zu Boden. Der Gestank ist wirklich überwältigend.`n`n");
			output("`^Durch die ganze Aufregung und die Übelkeit verlierst du ein paar Lebenspunkte.`0");
			$session[user][hitpoints]=round($session[user][hitpoints]*0.8);
		}else {
			output("`n`2Du überwindest deine Übelkeit und bist wieder bereit, weiter zu gehen.`0");
		}
		break;
	}
	$session[user][specialinc]="";

}elseif ($_GET['op']=="escape") {
	output("`n`2Du beschließt, so schnell wie möglich von hier weg zu rennen, auf deiner Flucht stürtzt du ein paar mal und verletzt dich. Als du meinst in Sicherheit zu sein, drehst du dich um und erschreckst dich fast zu Tode: die alte Frau steht hinter dir und ist nicht einmal außer Atem.`n`n");
	$session[user][specialinc]="graeultat.php";
	$session[user][reputation]--;
	switch(e_rand(1,3)){
		case 1:
		if ($session[user][hitpoints] > 2) {
			output("`^Du verlierst 10% deiner Lebenspunkte.`0");
			$session[user][hitpoints]=round($session[user][hitpoints]*0.9);
		}
		break;
		case 2:
		output("`^Du verlierst etwas Gold.`0");
		$session[user][gold]=round($session[user][gold]*0.7);
		break;
		case 3:
		if ($session[user][gems]>0){
			output("`^Du verlierst auf deiner Flucht einen Edelsteine.`0");
			$session[user][gems]--;
		}
		break;
	}
	addnav("Sich der Hexe stellen","forest.php?op=dead");
	addnav("Weiter rennen","forest.php?op=escape2");

}elseif ($_GET['op']=="escape2") {
	switch(e_rand(0,2)){
		case 0:
		case 1:
		output("`n`2Du schaffst es, vor der Hexe davon zu rennen. Aber vermutlich nur, weil sie die Lust verloren hat, dir zu folgen.`n");
		output("`^Du ruhst dich aus und verlierst dadurch einen Waldkampf.`0");
		$session[user][turns]-=1;
		$session[user][specialinc]="";
		break;
		case 2:
		output("`n`2Du schaffst es nicht, vor der Hexe zu flüchten. Erschöpft gibst du auf und stellst dich.`0`n");
		$session[user][specialinc]="graeultat.php";
		addnav("Sich der Hexe stellen","forest.php?op=dead");
		break;
	}

}elseif ($_GET['op']=="dead") {
	output("`n`2Du atmest tief durch, schließt die Augen und streckst deine Hände weit weg von deinem Körper. Du bietest dich der Hexe förmlich an.`nWenige Sekunden danach hat die Hexe ihren Zauberspruch beendet und saugt dir das Leben aus.`n`n");
	output("`4`bDir wird schwindlig und du fällst zu Boden, dein Leben ist nun vollkommen ausgesaugt.`b`n`nEine weitere Leiche liegt nun hinter dem Baum.");
	$session[user][hitpoints]=0;
	$session[user][alive]=false;
	$session[user][specialinc]="";
	addnews("`0".$session[user][name]." `0wurde von einer Hexe getötet.");
	addnav("Tägliche News","news.php");

}elseif ($_GET['op']=="look") {
	switch(e_rand(1,4)){
		case 1:
		case 2:
		output("`n`2Du holst tief Luft und näherst dich den Leichen.`n");
		output("`2Als du dich den Leichen näherst, hörst du, wie hinter dir ein Ast bricht, du ziehst deine Waffe und drehst dich sofort um.`0`n`n`3Eine alte Frau in schwerem Gewand steht dir gegenüber und murmelt etwas vor sich hin, in einer Sprache die du nicht verstehst.`0");
		output("`n`n`2Als du das Wort ergreifst, schaut sie aus der Leere direkt in deine Augen. \"`8Kann ich Euch behilflich sein?`2\" Fragst du nervös.`n");
		output("`2\"`6Wie kann mir jemand helfen, der selbst Hilfe nötig hat? Aber wenn Ihr darauf besteht, ich nehme gerne das was Ihr noch habt. Euer Leben!`2\" Erwiedert die Frau.`0`n");
		output("`n`3Die Frau fängt an zu kichern und fuchtelt mit den Händen in der Luft herum, murmelt wieder etwas in der Sprache vor sich hin, die du nicht kennst`0`n");
		$session[user][specialinc]="graeultat.php";
		addnav("Sich der Hexe stellen","forest.php?op=dead");
		addnav("Schnell wegrennen","forest.php?op=escape");
		break;
		case 3:
		case 4:
		output("`n`2Du holst tief Luft und näherst dich den Leichen.`n");
		output("`2Als du eine der Leichen auf die Seite rollst, faucht dich erstmal eine Ratte an, verschwindet aber gleich.`n`n`^Du findest 1 Edelstein.`0");
		$session[user][gems]+=1;
		$session[user][specialinc]="";
		break;
	}

}else{
	output("`n`c`b`#Überkommende Übelkeit`b`c`n`n");
	output("`2Als du dich auf die Suche nach einem Abenteuer begiebst, wird dir plötzlich schlecht, du setzt dich auf den alten Baumstamm in deiner Nähe, kaum als das du sitzt überkommt dich ein Brechreiz, du drehst dich um, schreckst sofort wieder zurück.`2`n`n");
	output("`2Hinter dem Baumstamm liegen ein paar angenagte und verweste Leiche, deren Gestank wohl deine Übelkeit verursacht hat. Dein Brechreiz siegt und deine letzte Mahlzeit verteilt sich vor deinen Füßen.`2");
	output("`n`nWillst du...`n`n... so schnell wie möglich von der Gräul entfliehen und <a href='forest.php?op=return'>diesen Ort sofort verlassen</a>?`n... dir die Leichen <a href='forest.php?op=look'>näher betrachten</a>?",true);
	$session[user][specialinc]="graeultat.php";
	addnav("","forest.php?op=look");
	addnav("","forest.php?op=return");
	addnav("Leichen näher betrachten","forest.php?op=look");
	addnav("Zurück in den Wald","forest.php?op=return");
}
?>