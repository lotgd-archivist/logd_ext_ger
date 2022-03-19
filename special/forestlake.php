<?php

// 30052004

// Datei:  forestlake.php
// Datum:  01.05.2004 ff
// Author: Durandil
// Inhalt: Flirt mit Aurelia / allgemein Flirt mit Partner im Wald.

/* Installation:
 - forestlake.php -> /logd/special/
 - forestlakepath.php -> /logd/
 - Verknüpfung in village.php (z.B. unter Wohnviertel) oder common.php (unter Plumpsklo-Zeile)
   mit folgender Zeile eintragen:
   if (@file_exists("forestlakepath.php")) addnav("Trampelpfad","forestlakepath.php");
*/

if (!isset($session)) exit();

// Einstellungen ...............................................................
$accountallowed = FALSE; // Muß auf FALSE stehen... wird später automatisch berechnet.
if ($_GET[ziel]=="forestlake") {
	$allowtovillage = TRUE;
	$allowtoforest  = FALSE;
}else{
	$allowtovillage = FALSE;
	$allowtoforest  = TRUE;
}
$forestlakedebugoutput = FAlSE;
// .............................................................................

// Daten des Partners aus Datenbank suchen......................................
$lover = "";
$loverID = $session[user][marriedto];
$loverHorse = "Pferd";
$loverHorseID = 0;
$loved = $session[user][login];
$lovedHorse = "Pferd";
$lovedID = $session[user][acctid];
$lovedHorseID = $session[user][hashorse];
if ($session[user][marriedto]>0){
	// $sql = "SELECT acctid,login,marriedto,hashorse FROM accounts WHERE acctid=$loverID AND marriedto=$lovedID ORDER BY acctid DESC";
	$sql = "SELECT acctid,login,marriedto,charisma,hashorse FROM accounts WHERE acctid=$loverID ORDER BY acctid DESC";
 	$result = db_query($sql) or die(db_error(LINK));
  	if (db_num_rows($result)>0){
		$row = db_fetch_assoc($result);
    		$lover = $row[login];
    		$loverHorseID = $row[hashorse];
  	}
}
if ($loverID==4294967295) { if ($session[user][sex]==0) { $lover = "Violet"; } else { $lover = "Seth"; } }
if ($row[charisma]==4294967295 && $row[marriedto]!=$session[user][acctid]) $loverID=0;


if (strcmp($session[user][acctid],$session[user][marriedto])<0){
	$chat = "Clearing_".$session[user][acctid]."_".$session[user][marriedto];
} else {
	$chat = "Clearing_".$session[user][marriedto]."_".$session[user][acctid];
}

if ($forestlakedebugoutput==TRUE){
	output("`nlogin: (".$session[user][acctid].") ".$session[user][login]);
  	output("`nhorse: ".$lovedHorseID);
  	output("`nlover: (".$loverID.") ".$lover);
  	output("`nhorse: ".$loverHorseID);
  	output("`nchat: ".$chat."`n");
}

if ($session[user][specialmisc]=="") $session[user][specialmisc]="done:";

