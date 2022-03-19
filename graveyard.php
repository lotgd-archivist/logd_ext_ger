<?php

// 15082004

require_once "common.php";

if ($session['user']['alive']) redirect("village.php");

page_header("Der Friedhof");
checkday();
$session['bufflist']=array();
$session['user']['drunkenness'] = 0;
$max = $session['user']['level'] * 5 + 50;
$favortoheal = round(10 * ($max-$session['user']['soulpoints'])/$max);

if ($_GET['op']=="search"){
	if ($session['user']['gravefights']<=0){
		output("`\$`bDeine Seele kann keine weiteren Qualen in diesem Nachleben mehr ertragen.`b`0");
		$_GET['op']="";
	}else{
		$session['user']['gravefights']--;
  		$battle=true;
  		$sql = "SELECT * FROM creatures WHERE location=1 ORDER BY rand(".e_rand().") LIMIT 1";
		$result = db_query($sql) or die(db_error(LINK));
		$badguy = db_fetch_assoc($result);
		$level = $session['user']['level'];
		$shift = 0;
		if ($level < 5) $shift = -1;
		$badguy['creatureattack'] = 9 + $shift + (int)(($level-1) * 1.5);
		// Make graveyard creatures easier.
		$badguy['creaturedefense'] = (int)((9 + $shift + (($level-1) * 1.5)) * .7);
		$badguy['creaturehealth'] = $level * 5 + 50;
		$badguy['creatureexp'] = e_rand(10 + round($level/3),20 + round($level/3));
		$badguy['creaturelevel'] = $level;
		//output("`#DEBUG: Creature level: {$badguy['creaturelevel']}`n");
		//output("`#DEBUG: Creature attack: {$badguy['creatureattack']}`n");
		//output("`#DEBUG: Creature defense: {$badguy['creaturedefense']}`n");
		//output("`#DEBUG: Creature health: {$badguy['creaturehealth']}`n");
		//output("`#DEBUG: Creature exp: {$badguy['creatureexp']}`n");
		$session['user']['badguy']=createstring($badguy);
	}
}
if ($HTTP_GET_VARS[op]=="fight" || $HTTP_GET_VARS[op]=="run"){
	if ($_GET['op']=="run"){
		if (e_rand(0,2)==1) {
			output("`\$Ramius`) verflucht dich f�r deine Feigheit.`n`n");
			$favor = 5 + e_rand(0, $session['user']['level']);
			if ($favor > $session['user']['deathpower'])
				$favor = $session['user']['deathpower'];
			if ($favor > 0) {
				output("`)Du hast `^$favor`) Gefallen bei `\$Ramius VERLOREN`).");
				$session['user']['deathpower']-=$favor;
			}
			addnav("Zur�ck zum Friedhof","graveyard.php");
			$session[user][reputation]--;
		} else {
			output("`)Als du zu fliehen versuchst, wirst du zum Kampf zur�ckberufen!`n`n");
			$battle=true;
		}
	} else {
		$battle = true;
	}
}

