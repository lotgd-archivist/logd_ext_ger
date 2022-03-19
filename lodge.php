<?php

// 09072004

/*************************************************************
HUNTER'S LODGE for LoGD 0.9.7 ext (GER)
by weasel and anpera

*************************************************************/

require_once "common.php"; 
addcommentary();
page_header("J�gerh�tte"); 
addnav("Zur�ck zum Dorf","village.php"); 
if ($_GET[op]!="points") addnav("Punkte","lodge.php?op=points");
if ($_GET[op]=="points") addnav("Empfehlungen","referral.php"); 

$config = unserialize($session['user']['donationconfig']); 
$pointsavailable=$session['user']['donation']-$session['user']['donationspent']; 

if ($_GET['op']==""){
	output("`b`cDie J�gerh�tte`c`b"); 
    	//output("Moo.  *chuckle*  Yeah, you talk to him, this is what it's like.  Well this can be fun.  Boy, that michele, she is one sexy chick.  And she's so much smarter than Eric.  That's what I like about her most, her sharp intelligence.  Ok, should we start helping him now?  No.  You could be a stenographer, Eric.  Can you even spell Stenagorapher.  I can, cause I'm smart.  SMRT.  We're giving him obliging pauses in our converstaion now.  Allright, text for the hunting lodge.  Well, shouldn't it be similar to the superuser grotto?  AAAAH, CAN EPEE BE IN CHARGE OF THE HUNTING LODGE?  "); 
    	output("`0Du folgst einem schmalen Pfad, der hinter den St�llen entlang f�hrt. Am Ende dieses Pfades steht die J�gerh�tte. Ein T�rsteher stoppt dich und m�chte deine Mitgliedskarte sehen `n`n "); 
         	addnav("Empfehlungen","referral.php"); 
       	if ($session['user']['donation']>=10){ 
        		output("Nach dem Zeigen deiner Mitgliedskarte sagt er, \"`7Sehr sch�n, willkommen in der J. C. Petersen J�gerh�tte.  Du hast noch `$`b$pointsavailable`b`7 Punkte zur Verf�gung,`0\" und l�sst dich rein. 
        		`n`n 
        		Du betritts einen Raum, der durch einen grossen Kamin am anderen Ende beherrscht wird. Die holzget�felten W�nde werden mit Waffen, Schilden und angebrachten Jagdtroph�en einschliesslich den K�pfen von einigen Drachen bedeckt, die im flackernden Licht des Kamines zu leben scheinen.
        		`n`n 
        		Viele hohe St�hle f�llen den Raum.  In dem Stuhl der am n�chsten beim Feuer ist, sitzt J. C. Petersen und liest
        		\"Alchemie Heute.\" 
        		`n`n 
        		W�hrend du dich n�herst, hebt ein grosser Jagdhund, der zu seinen F�ssen liegt, den Kopf und �berlegt ob er dich kennt.
        		Als er dich als vertrauensw�rdig einstuft legt er sich wieder hin und schl�ft weiter. 
        		`n`n 
       		In der N�he ein schroffes J�gergerede:`n"); 
        		viewcommentary("hunterlodge","Hinzuf�gen",25); 
        		addnav("Punkte einsetzen");
           		addnav("Charmepunkte abfragen (20 Punkte)","lodge.php?op=charm"); 
        		if ($config['namechange']==1){ 
            			addnav("Farbiger Name (25 Punkte)","lodge.php?op=namechange"); 
        		}else{ 
            			addnav("Farbiger Name (300 Punkte)","lodge.php?op=namechange"); 
        		} 
        		addnav("10 N�chte in der Kneipe (30 Punkte)","lodge.php?op=innstays"); 
        		addnav("2 Edelsteine (50 Punkte)","lodge.php?op=gems");
        		addnav("Extra Waldk�mpfe f�r 30 Tage (100 Punkte)","lodge.php?op=forestfights"); 
        		addnav("Heilerin Golinda f�r 30 Tage (100 Punkte)","lodge.php?op=golinda");
		addnav("Zur Burg reiten (100 Punkte)","lodge.php?op=reiten1");
		addnav("PvP-Immunit�t (300 Punkte)","lodge.php?op=immun");
		if ($session[user][house]>0 && $session[user][housekey]==$session[user][house]) addnav("Hausschl�ssel","lodge.php?op=keys1"); 
		if ($session[user][donation]>=2000) addnav("Sonderbonus");
		if ($session[user][donation]>=2000 && $pointsavailable>=50) addnav("Titel �ndern (50 Punkte)","lodge.php?op=titeel1");
    	}else{ 
        		output("Du ziehst die Karte deines Lieblingsgasthauses heraus, wo 9 von 10 Slots mit dem kleinen Profil von Cederik abgestempelt sind. 
        		`n`n 
        		Der T�rsteher schaut fl�chtig auf deine Karte, r�t dir nicht soviel zu trinken und weist dir den Weg zur�ck."); 
    	} 
}else if ($_GET['op']=="points"){ 
    	addnav("Zur�ck zur Lodge","lodge.php"); 
    	output("`bPunkte:`b`n`n
   	Legend of the Green Dragon bietet dir die M�glichkeit, spezielle \"Donationpoints\" zu sammeln, mit denen du Sonderfunktionen freischalten kannst.`n
  	Diese Punkte gibt es f�r besondere (geheime) Leistungen, sie k�nnen gekauft werden und f�r sogenannte \"Referrals\" (Empfehlungen) als Belohnung gesammelt werden. Erst wenn du mindestens 10 Donationpoints hast, kommst du in die Jagdh�tte.`n`n
	Klicke im Eingangsbereich der J�gerh�tte auf \"Empfehlungen\", wenn du wissen willst, wie du auf diesem Weg an Donationpoints kommst.");
	output("`n`nUm Punkte zu kaufen, �berweise `b1 Euro pro 100 Punkte`b auf das Konto des Admins.`n`bSchicke dazu bitte eine Hilfsanfrage  oder eine E-Mail an ".getsetting("gameadminemail","")." ab, um die Bankverbindung zu erfahren");
	if (getsetting("paypalemail","")) output(", oder benutze den PayPal Link \"ADMIN\" oben rechts");
	output(".`b`nDas Geld wird ausschliesslich zur Finanzierung des Servers verwendet.`n");
	output("`nWenn du den Programmierer von LoGD belohnen willst, kannst du pro gespendetem US-\$ ebenfalls 100 Punkte kassieren.
	Schicke dazu irgendeinen Beweis deiner Spende, z.B. einen Screenshot der PayPal-Best�tigung, an ".getsetting("gameadminemail","").". F�r eine Spende an den Programmierer (Eric Stevens a.k.a. MightyE) benutze den PayPal-Link, der auf jeder Seite oben rechts zu finden ist.");
	output("`n`n
   	`bDas kannst du mit diesen Punkten anstellen:`b`n
    	- Umsonst in der Kneipe wohnen (10 N�chte f�r 30 Punkte).`n 
	- Edelsteine kaufen (2 St�ck f�r 50 Punkte)`n
    	- Zus�tzliche Waldk�mpfe kaufen (100 Punkte f�r 30 Tage lang 1 extra Kampf; maximal 5 mehr pro Tag)!`n
	- 'Zur Burg reiten' im Wald freischalten (100 Punkte),`n
	- PvP-Immunit�t kaufen (300 Punkte f�r permanente Immunit�t)`n
    	- Einen farbigen Namen machen (300 Punkte). Umf�rben kostet nur noch 25 Punkte. `n
	- Anzeige der Charmepunkte (20 Punkte)`n
	- Ersatzschl�ssel (10) und zus�tzliche Schl�ssel (50) f�r dein Haus kaufen.`n 
	- Ab 2000 gesammelten Punkten (ob ausgegeben oder nicht) kannst du dir f�r 50 Punkte einen eigenen Titel aussuchen.`n
	`n`n`7Du hast noch `\$`b$pointsavailable`b`7 Punkte von insgesamt `4".$session[user][donation]." `7gesammelten Punkten �brig. 
    	"); 
}elseif ($_GET['op']=="golinda"){ 
    	output("30 Tage Zugang zu Golinda der Heilerin kosten 100 Punkte. Golinda heilt zum halben Preis.");
	if($pointsavailable<100){
		output("`n`n`\$Du hast nicht genug Punkte!`0");
	} else {
    		addnav("Bet�tige Zugang zu Golinda");
    		addnav("JA","lodge.php?op=golindaconfirm");
	}
    	addnav("Zur�ck zur Lodge","lodge.php"); 
}elseif ($_GET['op']=="golindaconfirm"){ 
    	if ($pointsavailable >= 100) {
      		$config['healer'] += 30;
      		output("J. C. Peterson gibt dir eine Karte und sagt \"Mit dieser Karte kannst du an 30 verschiedenen Tagen bei Golinda vorstellig werden.\"");
     	 	$session['user']['donationspent']+=100; 
    	}
    	addnav("Zur�ck zur Lodge","lodge.php");

}elseif ($_GET['op']=="reiten1"){
	if ($config['castle']) {
		output("Du hast diese Option bereits gekauft. Um zur Burg zu kommen, brauchst du ansonsten nur ein `bPferd`b. Ein `iPferd`i ist ein Tier der Kategorie 'Pferde' in Mericks Stall.");
	} else {
    		output("Hiermit schaffst du dir die M�glichkeit, mit einem Reittier im Wald auch zur Burg reiten zu k�nnen. Du kannst nur auf Pferden reiten, also die Tiere in Merick's Stall, die in der Kategorie 'Pferde' stehen.");
		if($pointsavailable<100){
			output("`n`n`\$Du hast nicht genug Punkte!`0");
		} else {
    			addnav("Bet�tige Freischaltung");
    			addnav("JA","lodge.php?op=reiten2");
		}
 	}
    	addnav("Zur�ck zur Lodge","lodge.php"); 
}elseif ($_GET['op']=="reiten2"){ 
    	if ($pointsavailable >= 100) {
      		$config['castle'] = 100;
      		output("J. C. Peterson gibt dir eine Karte und sagt \"Mit dieser Karte findest du den Weg zur Burg, wenn du ein Pferd hast.\"");
      		$session['user']['donationspent']+=100; 
    	}
    	addnav("Zur�ck zur Lodge","lodge.php");

}elseif ($_GET['op']=="forestfights"){ 
    	if (!is_array($config['forestfights'])) $config['forestfights']=array(); 
    	output("1 Extra Waldkampf pro Tag f�r 30 Tage kostet 100 Punkte. Du bekommst einen extra Waldkampf an jedem Tag, an dem du spielst.`n"); 
	if($pointsavailable<100){
		output("`n`n`\$Du hast nicht genug Punkte!`0");
	} else {
    		addnav("Best�tige Extra Waldk�mpfe"); 
    		addnav("JA","lodge.php?op=fightbuy"); 
	}
    	addnav("Zur�ck zur Lodge","lodge.php"); 
    	reset($config['forestfights']); 
    	while (list($key,$val)=each($config['forestfights'])){ 
        		//output("Du hast noch {$val['left']} Tage, an denen zu einen zus�tzlichen Waldkampf f�r deine am {$val['bought]} bekommst.`n");
		output("Du hast noch {$val['left']} Tage, an denen zu einen zus�tzlichen Waldkampf f�r deine am {$val['bought']} bekommst.`n");
    	} 
}elseif ($_GET['op']=="fightbuy"){ 
    	if (count($config['forestfights'])>=5){ 
        		output("Du Kannst maximal 5 extra Waldk�mpfe haben pro Tag.`n"); 
    	}else{ 
        		if ($pointsavailable>0){ 
            			array_push($config['forestfights'],array("left"=>30,"bought"=>date("M d"))); 
            			output("Du wirst in den n�chsten 30 Tagen, an denen du spielst, einen extra Waldkampf haben."); 
            			$session['user']['donationspent']+=100; 
        		}else{ 
            			output("Extra Waldk�mpfe zu kaufen kostet 100 Punkte, aber du hast nicht so viele."); 
        		} 
    	} 
  	addnav("Zur�ck zur Lodge","lodge.php"); 
}elseif ($_GET['op']=="innstays"){ 
    	output("10 freie �bernachtungen in der Kneipe kosten 30 Punkte. Bist du dir sicher, dass du das willst?"); 
	if($pointsavailable<30){
		output("`n`n`\$Du hast nicht genug Punkte!`0");
	} else {
    		addnav("Best�tige 10 freie �bernachtungen"); 
    		addnav("JA","lodge.php?op=innconfirm"); 
	}
    	addnav("Zur�ck zur Lodge","lodge.php"); 
}elseif ($_GET['op']=="innconfirm"){ 
    	if ($pointsavailable>=30){ 
    	    	output("J. C. Petersen gibt dir eine Karte und sagt \"Coupon: Gut f�r 10 �bernachtungen in der Boar's Head Kneipe\""); 
        		$config['innstays']+=10; 
        		$session['user']['donationspent']+=30; 
    	} 
	addnav("Zur�ck zur Lodge","lodge.php"); 
}elseif ($_GET['op']=="charm"){
    	output("Du fragst J. C. Petersen, ob er dein Aussehen beurteilen kann. Er mustert dich kurz und verspricht dir dann, dass er dir f�r die Kleinigkeit von 20 Punkten eine ehrliche Antwort geben wird.");
	if($pointsavailable<20){
		output("`n`n`\$Du hast nicht genug Punkte!`0");
	} else {
    		addnav("Best�tige Charmepunkt-Anzeige");
    		addnav("JA","lodge.php?op=charmconfirm");
	}
	addnav("Zur�ck zur Lodge","lodge.php");
}elseif ($_GET['op']=="charmconfirm"){
	if ($pointsavailable>=20){
		if ($session['user']['charm']<=0) output("J. C. Petersen schaut dich angewidert an und sagt \"Du bist h�sslich wie die Nacht, ich kann einfach nichts Sch�nes an dir finden.\"");
		elseif ($session['user']['charm']==1) output("J. C. Petersen schaut dich kurz an und sagt \"Du bist genauso h��lich wie jeder gemeine B�rger, mehr als `^1 Punkt`0 wird dir kein Preisrichter geben.\"");
		else output("J. C. Petersen mustert dich noch einmal ganz genau und sagt \"Du bist `^".$session['user']['charm']."`0mal so sch�n wie der gemeine B�rger.\"");
		$session['user']['donationspent']+=20;
	}
	addnav("Zur�ck zur Lodge","lodge.php");
}elseif ($_GET['op']=="gems"){ 
    	output("2 Edelsteine f�r 50 Punkte. Bist du dir sicher, dass du das willst?"); 
	if($pointsavailable<50){
		output("`n`n`\$Du hast nicht genug Punkte!`0");
	} else {
    		addnav("Best�tige 2 Edelsteine"); 
    		addnav("JA","lodge.php?op=gemsconfirm"); 
	}
    	addnav("Zur�ck zur Lodge","lodge.php"); 
}elseif ($_GET['op']=="gemsconfirm"){ 
    	if ($pointsavailable>=50){ 
        		output("J. C. Petersen gibt dir 2 Edelsteine und sagt \"Damit, mein Freund, wird dein Leben leichter werden\""); 
        		$session[user][gems]+=2; 
        		$session['user']['donationspent']+=50; 
    	} 

} else if ($_GET['op']=="titeel1"){
	addnav("Zur�ck zur Lodge","lodge.php");
	$n=$session[user][name];
	if ($session[user][ctitle]){
		$teil=$session[user][ctitle];
	} else {
		$teil=$session[user][title];
	}
	output("Dein bisheriger Titel lautet: `b$teil`b, dein kompletter Name: `b$n`b`n`nWie soll dein Titel von nun an lauten?`n(Sende ein leeres Feld ab, wenn du deinen regul�ren Titel wieder haben willst.)`n"); 
	$output.="<form action='lodge.php?op=titeel2' method='POST'><input name='teil' size='25' maxlength='25' value=\"".HTMLEntities($teil)."\"> <input type='submit' value='Vorschau'></form>";
	addnav("","lodge.php?op=titeel2"); 
} else if ($_GET['op']=="titeel2"){
	addnav("Zur�ck zur Lodge","lodge.php");
	$falsetitle = false;
	if ($_POST['teil']=="") {
		$teil=$session[user][title];
	} else {
		$teil=stripslashes($_POST['teil']);
		$teil = preg_replace("/`[^123456789!@#\$%^&QqRrVvGgTt]/","",$teil);
		// Anf�hrungszeichen machen nur Probleme...
		$teil = str_replace('\'','',$teil);
		$teil = str_replace('"','',$teil);
		
		// Titel nicht leer, aber auch nix regul�res drin?
		if(trim(preg_replace('/`./','',$teil))=='') {
			$teil=$session[user][title];
			$_POST['teil'] = '';
		}
		else {
			// Offene Tags zumachen
			// nicht mehr n�tig, weil die Tags verboten sind
			//$teil = closetags($teil,'`c`i`b');
					
			$cleartitle = strtolower(preg_replace("/`./","",$teil));
			foreach ($titles AS $this) {
				if (strtolower($this[0])==$cleartitle || strtolower($this[1])==$cleartitle) {
					$falsetitle = true;
					break;
				}
			}
		}
	}
	// Schauen, ob der neue Titel nich mehr als 25 Zeichen hat
	if (strlen($teil)>25) {
		output("Du hast dir zwar einen neuen Titel verdient, aber so lang muss er ja nun wirklich nicht sein.");
		output("`n`n<a href='lodge.php?op=titeel1'>Lass es mich nochmal probieren</a>",true); 
		addnav("","lodge.php?op=titeel1"); 
	}
	elseif ($falsetitle) {
		output('Diesen Titel hast du nicht verdient. Bitte w�hle einen eigenen.');
		output("`n`n<a href='lodge.php?op=titeel1'>Lass es mich nochmal probieren</a>",true); 
		addnav("","lodge.php?op=titeel1"); 		
	}
	else {
	$n=$session[user][name];
	if ($session[user][ctitle]==""){
		$neu=$teil.substr($n,strlen($session[user][title]));
	} else {
		$neu=$teil.substr($n,strlen($session[user][ctitle]));
	}
	output("Dein neuer Titel soll $teil`0 sein, dein Name also $neu`0 ?");
	if ($_POST['teil']=="") {
		// $teil=$session[user][title];
		 $output.="<form action=\"lodge.php?op=titeel3\" method='POST'><input type='hidden' name='teil' value=\"\"><input type='submit' value='Ja' class='button'>, �ndere meinen Titel zur�ck auf $teil f�r 50 Punkte.</form>"; 
	} else {
	//	$teil=stripslashes($_POST['teil']);
		 $output.="<form action=\"lodge.php?op=titeel3\" method='POST'><input type='hidden' name='teil' value=\"$teil\"><input type='submit' value='Ja' class='button'>, �ndere meinen Titel auf $teil f�r 50 Punkte.</form>"; 
	}
        	output("`n`n<a href='lodge.php?op=titeel1'>Nein, lass es mich nochmal probieren</a>",true); 
        	addnav("","lodge.php?op=titeel1"); 
        	addnav("","lodge.php?op=titeel3"); 
}
} else if ($_GET['op']=="titeel3"){
	addnav("Zur�ck zur Lodge","lodge.php");
	if ($pointsavailable>=50){ 
        //		$news = "`&{$session['user']['name']}`^ ist nun bekannt als `^";
		$teil=stripslashes($_POST['teil']);
		$teil = preg_replace("/`[^123456789!@#\$%^&QqRrVvGgTt]/","",$teil);
		// Anf�hrungszeichen machen nur Probleme...
		$teil = str_replace('\'','',$teil);
		$teil = str_replace('"','',$teil);
		
		// Titel nicht leer, aber auch nix regul�res drin?
		if(trim(preg_replace('/`./','',$teil))=='') {
			$teil=$session[user][title];
			$_POST['teil'] = '';
		}

		// Offene Tags zumachen
		// nicht mehr n�tig, weil die Tags verboten sind
		//$teil = closetags($teil,'`c`i`b');
		
		// Schauen, ob der neue Titel nich mehr als 25 Zeichen hat
		if (strlen($teil)>25) {
			output("Du hast dir zwar einen neuen Titel verdient, aber so lang muss er ja nun wirklich nicht sein.");
			output("`n`n<a href='lodge.php?op=titeel1'>Lass es mich nochmal probieren</a>",true); 
			addnav("","lodge.php?op=titeel1"); 
		}
		else {			
			$news = "`&{$session['user']['name']}`^ ist nun bekannt als `^";
		$n=$session[user][name];
		if ($session[user][ctitle]==""){
			$neu=$teil.substr($n,strlen($session[user][title]));
		} else {
			$neu=$teil.substr($n,strlen($session[user][ctitle]));
		}
 		$session['user']['donationspent']+=50;
		if ($teil>""){
			$session[user][name]=$neu;
			$session[user][ctitle]=$teil;
		}else{
			if ($session[user][ctitle]==""){
				$neu2=substr($n,strlen($session[user][title]));
			} else {
				$neu2=substr($n,strlen($session[user][ctitle]));
			}
			$session[user][name]="".$session[user][title]." $neu2";
			$session[user][ctitle]="";
		} 
        		$news.="{$session['user']['name']}`&!"; 
        		addnews($news); 
        		output("Gratulation, dein neuer Name ist jetzt  {$session['user']['name']}`0!`n`n"); 
}
    	}else{ 
        		output("Den Titel zu �ndern kostet 50 Punkte, aber du hast nur $pointsavailable Punkte."); 
    	} 

}elseif ($_GET['op']=="namechange"){ 
    	addnav("Zur�ck zur Lodge","lodge.php"); 
    	output("`bNamens Farbe �ndern`b`n`n"); 
    	if ($config['namechange']==1){ 
        		output("Da du schon vorher viele Punkte f�r die Farb�nderung gegeben hast kostet es dich diesmal nur 25 Punkte ."); 
    	}else{ 
        		output("Da es deine erste Farb�nderung ist kostet es dich 300 Punkte . Beim n�chsten Wechsel fallen nur 25 Punkte Kosten an"); 
    	} 
    	output("`n`nDein ge�nderter Name muss der selbe Name sein wie vor der Farb�nderung, nur dass er jetzt die Farbcodes enthalten darf.`n`n"); 
      	$n = $session[user][name]; 
	if ($session[user][ctitle]=="") {
        		//$x = strpos($n,$session[user][title])+1; 
        		//$regname=str_replace("`0","",trim(substr($n,$x+strlen($session[user][title])))); 
		        		$regname = str_replace(array($session['user']['title'].' ','`0'),'',$n);
 	} else {
        		//$x = strpos($n,$session[user][ctitle])+1; 
        		//$regname=str_replace("`0","",trim(substr($n,$x+strlen($session[user][ctitle])))); 
        		$regname = str_replace(array($session['user']['ctitle'].' ','`0'),'',$n);
 	}
    	output("Deine Name bisher ist: "); 
    	$output.=$regname; 
    	output(", und so wird er aussehen: $regname"); 
    	output("`n`n`0Wie soll dein Name aussehen ?`n"); 
    	$output.="<form action='lodge.php?op=namepreview' method='POST'><input name='newname' value=\"".HTMLEntities($regname)."\" size=\"30\" maxlength=\"30\"> <input type='submit' value='Vorschau'></form>"; 
    	addnav("","lodge.php?op=namepreview"); 
}elseif ($_GET['op']=="namepreview"){ 
    	addnav("Zur�ck zur Lodge","lodge.php"); 
    	$n = $session[user][name]; 
	if ($session[user][ctitle]=="") {
		        		//$x = strpos($n,$session[user][title])+1;
		        		//$regname=str_replace("`0","",trim(substr($n,$x+strlen($session[user][title]))));
		        		$regname = str_replace(array($session['user']['title'].' ','`0'),'',$n);
		 	} else {
		        		//$x = strpos($n,$session[user][ctitle])+1;
		        		//$regname=str_replace("`0","",trim(substr($n,$x+strlen($session[user][ctitle]))));
		        		$regname = str_replace(array($session['user']['ctitle'].' ','`0'),'',$n);
 	}
 	//		$comp1 = strtolower($session['user']['login']);
    	$_POST['newname']=str_replace("`0","",$_POST['newname']); 
    	$comp1 = strtolower(preg_replace("/[`][123456789!@#$%^&QqRrVvGgTt]/","",$regname)); // no black, no background colors
    	$comp2 = strtolower(preg_replace("/[`][123456789!@#$%^&QqRrVvGgTt]/","",$_POST['newname'])); 
    	//$output.="[$comp1] compared to [$comp2]"; 
    	if ($comp1!=$comp2) $msg.="Dein neuer Name muss genau so bleiben, wie dein alter Name. Du kannst die Gross-/Kleinschreibung �ndern, Farbcodes entfernen oder hinzuf�gen, aber ansonsten muss alles so bleiben. Du w�hlst {$_POST['newname']}`0`n"; 
    	if (strlen($_POST['newname'])>30) $msg.="Dein neuer name ist zu lang, inklusive Farbcodes darf er nicht l�nger als 30 Zeichen sein.`n"; 
    	$colorcount=0; 
    	for ($x=0;$x<strlen($_POST['newname']);$x++){ 
    		if (substr($_POST['newname'],$x,1)=="`"){ 
            			$x++; 
            			$colorcount++; 
        		} 
    	} 
    	if ($colorcount>getsetting("maxcolors",10)){ 
        		$msg.="Du hast zu viele Farben in deinem Namen benutzt. Du kannst maximal ".getsetting("maxcolors",10)." Farbcodes benutzen.`n"; 
    	} 
    	if ($msg==""){ 
        		output("Deine Name wird so aussehen: {$_POST['newname']}`n`n`0Ist es das was du willst?`n`n"); 
        		$p = ($config['namechange']==1?25:300); 
        		$output.="<form action=\"lodge.php?op=changename\" method='POST'><input type='hidden' name='name' value=\"".HTMLEntities($_POST['newname'])."\"><input type='submit' value='Ja' class='button'>, �ndere meinen Namen auf ".appoencode("{$_POST['newname']}`0")." f�r $p Punkte.</form>"; 
        		output("`n`n<a href='lodge.php?op=namechange'>Nein, lass es mich nochmal probieren</a>",true); 
        		addnav("","lodge.php?op=namechange"); 
        		addnav("","lodge.php?op=changename"); 
    	}else{ 
        		output("`bFalscher Name`b`n$msg"); 
        		output("`n`nDeine Name bisher ist: "); 
        		$output.=$regname; 
        		output("`0, und wird so aussehen $regname"); 
        		output("`n`nWie soll dein Name aussehen?`n"); 
        		$output.="<form action='lodge.php?op=namepreview' method='POST'><input name='newname' value=\"".HTMLEntities($regname)."\"size=\"30\" maxlength=\"30\"> <input type='submit' value='Vorschau'></form>"; 
        		addnav("","lodge.php?op=namepreview"); 
    	} 
}elseif ($_GET['op']=="immun"){
	if ($session['user']['pvpflag']=="5013-10-06 00:42:00"){
		output("J. C. Petersen nickt dir zu und gibt dir zu verstehen, dass du noch immer unter seinem Schutz stehst.");
	} elseif ($session['user']['pvpflag']=="1986-10-06 00:42:00") {
		output("J. C. Petersen zeigt dir einen Vogel und macht dir sehr schnell klar, dass er vorerst nichts mehr f�r dich tun kann. Er kann niemanden sch�tzen, der selbst mordend durchs Land zieht.");
	}else{
	 	output("Du fragst J. C. Petersen, ob er deinen Aufenthaltsort vor herumstreifenden Dieben und M�rdern verbergen kann.");
		output(" Er nickt und verspricht dir, dass dir f�r die Kleinigkeit von 300 Punkten niemand mehr ein Haar kr�mmen wird. Er wird auch mit Dag Durnick reden. Allerdings kann er f�r nichts mehr garantieren, wenn du selbst einen Mord begehst!`n`n");
		output("300 Punkte f�r permanente PvP Immunit�t ausgeben?`n(Die Immunit�t verf�llt, sobald du selbst PvP machst, oder ein Kopfgeld auf jemanden aussetzt und kann dann `bnicht`b mehr so schnell erneuert werden!)");
    		addnav("Immunit�t best�tigen?"); 
    		addnav("JA","lodge.php?op=immunconfirm");
	} 
  	addnav("Zur�ck zur Lodge","lodge.php"); 
}elseif ($_GET['op']=="immunconfirm"){ 
    	if ($pointsavailable>=300){ 
        		output("J. C. Petersen nutzt seinen Einfluss, um dich f�r PvP-Spieler unangreifbar zu machen. Es kann auch kein (weiteres) Kopfgeld auf dich ausgesetzt werden.`nDenke daran, dass du nur so lange gesch�tzt bist, bis du selbst jemanden angreifst, oder jemanden auf Dag's "); 
		output(" Kopfgeldliste setzt. Tust du das, kann selbst Petersen dir in Zukunft nicht mehr helfen.");
        		$session[user][pvpflag]="5013-10-06 00:42:00"; 
        		$session['user']['donationspent']+=300; 
    	}else{
		output("Du hast nicht genug Punkte!");
	}

}elseif ($_GET['op']=="changename"){ 
    	$p = ($config['namechange']==1?25:300); 
    	if ($pointsavailable>=$p){ 
        		$session['user']['donationspent']+=$p; 
        		$config['namechange']=1; 
        		$news = "{$session['user']['name']}`^ ist nun bekannt als `0";
		if ($session[user][ctitle]=="") {
			$session['user']['name']=$session['user']['title']." ".$_POST['name']."`0"; 
		} else {
			$session['user']['name']=$session['user']['ctitle']." ".$_POST['name']."`0"; 
		}
    		$news.="{$session['user']['name']}`^!"; 
     		addnews($news); 
     		output("Gratulation, dein neuer Name ist jetzt  {$session['user']['name']}`0!`n`n"); 
    	}else{ 
        		output("Eine Farb�nderung kostet $p Punkte, aber du hast nur $pointsavailable Punkte."); 
    	} 
    	addnav("Zur�ck zur Lodge","lodge.php"); 
}elseif ($_GET['op']=="keys1"){
	$sql = "SELECT * FROM items WHERE owner=0 AND class='Schl�ssel' AND value1=".$session[user][house]." ORDER BY id ASC";
	$result = db_query($sql) or die(db_error(LINK));
	if (db_num_rows($result)){
		output("`b`c`&Verlorene Schl�ssel:`c`b<table cellpadding=2 align='center'><tr><td>`bNr.`b</td><td>`bAktion`b</td></tr>",true);
		for ($i=0;$i<db_num_rows($result);$i++){
			$row = db_fetch_assoc($result);
			$bgcolor=($i%2==1?"trlight":"trdark");
			output("<tr class='$bgcolor'><td align='center'>$row[value2]</td><td><a href='lodge.php?op=keys2&id=$row[id]'>Ersetzen (10 Punkte)</a></td></tr>",true);
			addnav("","lodge.php?op=keys2&id=$row[id]");
		}
		output("</table>",true);
	}else{
		output("Der Schl�sselsatz f�r dein Haus ist komplett. Willst du einen zus�tzlichen Schl�ssel f�r 50 Punkte kaufen?");
		addnav("Zus�tzlicher Schl�ssel (50 Punkte)","lodge.php?op=keys2&id=new");
	}
	addnav("Zur�ck zur Lodge","lodge.php");
}elseif ($_GET['op']=="keys2"){
	if ($_GET[id]=="new"){
		output("`b50`b ");
	}else{
		output("`b10`b ");
	}
	output("Punkte f�r diesen Schl�ssel ausgeben?");
    	addnav("Schl�sselkauf best�tigen?"); 
    	addnav("JA","lodge.php?op=keys3&id=".$_GET[id]."");
	addnav("Zur�ck zur Lodge","lodge.php");
}elseif ($_GET['op']=="keys3"){
	if ($_GET[id]=="new"){
		if ($pointsavailable<50){
			output("Du hast nicht genug Punkte �brig.");
		}else{
			$sql = "SELECT * FROM items WHERE class='Schl�ssel' AND value1=".$session[user][house]." ORDER BY id ASC";
			$result = db_query($sql) or die(db_error(LINK));
			$nummer=db_num_rows($result)+1;
			db_free_result($result);
			$sql="INSERT INTO items (name,owner,class,value1,value2,gold,gems,description) VALUES ('Hausschl�ssel',".$session[user][acctid].",'Schl�ssel',".$session[user][house].",$nummer,0,0,'Schl�ssel f�r Haus Nummer ".$session[user][house]."')";
			db_query($sql) or die(db_error(LINK));
			$session['user']['donationspent']+=50;
			output("Du hast jetzt `b$nummer`b Schl�ssel f�r dein Haus! �berlege gut, an wen du sie vergibst.");
		}
	}else{
		if ($pointsavailable<10){
			output("Du hast nicht genug Punkte �brig.");
		}else{
			$nummer=$_GET[id];
			$sql="UPDATE items SET owner=".$session[user][acctid].",hvalue=0 WHERE id=$nummer";
			db_query($sql);
			$session['user']['donationspent']+=10;
			output("Der Schl�ssel wurde ersetzt.");
		}
	}
	addnav("Zur�ck zur Lodge","lodge.php");
}

$session['user']['donationconfig'] = serialize($config); 

page_footer(); 

?>