switch($HTTP_GET_VARS[op]){
case "search":
case "":
	// Zutritt ...................................................................
    	$session[user][specialinc] = "forestlake.php";
    	output("`@Während Du durch den Wald läufst, fällt Dir ein Baum auf, in den etwas geritzt ist. Aus der Nähe siehst Du, dass es ein Herz ist, in das ");
    	if ($loverID==0) { output("`$ Aurelia & Durandil forever"); } else { output("`$ $loved & $lover forever"); }
    	output(" `@ geritzt ist.");
    	output("`@Außerdem fallen Dir kleine Zweige auf, die alle so geflochten scheinen, als würden sie in eine bestimmte Richtung zeigen. ");
    	if ($loverID>0) {
		if ($loverID==4294967295) {
			output("Du überlegst, den Pfeilen zu folgen, aber denkst Dir dann, dass diese Zeichen bestimmt nicht für Dich bestimmt sind, weil Dein Schatz ja noch in der Kneipe arbeitet und gar nicht hier im Wald sein kann.`n`n");
      		} else {
			output("Du überlegst, den Pfeilen zu folgen, denn irgendwie hast Du ein gutes Gefühl dabei und mußt an Deinen Schatz denken.`n`n");
        			addnav("Folge den Pfeilen","forest.php?op=clearing");
      		}
    	} else {
		output("Aber da Du eh' Single bist, kann dieses Zeichen gar nicht für Dich bestimmt sein, und Du ziehst Dich diskret in den Wald zurück.`n`n");
    	}
    	output("`n`n`n`n`n`n`n`n`n`n`$@`@}-,-´-");
    	if ($allowtovillage==TRUE) addnav("Zurück ins Dorf","village.php");
    	if ($allowtoforest==TRUE) addnav("Zurück in den Wald","forest.php?op=runaway");
  	break;
  	// Auf der Lichtung ..........................................................
  	case "clearing":
    	output("`@Nach wenigen hundert Metern kommst Du an eine kleine Lichtung, die an einem klaren Waldsee liegt.`n");
    	output("`@Auf den ersten Blick siehst Du, dass erst vor kurzem noch jemand hier war. Aber bevor Du Dir groß Gedanken darüber machen kannst, spürst Du wie sich plötzlich von hinten zwei Arme um Dich schlingen...`n`n");
    	output("`^$lover`& küsst Dich sanft in den Nacken und flüstert Dir in's Ohr: `3\"`#Hallo Liebling, schön dass Du hierher gefunden hast.`n");
    	output("Was hältst Du davon, wenn wir uns einfach mal einen schönen Tag machen? Auf was hättest Du Lust?`3\"`n`n");
    	output("`@Auf der Lichtung vor Dir bemerkst Du nun eine von Rosen umkranzte Decke, neben der auch ein Picknickkorb zu stehen scheint. ");
    	output("`@Etwas weiter links lädt der See mit seinem klaren Wasser zum Schwimmen ein. ");
    	output("`@Und ganz hinten scheinen die Sonnenstrahlen durch auf einen umgefallenen Baum, der jedoch noch sehr stabil aussieht und der ideale Platz wäre, sich zu einem kleinen Plausch zu setzen.`n");
    	if ($loverHorseID+$lovedHorseID>0) {
		output("`@In nicht allzu weiter Ferne über den Baumwipfel seht ihr zudem eine Turmruine. Wenn ihr wollt, könnt ihr auch gemeinsam einen Ausritt dorthin unternehmen.`n");
    	} else {
		output("`@Wenn einer von euch ein Reittier besitzen würde, könntet ihr auch einen kleinen Ausritt unternehmen - Aber ihr habt ja genug netter Möglichkeiten.`n");
    	}
    	output("`n`n`n`n`n`n`n`n`n`n`$@`@}-,-´-");
    	if (!strpos($session[user][specialmisc],"_swim")) addnav("Im See schwimmen","forest.php?op=swim");
    	if (!strpos($session[user][specialmisc],"_music")) addnav("Musizieren","forest.php?op=music");
    	if (!strpos($session[user][specialmisc],"_picknick")) addnav("Picknicken","forest.php?op=picknick");
    	if (!strpos($session[user][specialmisc],"_flirt")) addnav("Plaudern","forest.php?op=flirt");
    	if ($loverHorseID+$lovedHorseID>0 && !strpos($session[user][specialmisc],"_ride") ) addnav("Ausreiten","forest.php?op=ride");
    	if ($allowtovillage==TRUE) addnav("Zurück ins Dorf","village.php");
    	if ($allowtoforest==TRUE) addnav("Zurück in den Wald","forest.php?op=return");
    	$session[user][specialinc] = "forestlake.php";
  	break;
// Ausreiten .................................................................
case "ride":
	$session[user][specialmisc].="_ride";
	// Besorge Namen der Tiere
    	$sql = "SELECT mountid,mountname FROM mounts WHERE mountid=$loverHorseID OR mountid=$lovedHorseID ORDER BY mountid DESC";
    	$result = db_query($sql) or die(db_error(LINK));
    	if (db_num_rows($result)>0) {
		$row = db_fetch_assoc($result);
      		if ($row[mountid]==$loverHorseID) $loverHorse = $row[mountname];
      		if ($row[mountid]==$lovedHorseID) $lovedHorse = $row[mountname];
    	}
    	if (($loverHorseID==0) && ($lovedHorseID>0)){ // Du hast ein Pferd
		output("`@Da $lover kein eigenes Reittier besitzt, stellst Du Deinen $lovedHorse zur Verfügung. Ihr reitet gemeinsam den See entlang.`n");
    	} else if (($loverHorseID>0) && ($lovedHorseID==0)) // Der andere hat ein Pferd
    		{ output("`@Du hast zwar kein Reittier, aber $lover hilft Dir und ihr reitet gemeinsam auf $loverHorse den See entlang.`n");
   	} else // Beide besitzen Tiere
    		{ output("`@Ihr schwingt euch auf eure Tiere, Du auf Dein $lovedHorse und $lover auf einem $loverHorse, und reitet den See entlang.`n");
    	}
    	output("`@Am hinteren Ende des Sees angekommen seht ihr, dass der Zufluss sich in Richtung der Turmruine durch den Wald windet, und dass es euch ein schmaler Streifen Wiese erlaubt, dorthin zu reiten.`n");
    	output("  `n`@`^$lover`@ ruft Dir fröhlich zu:`n");
    	output("          `3\"`#Komm schon $loved, wer zuerst da ist gewinnt und darf sich etwas wünschen!`3\"`n`n");
    	output("`@Kopf an Kopf wetteifernd eilt ihr den Fluss entlang, und schon bald bemerkt ihr, wie der Boden sich hebt und ihr einen Hügel hinaufreitend dem Turm näher kommt, und innerhalb einer knappen Viertelstunde seid ihr an der Ruine angekommen.`n`n");
    	output("`@Der Turm scheint ein alter Signalturm gewesen sein, wirkt allerdings schon ziemlich mitgenommen; die Zinnen über dem dritten Stockwerk sind bereits weggebröckelt, und auch die Decken sind teilweise eingestürzt.`n");
    	output("`@Trotzdem macht die Ruine irgendwie einen romantischen Eindruck, und Du kannst Dir vorstellen, dass man von oben - sofern die Treppen noch vorhanden sein sollten - bestimmt einen großartigen Ausblick hat. Wollt ihr euch den Turm nichtmal näher anschauen?`n");
    	output("`n`n`n`n`n`n`n`n`n`n`$@`@}-,-´-");
    	addnav("Turm untersuchen","forest.php?op=towerruin");
    	addnav("Zurück zur Lichtung","forest.php?op=rideback");
    	$session[user][specialinc] = "forestlake.php";
  	break;
  // Im Turm ...................................................................
  case "towerruin":
  	output("`@Ihr betretet Hand in Hand das Erdgeschoß des Turms. Das Glück ist euch hold, denn die Holzdecken oberhalb des ersten Obergeschosses sind zwar eingestürzt, die an den Wänden entlanglaufende Treppe ist jedoch aus Stein gemauert und hält euch, so dass ihr gemeinsam nach oben gehen und die Aussicht genießen könnt.`n");
    	output("`@Und diese ist noch weit schöner als ihr euch vorgestellt habt - soweit das Auge reicht seht ihr grüne Mischwälder, gelegentlich unterbrochen von riesigen erhabenen Sequoias, die noch aus einer früheren Zeit zu stammen scheinen. ");
    	output("`@Ihr beobachtet, wie der Fluss sich fröhlich in das Tal schlängelt, in den See fliesst, und sich mit kleinen Seitenläufen vereint in der Ferne verliert...`n`n");
    	output("`@Doch während ihr so verschlungen da oben steht, merkt ihr, wie der Wind immer heftiger an euch rüttelt, und als ihr euch umdreht, seht ihr eine dunkle Wetterfront auf euch zuziehen.`n");
    	output("`@Ihr bleibt trotzdem noch eine Weile oben, lasst den Wind durch eure Haare streifen und beobachtet, wie es in der Ferne blitzt und donnert. ");
    	output("`@Erst als es ungemütlich nass wird, eilt ihr die Treppen wieder herunter, macht es euch in einer trockenen Ecke des Untergeschosses mit ein paar Decken gemütlich und verbringt die Zeit bis zum Ende des Unwetters damit, den Gewalten der Natur zu lauschen und miteinander zu flüstern.`n`n");
    	viewcommentary($chat,"Flüstern",10,"flüstert zärtlich");
    	output("`n`n`n`n`n`n`n`n`n`n`$@`@}-,-´-");
    	addnav("Zurück zur Lichtung","forest.php?op=rideback");
    	$session[user][specialinc] = "forestlake.php";
  	break;
 // Zurück vom Ausritt ........................................................
 case "rideback":
    	output("`@Ihr beeilt euch, zurück zur Waldlichtung zu kommen, schließlich wartet da immer noch der Picknickkorb auf euch, und eine Runde im See zu schwimmen wäre auch eine gute Idee. Oder wollt ihr einfach nur ein gemütliches Pläuschchen halten?`n");
    	output("`n`n`n`n`n`n`n`n`n`n`$@`@}-,-´-");
    	if (!strpos($session[user][specialmisc],"_swim")) addnav("Im See schwimmen","forest.php?op=swim");
    	if (!strpos($session[user][specialmisc],"_music")) addnav("Musizieren","forest.php?op=music");
    	if (!strpos($session[user][specialmisc],"_picknick")) addnav("Picknicken","forest.php?op=picknick");
    	if (!strpos($session[user][specialmisc],"_flirt")) addnav("Plaudern","forest.php?op=flirt");
    	if ($loverHorseID+$lovedHorseID>0 && !strpos($session[user][specialmisc],"_ride") ) addnav("Ausreiten","forest.php?op=ride");
    	if ($allowtovillage==TRUE) addnav("Zurück ins Dorf","village.php");
    	if ($allowtoforest==TRUE) addnav("Zurück in den Wald","forest.php?op=return");
    	$session[user][specialinc] = "forestlake.php";
  	break;
 // Zusammen Musik machen .....................................................
 case "music":
	$session[user][specialmisc].="_music"; 
   	output("`3\"`#Wollen wir nicht zusammen etwas singen?`3\" fragt $lover, packt eine Laute aus und fängt an, die Saiten zu stimmen.`n`n");
    	output("`@Als $lover damit fertig ist, stimmt Du leise ein Lied an, und $lover begleitet Dich mit sanften Klängen...`n`n`n`%");

    	$rand = e_rand(0,1); // Zufällige Ereignisse während des Schwimmens.
    	switch($rand) {
		case 0:
        		if (!$session['user']['prefs']['nosounds']) output("<embed src=\"media/rightherewaitingforyou.mid\" width=10 height=10 autostart=true loop=false hidden=true volume=100>",true);
        		output("Oceans apart day after day`nAnd I slowly go insane`nI hear your voice on the line`nBut it doesn't stop the pain`n`n");
        		output("If I see you next to never`nHow can we say forever`n`n");
        		output("Wherever you go`nWhatever you do`nI will be right here waiting for you`nWhatever it takes`nOr how my heart breaks`nI will be right here waiting for you`n`n");
        		output("I took for granted, all the times`nThat I though would last somehow`nI hear the laughter, I taste the tears`nBut I can't get near you now`n`n");
        		output("Oh, can't you see it baby`nYou've got me goin' crazy`n`n");
        		output("Wherever you go`nWhatever you do`nI will be right here waiting for you`nWhatever it takes`nOr how my heart breaks`nI will be right here waiting for you`n`n");
        		output("I wonder how we can survive`nThis romance`nBut in the end if I'm with you`nI'll take the chance`n`n");
        		output("Oh, can't you see it baby`nYou've got me goin' crazy`n`n");
        		output("Wherever you go`nWhatever you do`nI will be right here waiting for you`nWhatever it takes`nOr how my heart breaks`nI will be right here waiting for you`n`n");
      		break;
      		case 1:
      		output("Take me now baby here as I am`nPull me close try an understand`nI work all day out in the hot sun`nStay with me now till the mornin' comesCome on now try and understand`nThe way I feel when I'm in your hands`nTake me now as the sun descends`nThey can't hurt you now`nThey can't hurt you now`nThey can't hurt you now`n`n");
        		output("Because the night belongs to lovers`nBecause the night belongs to us`nBecause the night belongs to lovers`nBecause the night belongs to us`n`n");
        		output("What I got I have earned`nWhat I'm not I have learned`nDesire and hunger is the fire I breathe`nJust stay in my bed till the morning comes`nCome on now try and understand`nThe way I feel when I'm in your hands`nTake me now as the sun descends`nThey can't hurt you now`nThey can't hurt you now`nThey can't hurt you now`n`n");
        		output("Because the night...`n`n");
        		output("Your love is here and now`nThe vicious circle turns and burns without`nThough I cannot live forgive me now`nThe time has come to take this moment and`nThey can't hurt you now`n`n");
        		output("Because the night...`n`n");
      		break;
    	}
    	output("`@Leise lässt Du Deinen Gesang zu den letzten Akkorden ausklingen, und ihr sitzt noch minutenlang da und schaut euch verträumt in die Augen...`n");
    	output("`n`n`n`n`n`n`n`n`n`n`$@`@}-,-´-");
    	if (!strpos($session[user][specialmisc],"_swim")) addnav("Im See schwimmen","forest.php?op=swim");
    	if (!strpos($session[user][specialmisc],"_music")) addnav("Musizieren","forest.php?op=music");
    	if (!strpos($session[user][specialmisc],"_picknick")) addnav("Picknicken","forest.php?op=picknick");
    	if (!strpos($session[user][specialmisc],"_flirt")) addnav("Plaudern","forest.php?op=flirt");
    	if ($loverHorseID+$lovedHorseID>0 && !strpos($session[user][specialmisc],"ride") ) addnav("Ausreiten","forest.php?op=ride");
    	if ($allowtovillage==TRUE) addnav("Zurück ins Dorf","village.php");
    	if ($allowtoforest==TRUE) addnav("Zurück in den Wald","forest.php?op=return");
    	$session[user][specialinc] = "forestlake.php";
  	break;
  // Im See Schwimmen ..........................................................
 case "swim":
    	output("`@Noch etwas schüchtern entkleidest Du Dich, genießt aber doch, wie `^$lover`@ Deinen nackten Körper bewundert und sich ebenfalls zu entkleiden beginnt. ");
    	output("`@Nackt wie von Gott geschaffen schwimmt ihr gemeinsam an das andere Ufer und zurück, wo ihr im seichteren Wasser herumzutollt und euch gegenseitig unter Wasser zu drücken versucht.");
    	$rand = e_rand(1,50); // Zufällige Ereignisse während des Schwimmens.
    	switch ($rand){
		case 1:
        		output("`n`n`^Während Du untertauchst, siehst Du etwas am Boden des Sees schimmern. Bei näherem Hinsehen erkennst Du einen Ring. Von einem unguten Gefühl erfasst beschließt Du, den Ring ganz schnell wieder zu vergessen, und widmest Dich schnell wieder ganz und gar $lover.");
        		$exp = e_rand(20,200);
        		output("`n`n`^Du erhältst $exp Erfahrungspunkt für diese weise Entscheidung.");
        		$session[user][experience]+=$exp;
      		break;
      		case 2:
      		output("`n`n`^Während Du untertauchst, siehst Du etwas am Boden des Sees schimmern. Bei näherem Hinsehen erkennst Du einen Ring. Du willst ihn bergen, doch bevor Du wirklich herankommst, merkst Du auf einmal, daß Dein Bein in einigen Schlingpflanzen am Grund verheddert ist. Du versuchst Dich loszureissen, doch es will Dir nicht gelingen. Nach einigen Minuten geht Dir der Atem aus und Deine Sinne schwinden...");
        		$exp = e_rand(20,200);
        		if ($exp>$session[user][experience])
        			{ output("`n`n`^Während Du langsam wieder zu Bewußtsein kommst, merkst Du, daß Du Dich nicht mehr daran erinnern kannst, was Du in den letzten Tagen getan hast.");
          			$session[user][experience]=0;
        		} else
        			{ output("`n`n`^Während Du langsam wieder zu Bewußtsein kommst, wird Dir bewußt, was für ein Trottel Du doch bist - durch diese dumme Aktion hast Du $exp Erfahrungspunkte verloren!");
          			$session[user][experience]-=$exp;
        		}
      		break;
      		case 3:
        		output("`n`n`^Während Du untertauchst, siehst Du etwas am Boden des Sees schimmern. Du tauchst hinab und greifst danach. Als Du wieder zurück an die Oberfläche kommst, siehst Du, dass Du zwei Ringe gefunden hast, von denen Du einen $lover gibst und den anderen selber ansteckst.");
        		$mhp = e_rand(1,round($session[user][level]/3));
        		output("`n`n`^Der Ring verleiht Dir $mhp zusätzliche Lebenspunkte.");
        		$session[user][maxhitpoints]+=$mhp;
		$db_query("INSERT INTO items (name,owner,class,value1,gold,gems,description,hvalue) VALUES ('Ring des Lebens',".$session[user][acctid].",'Schmuck',$mhp,100,$mhp,'Dieser schöne Ring aus dem See im Wald gibt dir $mhp Lebenspunkte')");
		$db_query("INSERT INTO items (name,owner,class,value1,gold,gems,description,hvalue) VALUES ('Ring des Lebens',$loverID,'Schmuck',0,100,0,'Diesen schönen Ring hat dir ".$session[user][login]."`0 am See im Wald geschenkt.')");
      		break;
      		case 4:
        		output("`n`n`^Während Du untertauchst, siehst Du etwas am Boden des Sees schimmern. Du tauchst hinab und greifst danach. Dabei rutscht der Ring auf Deinen Finger, und Du fühlst Dich plötzlich schwächer. Zurück an der Oberfläche merkst Du zudem, daß Du den Ring nicht wieder abbekommst!");
        		$mhp = e_rand(1,round($session[user][level]/3));
        		if ($session[user][maxhitpoints]<10) { $mhp = 0; }
        		if (($mhp>0) && ((($session[user][maxhitpoints]-$mhp)<10))) { $mhp = $session[user][maxhitpoints]-10; }
        		output("`n`n`^Der Ring raubt Dir $mhp Lebenspunkte!");
		$db_query("INSERT INTO items (name,owner,class,value1,gold,gems,description,hvalue) VALUES ('Der Ring',".$session[user][acctid].",'Fluch',$mhp,500,$mhp,'Dieser Ring saugt dir $mhp Lebenspunkte ab.')");
        		$session[user][maxhitpoints]-=$mhp;
      		break;
    	}
    	if ($rand<10)
    		{ if ($session[user][hitpoints]<$session[user][maxhitpoints])
      			{ $session[user][hitpoints]=$session[user][maxhitpoints];
        			output("`n`n`^Das herrlich klare und saubere Wasser hat Deine Lebenspunkte vollständig aufgefüllt.");
      		}
    	}
    	output("`n`n`n`@Nach einer guten Stunde gibt `^$lover`@ erschöpft auf, nimmt Dich in die Arme und spricht zärtlich:`n");
    	output("          `3\"`#Hat das einen Spaß gemacht. $loved, Du bist wundervoll und ich liebe Dich.`3\"`n`n");
    	output("`@Gemeinsam steigt ihr aus dem Wasser, trocknet euch ein wenig länger als notwendig gegenseitig ab, und überlegt, was ihr wohl als nächstes tun werdet.`n");
    	output("`n`n`n`n`n`n`n`n`n`n`$@`@}-,-´-");
	$session[user][specialmisc].="_swim";
    	if (!strpos($session[user][specialmisc],"_swim")) addnav("Im See schwimmen","forest.php?op=swim");
    	if (!strpos($session[user][specialmisc],"_music")) addnav("Musizieren","forest.php?op=music");
    	if (!strpos($session[user][specialmisc],"_picknick")) addnav("Picknicken","forest.php?op=picknick");
    	if (!strpos($session[user][specialmisc],"_flirt")) addnav("Plaudern","forest.php?op=flirt");
    	if ($loverHorseID+$lovedHorseID>0 && !strpos($session[user][specialmisc],"_ride") ) addnav("Ausreiten","forest.php?op=ride");
    	if ($allowtovillage==TRUE) addnav("Zurück ins Dorf","village.php");
    	if ($allowtoforest==TRUE) addnav("Zurück in den Wald","forest.php?op=return");
    	$session[user][specialinc] = "forestlake.php";
  	break;
  // Gemütlich picknicken ......................................................
  case "picknick":
	$session[user][specialmisc].="_picknick";
    	output("`@Gemeinsam setzt ihr euch auf die Decke. `^$lover`@ steckt Dir eine der Rosen in's Haar, küsst Dich zärtlich, und holt eine Weinflasche und zwei Gläser aus dem Picknickkorb. ");
    	output("`@Nachdem `^$lover`@ Dir ein Glas eingeschenkt hat, stoßt ihr auf eure gemeinsame Zukunft an, und beginnt über Gott und die Welt und vor allem über euch zu reden.`n`n");
    	output("`@Es dauert nicht lange, dann ist die erste Weinflasche leer, und bald darauf eine Zweite. Um dem Alkohol eine Grundlage zu geben beschließt ihr, etwas zu essen, und füttert euch gegenseitig mit Konfekt und kandiertem Obst aus dem scheinbar bodenlosen Korb.`n`n");
    	output("`@Als es irgendwann dunkel wird, seid ihr beide sehr satt und leicht beschwippst, und kuschelt euch gemeinsam unter eine Decke. Auf dem Rücken liegend beobachtet ihr, wie die Sterne langsam herauskommen. ");
    	// Rausch hinzufügen?
    	$session[user][drunkenness]+=3;
    	if (($lover=="Durandil") && ($loved=="Aurelia")) output("Bald merkst Du, dass es der Abendstern Durandil besonders angetan hat. ");
    	output("`n`n`n`n`n`n`n`n`n`n`$@`@}-,-´-");
    	if (!strpos($session[user][specialmisc],"_swim")) addnav("Im See schwimmen","forest.php?op=swim");
    	if (!strpos($session[user][specialmisc],"_music")) addnav("Musizieren","forest.php?op=music");
    	if (!strpos($session[user][specialmisc],"_picknick")) addnav("Picknicken","forest.php?op=picknick");
    	if (!strpos($session[user][specialmisc],"_flirt")) addnav("Plaudern","forest.php?op=flirt");
    	if ($loverHorseID+$lovedHorseID>0 && !strpos($session[user][specialmisc],"_ride") ) addnav("Ausreiten","forest.php?op=ride");
    	if ($allowtovillage==TRUE) addnav("Zurück ins Dorf","village.php");
    	if ($allowtoforest==TRUE) addnav("Zurück in den Wald","forest.php?op=return");
    	$session[user][specialinc] = "forestlake.php";
  	break;
  // Auf Baumstamm setzen und plaudern .........................................
  case "flirt":
	$session[user][specialmisc].="_flirt";
  	output("`@Zusammen mit `^$lover`@ setzt Du Dich auf den Baumstamm, um ein wenig zu plaudern. Instinktiv wisst ihr, dass euch hier niemand belauschen kann, und ihr euren Gefühlen freien Lauf lassen könnt.`n`n");
    	$rand = e_rand(1,100); // schenk ihr etwas, z.B. einen Edelstein!
    	switch ($rand){
		case 1:
        		output("`&Während des Gespräches steckt $lover Dir eine weitere Rose in's Haar und flüstert:`n`3\"`#Du bist wunderschön...`3\"`n`n"); break;
        		$session[user][charm]++;
      		break;
      		case 2:
        		output("`&Während des Gespräches bemerkst Du immer wieder, wie toll $lover doch aussiehst, und schämst Dich, daß Du Dich heute morgen nicht sorgfältiger frisch gemacht hast.`n`n"); break;
        		$session[user][charm]--;
      		break;
      		case 3:
        		$gems = e_rand(1, $session[user][level]/7+1);
        		output("`&$lover schenkt Dir während des Gespräches $gems Edelsteine:`n`3\"`#Sie sind zwar nicht so schön wie Du es bist, aber ich hoffe sie gefallen Dir trotzdem...`3\"`n`n");
        		$session[user][gems] += $gems;
      		break;
      		case 4:
        		$gems = e_rand(1, $session[user][level]/7+1);
        		if ($gems>$session[user][gems])
        			{ $session[user][gems] = 0;
          			output("`&Du bist so in das Gespräch vertieft, daß Du gar nicht merkst, wie Dir alle Deine Edelsteine aus der Tasche purzeln und im tiefen Gras verschwinden!`n`n"); break;
        		} else
        			{ $session[user][gems] -= $gems;
          			output("`&Du bist so in das Gespräch vertieft, daß Du gar nicht merkst, wie Dir $gems Edelsteine aus der Tasche purzeln und im tiefen Gras verschwinden!`n`n"); break;
        		}
      		break;
    	}
    	viewcommentary($chat,"Flüstern",10,"flüstert zärtlich");
    	output("`n`n`n`n`n`n`n`n`n`n`$@`@}-,-´-");
    	if (!strpos($session[user][specialmisc],"_swim")) addnav("Im See schwimmen","forest.php?op=swim");
    	if (!strpos($session[user][specialmisc],"_music")) addnav("Musizieren","forest.php?op=music");
    	if (!strpos($session[user][specialmisc],"_picknick")) addnav("Picknicken","forest.php?op=picknick");
    	if (!strpos($session[user][specialmisc],"_flirt")) addnav("Plaudern","forest.php?op=flirt");
    	if ($loverHorseID+$lovedHorseID>0 && !strpos($session[user][specialmisc],"_ride") ) addnav("Ausreiten","forest.php?op=ride");
    	if ($allowtovillage==TRUE) addnav("Zurück ins Dorf","village.php");
    	if ($allowtoforest==TRUE) addnav("Zurück in den Wald","forest.php?op=return");
    	$session[user][specialinc] = "forestlake.php";
  	break;
  	case "return":
    	output("`@Nachdem ihr euch eine verdiente Auszeit genommen habt, wird es Zeit, sich wieder den Gefahren des Alltags zu stellen.");
    	output("`@Ihr verabschiedet euch sehr herzlich und versprecht, euch so bald wie möglich wieder hier zu treffen.");
    	output("`n`n`n`n`n`n`n`n`n`n`$@`@}-,-´-");
    	//output("`n`n`n`n`n`n`n`n`n`7Mein Dank geht an Kaiserin siwi dafür, dass sie diese Waldbegegnung eingebunden hat, and natürlich und vor allem an Aurelia, die mich dazu inspiriert hat.");
    	$session[user][specialinc]="";
  	break;
  	case "runaway":
    	output("`@Irgendwie kannst Du Dich nicht recht aufraffen, den Pfeilen zu folgen. Könnte ja auch eine Falle sein, oder?`n");
    	output("Und falls dort jemand auf Dich wartet, soll er halt warten.");
    	$session[user][specialinc]="";
  	break;
}
?>