if ($battle){
	//make some adjustments to the user to put them on mostly even ground with the undead guy.
	$originalhitpoints = $session['user']['hitpoints'];
	$session['user']['hitpoints'] = $session['user']['soulpoints'];
	$originalattack = $session['user']['attack'];
	$originaldefense = $session['user']['defence'];
	$session['user']['attack'] = 10 + round(($session['user']['level'] - 1) * 1.5);
	$session['user']['defence'] = 10 + round(($session['user']['level'] - 1) * 1.5);
	include("battle.php");
	//reverse those adjustments, battle calculations are over.
	$session['user']['attack'] = $originalattack;
	$session['user']['defence'] = $originaldefense;
	$session['user']['soulpoints'] = $session['user']['hitpoints'];
	$session['user']['hitpoints'] = $originalhitpoints;
	if ($victory) {
		output("`b`&{$badguy['creaturelose']}`0`b`n"); 
		output("`b`\$Du hast {$badguy['creaturename']} erniedrigt!`0`b`n");
		output("`#Du bekommst `^{$badguy['creatureexp']}`# Gefallen bei `\$Ramius`#!`n`0");
		$session['user']['deathpower']+=$badguy['creatureexp'];
		$badguy=array();
		$_GET['op']="";
		if (e_rand(1,7)==3) addnav("Fluss der Seelen","styx.php");
	}else{
		if ($defeat){
			//addnav("Zur�ck zu den Schatten","shades.php");
			$sql = "SELECT taunt FROM taunts ORDER BY rand(".e_rand().") LIMIT 1";
			$result = db_query($sql) or die(db_error(LINK));
			$taunt = db_fetch_assoc($result);
			$taunt = str_replace("%s",($session[user][sex]?"sie":"ihn"),$taunt[taunt]);
			$taunt = str_replace("%o",($session[user][sex]?"sie":"er"),$taunt);
			$taunt = str_replace("%p",($session[user][sex]?"ihre(m/r)":"seine(r/m)"),$taunt);
			$taunt = str_replace("%x",($session[user][weapon]),$taunt);
			$taunt = str_replace("%X",$badguy[creatureweapon],$taunt);
			$taunt = str_replace("%W",$badguy[creaturename],$taunt);
			$taunt = str_replace("%w",$session[user][name],$taunt);
			
			addnews("`)".$session[user][name]."`) wurde auf dem Friedhof von {$badguy['creaturename']} erniedrigt.`n$taunt");
			output("`b`&Du wurdest von `%{$badguy['creaturename']} `&erniedrigt!!!`n");
			output("Du kannst heute keine weiteren Seelen mehr qu�len.");
			$session['user']['donation']+=1;
			$session['user']['gravefights']=0;
			addnav("F?Zur�ck zum Friedhof","graveyard.php");
		}else{
			addnav("Q?Qu�len","graveyard.php?op=fight");
			addnav("F?Fliehen","graveyard.php?op=run");
			if (getsetting("autofight",0)){
				addnav("AutoFight");
				addnav("5 Runden qu�len","graveyard.php?op=fight&auto=five");
				addnav("Bis zum bitteren Ende","graveyard.php?op=fight&auto=full");
			}
		}
	}
}

