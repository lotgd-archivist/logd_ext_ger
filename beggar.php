<?php

// 22072004

/*
- Beggar-Script by LionSource.com - ThunderEye
- made for LoGD 0.9.6 but should be work with newer versions
ALTER TABLE `accounts` ADD `gotfreegold` TINYINT(1) DEFAULT '0' NOT NULL ;
add "paidgold" in table "settings" and set "value" to 1
"gotfreegold"=>"Freigold genommen,bool",  - in user.php
"paidgold"=>"Gold das in Bettlergasse spendiert wurde (Wert-1),int", - in configuration.php

�nderungen by anpera:
- statt gotfreegold einzuf�hren, wird das von den empfangbaren �berweisungen abgezogen.
- Wert -1 entfernt
- Bild entfernt
- F�r 0.9.7 ext (GER) angepasst
*/

require_once "common.php";

page_header("Bettelstein");

if ($HTTP_GET_VARS[op]=="spenden"){
	output("`0Von dem Elend am Bettelstein deprimiert, l�sst du dich vor dem magischen Stein mit der blauen Aura nieder. Wild entschlossen, der Armut entgegen zu wirken, planst du Gold f�r die Bed�rftigen zu spenden.`n`nJeder Verarmte kann dann von diesem Stein etwas Gold entnehmen.`n");
	addnav("Zur�ck zum Dorfplatz","village.php");
	output("<form action='beggar.php?op=spenden2' method='POST'>`)Du spendest <input name='goldspende' id='goldspende' size='5' value='".$session[user][gold]."'> `^Goldst�cke`) f�r die Bed�rftigen.`n`n",true);
	output("<input type='submit'value='Spendieren'></form>",true);
	output("<script language='javascript'>document.getElementById('goldspende').focus();</script>",true);
	addnav("","beggar.php?op=spenden2");

	// $goldsumme=getsetting("paidgold",0)-1;

}else if ($HTTP_GET_VARS[op]=="spenden2"){
	$goldsumme = abs((int)$_POST['goldspende']);
	if ($session[user][gold]<$goldsumme){
		output("`)Du verf�gst nicht �ber ausreichend Gold, um eine derartige Summe zu spenden.`nVersuche es erneut.");
		addnav("Zur�ck zum Stein","beggar.php");
	}else if ($goldsumme==0){
		output("`)Du legst `^0 Goldst�cke`) auf den Stein und bist verwundert, warum keiner reagiert. Hoppla, das war wohl nichts, versuche es erneut.");
		addnav("Zur�ck zum Stein","beggar.php");
	}else if (getsetting("paidgold","0")+$goldsumme>25000){
		output("`)Du legst `^$goldsumme Goldst�cke`) auf den Stein, aber nichts passiert. Scheinbar ist der Stein voll, wenn ein Stein �berhaupt irgendwie voll sein kann. Entt�uscht nimmst du dein Gold wieder an dich.");
		addnav("Zur�ck zum Stein","beggar.php");	
	}else if ($goldsumme<=10){
		output("`)Du hast `^$goldsumme Gold`) gespendet. Wow, damit wirst du eine Menge Bettler gl�cklich machen...");
		if (e_rand(1,10)==2){
			output("`n`n`&Du verlierst einen Charmepunkt!`0");
			$session[user][charm]-=1;
		}
		addnav("Zur�ck zum Stein","beggar.php");
		savesetting("paidgold",getsetting("paidgold","0")+$goldsumme);
		$session[user][gold]-=$goldsumme;
	}else if ($goldsumme<$session[user][level]*2){
		output("`)Eine Spende f�r die Armen sollte mindestens das Doppelte deines Levels (`^".($session[user][level]*2)." Goldst�cke`)) betragen, sonst nimmt es niemand wahr.");
		addnav("Zur�ck zum Stein","beggar.php");
	}else{
		output("`0Eine Welle der Begeisterung schwappt durch die Bettlergasse. Du hast `^$goldsumme Goldst�cke`0 gespendet und erntest von allen Betroffenen ein L�cheln!`n`)Nun k�nnen sich die Bed�rftigen an dem Gold erfreuen.");
		addnav("Zur�ck zum Stein","beggar.php");
			if ($goldsumme>=$session[user][level]*150 && e_rand(1,5)==2){
				output("`n`n`^Du erh�ltst einen Charmepunkt! `0");
				$session[user][charm]++;
			}
		savesetting("paidgold",getsetting("paidgold","0")+$goldsumme);
		$session[user][gold]-=$goldsumme;
		$sql = "INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),'beggar',".$session[user][acctid].",\"/me hat `^$goldsumme Goldst�cke`& auf dem Spenden-Stein hinterlegt!\")";
		db_query($sql) or die(db_error(LINK));
	}

}else if ($HTTP_GET_VARS[op]=="goldnehmen"){
	$goldsumme=getsetting("paidgold","0");
	$golduser=round(($session[user][level]*getsetting("transferperlevel",25))/getsetting("transferreceive",3));
	$transleft = getsetting("transferreceive",3) - $session[user][transferredtoday];
	if ($transleft<=0){
		output("`n`n`)Du trittst an den Spenden-Stein und h�ltst die H�nde auf. Der Stein beginnt zu gl�hen und du bemerkst, dass du gescannt wirst. Doch statt Gold erscheint nur eine Meldung:`n`n`3Name: `#".$session[user][name]."`n`3Gold erhalten: `^".$session[user][transferredtoday]."x`n`3Status: `#keine �bereinstimmung mit einer verarmten Person`n`n`3Zugriff auf die Goldreserven verweigert.");
		addnav("Zur�ck zum Dorfplatz","village.php");
	}else{

	if (getsetting("paidgold","0")<1){
		addnav("Zur�ck zum Stein","beggar.php");
		output("`n`n`)Du trittst an den Spenden-Stein und m�chtest etwas Gold wegnehmen. Zu deiner Entt�uschung musst du jedoch feststellen, dass da kein Gold mehr ist, was du nehmen k�nntest. Das n�chste Mal solltest du schneller sein.");
	}else if ($session[user][gold]>=$session[user][level]*750){
		output("`n`n`)Du trittst an den Spenden-Stein und h�ltst die H�nde auf. Der Stein beginnt zu gl�hen und du bemerkst, dass du gescannt wirst. Doch statt Gold erscheint nur eine Meldung:`n`n`3Name: `#".$session[user][name]."`n`3Gold: `^".$session[user][gold]."`# in der Hand`n`3Status: `#keine �bereinstimmung mit einer verarmten Person`n`n`3Zugriff auf die Goldreserven verweigert.");
		addnav("Zur�ck zum Dorfplatz","village.php");
	}else if ($session[user][goldinbank]>=$session[user][level]*750){
		output("`n`n`)Du trittst an den Spenden-Stein und h�ltst die H�nde auf. Der Stein beginnt zu gl�hen und du bemerkst, dass du gescannt wirst. Doch statt Gold erscheint nur eine Meldung:`n`n`3Name: `#".$session[user][name]."`n`3Gold: `^".$session[user][goldinbank]."`# auf der Bank`n`3Status: `#keine �bereinstimmung mit einer verarmten Person`n`n`3Zugriff auf die Goldreserven verweigert.");
		addnav("Zur�ck zum Dorfplatz","village.php");
	}else if (($session[user][goldinbank]+$session[user][gold])>=$session[user][level]*750){
		output("`n`n`)Du trittst an den Spenden-Stein und h�ltst die H�nde auf. Der Stein beginnt zu gl�hen und du bemerkst, dass du gescannt wirst. Doch statt Gold erscheint nur eine Meldung:`n`n`3Name: `#".$session[user][name]."`n`3Gold: `^".$session[user][gold]."`# in der Hand und `^".$session[user][goldinbank]."`# auf der Bank, das macht `^".($session[user][gold]+$session[user][goldinbank])."`# insgesamt`n`3Status: `#keine �bereinstimmung mit einer verarmten Person`n`n`3Zugriff auf die Goldreserven verweigert.");
		addnav("Zur�ck zum Dorfplatz","village.php");
	}else if (($session[user][weapondmg]>=15) && ($session[user][armordef]>=15)){
		output("`n`n`)Du trittst an den Spenden-Stein und h�ltst die H�nde auf. Der Stein beginnt zu gl�hen und du bemerkst, dass du gescannt wirst. Doch statt Gold erscheint nur eine Meldung:`n`n`3Name: `#".$session[user][name]."`n`3Ausr�stung: `#".$session[user][weapon]." und ".$session[user][armor]."`n`3Status: `#keine �bereinstimmung mit einer verarmten Person`n`n`3Zugriff auf die Goldreserven verweigert.");
		addnav("Zur�ck zum Dorfplatz","village.php");
	}else if ($session[user][gems]>=$session[user][level]){
		output("`n`n`)Du trittst an den Spenden-Stein und h�ltst die H�nde auf. Der Stein beginnt zu gl�hen und du bemerkst, dass du gescannt wirst. Doch statt Gold erscheint nur eine Meldung:`n`n`3Name: `#".$session[user][name]."`n`3Edelsteine: `^".$session[user][gems]."`# in der Hand`n`3Status: `#keine �bereinstimmung mit einer verarmten Person`n`n`3Zugriff auf die Goldreserven verweigert.");
		addnav("Zur�ck zum Dorfplatz","village.php");
	}else if ($session[user][housekey]>0){
		output("`n`n`)Du trittst an den Spenden-Stein und h�ltst die H�nde auf. Der Stein beginnt zu gl�hen und du bemerkst, dass du gescannt wirst. Doch statt Gold erscheint nur eine Meldung:`n`n`3Name: `#".$session[user][name]."`n`3Besitzt Haus Nummer: `^".$session[user][house]."`#`n`3Status: `#keine �bereinstimmung mit einer verarmten Person`n`n`3Zugriff auf die Goldreserven verweigert.");
		addnav("Zur�ck zum Dorfplatz","village.php");
	}else if ($goldsumme<$golduser){
		$golduser=$goldsumme;
		output("`n`n`)Mit einem beherzten Griff schnappst du dir das Gold von dem Stein. Nichts zu knapp, denn es waren nur noch `^$goldsumme Goldst�cke`) �brig.");
		addnav("Zur�ck zum Stein","beggar.php");
		$session[user][gold]+=$golduser;
		savesetting("paidgold",strval(getsetting("paidgold","0")-$golduser));
 		$session[user][transferredtoday]--;
	}else{
		output("`n`n`)Du trittst an den Spenden-Stein und h�ltst die H�nde auf. Der Stein beginnt zu gl�hen und du bemerkst, dass du gescannt wirst. Vor dir materialisiert sich ein H�ufchen Gold. Voller Dankbarkeit an den Spender, nimmst du die bereitgelegten `^$golduser Goldst�cke`) weg und gehst deines Weges.");
		addnav("Zur�ck zum Stein","beggar.php");
		$session[user][gold]+=$golduser;
		savesetting("paidgold",strval(getsetting("paidgold","0")-$golduser));
 		$session[user][transferredtoday]--;
	}
	}

}else{
	addcommentary();
	output("`)Hier lungern verarmte Helden aller R�nge herum, die offenbar nicht wissen, dass man im Wald selber Gold verdienen kann, um sich der niveaulosesten aller Sachen herzugeben - betteln.`nIn einer Nische in dem magischen Felsen k�nnen Goldm�nzen deponiert werden, die den armen Helden zugute kommen.");
	$goldsumme=getsetting("paidgold","0");
	if (getsetting("paidgold","0")<1){
		addnav("Gold spenden","beggar.php?op=spenden");
	}else if ($session[user][transferredtoday]>=getsetting("transferreceive",3)){
		output("`n`n`0Es liegen noch `^$goldsumme Goldst�cke`0 auf dem Spenden-Stein. Da du heute schon genug Gold in Empfang genommen hast, darfst du jedoch nichts mehr davon nehmen.");
		addnav("Gold spenden","beggar.php?op=spenden");
	}else if (getsetting("paidgold","0")>0){
	$golduser=round(($session[user][level]*getsetting("transferperlevel",25))/getsetting("transferreceive",3));
		if ($goldsumme<$golduser){
			$golduser=$goldsumme;
			addnav("$golduser Gold wegnehmen","beggar.php?op=goldnehmen");
			addnav("Gold spenden","beggar.php?op=spenden");
			output("`n`n`0Es liegen nur noch `^".(getsetting("paidgold","0"))." Goldst�cke`0 auf dem Spenden-Stein bereit, jetzt aber schnell.");
		}else{
		addnav("$golduser Gold wegnehmen","beggar.php?op=goldnehmen");
		addnav("Gold spenden","beggar.php?op=spenden");
		output("`n`n`0Es liegen noch `^".(getsetting("paidgold","0"))." Goldst�cke`0 auf dem Spenden-Stein bereit.");
		}
	}
	output("`n`n`)Hier verliert kaum einer ein Wort, es wird nur gebettelt oder gedankt:`n");
	viewcommentary("beggar","Betteln",10,"bettelt");
	addnav("Zur�ck zum Dorfplatz","village.php");
}

page_footer();
?>