<?php

// 25082004

// originally found at www.lotgd.com 
// changes & translation by anpera
// additional changes by nTE

checkday();
page_header("Die Burg"); 
$runden=$session[user][turns];
$castleoptions = unserialize($session['user']['specialmisc']);
if (!is_array($castleoptions)) {
	init_castlearray();
}
else {
	if ($castleoptions['castleinit'] != 1) {
		init_castlearray();
	}
}
$session['user']['specialmisc'] = serialize($castleoptions);


function init_castlearray(){
	$castleoptions = array();
	$castleoptions['beautyshop_uses'] = 0;
	$castleoptions['well_uses'] = 0;
	$castleoptions['castleinit'] = 1;
}

function castlenav($what, $runden){
	global $session;
	switch ($what){ 
		case "main":
		addnav("Burghof"); 
		addnav("Wunschbrunnen (1 Edelstein)","forest.php?op=well");
		addnav("Glücksspieler","stonesgame.php");
		// addnav("Orc Fields Bar","forest.php?op=bar");
		if ($runden>0) addnav("Übungsraum","forest.php?op=train");
		addnav("Shops"); 
		addnav("Waffenschmied","forest.php?op=blacksmith"); 
		addnav("Rüstungsschmied","forest.php?op=armourer"); 
		addnav("Kala's Beautyshop","forest.php?op=medicine"); 
		addnav("Sonstige");
		if ($runden>0) addnav("Katakomben betreten...","forest.php?op=catacombs"); 
		addnav("v?Burg verlassen","forest.php?op=leavecastle"); 
		break; 
		case "return": 
		addnav("Nach draussen","forest.php?op=return"); 
		break;
		default:
		$session['user']['specialinc']="";
		forest();
		break;
	} 
}

function catacombs(){ 
	global $session; 
	output("`n`%Du kannst in folgende Richtungen gehen:"); 
	$where=false; 
	switch (e_rand(1,2)){ 
		case 1: 
		output("`n<a href='forest.php?op=north'>Norden</a>",true); 
		addnav("Norden","forest.php?op=north"); 
		addnav("","forest.php?op=north"); 
		$where=true; 
		break; 
		case 2: 
		break; 
	} 
	switch (e_rand(1,2)){ 
		case 1: 
		output("`n<a href='forest.php?op=east'>Osten</a>",true); 
		addnav("Osten","forest.php?op=east"); 
		addnav("","forest.php?op=east"); 
		$where=true; 
		break; 
		case 2: 
		break; 
	} 
	switch (e_rand(1,2)){ 
		case 1: 
		output("`n<a href='forest.php?op=south'>Süden</a>",true); 
		addnav("Süden","forest.php?op=south"); 
		addnav("","forest.php?op=south"); 
		$where=true; 
		break; 
		case 2: 
		break; 
	} 
	switch (e_rand(1,2)){ 
		case 1: 
		output("`n<a href='forest.php?op=west'>Westen</a>",true); 
		addnav("Westen","forest.php?op=west"); 
		addnav("","forest.php?op=west"); 
		$where=true; 
		break; 
		case 2: 
		break; 
	} 
	if ($where == false){ 
		switch (e_rand(1,5)){ 
			case 1: 
			output("`n<a href='forest.php?op=north'>Norden</a>",true); 
			addnav("Norden","forest.php?op=north"); 
			addnav("","forest.php?op=north"); 
			break; 
			case 2: 
			output("`n<a href='forest.php?op=east'>Osten</a>",true); 
			addnav("Osten","forest.php?op=east"); 
			addnav("","forest.php?op=east"); 
			break; 
			case 3: 
			output("`n<a href='forest.php?op=south'>Süden</a>",true); 
			addnav("Süden","forest.php?op=south"); 
			addnav("","forest.php?op=south"); 
			break; 
			case 4: 
			output("`n<a href='forest.php?op=west'>Westen</a>",true); 
			addnav("Westen","forest.php?op=west"); 
			addnav("","forest.php?op=west"); 
			break; 
			case 5:
			switch(e_rand(1,5)){
				case 1:
				addnews($session[user][name]." hat große Reichtümer in den Katakomben gefunden!"); 
				$gems = e_rand(1,3); 
				$gold = e_rand($session[user][level]*11,$session[user][level]*100); 
				output("`^ Vorwärts!`n`n`%Du erreichst eine verschlossene Tür und drückst sie auf. Dahinter findest du Berge von Reichtümern und du stopfst dir die Taschen voll!`n"); 
				output("`n`^Du hast $gems Edelsteine und $gold Gold mitgenommen!"); 
				$session[user][gems]+=$gems; 
				$session[user][gold]+=$gold; 
				break;
				case 2:
				output("`^ Vorwärts!`n`n`%Du erreichst eine verschlossene Tür und drückst sie auf. Dahinter findest du Berge von Gold und du stopfst dir die Taschen voll!`n"); 
				$gold = e_rand($session[user][level]*11,$session[user][level]*100);
				output("`n`^Du hast $gold Gold mitnehmen können!"); 
				$session[user][gold]+=$gold; 
				break;
				case 3:
				output("`^ Vorwärts!`n`n`%Du erreichst eine verschlossene Tür und drückst sie auf. Dahinter findest du ... `bnichts`b! Ein anderer war wohl schneller als du.`n"); 
				output("`nSchwer enttäuscht suchst du einen Ausgang aus den Katakomben."); 
				break;
				case 4:
				output("`^ Vorwärts!`n`n`%Du erreichst eine verschlossene Tür und drückst sie auf. Dahinter findest du ... `bnichts`b! Ein anderer war wohl schneller als du.`n"); 
				output("Schwer enttäuscht suchst du einen Ausgang aus den Katakomben.`n`nWenigstens hast du durch das Herumirren etwas an `^Erfahrung`% gewonnen."); 
				$session[user][experience]+=$session[user][experience]*0.02;
				break;
				case 5:
				output("`^ Vorwärts!`n`n`%Du erreichst eine verschlossene Tür und drückst sie auf. Dahinter findest du einen besonders schönen Edelstein und steckst ihn ein!`n"); 
				output("`n`^Du hast 1 Edelstein gefunden!"); 
				$session[user][gems]+=1; 
				break;
			}
			output("`n`n`n<a href='forest.php?op=exitlab'>Katakomben verlassen</a>",true); 
			addnav("Katakomben verlassen","forest.php?op=exitlab"); 
			addnav("","forest.php?op=exitlab");
			break; 
		} 
	} 
}