if ($_GET['op']==""){
	output("`)`c`bDer Friedhof`b`c");
	output("Dein Geist wandert auf einen einsamen mit Unkraut �berwucherten Friedhof. Die Pflanzen scheinen nach deinem Geist im Vorbeischweben zu greifen.
	Du bist umgeben von den �berresten alter Grabsteine. Einige liegen auf dem Gesicht, andere sind in St�cke zerbrochen. Fast kannst du das Wehklagen 
	der hier gefangenen Seelen h�ren.
	`n`nMitten im Friedhof steht ein altert�mliches Mausoleum, dem die Spuren ungez�hlter Jahre deutlich anzusehen sind. 
	Ein b�se schauender Steingargoyle ziert die Dachspitze; seine Augen scheinen dir zu folgen und sein aufklaffender Mund ist gespickt mit scharfen Steinz�hnen.
	Auf der Gedenktafel �ber der T�r ist zu lesen: `\$Ramius, Herr �ber den Tod`).");

	addnav("Etwas zum Qu�len suchen","graveyard.php?op=search");
	addnav("M?Mausoleum betreten","graveyard.php?op=enter");
	//addnav("Kriegerliste","list.php");
	addnav("Zur�ck zu den Schatten","shades.php");
}elseif ($_GET['op']=="enter"){
//	output("You enter the mausoleum and find yourself in a cold marble chamber.  no don't type that it's distracting.  Can't think, too much typing, stop, stoooppppp.  Now why couldn't you type verbatim a minute ago.  Ugh, sonofabitch.  The air is so, no the air is dammit.  The air within the chamber chils you to the bone?  You don't have bones, shit.  What'd I say before? Ugh.  Hahahaha. hahaha.  Hiccup.  Hiccup.  Hiccup.  Stop it, you're gonna kill me, stop stop!  Shit I forget what we actually stop that!  dammit.  Ok, you enter the mausoleum, and find yourself in a cold stark marble chamber.  The air surrounding you is chilled, no, is, the air around you carries the chill of death itself.  From the darkness two black eyes seem to stare in to your soul.  Within your mind you hear (dramatic pause) Whahahaha.  Within your mind you hear YOUR MOM.  That's what he would say.  Ok, within your mind you hear.  Ugh.  How are you gonna pick out the good crap in this, I mean, if I just keep talking, will you really just keep typing it?  Crap, you wrapped around, I didn't even know you could do that.  All right, where did we stop at ? Within something.  Within your mind you hear.  Within your mind a deep voice, no, um, a deep voice penetrates your mind.  Period.  Now whatever Ramius says.  What's he say, what'd you have him say before?  I am Ramius, your mom.  Why have you come here, no, I know no, allright, lemme think.  Ok, this'll be like text, so I don't know if it will be in those quotation mark things.  Allright.  Your mortal coil has forsaken you.  Now you turn to me.  To prove your worth in death go out amongst the ugh.  Go out amongst the... We need a word for, cause the other things are clearly dead, go out amongst.  Hmm.  Meow.  Don't type that.  Ugh, hehehe.  hahahaha.  Stop it, you're gonna, stop it.  all right.  Go out and find those that have eluded my grasp.  Send their souls where yours has gone.  Then return to me to seek the favor you, no, to fi-- no.  And return to me, and I shall reward you well.  Period.  Oh well, yeah, you already put a period.  Holy crap, you wrapped around a second time?  Hehehehe.  Well.  I think at this point, we need to go through and get rid of all the crap that you typed and you didn't need to type.  I'm gonna talk so fast that you can't hj.  Bjwuhehe.  Bwahahaha.  Choahaha.  Heh, you ass.  I'm done, I don't have anything else to say.  Ugh.  I'm gonna go check my swimming skill.  Aah, look, I'm 95, I'm done.  Alright, I'm gonna go camp.  Are you still freaking typing what I'm saying?  ugh.  I am a stupid moron.  I have the ass of a flaming monkey.  Hehe.  I'm the one who ate the fart.  Look at you, look at the things you're typing.  No, cause I clearly said Choo, See eightch Ooh Ooh.  See eitch Ooh Ooh, what? I didn't say that.  Hehhehehehe.  Huh.  (evil glare)");
	output("`)`b`cDas Mausoleum`c`b");
	output("Du betrittst das Mausoleum und siehst dich in einer kalten, kahlen Kammer aus Marmor. Die Luft um dich herum tr�gt die K�lte des Todes selbst.
	Aus der Dunkelheit starren zwei schwarze Augen direkt in deine Seele. Ein feuchtkalter Griff scheint deine Seele zu umklammern und sie mit den Worten des Todesgottes `\$Ramius`) h�chstpers�nlich zu erf�llen.`n`n
	\"`0Dein sterblicher K�rper hat dich im Stich gelassen. Und jetzt wendest du dich an mich. Es gibt in diesem Land diejenigen, die sich meinem Griff entziehen konnten und ein Leben �ber das Leben hinaus besitzen. Um mir deinen Wert f�r mich zu beweisen 
	und dir Gefallen zu verdienen, gehe raus und qu�le deren Seelen. Solltest du mir genug Gefallen getan haben, werde ich dich belohnen.`)\"");
	addnav("Frage Ramius nach dem Wert deiner Seele","graveyard.php?op=question");
	addnav("S?Seele wiederherstellen ($favortoheal Gefallen)","graveyard.php?op=restore");
	
	addnav("F?Zur�ck zum Friedhof","graveyard.php");
}elseif ($_GET['op']=="restore"){
	output("`)`b`cDas Mausoleum`c`b");
	if ($session['user']['soulpoints']<$max){
		if ($session['user']['deathpower']>=$favortoheal){
			output("`\$Ramius`) nennt dich einen Schw�chling, weil du nach Wiederherstellung deiner Seele fragst. Aber da du genug Gefallen bei ihm gut hast, gibt er deiner Bitte zum Preis von `4$favortoheal`) Gefallen nach.");
			$session['user']['deathpower']-=$favortoheal;
			$session['user']['soulpoints']=$max;
		}else{
			output("`\$Ramius`) verflucht dich und wirft dich aus dem Mausoleum. Du mu�t ihm erst genug Gefallen getan haben, bevor er dir die Wiederherstellung deiner Seele gew�hrt.");
		}
	}else{
		output("`\$Ramius`) seufzt und murmelt etwas von \"`7Nur weil sie tot sind, hei�t das doch nicht, dass sie nicht zu denken brauchen, oder?`)\"`n`n");
		output("Vielleicht solltest du erstmal eine Wiederherstellung `in�tig`i haben, bevor du danach fragst.");
	}
	addnav("Frage Ramius nach dem Wert deiner Seele","graveyard.php?op=question");
	//addnav("Restore Your Soul ($favortoheal favor)","graveyard.php?op=restore");
	
	addnav("Zur�ck zum Friedhof","graveyard.php");
}elseif ($_GET['op']=="question"){
	if ($session['user']['deathpower']>=100) {
		output("`\$Ramius`) spricht: \"`7Du hast mich tats�chlich beeindruckt. Ich sollte dir die M�glichkeit gew�hren, deine Feinde in der Welt der Sterblichen zu besuchen.`)\"");
		addnav("Ramius' Gefallen");
		addnav("h?Feind heimsuchen (25 Gefallen)","graveyard.php?op=haunt");
		if ($session[user][reputation]<=-10) output(" Er weist dich noch darauf hin, dass er keinen Einfluss auf das Ged�chtnis der Lebenden - und besonders der H�ndler -  hat.");
		if ($session[user][reputation]<=-40) output("`n`n\"`7Wegen der Unehrenhaftigkeit deines Lebens kann ich dir nicht erlauben, vorzeitig zu den Lebenden zur�ckzukehren, obwohl du mir gute Dienste geleistet hast.`)\"");
		if ($session[user][reputation]>-40) addnav("e?Wiedererwecken (100 Gefallen)","newday.php?resurrection=true");
		addnav("5 Donationpoints (100 Gefallen)","graveyard.php?op=dona");
		addnav("Sonstiges");
	}elseif ($session['user']['deathpower'] >= 25){
		output("`\$Ramius`) spricht: \"`7Ich bin nicht wirklich beeindruckt von deinen Bem�hungen, aber einen kleinen Gefallen werde ich dir gew�hren. F�hre meine Arbeit fort und ich kann dir vielleicht mehr meiner Kraft anbieten.`)\""); 
		addnav("Ramius' Gefallen");
		addnav("h?Feind heimsuchen (25 Gefallen)","graveyard.php?op=haunt");
		addnav("Sonstiges");
	}else{
		output("`\$Ramius`) spricht: \"`7Ich bin von deinen Bem�hungen noch nicht beeindruckt. F�hre meine Arbeit fort und wir k�nnen weiter reden.`)\"");
		if (!$session['user']['prefs']['nosounds']) output("<embed src=\"media/lachen.wav\" width=10 height=10 autostart=true loop=false hidden=true volume=100>",true);
	}
	if ($session['user']['deathpower'] >= 1) addnav("Zeit bei den Schatten (1 Gefallen)","graveyard.php?op=time");
	output("`n`nDu hast `6{$session['user']['deathpower']}`) Gefallen bei `\$Ramius`).");
	addnav("Frage Ramius nach dem Wert deiner Seele","graveyard.php?op=question");
	addnav("S?Seele wiederherstellen ($favortoheal Gefallen)","graveyard.php?op=restore");
	
	addnav("Zur�ck zum Friedhof","graveyard.php");
}elseif ($_GET['op']=="dona"){
	output("`\$Ramius`)' Gel�chter l�sst den Boden erbeben. \"`7Du verzichtest f�r ein paar Punkte auf das Leben? Bitte, soll mir nur Recht sein.`)\" Mit diesen Worten gibt er deiner Bitte nach.`nDu bekommst 5 Donationpoints.");
	$session['user']['deathpower']-=100;
	$session['user']['donation']+=5; 
	addnav("Zur�ck zum Mausoleum","graveyard.php?op=enter");
	addnav("Zur�ck zum Friedhof","graveyard.php");
}elseif ($_GET['op']=="time"){
	$time = gametime();
	$tomorrow = strtotime(date("Y-m-d H:i:s",$time)." + 1 day");
	$tomorrow = strtotime(date("Y-m-d 00:00:00",$tomorrow));
	$secstotomorrow = $tomorrow-$time;
	$realsecstotomorrow = $secstotomorrow / getsetting("daysperday",4);
	output("`\$Ramius`) raunzt dir genervt entgegen, dass du noch `^".date("G\\h, i\\m, s\\s",strtotime("1980-01-01 00:00:00 + $realsecstotomorrow seconds"))."`) hier bleiben musst, wenn du nicht weiter f�r ihn arbeiten willst.");
	$session['user']['deathpower']-=1;
	addnav("Zur�ck zum Mausoleum","graveyard.php?op=enter");
	addnav("Zur�ck zum Friedhof","graveyard.php");
}elseif ($_GET['op']=="haunt"){
	output("`\$Ramius`)  ist von deinen Aktionen beeindruckt und gew�hrt dir die Macht, einen Feind heimzusuchen.`n`n");
	output("<form action='graveyard.php?op=haunt2' method='POST'>",true);
	addnav("","graveyard.php?op=haunt2");
	output("Wen willst du heimsuchen? <input name='name' id='name'> <input type='submit' class='button' value='Suchen'>",true);
	output("</form>",true);
	output("<script language='JavaScript'>document.getElementById('name').focus()</script>",true);
	addnav("Zur�ck zum Mausoleum","graveyard.php?op=enter");
}elseif ($_GET['op']=="haunt2"){
	$string="%";
	for ($x=0;$x<strlen($_POST['name']);$x++){
		$string .= substr($_POST['name'],$x,1)."%";
	}
	$sql = "SELECT login,name,level FROM accounts WHERE name LIKE '".addslashes($string)."' AND locked=0 ORDER BY level,login";
	$result = db_query($sql);
	if (db_num_rows($result)<=0){
		output("`\$Ramius`)  kann niemanden mit einem solchen Namen finden.");
	}elseif(db_num_rows($result)>100){
		output("`\$Ramius`) denkt, du solltest die Zahl derer, die du heimsuchen willst, etwas einschr�nken.");
		output("<form action='graveyard.php?op=haunt2' method='POST'>",true);
		addnav("","graveyard.php?op=haunt2");
		output("Wen willst du heimsuchen? <input name='name' id='name'> <input type='submit' class='button' value='Suchen'>",true);
		output("</form>",true);
		output("<script language='JavaScript'>document.getElementById('name').focus()</script>",true);
	}else{
		output("`\$Ramius`) wird dir gestatten, eine der folgenden Personen heimzusuchen:`n");
		output("<table cellpadding='3' cellspacing='0' border='0'>",true);
		output("<tr class='trhead'><td>Name</td><td>Level</td></tr>",true);
		for ($i=0;$i<db_num_rows($result);$i++){
			$row = db_fetch_assoc($result);
			output("<tr class='".($i%2?"trlight":"trdark")."'><td><a href='graveyard.php?op=haunt3&name=".HTMLEntities($row['login'])."'>",true);
			output($row['name']);
			output("</a></td><td>",true);
			output($row['level']);
			output("</td></tr>",true);
			addnav("","graveyard.php?op=haunt3&name=".HTMLEntities($row['login']));
		}
		output("</table>",true);
	}
	addnav("Frage Ramius nach dem Wert deiner Seele","graveyard.php?op=question");
	addnav("S?Seele wiederherstellen ($favortoheal Gefallen)","graveyard.php?op=restore");
	addnav("M?Zur�ck zum Mausoleum","graveyard.php?op=enter");
}elseif ($_GET['op']=="haunt3"){
	output("`)`c`bDas Mausoleum`b`c");
	$sql = "SELECT name,level,hauntedby,acctid FROM accounts WHERE login='{$_GET['name']}'";
	$result = db_query($sql);
	if (db_num_rows($result)>0){
		$row = db_fetch_assoc($result);
		if ($row['hauntedby']!=""){
			output("Diese Person wurde bereits heimgesucht. W�hle eine andere");
		}else{
			$session['user']['deathpower']-=25;
			$roll1 = e_rand(0,$row['level']);
			$roll2 = e_rand(0,$session['user']['level']);
			if ($roll2>$roll1){
				output("Du hast `7{$row['name']}`) erfolgreich heimgesucht!");
				$sql = "UPDATE accounts SET hauntedby='{$session['user']['name']}' WHERE login='{$_GET['name']}'";
				db_query($sql);
				addnews("`7{$session['user']['name']}`) hat `7{$row['name']}`) heimgesucht!");
				$session['user']['donation']+=1;
		 		systemmail($row['acctid'],"`)du wurdest heimgesucht","`)Du wurdest von {$session['user']['name']} heimgesucht"); 
			}else{
				addnews("`7{$session['user']['name']}`) hat erfolglos versucht, `7{$row['name']}`) heimzusuchen!");
				switch (e_rand(0,5)){
				case 0:
					output("Gerade als du `7{$row['name']}`) heimsuchen wolltest, versaut dir ein Niesen komplett den Erfolg.");
					break;
				case 1:
					output("Die Heimsuchung von `7{$row['name']}`) l�uft richtig gut. Leider schl�ft dein Opfer tief und fest und bekommt von deiner Anwesenheit absolut nichts mit.");
					break;
				case 2:
					output("Du machst dich zur Heimsuchung von `7{$row['name']}`) bereit, stolperst aber �ber deinen Geisterschwanz und landest flach auf deinem .... �hm ... Gesicht.");
					break;
				case 3:
					output("Du willst `7{$row['name']}`) im Schlaf heimsuchen, doch dein Opfer dreht sich nur im Bett um und murmelt etwas von 'nie wieder W�rstchen so kurz vor dem Schlafengehen'.");
					break;
				case 4:
					output("Du weckst `7{$row['name']}`) auf. Dein Opfer schaut dich kurz an, sagt \"Niedlich!\" und versucht dich in einem Einmachglas einzufangen.");
					break;
				case 5:
					output("Du versuchst `7{$row['name']}`) zu erschrecken, siehst dich dabei im Augenwinkel selbst im Spiegel und ger�tst in Panik, weil du einen Geist gesehen hast!");
					break;
				}
			}
		}
	}else{
		output("`\$Ramius`) kann sich nicht mehr auf diese Person konzentrieren. Du kannst sie jetzt nicht heimsuchen.");
	}
	addnav("Frage Ramius nach dem Wert deiner Seele","graveyard.php?op=question");
	addnav("S?Seele wiederherstellen ($favortoheal Gefallen)","graveyard.php?op=restore");
	addnav("M?Zur�ck zum Mausoleum","graveyard.php?op=enter");
}

page_footer();
?>
