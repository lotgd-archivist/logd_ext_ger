<?php

// 24072004

require_once "common.php";

page_header("Der Gr�ne Drachen!");
if ($HTTP_GET_VARS[op]==""){
  output("`\$Du erstickst jeden Drang zu fliehen und betrittst vorsichtig die H�hle. Du spekulierst ");
	output("darauf, den gro�en Drachen im Schlaf zu �berraschen, um ihn mit einem Minimum an eigenem Schmerz ");
	output("zu erlegen. Leider ist das nicht der Fall. Du biegst in der H�hle um eine Ecke ");
	output("und entdeckst das Riesenbiest, das mit den Hinterbeinen auf einem gewaltigen Haufen Gold sitzt und ");
	output("seine Z�hne mit etwas reinigt, das wie eine Rippe aussieht.");
	$badguy = array("creaturename"=>"`@Der Gr�ne Drachen`0","creaturelevel"=>18,"creatureweapon"=>"Gigantischer Flammensto�","creatureattack"=>45,"creaturedefense"=>25,"creaturehealth"=>300, "diddamage"=>0);
	//toughen up each consecutive dragon.
	//      $atkflux = e_rand(0,$session['user']['dragonkills']*2);
	//      $defflux = e_rand(0,($session['user']['dragonkills']*2-$atkflux));
	//      $hpflux = ($session['user']['dragonkills']*2 - ($atkflux+$defflux)) * 5;
	//      $badguy['creatureattack']+=$atkflux;
	//      $badguy['creaturedefense']+=$defflux;
	//      $badguy['creaturehealth']+=$hpflux;

	// First, find out how each dragonpoint has been spent and count those
	// used on attack and defense.
	// Coded by JT, based on collaboration with MightyE
	$points = 0;
	while(list($key,$val)=each($session[user][dragonpoints])){
		if ($val=="at" || $val == "de") $points++;
	}
	// Now, add points for hitpoint buffs that have been done by the dragon
	// or by potions!
	$points += (int)(($session['user']['maxhitpoints'] - 150)/5);

	// Okay.. *now* buff the dragon a bit.
	if ($beta)	
		$points = round($points*1.5,0);
	else
		$points = round($points*.75,0);

	$atkflux = e_rand(0, $points);
	$defflux = e_rand(0,$points-$atkflux);
	$hpflux = ($points - ($atkflux+$defflux)) * 5;
	$badguy['creatureattack']+=$atkflux;
	$badguy['creaturedefense']+=$defflux;
	$badguy['creaturehealth']+=$hpflux;
	$session[user][badguy]=createstring($badguy);
	$battle=true;
}else if($HTTP_GET_VARS[op]=="autochallenge"){
	output("`\$Auf dem Weg zum Dorfplatz h�rst du ein seltsames Ger�usch aus Richtung Wald und sp�rst ein ebenso seltsames Verlangen, der Ursache f�r das Ger�usch nachzugehen. ");
	output("Die Leute auf dem Dorfplatz scheinen in ihrer Unterhaltung nichts davon mitbekommen zu haben, also machst du dich alleine auf den Weg. Kaum im Wald h�rst du das Ger�usch erneut, diesmal schon wesentlich n�her. ");
	output("`nIn der Ferne siehst du ihn: Den `@gr�nen Drachen`\$! Gerade dabei, eine H�hle zu betreten. Er scheint m�de zu sein. Das ist `bDIE`b Gelegenheit! Nie hast du dich st�rker gef�hlt...");
	addnav("Weiter...","dragon.php");
}else if($HTTP_GET_VARS[op]=="prologue1"){
	output("`@Sieg!`n`n");
	$flawless = 0;
  	if ($HTTP_GET_VARS['flawless']) {
		$flawless = 1;
		output("`b`c`&~~ Perfekter Kampf! ~~`0`c`b`n`n");
	}
	output("`2Vor dir liegt regungslos der gro�e Drache. Sein schwerer Atem ist wie S�ure f�r deine Lungen.  ");
	output("Du bist vom Kopf bis zu den Zehen mit dem dicken schwarzen Blut dieser stinkenden Kreatur bedeckt.  ");
	output("Das Riesenbiest f�ngt pl�tzlich an, den Mund zu bewegen. Ver�rgert �ber dich selbst, dass du dich von dem vorget�uschten Tod ");
	output("der Kreatur reinlegen lassen hast, springst du zur�ck und erwartest, dass der riesige Schwanz auf dich zugeschossen kommt. Doch das passiert ");
	output("nicht. Stattdessen beginnt der Drachen zu sprechen.`n`n");
	output("\"`^Warum bist du hierher gekommen, Sterblicher? Was habe ich dir getan?`2\", sagt er mit sichtlicher Anstrengung.  ");
	output("\"`^Meinesgleichen wurde schon immer gesucht, um vernichtet zu werden. Warum? Wegen Geschichten aus fernen L�ndern, ");
	output("die von Drachen erz�hlen, die Jagd auf die Schwachen machen? Ich sage dir, dass diese M�rchen nur durch Missverst�ndnisse ");
	output("�ber uns entstehen und nicht, weil wir eure Kinder fressen.`2\" Das Biest macht eine Pause um schwer zu atmen, dann f�hrt es fort: ");
	output("\"`^Ich werde dir jetzt ein Geheimnis verraten. Hinter mir liegen meine Eier. Meine Jungen werden schl�pfen und sich gegenseitig ");
	output("auffressen. Nur eins wird �berleben, aber das wird das st�rkste sein. Es wird sehr schnell wachsen und ");
	output("genauso stark werden wie ich.`2\" Der Atem des Drachen wird k�rzer und flacher.`n`n");
	output("Du fragst: \"`#Warum erz�hlst du mir das? Kannst du dir nicht denken, dass ich deine Eier jetzt auch vernichten werde?`2\"");
	output("\"`^Nein, das wirst du nicht. Ich kenne noch ein weiteres Geheimnis, von dem du offensichtlich nichts wei�t.`2\"`n`n");
	output("\"`#Bitte erz�hle, oh m�chtiges Wesen!`2\"`n`n");
	output("Das gro�e Biest macht eine Pause, um seine letzten Kr�fte zu sammeln. \"`^Eure Art vertr�gt das Blut Meinesgleichen nicht. ");
	output("Selbst wenn du �berleben solltest, wirst du nur noch ein schwacher Mensch sein, kaum in der Lage, eine Waffe zu halten. Dein Geist ");
	output("wird vollst�ndig geleert sein von allem, was du je gelernt hast. Nein, du bist keine Bedrohung f�r meine Kinder, denn du bist bereits tot!`2\"`n`n");
	output("Du bemerkst, dass deine Wahrnehmung tats�chlich bereits zu schwinden beginnt und fliehst Hals �ber Kopf aus der H�hle, nur darauf fixiert, ");
	output("die H�tte des Heilers zu erreichen, bevor es zu sp�t ist. Irgendwo unterwegs verlierst du deine Waffe und schlie�lich ");
	output("stolperst du �ber einen Stein in einem schmalen Bach. Deine Sicht ist inzwischen auf einen kleinen Kreis beschr�nkt, der in deinem Kopf ");
	output("herumzuwandern scheint. W�hrend du so da liegst und in die B�ume starrst, glaubst du die Ger�usche des Dorfes ");
	output("in der N�he zu h�ren. Dein letzter ironischer Gedanke ist, dass, obwohl du den Drachen besiegt hast, er doch ");
	output("dich besiegt hat.`n`n");
	output("W�hrend sich deine Wahrnehmung vollst�ndig verabschiedet, f�llt in der Drachenh�hle weit entfernt ein Ei auf die Seite und ein kleiner Riss ");
	output("erscheint in der dicken, lederartigen Schale.");

	if ($flawless) {
		output("`nDu f�llst vorw�rts um. Im Fallen erinnerst du sich, dass du es im letzten Moment doch noch geschafft hast, etwas von dem Schatz des Drachen einzustecken. Vielleicht war das alles ja doch kein totaler Verlust.");
	}
	addnav("Es ist ein neuer Tag","news.php");
	$sql = "describe accounts";
	$result = db_query($sql) or die(db_error(LINK));
	$hpgain = $session[user][maxhitpoints] - ($session[user][level]*10);
	if ($session[user][goldinbank]<0){
	$session[user][goldinbank]=round($session[user][goldinbank]/10);
	$nochange=array("acctid"=>1
	               ,"name"=>1
								 ,"sex"=>1
								 ,"password"=>1
								 ,"marriedto"=>1
								,"charisma"=>1
								 ,"title"=>1
								 ,"login"=>1
								 ,"dragonkills"=>1
								 ,"locked"=>1
								 ,"loggedin"=>1
								 ,"superuser"=>1
								 ,"gems"=>1
								 ,"hashorse"=>1
								 ,"gentime"=>1
								 ,"gentimecount"=>1
								 ,"lastip"=>1
								 ,"uniqueid"=>1
								 ,"dragonpoints"=>1
								,"goldinbank"=>1
								 ,"laston"=>1
								 ,"prefs"=>1
								 ,"lastmotd"=>1
								 ,"emailaddress"=>1
								 ,"emailvalidation"=>1
								 ,"gensize"=>1
								 ,"bestdragonage"=>1
								 ,"dragonage"=>1
								 ,"donation"=>1
								 ,"donationspent"=>1
								 ,"donationconfig"=>1
								 ,"bio"=>1
								,"pvpflag"=>1
								 ,"charm"=>1
								,"house"=>1
								,"housekey"=>1
								 ,"banoverride"=>1 // jt
								 ,"referer"=>1 //jt
								 ,"refererawarded"=>1 //jt
								 ,"lastwebvote"=>1
								 ,"ctitle"=>1
								 ,"beta"=>1
								,"punch"=>1
								,"avatar"=>1
								,"battlepoints"=>1
								,"reputation"=>1
								 );
	} else {
	$nochange=array("acctid"=>1
	               ,"name"=>1
								 ,"sex"=>1
								 ,"password"=>1
								 ,"marriedto"=>1
								,"charisma"=>1
								 ,"title"=>1
								 ,"login"=>1
								 ,"dragonkills"=>1
								 ,"locked"=>1
								 ,"loggedin"=>1
								 ,"superuser"=>1
								 ,"gems"=>1
								 ,"hashorse"=>1
								 ,"gentime"=>1
								 ,"gentimecount"=>1
								 ,"lastip"=>1
								 ,"uniqueid"=>1
								 ,"dragonpoints"=>1
								 ,"laston"=>1
								 ,"prefs"=>1
								 ,"lastmotd"=>1
								 ,"emailaddress"=>1
								 ,"emailvalidation"=>1
								 ,"gensize"=>1
								 ,"bestdragonage"=>1
								 ,"dragonage"=>1
								 ,"donation"=>1
								 ,"donationspent"=>1
								 ,"donationconfig"=>1
								 ,"bio"=>1
								,"pvpflag"=>1
								 ,"charm"=>1
								,"house"=>1
								,"housekey"=>1
								 ,"banoverride"=>1 // jt
								 ,"referer"=>1 //jt
								 ,"refererawarded"=>1 //jt
								 ,"lastwebvote"=>1
								 ,"ctitle"=>1
								 ,"beta"=>1
								,"punch"=>1
								,"avatar"=>1
								,"battlepoints"=>1
								,"reputation"=>1
								 );
	}
	$session[user][dragonage] = $session[user][age];
	if ($session[user][dragonage] <  $session[user][bestdragonage] ||
			$session[user][bestdragonage] == 0) {
		$session[user][bestdragonage] = $session[user][dragonage];
	}
	for ($i=0;$i<db_num_rows($result);$i++){
	  $row = db_fetch_assoc($result);
		if ($nochange[$row[Field]]){
		
		}else{
		  $session[user][$row[Field]] = $row["Default"];
		}
	}
	$session[bufflist] = array();
	$session[user][gold]=	getsetting("newplayerstartgold",50);

	$newtitle=$titles[$session[user][dragonkills]][$session[user][sex]];
	if ($newtitle==""){
	  $newtitle = ($session[user][sex]?"G�ttin":"Gott");
	}


	$session[user][gold]+=getsetting("newplayerstartgold",50)*$session[user][dragonkills];
	if ($session[user][gold]>(6*getsetting("newplayerstartgold",50))){
	  $session[user][gold]=6*getsetting("newplayerstartgold",50);
	//	$session[user][gems]+=($session[user][dragonkills]-5);
		$session['user']['donation']+=$session[user][dragonkills];
	}
	if ($flawless) {
		$session['user']['gold'] += 3*getsetting("newplayerstartgold",50);
		$session['user']['gems'] += 1;
		$session['user']['donation']+=($session[user][dragonkills]+5);
	}
	$session[user][maxhitpoints]+=$hpgain;
	$session[user][hitpoints]=$session[user][maxhitpoints];
	// Handle custom titles
	if ($session[user][ctitle] == "") {
		if ($session[user][title]!=""){
			$n = $session[user][name];
			$x = strpos($n,$session[user][title]);
			if ($x!==false){
				$regname=substr($n,$x+strlen($session[user][title]));
				$session['user']['name'] = substr($n,0,$x).$newtitle.$regname;
				$session['user']['title'] = $newtitle;
			}else{
				$regname = $session['user']['name'];
				$session['user']['name'] = $newtitle." ".$session['user']['name'];
				$session['user']['title'] = $newtitle;
			}
		}else{
			$regname = $session['user']['name'];
			$session[user][name] = $newtitle." ".$session[user][name];
			$session[user][title] = $newtitle;
		}
	} else {
		$regname = substr($session['user']['name'], strlen($session['user']['ctitle']));
		$session[user][title] = $newtitle;
	}
	while(list($key,$val)=each($session[user][dragonpoints])){
		if ($val=="at"){
			$session[user][attack]++;
		}
		if ($val=="de"){
			$session[user][defence]++;
		}
	}
	$session[user][laston]=date("Y-m-d H:i:s",strtotime(date("r")."-1 day"));
	output("`n`nDu erwachst umgeben von B�umen. In der N�he h�rst du die Ger�usche eines Dorfs.  ");
	output("Dunkel erinnerst du dich daran, dass du ein neuer Krieger bist, und an irgendwas von einem gef�hrlichen gr�nen Drachen, der die Gegend heimsucht. ");
	output("Du beschlie�t, dass du dir einen Namen verdienen k�nntest, wenn du dich vielleicht eines Tages dieser abscheulichen Kreatur stellst. ");
	addnews("`#".$regname."`# hat sich den Titel `&".$session[user][title]."`# f�r den `^".$session[user][dragonkills]."`#ten erfolgreichen Kampf gegen den `@Gr�nen Drachen`# verdient!");
	output("`n`n`^Du bist von nun an bekannt als `&".$session[user][name]."`^!!");
	output("`n`n`&Weil du den Drachen ".$session[user][dragonkills]." mal besiegt hast, startest du mit einigen Extras. Ausserdem beh�ltst du alle zus�tzlichen Lebenspunkte, die du verdient oder gekauft hast.`n");
	$session['user']['charm']+=5;
	output("`^Du bekommst F�NF Charmepunkte f�r deinen Sieg �ber den Drachen!`n");
	$dkname = $session[user][name];
	savesetting("newdragonkill",addslashes($dkname));
	debuglog("slew the dragon and starts with {$session['user']['gold']} gold and {$session['user']['gems']} gems");
	// dragonkill ends arenafight
	$sql = "DELETE FROM pvp WHERE acctid1=".$session[user][acctid]." OR acctid2=".$session[user][acctid];
	db_query($sql) or die(db_error(LINK));
	$sql = "DELETE FROM items WHERE owner=".$session[user][acctid]." AND (class='Beute' OR class='Fluch' OR class='Geschenk' OR class='Schmuck' OR class='Waffe' OR class='R�stung' OR class='Zauber')";
	db_query($sql) or die(db_error(LINK));
}

