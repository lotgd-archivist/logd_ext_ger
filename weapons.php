<?php

// 21072004

// modifications by anpera:
// stealing enabled with 1:15 success (thieves have 2:12 chance) and 'pay from bank'

require_once "common.php";
checkday();

page_header("MightyE's Waffenladen");
output("`c`b`&MightyE's Waffen`0`b`c");
$tradeinvalue = round(($session[user][weaponvalue]*.75),0);
if ($HTTP_GET_VARS[op]==""){
  output("`!MightyE `7steht hinter einem Ladentisch und scheint dir nur wenig Interesse entgegen zu bringen, als du eintrittst. ");
	output("Aus Erfahrung weißt du aber, dass er jede deiner Bewegungen misstrauisch beobachtet. Er mag ein bescheidener ");
	output("Waffenhändler sein, aber er trägt immer noch die Grazie eines Mannes in sich, der seine Waffen gebraucht hat, ");
	output("um stärkere ".($session[user][gender]?"Frauen":"Männer")." als dich zu töten.`n`n");
	output("Der massive Griff eines Claymore ragt hinter seiner Schulter hervor, dessen Schimmer im Licht der Fackeln ");
	output("viel heller wirkt, als seine Glatze, die er mehr zum strategischen Vorteil rasiert hält, ");
	output("obwohl auch die Natur bereits auf einem bestimmten Level der Kahlköpfigkeit besteht. ");
	output("`n`n`!MightyE`7  nickt dir schließlich zu und wünscht sich, während er seinen Spitzbart streichelt, ");
	output("eine Gelegenheit, um eine seiner Waffen benutzen zu können.`n`n");
  addnav("Waffen anschauen","weapons.php?op=peruse");
	addnav("Inventar anzeigen","prefs.php?op=inventory&back=weapons.php");
	addnav("Zurück ins Dorf","village.php");
}else if ($HTTP_GET_VARS[op]=="peruse"){
	$sql = "SELECT max(level) AS level FROM weapons WHERE level<=".(int)$session[user][dragonkills];
	$result = db_query($sql) or die(db_error(LINK));
	$row = db_fetch_assoc($result);
	
  $sql = "SELECT * FROM weapons WHERE level = ".(int)$row[level]." ORDER BY damage ASC";
	$result = db_query($sql) or die(db_error(LINK));
	output("`7Du schlenderst durch den Laden und tust dein Bestes, so auszusehen, als ob du wüßtest, was die meisten dieser Objekte machen. ");
	output("`!MightyE`7 schaut dich an und sagt \"`#Ich gebe dir `^$tradeinvalue`# ");
	output(" Gold für `5".$session[user][weapon]."`#. Klicke einfach auf die Waffe, die du kaufen willst... was auch immer 'klick' bedeuten mag`7.\". ");
	output("Dabei schaut er völlig verwirrt. Er steht ein paar Sekunden nur da, schnippt mit den Fingern und fragt sich, ob das ");
	output("mit 'klicken' gemeint sein könnte, bevor er sich wieder seiner Arbeit zuwendet: Herumstehen und gut aussehen.");
	if($session[user][reputation]<=-10) output("`nEr sieht dich misstrauisch an, als ob er wüsste, dass du hier hin und wieder versuchst, ihm seine schönen Waffen zu klauen.");
	output("<table border='0' cellpadding='0'>",true);
	output("<tr class='trhead'><td>`bName`b</td><td align='center'>`bSchaden`b</td><td align='right'>`bPreis`b</td></tr>",true);
	for ($i=0;$i<db_num_rows($result);$i++){
	  	$row = db_fetch_assoc($result);
		$bgcolor=($i%2==1?"trlight":"trdark");
		if ($row[value]<=($session[user][gold]+$tradeinvalue)){
			output("<tr class='$bgcolor'><td>Kaufe <a href='weapons.php?op=buy&id=$row[weaponid]'>$row[weaponname]</a></td><td align='center'>$row[damage]</td><td align='right'>$row[value]</td></tr>",true);
			addnav("","weapons.php?op=buy&id=$row[weaponid]");
		}else{
//			output("<tr class='$bgcolor'><td>$row[weaponname]</td><td align='center'>$row[damage]</td><td align='right'>$row[value]</td></tr>",true);
//			addnav("","weapons.php?op=buy&id=$row[weaponid]");
			output("<tr class='$bgcolor'><td>- - - - <a href='weapons.php?op=buy&id=$row[weaponid]'>$row[weaponname]</a></td><td align='center'>$row[damage]</td><td align='right'>$row[value]</td></tr>",true);
			addnav("","weapons.php?op=buy&id=$row[weaponid]");
		}
	}
	output("</table>",true);
	addnav("Zurück ins Dorf","village.php");
}else if ($HTTP_GET_VARS[op]=="buy"){
  	$sql = "SELECT * FROM weapons WHERE weaponid='$HTTP_GET_VARS[id]'";
	$result = db_query($sql) or die(db_error(LINK));
	if (db_num_rows($result)==0){
	  	output("`!MightyE`7 schaut dich eine Sekunde lang verwirrt an und kommt zu dem Schluss, dass du ein paar Schläge zuviel auf den Kopf bekommen hast. Schließlich nickt er und grinst.");
		addnav("Nochmal versuchen?","weapons.php");
		addnav("Zurück zum Dorf","village.php");
	}else{
	 	 $row = db_fetch_assoc($result);
		if ($row[value]>($session[user][gold]+$tradeinvalue)){
			if ($session[user][thievery]>=2) {
				$klau=e_rand(1,15);
			} else {
				$klau=e_rand(2,18);
			}
			$session[user][reputation]-=10;
			if ($session[user][reputation]<=-10){
				if ($session[user][reputation]<=-20) $klau=10;
				if ($klau==1){ // Fall nur für Diebe
					output("`5Mit den Fertigkeiten eines erfahrenen Diebes tauschst du `%$row[weaponname]`5 gegen `%".$session[user][weapon]."`5 aus und verlässt fröhlich pfeifend den Laden. ");
					output(" `bGlück gehabt!`b  `!MightyE`5 war gerade durch irgendwas am Fenster abgelenkt. Aber nochmal passiert ihm das nicht! Stolz auf deine ");
					output("fette Beute stolzierst du über den Dorfplatz - bis dir jemand mitteilt, dass dir da noch ein Preisschild herumbaumelt...`nDu verlierst einen Charmepunkt!");
					$session[user][weapon] = $row[weaponname];
					$session[user][attack]-=$session[user][weapondmg];
					$session[user][weapondmg] = $row[damage];
					$session[user][attack]+=$session[user][weapondmg];
					$session[user][weaponvalue] = $row[value];
					if ($session[user][charm]) $session[user][charm]-=1;
					addnav("Zurück zum Dorf","village.php");
				} else if ($klau==2 || $klau==3) { // Diebstahl gelingt perfekt
					output("`5Da dir das nötige Kleingold fehlt, grapschst du dir `%$row[weaponname]`5 und tauschst `%".$session[user][weapon]."`5 unauffällig dagegen aus. ");
					output(" `bGlück gehabt!`b `!MightyE`5 war gerade durch irgendwas am Fenster abgelenkt. Aber nochmal wird ihm das nicht passieren! Stolz auf deine ");
					output("fette Beute stolzierst du über den Dorfplatz - bis dir jemand mitteilt, dass dir da noch ein Preisschild herumbaumelt...`nDu verlierst einen Charmepunkt!");
					$session[user][weapon] = $row[weaponname];
					$session[user][attack]-=$session[user][weapondmg];
					$session[user][weapondmg] = $row[damage];
					$session[user][attack]+=$session[user][weapondmg];
					$session[user][weaponvalue] = $row[value];
					if ($session[user][charm]) $session[user][charm]-=1;
					addnav("Zurück zum Dorf","village.php");
				} else if ($klau==4 || $klau==5) { // Diebstahl gelingt, aber nachher erwischt
					output("`5Du grapschst dir `%$row[weaponname]`5 und tauschst `%".$session[user][weapon]."`5 unauffällig dagegen aus. ");
					output(" So schnell und unauffällig wie du kannst verlässt du den Laden. Geschafft! Als du mit deiner Beute über den Dorfplatz stolzierst, siehst du aus dem ");
					output("Augenwinkel `#MightyE`5 auf dich zurauschen. Er packt dich mit einer Hand an ".$session[user][armor]." und zerrt dich mit zur Stadtbank...`n`n");
					output("`#MightyE`5 zwingt dich mit seinen Händen eng um deinen Hals geschlungen dazu, die `^".($row['value']-$tradeinvalue)."`5 Gold, die du ihm schuldest, von der Bank zu zahlen!");
					if ($session[user][goldinbank]<0){
						output("Da du jedoch schon Schulden bei der Bank hast, bekommt er von dort nicht was er verlangt.`n");
						output("Er entreißt dir $row[weaponname] gewaltsam, ");
						output(" drückt dir dein(e/n) alte(n/s) ".$session[user][weapon]." in die Hand und schlägt dich nieder. Er raunzt noch etwas, dass du Glück hast, so arm zu sein, sonst hätte er dich umgebracht und dass er dich beim nächsten Diebstahl");
						output(" ganz sicher umbringen wird, bevor er in seinen Laden zurück geht, wo bereits ein Kunde wartet.`n");
						$session[user][hitpoints]=round($session[user][hitpoints]/2);
					}else{
						$session[user][goldinbank]-=($row[value]-$tradeinvalue);
						if ($session[user][goldinbank]<0) output("`nDu hast dadurch jetzt `^".abs($session[user][goldinbank])." Gold`5 Schulden bei der Bank!!");
						output("`nDas nächste Mal bringt er dich um. Da bist du ganz sicher.");
						//debuglog("lost " . ($row['value']-$tradeinvalue) . " gold in bank for stealing the " . $row['weaponname'] . " weapon");
						$session[user][weapon] = $row[weaponname];
						$session[user][attack]-=$session[user][weapondmg];
						$session[user][weapondmg] = $row[damage];
						$session[user][attack]+=$session[user][weapondmg];
						$session[user][weaponvalue] = $row[value];
					}
					addnav("Zurück zum Dorf","village.php");
				} else { // Diebstahl gelingt nicht
			  		output("Während du wartest, bis `!MightyE`7 in eine andere Richtung schaut, näherst du dich vorsichtig dem `5$row[weaponname]`7 und nimmst es leise vom Regal. ");
					output("Deiner fetten Beute gewiss drehst du dich leise, vorsichtig, wie ein Ninja, zur Tür, nur um zu entdecken, ");
					output("dass `!MightyE`7 drohend in der Tür steht und dir den Weg abschneidet. Du versuchst einen Flugtritt. Mitten im Flug hörst du das \"SCHING\" eines Schwerts, ");
					output("das seine Scheide verlässt.... dein Fuß ist weg. Du landest auf dem Beinstumpf und `!MightyE`7 steht immer noch im Torbogen, das Schwert ohne Gebrauchsspuren wieder im  Halfter und mit ");
					output("vor der stämmigen Brust bedrohlich verschränkten Armen. \"`#Vielleicht willst du dafür bezahlen?`7\" ist alles, was er sagt, ");
					output("während du vor seinen Füßen zusammen brichst und deinen Lebenssaft unter deinem dir verbliebenen Fuß über den Boden ausschüttest.`n");
					$session[user][alive]=false;
					//debuglog("lost " . $session['user']['gold'] . " gold on hand due to stealing from Pegasus");
					$session[user][gold]=0;
					$session[user][hitpoints]=0;
					$session[user][experience]=round($session[user][experience]*.9,0);
					$session[user][gravefights]=round($session[user][gravefights]*0.75);
					output("`b`&Du wurdest von `!MightyE`& umgebracht!!!`n");
					output("`4Das Gold, das du dabei hattest, hast du verloren!`n");
					output("`4Du hast 10% deiner Erfahrung verloren!`n");
					output("Du kannst morgen wieder kämpfen.`n");
					output("`nWegen der Unehrenhaftigkeit deines Todes landest du im Fegefeuer und wirst das Reich der Schatten aus eigener Kraft heute nicht mehr verlassen können!");
					addnav("Tägliche News","news.php");
					addnews("`%".$session[user][name]."`5 wurde beim Versuch, in `!MightyE`5's Waffenladen zu stehlen, niedergemetzelt.");
				}
				if ($session[user][reputation]<=-10) $session[user][reputation]-=10;
			}else{
				$session[user][reputation]-=10;
				if ($klau==1){ // Fall nur für Diebe
					output("`5Mit den Fertigkeiten eines erfahrenen Diebes tauschst du `%$row[weaponname]`5 gegen `%".$session[user][weapon]."`5 aus und verlässt fröhlich pfeifend den Laden. ");
					output(" `bGlück gehabt!`b  `!MightyE`5 war gerade durch irgendwas am Fenster abgelenkt. Aber irgendwann wird er den Diebstahl bemerken und in Zukunft wesentlich besser aufpassen! Stolz auf deine ");
					output("fette Beute stolzierst du über den Dorfplatz - bis dir jemand mitteilt, dass dir da noch ein Preisschild herumbaumelt...`nDu verlierst einen Charmepunkt!");
					$session[user][weapon] = $row[weaponname];
					$session[user][attack]-=$session[user][weapondmg];
					$session[user][weapondmg] = $row[damage];
					$session[user][attack]+=$session[user][weapondmg];
					$session[user][weaponvalue] = $row[value];
					if ($session[user][charm]) $session[user][charm]-=1;
					addnav("Zurück zum Dorf","village.php");
				} else if ($klau==2 || $klau==3) { // Diebstahl gelingt perfekt
					output("`5Da dir das nötige Kleingold fehlt, grapschst du dir `%$row[weaponname]`5 und tauschst `%".$session[user][weapon]."`5 unauffällig dagegen aus. ");
					output(" `bGlück gehabt!`b `!MightyE`5 war gerade durch irgendwas am Fenster abgelenkt. Aber irgendwann wird er den Diebstahl bemerken und in Zukunft besser aufpassen. Stolz auf deine ");
					output("fette Beute stolzierst du über den Dorfplatz - bis dir jemand mitteilt, dass dir da noch ein Preisschild herumbaumelt...`nDu verlierst einen Charmepunkt!");
					$session[user][weapon] = $row[weaponname];
					$session[user][attack]-=$session[user][weapondmg];
					$session[user][weapondmg] = $row[damage];
					$session[user][attack]+=$session[user][weapondmg];
					$session[user][weaponvalue] = $row[value];
					if ($session[user][charm]) $session[user][charm]-=1;
					addnav("Zurück zum Dorf","village.php");
				} else if ($klau==4 || $klau==5) { // Diebstahl gelingt, aber nachher erwischt
					output("`5Du grapschst dir `%$row[weaponname]`5 und tauschst `%".$session[user][weapon]."`5 unauffällig dagegen aus. ");
					output(" So schnell und unauffällig wie du kannst verlässt du den Laden. Geschafft! Als du mit deiner Beute über den Dorfplatz stolzierst, siehst du aus dem ");
					output("Augenwinkel `#MightyE`5 auf dich zurauschen. Er packt dich mit einer Hand an ".$session[user][armor]." und zerrt dich mit zur Stadtbank...`n`n");
					output("`#MightyE`5 zwingt dich mit seinen Händen eng um deinen Hals geschlungen dazu, die `^".($row['value']-$tradeinvalue)."`5 Gold, die du ihm schuldest, von der Bank zu zahlen!");
					if ($session[user][goldinbank]<0){
						output("Da du jedoch schon Schulden bei der Bank hast, bekommt er von dort nicht was er verlangt.`n");
						output("Er entreißt dir $row[weaponname] gewaltsam, ");
						output(" drückt dir dein(e/n) alte(n/s) ".$session[user][weapon]." in die Hand und schlägt dich nieder. Er raunzt noch etwas, dass du Glück hast, so arm zu sein, sonst hätte er dich umgebracht und dass er dich beim nächsten Diebstahl");
						output(" ganz sicher umbringen wird, bevor er in seinen Laden zurück geht, wo bereits ein Kunde wartet.`n");
						$session[user][hitpoints]=round($session[user][hitpoints]/2);
					}else{
						$session[user][goldinbank]-=($row[value]-$tradeinvalue);
						if ($session[user][goldinbank]<0) output("`nDu hast dadurch jetzt `^".abs($session[user][goldinbank])." Gold`5 Schulden bei der Bank!!");
						//debuglog("lost " . ($row['value']-$tradeinvalue) . " gold in bank for stealing the " . $row['weaponname'] . " weapon");
						output("`nDas nächste Mal bringt er dich wahrscheinlich um.");
						$session[user][weapon] = $row[weaponname];
						$session[user][attack]-=$session[user][weapondmg];
						$session[user][weapondmg] = $row[damage];
						$session[user][attack]+=$session[user][weapondmg];
						$session[user][weaponvalue] = $row[value];
					}
					addnav("Zurück zum Dorf","village.php");
				} else { // Diebstahl gelingt nicht
					output("`5Du grapschst dir `%$row[weaponname]`5 und tauschst `%".$session[user][weapon]."`5 unauffällig dagegen aus. ");
					output(" So schnell und unauffällig wie du kannst verlässt du den Laden. Geschafft! Als du mit deiner Beute über den Dorfplatz stolzierst, siehst du aus dem ");
					output("Augenwinkel `#MightyE`5 auf dich zurauschen. Er packt dich mit einer Hand an ".$session[user][armor].".`n`n");
					output("Er entreißt dir $row[weaponname] gewaltsam, ");
					output(" drückt dir dein(e/n) alte(n/s) ".$session[user][weapon]." in die Hand und schlägt dich nieder. Er raunzt noch etwas, dass er dich beim nächsten Diebstahl");
					output(" ganz sicher umbringen wird, bevor er in seinen Laden zurück geht, wo bereits ein Kunde wartet.`n");
					$session[user][hitpoints]=1;
					if ($session[user][turns]>0){
						output("`n`4Du verlierst einen Waldkampf und fast alle Lebenspunkte.");
						$session[user][turns]-=1;
					}else{
						output("`n`4MightyE hat dich so schlimm erwischt, dass eine Narbe bleiben wird.`nDu verlierst 3 Charmepunkte und fast alle Lebenspunkte.");
						$session[user][charm]-=3;
						if ($session[user][charm]<0) $session[user][charm]=0;
					}
					addnav("Zurück zum Dorf","village.php");
				}
			}
		}else{
			output("`!MightyE`7 nimmt dein `5".$session[user][weapon]."`7 stellt es aus und hängt sofort ein neues Preisschild dran. ");
			//debuglog("spent " . ($row['value']-$tradeinvalue) . " gold on the " . $row['weaponname'] . " weapon");
			 $session[user][gold]-=$row[value];
			$session[user][weapon] = $row[weaponname];
			$session[user][gold]+=$tradeinvalue;
			$session[user][attack]-=$session[user][weapondmg];
			$session[user][weapondmg] = $row[damage];
			$session[user][attack]+=$session[user][weapondmg];
			$session[user][weaponvalue] = $row[value];
			output("`n`nIm Gegenzug händigt er dir ein glänzendes, neues `5$row[weaponname]`7 aus, das du probeweise im Raum schwingst. Dabei schlägst du `!MightyE`7 fast den Kopf ab. ");
			output("Er duckt sich so, als ob du nicht der erste bist, der seine neue Waffe sofort ausprobieren will...");
			addnav("Zurück zum Dorf","village.php");
		}
	}
}

page_footer();
?>
