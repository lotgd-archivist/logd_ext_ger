<?php

// 21072004

// modifications by anpera:
// stealing enabled with 1:15 success (thieves have 2:12 chance)

require_once "common.php";
checkday();

page_header("Pegasus R�stungen");
output("`c`b`%Pegasus R�stungen`0`b`c");
$tradeinvalue = round(($session[user][armorvalue]*.75),0);
if ($HTTP_GET_VARS[op]==""){
	output("`5Die gerechte und h�bsche `#Pegasus`5 begr��t dich mit einem herzlichen L�cheln, als du ihren bunten Zigeunerwagen ");
	output("betrittst, der nicht ganz zuf�llig direkt neben `!MightyE`5's Waffenladen steht. Ihr Erscheinungsbild ");
	output("ist genauso grell und farbenfroh, wie ihr Wagen und lenkt dich fast (aber nicht ganz) von ihren ");
	output("gro�en grauen Augen und der zwischen ihren nicht ganz ausreichenden Zigeunerklamotten hindurchleuchtenden Haut ab.");
	output("`n`n");
	addnav("Pegasus' Waren durchst�bern","armor.php?op=browse");
	addnav("Inventar anzeigen","prefs.php?op=inventory&back=armor.php");
	addnav("Zur�ck zum Dorf","village.php");
}else if ($HTTP_GET_VARS[op]=="browse"){
	$sql = "SELECT max(level) AS level FROM armor WHERE level<=".$session[user][dragonkills];
	$result = db_query($sql) or die(db_error(LINK));
	$row = db_fetch_assoc($result);

  	$sql = "SELECT * FROM armor WHERE level=$row[level] ORDER BY value";
	$result = db_query($sql) or die(db_error(LINK));
	output("`5Du blickst �ber die verschiedenen Kleidungsst�cke und fragst dich, ob `#Pegasus`5 einige davon f�r dich ");
	output("anprobieren w�rde. Aber dann bemerkst du, dass sie damit besch�ftigt ist, `!MightyE`5 vertr�umt durch das Fenster seines Ladens dabei zu beobachten, ");
	output("wie er gerade mit nacktem Oberk�rper einem Kunden den Gebrauch einer seiner Waren demonstriert. Als sie kurz wahrnimmt, dass du ");
	output("ihre Waren durchst�berst, blickt sie auf dein(e/n) ".$session[user][armor]." und bietet dir daf�r im Tausch `^$tradeinvalue`5 Gold an.");
	if($session['user']['reputation']<=-10) output("`nSie sieht dich misstrauisch an, als ob sie w�sste, dass du hier hin und wieder versuchst, ihr ihre sch�nen R�stungen zu klauen.");
	output("<table border='0' cellpadding='0'>",true);
	output("<tr class='trhead'><td>`bName`b</td><td align='center'>`bVerteidigung`b</td><td align='right'>`bPreis`b</td></tr>",true);
	for ($i=0;$i<db_num_rows($result);$i++){
	  	$row = db_fetch_assoc($result);
		$bgcolor=($i%2==1?"trlight":"trdark");
		if ($row[value]<=($session[user][gold]+$tradeinvalue)){
			output("<tr class='$bgcolor'><td>Kaufe <a href='armor.php?op=buy&id=$row[armorid]'>$row[armorname]</a></td><td align='center'>$row[defense]</td><td align='right'>$row[value]</td></tr>",true);
			addnav("","armor.php?op=buy&id=$row[armorid]");
		}else{
//			output("<tr class='$bgcolor'><td>$row[armorname]</td><td align='center'>$row[defense]</td><td align='right'>$row[value]</td></tr>",true);
//			addnav("","armor.php?op=buy&id=$row[armorid]");
			output("<tr class='$bgcolor'><td>- - - - <a href='armor.php?op=buy&id=$row[armorid]'>$row[armorname]</a></td><td align='center'>$row[defense]</td><td align='right'>$row[value]</td></tr>",true);
			addnav("","armor.php?op=buy&id=$row[armorid]");

		}
	}
	output("</table>",true);
	addnav("Zur�ck zum Dorf","village.php");
}else if ($HTTP_GET_VARS[op]=="buy"){
  	$sql = "SELECT * FROM armor WHERE armorid='$HTTP_GET_VARS[id]'";
	$result = db_query($sql) or die(db_error(LINK));
	if (db_num_rows($result)==0){
	  	output("`#Pegasus`5 schaut dich ein paar Sekunden verwirrt an, entschlie�t sich dann aber zu glauben, dass du wohl ein paar Schl�ge zu viel auf den Kopf bekommen hast und nickt l�chelnd.");
		addnav("Nochmal?","armor.php");
		addnav("Zur�ck zum Dorf","village.php");
	}else{
	  	$row = db_fetch_assoc($result);
		if ($row[value]>($session[user][gold]+$tradeinvalue)){
			if ($session[user][thievery]>=2) {
				$klau=e_rand(1,15);
			} else {
				$klau=e_rand(2,18);
			}
			if ($session['user']['reputation']<=-10){
				if ($session['user']['reputation']<=-20) $klau=10;
				$session['user']['reputation']-=10;
				if ($klau==1){ // Fall nur f�r Diebe
					output("`5Mit den Fertigkeiten eines erfahrenen Diebes tauschst du `%$row[armorname]`5 gegen `%".$session[user][armor]."`5 aus und verl�sst fr�hlich pfeifend den Laden. ");
					output(" `bGl�ck gehabt!`b `#Pegasus`5 starrt immer noch vertr�umt zu MightyE r�ber und hat nichts bemerkt. Aber nochmal wird ihr das nicht passieren! Stolz auf deine ");
					output("fette Beute stolzierst du �ber den Dorfplatz - bis dir jemand mitteilt, da� dir da noch ein Preisschild herumbaumelt...`nDu verlierst einen Charmepunkt!");
				 	$session[user][armor] = $row[armorname];
					if ($session[user][charm]) $session[user][charm]-=1;
					$session[user][defence]-=$session[user][armordef];
					$session[user][armordef] = $row[defense];
					$session[user][defence]+=$session[user][armordef];
					$session[user][armorvalue] = $row[value];
					addnav("Zur�ck zum Dorf","village.php");
				} else if ($klau==2 || $klau==3) { // Diebstahl gelingt perfekt
					output("`5Du grapschst dir `%$row[armorname]`5 und tauschst `%".$session[user][armor]."`5 unauff�llig dagegen aus. ");
					output(" `bGl�ck gehabt!`b `#Pegasus`5 starrt immer noch vertr�umt zu MightyE r�ber und hat nichts bemerkt. Aber nochmal wird ihr das nicht passieren! Stolz auf deine ");
					output("fette Beute stolzierst du �ber den Dorfplatz - bis dir jemand mitteilt, da� dir da noch ein Preisschild herumbaumelt...`nDu verlierst einen Charmepunkt!");
			 		$session[user][armor] = $row[armorname];
					if ($session[user][charm]) $session[user][charm]-=1;
					$session[user][defence]-=$session[user][armordef];
					$session[user][armordef] = $row[defense];
					$session[user][defence]+=$session[user][armordef];
					$session[user][armorvalue] = $row[value];
					addnav("Zur�ck zum Dorf","village.php");
				} else if ($klau==4 || $klau==5) { // Diebstahl gelingt, aber nachher erwischt
					output("`5Du grapschst dir `%$row[armorname]`5 und tauschst `%".$session[user][armor]."`5 unauff�llig dagegen aus. ");
					output(" So schnell und unauff�llig wie du kannst verl�sst du den Laden. Geschafft! Als du mit deiner Beute �ber den Dorfplatz stolzierst, siehst du aus dem ");
					output("Augenwinkel `#Pegasus`5 knapp an dir vorbei Richtung Stadtbank laufen. Im Vorbeigehen rei�t sie das Preisschild ab, das noch immer von deiner neuen R�stung baumelt...`n`n");
					if ($session[user][goldinbank]<0){
						output("Da du jedoch schon Schulden bei der Bank hast, bekam `#Pegasus`5 von dort nicht, was sie verlangte.`n");
						output("Als du dein(e/n) `%$row[armorname]`5 stolz auf dem Dorfplatz pr�sentierst, packt dich von hinten `!MightyE`5's starke Hand. Er entrei�t dir $row[armorname] gewaltsam, ");
						output(" dr�ckt dir dein(e/n) alte(n/s) ".$session[user][armor]." in die Hand und schl�gt dich nieder. Er raunzt noch etwas, da� du Gl�ck hast, so arm zu sein, sonst h�tte er er dich umgebracht und da� er dich beim n�chsten Diebstahl");
						output(" ganz sicher umbringen wird, bevor er in seinen Laden zur�ck geht, wo bereits ein Kunde wartet.`n");
						output(" `#Pegasus`5 wird dir sowas nicht nochmal durchgehen lassen!");
						$session[user][hitpoints]=round($session[user][hitpoints]/2);
					}else{
						output("`#Pegasus`5 hat sich die ".($row['value']-$tradeinvalue)." Gold, die du ihr schuldest, von der Bank geholt!");
						output(" Sie wird dir sowas nicht nochmal durchgehen lassen.");
						$session[user][goldinbank]-=($row[value]-$tradeinvalue);
						if ($session[user][goldinbank]<0) output("`nDu hast dadurch jetzt `^".abs($session[user][goldinbank])." Gold`5 Schulden bei der Bank!!");
						//debuglog("lost " . ($row['value']-$tradeinvalue) . " gold in bank for stealing the " . $row['armorname'] . " armor");
			 			$session[user][armor] = $row[armorname];
						$session[user][defence]-=$session[user][armordef];
						$session[user][armordef] = $row[defense];
						$session[user][defence]+=$session[user][armordef];
						$session[user][armorvalue] = $row[value];
					}
					addnav("Zur�ck zum Dorf","village.php");
				} else { // Diebstahl gelingt nicht
			  		output("`5Du wartest, bis `#Pegasus`5 wieder abgelenkt ist. Dann n�herst du dich vorsichtig `%$row[armorname]`5 und l�sst die R�stung leise vom Stapel verschwinden, auf dem sie lag. ");
					output("Deiner Beute sicher drehst du dich um ... nur um festzustellen, dass du dich nicht ganz umdrehen kannst, weil sich zwei H�nde fest um deinen ");
					output("Hals schliessen. Du schaust runter, verfolgst die H�nde bis zu einem Arm, an dem sie befestigt sind, der wiederum an einem �u�erst muskul�sen `!MightyE`5 befestigt ist. Du versuchst ");
					output("zu erkl�ren, was hier passiert ist, aber dein Hals scheint nicht in der Lage zu sein, deine Stimme oder gar den so dringend ben�tigten Sauerstoff hindurch zu lassen.  ");
					output("`n`nAls langsam Dunkelheit in deine Wahrnehmung schl�pft, schaust du flehend zu `%Pegasus`5, doch die starrt nur v�llig vertr�umt und mit den H�nden seitlich auf dem l�chelnden Gesicht ");
					output("auf `!MightyE`5.`n`n");
					$session[user][alive]=false;
					//debuglog("lost " . $session['user']['gold'] . " gold on hand due to stealing from Pegasus");
					$session[user][gold]=0;
					$session[user][hitpoints]=0;
					$session[user][experience]=round($session[user][experience]*.9,0);
					$session[user][gravefights]=round($session[user][gravefights]*.75);
					output("`b`&Du wurdest von `!MightyE`& umgebracht!!!`n");
					output("`4Das Gold, das du dabei hattest, hast du verloren!`n");
					output("`4Du hast 10% deiner Erfahrung verloren!`n");
					output("Du kannst morgen wieder k�mpfen.`n");
					output("`nWegen der Unehrenhaftigkeit deines Todes landest du im Fegefeuer und wirst das Reich der Schatten aus eigener Kraft heute nicht mehr verlassen k�nnen!");
					addnav("T�gliche News","news.php");
					addnews("`%".$session[user][name]."`5 wurde von `!MightyE`5 f�r den Versuch, bei `#Pegasus`5 zu stehlen, erw�rgt.");
				}
			}else{
				$session['user']['reputation']-=10;
				if ($klau==1){ // Fall nur f�r Diebe
					output("`5Mit den Fertigkeiten eines erfahrenen Diebes tauschst du `%$row[armorname]`5 gegen `%".$session[user][armor]."`5 aus und verl�sst fr�hlich pfeifend den Laden. ");
					output(" `bGl�ck gehabt!`b `#Pegasus`5 starrt immer noch vertr�umt zu MightyE r�ber und hat nichts bemerkt. Trotzdem wird sie den Diebstahl fr�her oder sp�ter bemerken und in Zukunft besser aufpassen! Stolz auf deine ");
					output("fette Beute stolzierst du �ber den Dorfplatz - bis dir jemand mitteilt, da� dir da noch ein Preisschild herumbaumelt...`nDu verlierst einen Charmepunkt!");
				 	$session[user][armor] = $row[armorname];
					if ($session[user][charm]) $session[user][charm]-=1;
					$session[user][defence]-=$session[user][armordef];
					$session[user][armordef] = $row[defense];
					$session[user][defence]+=$session[user][armordef];
					$session[user][armorvalue] = $row[value];
					addnav("Zur�ck zum Dorf","village.php");
				} else if ($klau==2 || $klau==3) { // Diebstahl gelingt perfekt
					output("`5Du grapschst dir `%$row[armorname]`5 und tauschst `%".$session[user][armor]."`5 unauff�llig dagegen aus. ");
					output(" `bGl�ck gehabt!`b `#Pegasus`5 starrt immer noch vertr�umt zu MightyE r�ber und hat nichts bemerkt. Trotzdem wird sie den Diebstahl fr�her oder sp�ter bemerken und in Zukunft besser aufpassen! Stolz auf deine ");
					output("fette Beute stolzierst du �ber den Dorfplatz - bis dir jemand mitteilt, da� dir da noch ein Preisschild herumbaumelt...`nDu verlierst einen Charmepunkt!");
			 		$session[user][armor] = $row[armorname];
					if ($session[user][charm]) $session[user][charm]-=1;
					$session[user][defence]-=$session[user][armordef];
					$session[user][armordef] = $row[defense];
					$session[user][defence]+=$session[user][armordef];
					$session[user][armorvalue] = $row[value];
					addnav("Zur�ck zum Dorf","village.php");
				} else if ($klau==4 || $klau==5) { // Diebstahl gelingt, aber nachher erwischt
					output("`5Du grapschst dir `%$row[armorname]`5 und tauschst `%".$session[user][armor]."`5 unauff�llig dagegen aus. ");
					output(" So schnell und unauff�llig wie du kannst verl�sst du den Laden. Geschafft! Als du mit deiner Beute �ber den Dorfplatz stolzierst, siehst du aus dem ");
					output("Augenwinkel `#Pegasus`5 knapp an dir vorbei Richtung Stadtbank laufen. Im Vorbeigehen rei�t sie das Preisschild ab, das noch immer von deiner neuen R�stung baumelt...`n`n");
					if ($session[user][goldinbank]<0){
						output("Da du jedoch schon Schulden bei der Bank hast, bekam `#Pegasus`5 von dort nicht was sie verlangte.`n");
						output("Als du dein(e/n) `%$row[armorname]`5 stolz auf dem Dorfplatz pr�sentierst, packt dich von hinten `!MightyE`5's starke Hand. Er entrei�t dir $row[armorname] gewaltsam, ");
						output(" dr�ckt dir dein(e/n) alte(n/s) ".$session[user][armor]." in die Hand und schl�gt dich nieder. Er raunzt noch etwas, da� du Gl�ck hast, so arm zu sein, sonst h�tte er er dich umgebracht und da� er dich beim n�chsten Diebstahl");
						output(" ganz sicher umbringen wird, bevor er in seinen Laden zur�ck geht, wo bereits ein Kunde wartet.`n");
						output(" `#Pegasus`5 wird dich in Zukunft sehr genau im Auge behalten, wenn du ihren Laden betrittst.");
						$session[user][hitpoints]=round($session[user][hitpoints]/2);
					}else{
						output("`#Pegasus`5 hat sich die ".($row['value']-$tradeinvalue)." Gold, die du ihr schuldest, von der Bank geholt!");
						output(" Sie wird dich in Zukunft sehr genau im Auge behalten, wenn du ihren Laden betrittst.");
						$session[user][goldinbank]-=($row[value]-$tradeinvalue);
						if ($session[user][goldinbank]<0) output("`nDu hast dadurch jetzt `^".abs($session[user][goldinbank])." Gold`5 Schulden bei der Bank!!");
						//debuglog("lost " . ($row['value']-$tradeinvalue) . " gold in bank for stealing the " . $row['armorname'] . " armor");
			 			$session[user][armor] = $row[armorname];
						$session[user][defence]-=$session[user][armordef];
						$session[user][armordef] = $row[defense];
						$session[user][defence]+=$session[user][armordef];
						$session[user][armorvalue] = $row[value];
					}
					addnav("Zur�ck zum Dorf","village.php");
				} else { // Diebstahl gelingt nicht
					output("`5Du grapschst dir `%$row[armorname]`5 und tauschst `%".$session[user][armor]."`5 unauff�llig dagegen aus. ");
					output(" So schnell und unauff�llig wie du kannst verl�sst du den Laden. Geschafft! ");
					output("Als du dein(e/n) `%$row[armorname]`5 stolz auf dem Dorfplatz pr�sentierst, packt dich von hinten `!MightyE`5's starke Hand. Er entrei�t dir $row[armorname] gewaltsam, ");
					output(" dr�ckt dir dein(e/n) alte(n/s) ".$session[user][armor]." in die Hand und schl�gt dich nieder. Er raunzt noch etwas, da� er dich beim n�chsten Diebstahl");
					output(" ganz sicher umbringen wird, bevor er in seinen Laden zur�ck geht, wo bereits ein Kunde wartet.`n");
					$session[user][hitpoints]=1;
					if ($session[user][turns]>0){
						output("`n`4Du verlierst einen Waldkampf und fast alle Lebenspunkte.");
						$session[user][turns]-=1;
					}else{
						output("`n`4MightyE hat dich so schlimm erwischt, dass eine Narbe bleiben wird.`nDu verlierst 3 Charmepunkte und fast alle Lebenspunkte.");
						$session[user][charm]-=3;
						if ($session[user][charm]<0) $session[user][charm]=0;
					}
					addnav("Zur�ck zum Dorf","village.php");
				}
			}
		}else{
			output("`#Pegasus`5  nimmt dein Gold und sehr zu deiner �berraschung nimmt sie auch dein(e/n) `%".$session[user][armor]."`5  h�ngt ein Preisschild dran und legt die R�stung h�bsch zu den anderen. ");
			output("`n`nIm Gegenzug h�ndigt sie dir deine wunderbare neue R�stung `%$row[armorname]`5 aus.");
			output("`n`nDu f�ngst an zu protestieren: \"`@Werde ich nicht albern aussehen, wenn ich nichts au�er `&$row[armorname]`@ trage?`5\" Du denkst einen Augenblick dar�ber nach, dann wird dir klar, dass jeder in der  ");
			output("Stadt ja das Selbe macht.	\"`@Na gut. Andere L�nder, andere Sitten`5\"");
			//debuglog("spent " . ($row['value']-$tradeinvalue) . " gold on the " . $row['armorname'] . " armor");
	 		 $session[user][gold]-=$row[value];
			$session[user][armor] = $row[armorname];
			$session[user][gold]+=$tradeinvalue;
			$session[user][defence]-=$session[user][armordef];
			$session[user][armordef] = $row[defense];
			$session[user][defence]+=$session[user][armordef];
			$session[user][armorvalue] = $row[value];
			addnav("Zur�ck zum Dorf","village.php");
		}
	}
}
page_footer();
?>
