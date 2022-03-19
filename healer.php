<?php

// 24062004

require_once "common.php";
$config = unserialize($session['user']['donationconfig']);
if ($config['healer'] || $session[user][acctid]==getsetting("hasegg",0)) $golinda = 1;

if ($golinda) {
	page_header("Golindas H�tte");
	output("`#`b`cGolindas H�tte`c`b`n");
} else {
	page_header("H�tte des Heilers");
	output("`#`b`cH�tte des Heilers`c`b`n");
}
$loglev = log($session[user][level]);
$cost = ($loglev * ($session[user][maxhitpoints]-$session[user][hitpoints])) + ($loglev*10);
if ($golinda) $cost *= .5;
$cost = round($cost,0);

if ($HTTP_GET_VARS[op]==""){
  	checkday();
	if ($golinda) {
		output("`3Eine sehr zierliche und wunderh�bsche Br�nette schaut auf, als du eintrittst. \"`6Ah, Du musst {$session['user']['name']}.`6  sein. Mir wurde gesagt, dass du kommen w�rdest. Komm rein... komm rein!`3\", ruft sie.`n`nDu gehst tiefer in die H�tte.`n`n");
	} else {
		output("`3Du gehst geb�ckt in die rauchgef�llte Grash�tte.  Das stechende Aroma l�sst dich husten und zieht die Aufmerksamkeit einer uralten grauhaarigen Person auf dich, die den Job, dich an einen Felsen zu erinnern, bemerkenswert gut ausf�hrt. Das erkl�rt, dass du den kleinen Kerl bis jetzt nicht bemerkt hast. Kann ja nicht dein Fehler sein - als Krieger... Nop, definitiv nicht.`n`n");
	}
	if ($session[user][hitpoints] < $session[user][maxhitpoints]){
		if ($golinda) {
			output("`3\"`6Nun... lass uns mal sehen. Hmmm. Hmmm. Du siehst ein bisschen angeschlagen aus.`3\"`n`n\"`5�h... ja. Ich sch�tze schon. Was wird mich das kosten?`3\", fragst du betreten, \"`5Wei�t du, normalerweise werde ich nicht so leicht verletzt.`3\"`n`n\"`6Ich wei�, ich wei�. Niemand von euch wird `^jemals`6 verletzt. Aber egal. F�r `$`b$cost`b`6 Goldst�cke mache ich dich wieder frisch wie einen Sommerregen. Ich kann dich auch zu einem niedrigeren Preis teilweise heilen, wenn du dir die volle Heilung nicht leisten kannst.`3\", sagt Golinda mit einem s��en L�cheln.");
		} else {
			output("\"`6Sehen kann ich dich. Bevor du sehen konntest mich, hmm?`3\" bemerkt das alte Wesen. \"`6Ich kenne dich, ja; Heilung du suchst. Bereit zu heilen dich ich bin, wenn bereit zu bezahlen du bist.`3\"`n`n\"`5Oh-oh. Wieviel?`3\" fragst du, bereit dich von diesem stinkenden alten Dings ausnehmen zu lassen.`n`nDas alte Wesen pocht dir mit einem knorrigen Stab auf die Rippen: \"`6F�r dich... `$`b$cost`b`6 Goldst�cke f�r eine komplette Heilung!!`3\". Dabei kr�mmt es sich und zieht ein Tonfl�schchen hinter einem Haufen Sch�del hervor. Der Anblick dieses Dings, das sich �ber den Sch�delhaufen kr�mmt, um das Fl�schchen zu holen, verursacht wohl genug geistigen Schaden, um eine gr��ere Flasche zu verlangen.  \"`6Ich auch habe einige - �hm... 'g�nstigere' Tr�nke im Angebot.`3\" sagt das Wesen, w�hrend es auf  einen verstaubten Haufen zerbrochener Tonkr�ge deutet. \"`6Sie werden heilen einen bestimmten Prozentsatz deiner `iBesch�digung`i.`3\"");
		}
		addnav("Heiltr�nke");
		addnav("`^Komplette Heilung`0","healer.php?op=buy&pct=100");
		for ($i=90;$i>0;$i-=10){
			addnav("$i% - ".round($cost*$i/100,0)." Gold","healer.php?op=buy&pct=$i");
		}
		addnav("`bZur�ck`b");
		addnav("Zur�ck in den Wald", "forest.php");
		addnav("Zur�ck ins Dorf","village.php");
	}else if($session[user][hitpoints] == $session[user][maxhitpoints]){
		if ($golinda) {
			output("`3Golinda untersucht dich sehr sorgf�ltig. \"`6Nun, du hast diesen leicht eingewachsenen Zehennagel hier, aber ansonsten bist du vollkommen gesund. `^Ich`6 glaube, du bist nur hier her gekommen, weil du einsam warst.`3\", kichert sie.`n`nDu erkennst, dass sie Recht hat und dass du sie von ihren anderen Patienten abh�ltst. Deswegen gehst du zur�ck in den Wald.");
		} else {
			output("`3Die alte Kreatur schaut in deine Richtung und grunzt: \"`6Einen Heiltrank du nicht brauchst. Warum du mich st�rst, ich mich frage.`3\" Der Geruch seines Atems l�sst dich w�nschen, du w�rst gar nicht erst gekommen. Du denkst, es ist das Beste, einfach wieder zu gehen.");
		}
		forest(true);
	}else{
		if ($golinda) {
			output("`3Golinda untersucht dich sehr sorgf�ltig. \"`6Ohje! Du hast nicht einmal einen eingewachsenen Zehennagel, den ich heilen k�nnte! Du bist ein Prachtexemplar der " . ($session['user']['sex'] == 1 ? "Frauenschaft" : "M�nnerschaft") . "!  Komm bitte wieder, wenn du verletzt wurdest`3\". Damit wendet sie sich wieder ihrer Tr�nkemischerei zu.`n`n\"`6Das werde ich`3\", stammelst du unglaublich verlegen und gehst zur�ck in den Wald.");
		} else {
			output("`3Die alte Kreatur blickt dich an und mit einem `^Wirbelwind einer Bewegung`3, die dich v�llig unvorbereitet erwischt, bringt sie ihren knorrigen Stab in direkten Kontakt mit deinem Hinterkopf. Du st�hnst und brichst zusammen.`n`nLangsam �ffnest du die Augen und bemerkst, dass dieses Biest gerade die letzten Tropfen aus einem Tonkrug in deinen Rachen sch�ttet.`n`n\"`6Dieser Trank kostenlos ist.`3\" ist alles, was es zu sagen hat. Du hast das dringende Bed�rfnis, die H�tte so schnell wie m�glich zu verlassen.");
			$session[user][hitpoints] = $session[user][maxhitpoints];
		}
		forest(true);
	}
}else{
	$newcost=round($HTTP_GET_VARS[pct]*$cost/100,0);
	if ($session[user][gold]>=$newcost){
		$session[user][gold]-=$newcost;
		//debuglog("spent $newcost gold on healing");
		$diff = round(($session[user][maxhitpoints]-$session[user][hitpoints])*$HTTP_GET_VARS[pct]/100,0);
		$session[user][hitpoints] += $diff;
		if ($golinda) {
			output("`3Du erwartest ein fauliges Ges�ff und kippst den Trank herunter, aber als die Fl�ssigkeit dir den Rachen hinunter l�uft, schmeckst du Zimt, Honig und irgendetwas fruchtiges. Du f�hlst W�rme durch deinen K�rper str�men und deine Muskeln fangen an, sich von selbst zusammenzuf�gen. Mit klarem Kopf und wieder bei bester Gesundheit gibst du Golinda ihr Gold und verl�sst die H�tte in Richtung Wald.");
		} else {
			output("`3Mit verzerrtem Gesicht kippst du den Trank, den dir die Kreatur gegeben hat, runter. Trotz des fauligen Geschmacks f�hlst du, wie sich W�rme in deinen Adern ausbreitet und deine Muskeln heilen. Leicht taumelnd gibst du der Kreatur ihr Geld und verl�sst die H�tte.");
		}
		output("`n`n`#Du wurdest um $diff Punkte geheilt!");
		if ($HTTP_GET_VARS[pct]==100 && $session[user][dragonkills]>3 && e_rand(1,2)==2 && $session[user][reputation]>0) $session[user][reputation]--;
		forest(true);
	}else{
		if ($golinda) {
			output("`3\"`6Tss, tss!`3\", murmelt Golinda. \"`6Vielleicht solltest du erstmal zur Bank gehen und wiederkommen, sobald du `b`\$$newcost`6`b Gold hast?`3\"`n`nDu f�hlst dich ziemlich bl�de, weil du ihre kostbare Zeit vergeudet hast.`n`n\"Oder vielleicht w�re ein billigerer Trank besser f�r dich?`3\", schl�gt sie freundlich vor.");
		} else {
			output("`3Die alte Kreatur durchbohrt dich mit einem harten, grausamen Blick. Deine blitzschnellen Reflexe erm�glichen dir, dem Schlag mit seinem knorrigen Stab auszuweichen. Vielleicht solltest du erst etwas Gold besorgen, bevor du versuchst, in den lokalen Handel einzusteigen. `n`nDir f�llt ein, dass die Kreatur `b`\$$newcost`3`b Goldm�nzen verlangt hat.");
		}
		addnav("Heiltr�nke");
		addnav("`^Komplette Heilung`0","healer.php?op=buy&pct=100");
		for ($i=90;$i>0;$i-=10){
			addnav("$i% - ".round($cost*$i/100,0)." Gold","healer.php?op=buy&pct=$i");
		}
		addnav("`bZur�ck`b");
		addnav("Zur�ck in den Wald","forest.php");
		addnav("Zur�ck ins Dorf","village.php");
	}
}
page_footer();
?>