if ($HTTP_GET_VARS[op]=="run"){
  output("Der Schwanz der Kreatur versperrt den einzigen Ausgang aus der H�hle!");
	$HTTP_GET_VARS[op]="fight";
}
if ($HTTP_GET_VARS[op]=="fight" || $HTTP_GET_VARS[op]=="run"){
	$battle=true;
}
if ($battle){
  include("battle.php");
	if ($victory){
		$flawless = 0;
		if ($badguy['diddamage'] != 1) $flawless = 1;
		$badguy=array();
		$session[user][badguy]="";
		$session[user][dragonkills]++;
		$session[user][reputation]+=2;
		output("`&Mit einem letzten m�chtigen Knall l�sst `@der Gr�ne Drachen`& ein furchtbares Br�llen los und f�llt dir vor die F��e, endlich tot.");
		addnews("`&".$session[user][name]."`& hat die abscheuliche, als `@Gr�ner Drachen`& bekannte Kreatur besiegt. �ber alle L�nder freuen sich die V�lker!");
		addnav("Weiter","dragon.php?op=prologue1&flawless=$flawless");
	}else{
		if($defeat){
			addnav("T�gliche News","news.php");
			$sql = "SELECT taunt FROM taunts ORDER BY rand(".e_rand().") LIMIT 1";
			$result = db_query($sql) or die(db_error(LINK));
			$taunt = db_fetch_assoc($result);
			$taunt = str_replace("%s",($session[user][sex]?"sie":"ihn"),$taunt[taunt]);
			$taunt = str_replace("%o",($session[user][sex]?"sie":"er"),$taunt);
			$taunt = str_replace("%p",($session[user][sex]?"ihre(r/m)":"seine(r/m)"),$taunt);
			$taunt = str_replace("%x",($session[user][weapon]),$taunt);
			$taunt = str_replace("%X",$badguy[creatureweapon],$taunt);
			$taunt = str_replace("%W",$badguy[creaturename],$taunt);
			$taunt = str_replace("%w",$session[user][name],$taunt);
			$session[user][reputation]--;
			addnews("`%".$session[user][name]."`5 wurde gefressen, als ".($session[user][sex]?"sie":"er")." dem `@Gr�nen Drachen`5 begegnete!!!  ".($session[user][sex]?"Ihre":"Seine")." Knochen liegen nun am Eingang der H�hle, genau wie die der Krieger, die vorher kamen.`n$taunt");
			$session[user][alive]=false;
			debuglog("lost {$session['user']['gold']} gold when they were slain");
			$session[user][gold]=0;
			$session[user][hitpoints]=0;
			$session[user][badguy]="";
			output("`b`%$badguy[creaturename]`& hat dich gefressen!!!`n");
			output("`4Du hast dein ganzes Gold verloren!`n");
			output("Du kannst morgen wieder k�mpfen.");
			
			page_footer();
		}else{
		  fightnav(true,false);
		}
	}
}
page_footer();
?>