if ($HTTP_GET_VARS[op]=="enter"){ 
	$session[user][specialinc]="castle.php"; 
	output("`#Die Wache tritt beiseite und du läufst durch das Tor in die Burg. Die Mitte des Burghofs ist ein großer, mit Gras bewachsener Platz, um den herum viele interessante Stände und Läden sind. Einige davon klingen wirklich verlockend! Du weisst gar nicht, wo du zuerst hingehen sollst, aber auf dem Platz stehen einige Leute, so beschließt du, sie einfach zu fragen.`n"); 
	viewcommentary("Courtyard","Frage nach Tipps",30,"fragt"); 
	castlenav("main", $runden); 
/******Leave Castle******/ 
}elseif ($HTTP_GET_VARS[op]=="leave"){ 
	$session[user][specialinc]="";
	$session['user']['specialmisc'] = 0;
	output("`#Du beschließt, daß du keine Zeit für die Burg hast und kehrst um. Du nimmst den selben Pfad zurück in den Wald, den du gekommen bist..."); 
	//output("`n`n`^Du vertrödelst einen Waldkampf!"); 
	//if ($session[user][turns]>0) $session[user][turns]--; 
}elseif ($HTTP_GET_VARS[op]=="leavecastle"){ 
	$session[user][specialinc]="";
	$session['user']['specialmisc'] = 0;
	output("`#Du gehst durch das Tor und über den Pfad zurück in den Wald."); 
	output("`n`n`^Du vertrödelst einen Waldkampf!"); 
	if ($session[user][turns]>0) $session[user][turns]--; 
/********Return to Courtyard*******/ 
}elseif ($HTTP_GET_VARS[op]=="return"){ 
	$session[user][specialinc]="castle.php"; 
	output("`#Du gehst nach draussen. Die Mitte des Burghofs ist ein großer, mit Gras bewachsener Platz, um den herum viele interessante Stände und Läden sind. Einige davon klingen wirklich verlockend! Du weisst gar nicht, wo du zuerst hingehen sollst, aber auf dem Platz stehen einige Leute, so beschließt du, sie einfach zu fragen.`n"); 
	viewcommentary("Courtyard","Frage nach Tipps",30,"fragt"); 
	castlenav("main", $runden); 
/*********catacombs*******/ 
}elseif ($HTTP_GET_VARS[op]=="catacombs"){ 
	$session[user][specialinc]="castle.php"; 
	output("`#Du betrittst die Katakomben. Ein Schild am Eingang warnt: `%'Große Reichtümer warten im Inneren, aber ebenso großes Leid! Der Weg nach draussen liegt im `^Osten`%... Merk dir das!!'`n"); 
	catacombs(); 
}elseif ($HTTP_GET_VARS[op]=="north"){ 
	$session[user][specialinc]="castle.php"; 
	output("`#Im Inneren der Katakomben gehst du auf der Suche nach Reichtum nach `^Norden`#...`n"); 
	catacombs(); 
}elseif ($HTTP_GET_VARS[op]=="east"){ 
	$session[user][specialinc]="castle.php"; 
	output("`#Im Inneren der Katakomben gehst du auf der Suche nach Reichtum nach `^Osten`#...`n"); 
	switch (e_rand(1,5)){ 
		case 1: 
		case 2: 
		case 3: 
		case 4: 
		catacombs(); 
		break; 
		case 5: 
		catacombs(); 
		output("`n`n`^Du findest einen Ausgang..."); 
		output("`n<a href='forest.php?op=exitlab'>Katakomben verlassen</a>",true); 
		addnav("Ausgang"); 
		addnav("Katakomben verlassen","forest.php?op=exitlab"); 
		addnav("","forest.php?op=exitlab"); 
		break; 
	} 
}elseif ($HTTP_GET_VARS[op]=="south"){ 
	$session[user][specialinc]="castle.php"; 
	output("`#Im Inneren der Katakomben gehst du auf der Suche nach Reichtum nach `^Süden`#...`n"); 
	catacombs(); 
}elseif ($HTTP_GET_VARS[op]=="west"){ 
	$session[user][specialinc]="castle.php"; 
	output("`#Im Inneren der Katakomben gehst du auf der Suche nach Reichtum nach `^Westen`#...`n"); 
	catacombs(); 
}elseif ($HTTP_GET_VARS[op]=="exitlab"){ 
	$session['user']['specialmisc'] = 0;
	output("`#Du hast es geschafft einen Ausgang aus den Katakomben zu finden. Allerdings musst du feststellen, dass du wieder im Wald gelandet bist. Dein Abenteuer in den Katakomben hatte seinen Preis...`n"); 
	$ff = e_rand(1,4);
	output("`n`^Du verlierst $ff Waldkämpfe!"); 
	if ($session[user][turns]>0) $session[user][turns]-=$ff; 
/*********Bar*******/ 
}elseif ($HTTP_GET_VARS[op]=="bar"){ 
	$session[user][specialinc]="castle.php"; 
	output("`#You walk through the door of the `^'Orc Fields Bar'`# and the first thing you notice is the door on the side from which many Orcs magically appear from. You've heard of this place, the unlimited supply or Orcs to kill make it a good place to relax and let off some steam!`n"); 
	output("`n`n"); 
	viewcommentary("orcfield","Kill Orcs here",30,"announces"); 
	castlenav("return", $runden); 
/********Armourer********/ 
}elseif ($HTTP_GET_VARS[op]=="armourer"){ 
	$session[user][specialinc]="castle.php"; 
	if (strchr($session[user][armor],"High-Grade")){ 
		output("`#Du betrittst `@Thoric's`# Rüstungsladen. Du siehst Thoric in ein Buch vertieft in einer Ecke sitzen. Er schaut auf und wirft sofort einen Blick auf dein(e/n) `^".$session[user][armor]."`#. `%'Schön zu sehen, daß du meine Handwerkskunst trägst.'`# murmelt er, bevor er sich wieder seinem Buch zuwendet."); 
	}else{ 
		$newdefence = $session[user][armordef] + 2; 
		$cost = $session[user][armordef] * 200;
		output("`#Du betrittst `@Thoric's`# Rüstungsladen. Du siehst Thoric in ein Buch vertieft in einer Ecke sitzen. Er schaut auf und wirft sofort einen Blick auf dein(e/n) `^".$session[user][armor]."`#. ");
		if ($cost == 0){
			output("`%'Sieht nicht so aus, als ob ich aus damit irgendetwas machen könnte.'`#, murmelt er, bevor er sich wieder seinem Buch zuwendet.");
			output("`n`n`^Niedergeschlagen machst du dich daran den Laden zu verlassen...");
		}else if ($cost > $session[user][gold]){
			output("`%'Ich könnte das zu eine(r/m) `!High-Grade ".$session[user][armor]."`% mit `^$newdefence`% Rüstungsschutz machen, wenn du willst. Und das kostet dich nur `^$cost`% Gold!'`#, murmelt er, bevor er sich wieder seinem Buch zuwendet.");
			output("`n`n`^Da du aber nicht so viel Gold dabei hast, beschließt du den Laden zu verlassen...");
		}else{
			output("`%'Ich könnte das zu eine(r/m) `!High-Grade ".$session[user][armor]."`% mit `^$newdefence`% Rüstungsschutz machen, wenn du willst. Und das kostet dich nur `^$cost`% Gold!'`#, murmelt er, bevor er sich wieder seinem Buch zuwendet.");
			output("`n`n<a href='forest.php?op=upgradearmour'>Rüstung verbessern</a>",true); 
			addnav("","forest.php?op=upgradearmour"); 
			addnav("Rüstung verbessern","forest.php?op=upgradearmour"); 
		} 
	} 
	castlenav("return", $runden); 
}elseif ($HTTP_GET_VARS[op]=="upgradearmour"){ 
	$session[user][specialinc]="castle.php"; 
	output("`#`@Thoric`# nimmt dein(e/n) `^".$session[user][armor]."`# und arbeitet eine Weile daran. Bald steht er auf, passt dir die Rüstung an und macht noch ein paar abschließende Änderungen. Die Rüstung fühlt sich jetzt etwas schwerer an, scheint aber tatsächlich von viel höherer Qualität zu sein als vorher. Zufrieden verlässt du den Laden."); 
	$newarmor = "High-Grade ".$session[user][armor]; 
	$cost = $session[user][armordef] * 200; 
	$session[user][gold]-=$cost; 
	$session[user][armor]= $newarmor; 
	$session[user][armordef]+=2; 
	$session[user][armorvalue]+=$cost; 
	$session[user][defence]+=2; 
	castlenav("return", $runden); 
/********Blacksmith********/ 
}elseif ($HTTP_GET_VARS[op]=="blacksmith"){ 
	$session[user][specialinc]="castle.php"; 
	if (strchr($session[user][weapon],"High-Grade")){ 
		output("`#Du betrittst die Schmiede. Der Waffenschmied beugt sich über einen Schmelztiegel mit geschmolzenem Metall und betrachtet dein(e/n) `^".$session[user][weapon]."`#. `%'Das war ne tolle Arbeit, die ich da für dich gemacht hab, also warum bist du hier?'`#, gibt er an. Etwas enttäuscht verlässt du die Schmiede."); 
	}else{ 
		$newattack = $session[user][weapondmg] + 2; 
		$cost = $session[user][weapondmg] * 200; 
		output("`#Du betrittst die Schmiede. Der Waffenschmied beugt sich über einen Schmelztiegel mit geschmolzenem Metall und betrachtet dein(e/n) `^".$session[user][weapon]."`#. "); 
		if ($cost == 0){ 
			output("`%'Du erwartest doch nicht, dass sowas bearbeite? Komm wieder wenn du eine ordentliche Waffe hast.'");
			output("`n`n`^Niedergeschlagen machst du dich daran den Laden zu verlassen...");
		}else if ($cost > $session[user][gold]){
			output("`%'Daraus kann ich ein `5High-Grade ".$session[user][weapon]."`% mit `5$newattack`% Schaden machen! Aber das wird dich `5$cost`% Gold kosten...'");
			output("`n`n`^Da du nicht genug Gold hast, beschließt du den Laden zu verlassen..."); 
		}else{ 
			output("`%'Daraus kann ich ein `5High-Grade ".$session[user][weapon]."`% mit `5$newattack`% Schaden machen! Aber das wird dich `5$cost`% Gold kosten...'");
			output("`n`n<a href='forest.php?op=upgradeweapon'>Waffe verbessern</a>",true); 
			addnav("","forest.php?op=upgradeweapon"); 
			addnav("Waffenschmied"); 
			addnav("Waffe verbessern","forest.php?op=upgradeweapon"); 
		} 
	} 
	castlenav("return", $runden); 
}elseif ($HTTP_GET_VARS[op]=="upgradeweapon"){ 
	$session[user][specialinc]="castle.php"; 
	output("`#Der Waffenschmied nimmt `^".$session[user][weapon]."`# und arbeitet eine Weile daran. Bald steht er auf und gibt dir deine Waffe zurück. Sie scheint etwas schwerer zu sein, aber die Qualität scheint wesentlich besser als vorher zu sein. Zufrieden verlässt du den Shop. "); 
	$newweapon = "High-Grade ".$session[user][weapon]; 
	$cost = $session[user][weapondmg] * 200; 
	$session[user][gold]-=$cost; 
	$session[user][weapon]= $newweapon; 
	$session[user][weapondmg]+=2; 
	$session[user][weaponvalue]+=$cost; 
	$session[user][attack]+=2; 
	castlenav("return", $runden); 
/********Training Room********/ 
}elseif ($HTTP_GET_VARS[op]=="train"){ 
	$session[user][specialinc]="castle.php"; 
	output("`#Du betrittst den Trainingsraum und schaust dich um. Du siehst diverse Schwerter, Dummies und Trainer. Hier kannst du Waldkämpfe zum Trainieren verbringen und gefahrlos deine Erfahrung steigern.`n"); 
	if ($session[user][turns] < 1){ 
		output("`n`n`%Du hast leider keine Waldkämpfe zum Trainieren übrig!"); 
	}else{ 
		output("`%Wieviele Runden willst du trainieren?`n"); 
		output("<form action='forest.php?op=rain2' method='POST'><input name='trai' id='trai'><input type='submit' class='button' value='Trainieren'></form>",true); 
		output("<script language='JavaScript'>document.getElementById('trai').focus();</script>",true); 
		addnav("","forest.php?op=rain2"); 
	} 
	castlenav("return", $runden); 
}elseif ($HTTP_GET_VARS[op]=="rain2"){
	$trai = abs((int)$HTTP_GET_VARS[trai] + (int)$HTTP_POST_VARS[trai]);
	$session[user][specialinc]="castle.php"; 
	if ($session[user][turns] <= $trai) $trai = $session[user][turns];
	if ($session[user][turns]<=0){
		output("`^Du fällst erschöpft um und landest sehr unsanft auf dem rauhen Boden, bevor du trainieren konntest. Du verlierst einen Teil deiner Lebensenergie.");
		$session[user][hitpoints]=round($session[user][hitpoints]*0.8);
		if ($session[user][hitpoints]<=0) $session[user][hitpoints]=1;
	}else{
		$session[user][turns]-=$trai; 
		$exp = $session[user][level]*e_rand(5,12)+e_rand(0,9); 
		$totalexp = $exp*$trai; 
		$session[user][experience]+=$totalexp; 
		output("`^Du trainierst $trai Runden und bekommst $totalexp Erfahrungspunkte!`n"); 
	}
	castlenav("return", $runden); 
/********Well********/ 
}elseif ($HTTP_GET_VARS[op]=="well"){ 
	$session[user][specialinc]="castle.php"; 
	output("`#Auf einer Seite des Burgplatzes befindet sich ein Wunschbrunnen. Du läufst hin und schaust hinunter. Ein Schild davor behauptet: `^'Wirf einen Edelstein hinein und wünsch dir was...'"); 
	$castleoptions = unserialize($session['user']['specialmisc']);
	if ($session[user][gems] < 1 && (e_rand(1,10) != 7 || $castleoptions['well_uses']>=1)){ 
		output("`n`n`%Da du keinen Edelstein hast, hat sich die Sache für dich erledigt..."); 
	}else{ 
		output("`n`nDu wirfst einen Edelstein hinein und wünschst dir ");
		$session[user][gems]--;
		$castleoptions['well_uses']+=1;
		$rand1 = e_rand(1,6); 
		switch ($rand1){ 
			case 1: 
			output("`^Erfahrung..."); 
			break; 
			case 2: 
			output("`^Mehr Gold..."); 
			break; 
			case 3: 
			output("`^Mehr Lebenskraft..."); 
			break; 
			case 4: 
			output("`^Den Edelstein zurück..."); 
			break; 
			case 5: 
			output("`^Einen längeren Tag..."); 
			break; 
			case 6: 
			output("`^Mehr Charme..."); 
			break; 
		} 
		$rand2 = e_rand(1,4); 
		switch ($rand2){ 
			case 1: 
			case 2: 
			case 3:  
			output("`n`n`%Leider gewähren dir die Götter diesen Wunsch nicht."); 
			break; 
			case 4: 
			output("`n`n`%Die Götter gewähren dir diesen Wunsch!"); 
			switch ($rand1){ 
				case 1: 
				$reward = e_rand($session[user][experience] * 0.05,$session[user][experience] * 0.1); 
				$session[user][experience] += $reward; 
				output("`6$reward`^ Erfahrungspunkte..."); 
				break; 
				case 2: 
				$gold = e_rand($session[user][level]*10,$session[user][level]*100); 
				$session[user][gold] += $gold; 
				output("`6$gold `^mehr Gold..."); 
				break; 
				case 3: 
				$reward = 1; 
				$session[user][maxhitpoints] += $reward; 
				output("`6$reward `^zusätzlichen Lebenspunkt..."); 
				break; 
				case 4: 
				$gems = e_rand(2,4); 
				$session[user][gems] += $gems; 
				output("`6$gems `^Edelsteine..."); 
				break; 
				case 5: 
				$ff = e_rand(1,4); 
				$session[user][turns] += $ff; 
				output("`6$ff `^mehr Waldkämpfe..."); 
				break; 
				case 6: 
				$charm = e_rand(1,5); 
				$session[user][charm] += $charm; 
				output("`6$charm `^mehr Charme..."); 
				break; 
			} 
			break; 
		} 
	$session['user']['specialmisc'] = serialize($castleoptions);
	} 
	castlenav("return", $runden); 
/********Healer********/ 
}elseif ($HTTP_GET_VARS[op]=="medicine"){
	$session['user']['specialinc']="castle.php"; 
	output("`#`b`cKala's Beautyshop`c`b`n");
	$loglev = log($session[user][level]); 
	$cost = ($loglev * ($session[user][maxhitpoints]-$session[user][hitpoints])) + ($loglev*10);
	$cost=$cost*0.9; 
	$cost = round($cost,0); 
	output("`3Die wunderschöne Kala begrüsst dich in ihrem Beautyshop. \"`6Ah.. hallo {$session['user']['name']}.`6 Brauchst du Heilung? Willst du schöner werden? Oder soll sich deine Schönheit endlich bezahlt machen? Dann bist du hier genau richtig!`3\", sagt sie."); 
	output("Du fragst sie, was genau sie damit meint. \"`6Also: Heilung dürfte dir klar sein. Mit einer Gesichtsmaske - vorzugsweise aus Gurken von Violet - kann ich dich attraktiver machen. Und wenn du willst, kannst du mir etwas von deiner Schönheit ... überlassen und dafür etwas Erfahrung gewinnen.`3\"");
	if ($session[user][hitpoints] < $session[user][maxhitpoints]) addnav("Komplette Heilung (`^$cost Gold`0)","forest.php?op=buy1");
	addnav("Gurkenmaske (`#1 Edelstein`0)","forest.php?op=maske");
	if ($session[user][charm]>0) addnav("Charme opfern (`^100 Gold`0)","forest.php?op=copfer");
	castlenav("return", $runden);
}elseif ($HTTP_GET_VARS[op]=="buy1"){ 
	$session['user']['specialinc']="castle.php"; 
	$loglev = log($session[user][level]); 
	$cost = ($loglev * ($session[user][maxhitpoints]-$session[user][hitpoints])) + ($loglev*10);
	$cost=$cost*0.9; 
	$cost = round($cost,0); 
	if ($session[user][gold]>=$cost){ 
		$session[user][gold]-=$cost; 
		//debuglog("spent $cost gold on healing"); 
		$session[user][hitpoints] = $session[user][maxhitpoints]; 
		output("`3Kala gibt dir einen großen, wohlschmeckenden Heiltrank. Du bist angenehm überrascht, da du eigentlich etwas ähnliches wie das Zeug vom Heiler im Wald erwartet hättest. Kala's Trank entfaltet sofort seine Wirkung.`n`n`^Du bist vollständig geheilt."); 
	}else{ 
		output("`3\"`6Also ohne Gold bekommst du hier gar nichts! Verschwinde lieber!`3\" raunzt Kala dich an, als sie merkt, dass du keine $cost Gold dabei hast."); 
	} 
	castlenav("return", $runden); 
}elseif ($HTTP_GET_VARS[op]=="maske"){
	$session['user']['specialinc']="castle.php"; 
	$castleoptions = unserialize($session['user']['specialmisc']);
	if ($session[user][gems]>=1 && $castleoptions['beautyshop_uses']<3){ 
		$session[user][gems]-=1; 
		//debuglog("spent 1 gem for charm in castle"); 
		$session[user][charm]+= 1;
		$castleoptions['beautyshop_uses']+=1; 
		output("`3Du gibst ihr einen Edelstein und Kala packt dich mit einer Kraft, die du ihr nicht zugetraut hättest, auf einen Stuhl und fängt sofort an dein Gesicht mir irgendwelchen mehr oder weniger schleimigen Dingen zu bedecken. Dabei scheint sie hin und wieder ");
		output(" von den Zutaten zu naschen, aber sicher bist du dir nicht, denn deine Augen waren das Erste, was unter Gurkenscheiben verschwunden ist. Du kommst dir ziemlich albern vor, aber nach einiger Zeit, als du das Ergebnis präsentiert bekommst, bist du der Meinung, dass es sich doch gelohnt hat.");
		output("`n`n`^Du erhältst einen Charmepunkt!");
	}else if ($castleoptions['beautyshop_uses']>=3) {
		output("`3\"`6Ja, ich könnte dir noch eine Gurkenmaske machen, aber helfen wird sie dir heute nicht mehr. Ich habe alles getan, was in meiner Macht steht.`3\" Mit diesen Worten geleitet Kala dich hinaus zum Burghof.");
	} else { 
		output("`3\"`6Also ohne Edelstein bekommst du hier gar nichts! Verschwinde lieber!`3\" raunzt Kala dich an, als sie merkt, dass du keinen Edelstein hast."); 
	}
	$session['user']['specialmisc'] = serialize($castleoptions); 
	castlenav("return", $runden); 
}elseif ($HTTP_GET_VARS[op]=="copfer"){
	$session['user']['specialinc']="castle.php"; 
	if ($session[user][gold]>=100){ 
		$session[user][gold]-=100; 
		//debuglog("spent 100 gold on turning charm into experience");
		$amt=e_rand(1,5);
		$exp=20*($session[user][level]+2*$amt);
		$session[user][charm]-=$amt;
		if ($session[user][charm]<0) $session[user][charm]=0;
		$session[user][experience]+=$exp;
		$castleoptions = unserialize($session['user']['specialmisc']); 
		if ($castleoptions['beautyshop_uses']>0) $castleoptions['beautyshop_uses']-=1;
		$session['user']['specialmisc'] = serialize($castleoptions);
		output("`3Kala nimmt dein Gold und reibt dein Gesicht mit einer übel riechenden Pampe ein. Nach einer Weile wäscht sie dir das Zeug mit Wasser ab - und gibt dir das Wasser mit der Pampe zu trinken!");
		output(" Noch etwas benommen von dem furchtbaren Anblick im Spiegel leistest du kaum Widerstand und trinkst.`n`n`^Du VERLIERST $amt Charmepunkte!`nDu bekommst $exp Erfahrungspunkte dafür.");
	}else{ 
		output("`3\"`6Also ohne Gold bekommst du hier gar nichts! Verschwinde lieber!`3\" raunzt Kala dich an, als sie merkt, dass du keine 100 Gold dabei hast."); 
	} 
	castlenav("return", $runden); 
/********Guard Fight********/
} else if ($HTTP_GET_VARS[op]=="guardfight" || $_GET['op'] == "fight" || $_GET['op'] == "run"){ 
	if ($HTTP_GET_VARS[op]=="guardfight"){ 
		$session['user']['specialinc']="castle.php"; 
		$badguy = array("creaturename"=>"Greifenwache","creaturelevel"=>$session[user][level],"creatureweapon"=>"Scharfe Krallen und Schnabel","creatureattack"=>$session[user][attack],"creaturedefense"=>$session[user][defence],"creaturehealth"=>$session[user][maxhitpoints], "diddamage"=>0); 
		$session[user][badguy]=createstring($badguy); 
		$fight=true; 
	}elseif ($_GET['op'] == "fight") { 
		$session['user']['specialinc']="castle.php"; 
		$fight=true; 
	} elseif ($_GET['op'] == "run") { 
		$session['user']['specialinc']="castle.php"; 
		output("`%Dein Stolz verbietet es dir, vor diesem Kampf davonzulaufen!`n"); 
		$fight=true; 
	} 
	if ($fight){ 
		$session['user']['specialinc']="castle.php"; 
		if (count($session[bufflist])>0 && is_array($session[bufflist]) || $HTTP_GET_VARS[skill]!=""){ 
			$HTTP_GET_VARS[skill]=""; 
			if ($HTTP_GET_VARS['skill']=="") $session['user']['buffbackup']=serialize($session['bufflist']); 
			$session[bufflist]=array(); 
			output("`&Dein Stolz verbietet es dir, deine besonderen Fähigkeiten einzusetzen!`0"); 
		} 
		include "battle.php"; 
		if ($victory){ 
			output("`n`#Du hast die Greifenwache besiegt und dir wird der Eintritt zur Burg gewährt!`n"); 
			$session[user][reputation]++;
			output("`n`%Die Wache tritt beiseite und du läufst durch das Tor in die Burg. Die Mitte des Burghofs ist ein großer, mit Gras bewachsener Platz, um den herum viele interessante Stände und Läden sind. Einige davon klingen wirklich verlockend!`n"); 
			castlenav("main", $runden); 
		}elseif ($defeat){ 
			output("`n`^Kurz vor dem endgültigen Todesstoß fliegt die Greifenwache zurück auf ihren Platz und bewacht wieder das Tor. Du hast nur noch 1 Lebenspunkt und verlierst 3 Waldkämpfe, aber du hast Glück, noch am Leben zu sein !"); 
			$session[user][hitpoints]=1; 
			$session[user][turns]-=2; 
			$session['user']['specialinc']=""; 
		}else{ 
			fightnav(false,true); 
		} 
	} 
} else {
	if (e_rand(1,100) <95){ 
		$session[user][specialinc]="castle.php";
		output("`#Du folgst einem unbefestigten Pfad und siehst dabei in der Ferne gelegentlich eine große Burg... Könnte `bdas`b die legendäre `^Orkburg`# sein?`n"); 
		output("`%Du kommst näher und bist dir plötzlich gar nicht mehr so sicher, ob du dich der Burg wirklich weiter nähern, oder lieber umkehren solltest.`n`n"); 
		output("`#Aber du gibst dir einen Ruck, lässt deine Ängste hinter dir und läufst weiter auf die Burg zu. Als du näher kommst, bemerkst du, daß ein Greif vor dem Tor Wache hält. Du kommst dort an und die mystische Kreatur spricht dich an. `%'Willkommen in der Orkburg! Wenn du hier rein willst, musst du deine Tapferkeit entweder schon beim Kampf mit dem `@Grünen Drachen`% bewiesen haben, oder du musst mich in einem fairen Kampf besiegen!'"); 
		if ($session['user']['dragonkills']>0){
			output("`n`n`^Da du den Drachen bereits mindestens 1x gekillt hast, darfst du passieren."); 
			output("`n`n<a href='forest.php?op=enter'>Die Burg betreten</a>`n<a href='forest.php?op=leave'>Umkehren</a>",true); 
			addnav("Die Burg betreten","forest.php?op=enter"); 
			addnav("","forest.php?op=enter"); 
		}else{ 
			output("`n`n<a href='forest.php?op=guardfight'>Bekämpfe die Wache</a>`n<a href='forest.php?op=leave'>Kehre um</a>",true); 
			addnav("Wache bekämpfen","forest.php?op=guardfight"); 
			addnav("","forest.php?op=guardfight"); 
		} 
		addnav("","forest.php?op=leave"); 
		addnav("Umkehren","forest.php?op=leave"); 
	}else{ 
		$session[user][specialinc]="";
		$session['user']['specialmisc'] = 0;
		output("`#Du folgst einem unbefestigten Pfad und verirrst dich total!"); 
		output("`n`n`^Beim Versuch, einen Weg zurück zu finden, verlierst du 2 Waldkämpfe!`n`n"); 
		if ($session[user][turns]>0) $session[user][turns]-=2; 
		forest();
	}
}
?>
