<?php

// 24072004

require_once "common.php";

page_header("Der Grüne Drachen!");
if ($HTTP_GET_VARS[op]==""){
  output("`\$Du erstickst jeden Drang zu fliehen und betrittst vorsichtig die Höhle. Du spekulierst ");
	output("darauf, den großen Drachen im Schlaf zu überraschen, um ihn mit einem Minimum an eigenem Schmerz ");
	output("zu erlegen. Leider ist das nicht der Fall. Du biegst in der Höhle um eine Ecke ");
	output("und entdeckst das Riesenbiest, das mit den Hinterbeinen auf einem gewaltigen Haufen Gold sitzt und ");
	output("seine Zähne mit etwas reinigt, das wie eine Rippe aussieht.");
	$badguy = array("creaturename"=>"`@Der Grüne Drachen`0","creaturelevel"=>18,"creatureweapon"=>"Gigantischer Flammenstoß","creatureattack"=>45,"creaturedefense"=>25,"creaturehealth"=>300, "diddamage"=>0);
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
	output("`\$Auf dem Weg zum Dorfplatz hörst du ein seltsames Geräusch aus Richtung Wald und spürst ein ebenso seltsames Verlangen, der Ursache für das Geräusch nachzugehen. ");
	output("Die Leute auf dem Dorfplatz scheinen in ihrer Unterhaltung nichts davon mitbekommen zu haben, also machst du dich alleine auf den Weg. Kaum im Wald hörst du das Geräusch erneut, diesmal schon wesentlich näher. ");
	output("`nIn der Ferne siehst du ihn: Den `@grünen Drachen`\$! Gerade dabei, eine Höhle zu betreten. Er scheint müde zu sein. Das ist `bDIE`b Gelegenheit! Nie hast du dich stärker gefühlt...");
	addnav("Weiter...","dragon.php");
}else if($HTTP_GET_VARS[op]=="prologue1"){
	output("`@Sieg!`n`n");
	$flawless = 0;
  	if ($HTTP_GET_VARS['flawless']) {
		$flawless = 1;
		output("`b`c`&~~ Perfekter Kampf! ~~`0`c`b`n`n");
	}
	output("`2Vor dir liegt regungslos der große Drache. Sein schwerer Atem ist wie Säure für deine Lungen.  ");
	output("Du bist vom Kopf bis zu den Zehen mit dem dicken schwarzen Blut dieser stinkenden Kreatur bedeckt.  ");
	output("Das Riesenbiest fängt plötzlich an, den Mund zu bewegen. Verärgert über dich selbst, dass du dich von dem vorgetäuschten Tod ");
	output("der Kreatur reinlegen lassen hast, springst du zurück und erwartest, dass der riesige Schwanz auf dich zugeschossen kommt. Doch das passiert ");
	output("nicht. Stattdessen beginnt der Drachen zu sprechen.`n`n");
	output("\"`^Warum bist du hierher gekommen, Sterblicher? Was habe ich dir getan?`2\", sagt er mit sichtlicher Anstrengung.  ");
	output("\"`^Meinesgleichen wurde schon immer gesucht, um vernichtet zu werden. Warum? Wegen Geschichten aus fernen Ländern, ");
	output("die von Drachen erzählen, die Jagd auf die Schwachen machen? Ich sage dir, dass diese Märchen nur durch Missverständnisse ");
	output("über uns entstehen und nicht, weil wir eure Kinder fressen.`2\" Das Biest macht eine Pause um schwer zu atmen, dann fährt es fort: ");
	output("\"`^Ich werde dir jetzt ein Geheimnis verraten. Hinter mir liegen meine Eier. Meine Jungen werden schlüpfen und sich gegenseitig ");
	output("auffressen. Nur eins wird überleben, aber das wird das stärkste sein. Es wird sehr schnell wachsen und ");
	output("genauso stark werden wie ich.`2\" Der Atem des Drachen wird kürzer und flacher.`n`n");
	output("Du fragst: \"`#Warum erzählst du mir das? Kannst du dir nicht denken, dass ich deine Eier jetzt auch vernichten werde?`2\"");
	output("\"`^Nein, das wirst du nicht. Ich kenne noch ein weiteres Geheimnis, von dem du offensichtlich nichts weißt.`2\"`n`n");
	output("\"`#Bitte erzähle, oh mächtiges Wesen!`2\"`n`n");
	output("Das große Biest macht eine Pause, um seine letzten Kräfte zu sammeln. \"`^Eure Art verträgt das Blut Meinesgleichen nicht. ");
	output("Selbst wenn du überleben solltest, wirst du nur noch ein schwacher Mensch sein, kaum in der Lage, eine Waffe zu halten. Dein Geist ");
	output("wird vollständig geleert sein von allem, was du je gelernt hast. Nein, du bist keine Bedrohung für meine Kinder, denn du bist bereits tot!`2\"`n`n");
	output("Du bemerkst, dass deine Wahrnehmung tatsächlich bereits zu schwinden beginnt und fliehst Hals über Kopf aus der Höhle, nur darauf fixiert, ");
	output("die Hütte des Heilers zu erreichen, bevor es zu spät ist. Irgendwo unterwegs verlierst du deine Waffe und schließlich ");
	output("stolperst du über einen Stein in einem schmalen Bach. Deine Sicht ist inzwischen auf einen kleinen Kreis beschränkt, der in deinem Kopf ");
	output("herumzuwandern scheint. Während du so da liegst und in die Bäume starrst, glaubst du die Geräusche des Dorfes ");
	output("in der Nähe zu hören. Dein letzter ironischer Gedanke ist, dass, obwohl du den Drachen besiegt hast, er doch ");
	output("dich besiegt hat.`n`n");
	output("Während sich deine Wahrnehmung vollständig verabschiedet, fällt in der Drachenhöhle weit entfernt ein Ei auf die Seite und ein kleiner Riss ");
	output("erscheint in der dicken, lederartigen Schale.");

	if ($flawless) {
		output("`nDu fällst vorwärts um. Im Fallen erinnerst du sich, dass du es im letzten Moment doch noch geschafft hast, etwas von dem Schatz des Drachen einzustecken. Vielleicht war das alles ja doch kein totaler Verlust.");
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
	  $newtitle = ($session[user][sex]?"Göttin":"Gott");
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
	output("`n`nDu erwachst umgeben von Bäumen. In der Nähe hörst du die Geräusche eines Dorfs.  ");
	output("Dunkel erinnerst du dich daran, dass du ein neuer Krieger bist, und an irgendwas von einem gefährlichen grünen Drachen, der die Gegend heimsucht. ");
	output("Du beschließt, dass du dir einen Namen verdienen könntest, wenn du dich vielleicht eines Tages dieser abscheulichen Kreatur stellst. ");
	addnews("`#".$regname."`# hat sich den Titel `&".$session[user][title]."`# für den `^".$session[user][dragonkills]."`#ten erfolgreichen Kampf gegen den `@Grünen Drachen`# verdient!");
	output("`n`n`^Du bist von nun an bekannt als `&".$session[user][name]."`^!!");
	output("`n`n`&Weil du den Drachen ".$session[user][dragonkills]." mal besiegt hast, startest du mit einigen Extras. Ausserdem behältst du alle zusätzlichen Lebenspunkte, die du verdient oder gekauft hast.`n");
	$session['user']['charm']+=5;
	output("`^Du bekommst FÜNF Charmepunkte für deinen Sieg über den Drachen!`n");
	$dkname = $session[user][name];
	savesetting("newdragonkill",addslashes($dkname));
	debuglog("slew the dragon and starts with {$session['user']['gold']} gold and {$session['user']['gems']} gems");
	// dragonkill ends arenafight
	$sql = "DELETE FROM pvp WHERE acctid1=".$session[user][acctid]." OR acctid2=".$session[user][acctid];
	db_query($sql) or die(db_error(LINK));
	$sql = "DELETE FROM items WHERE owner=".$session[user][acctid]." AND (class='Beute' OR class='Fluch' OR class='Geschenk' OR class='Schmuck' OR class='Waffe' OR class='Rüstung' OR class='Zauber')";
	db_query($sql) or die(db_error(LINK));
}

if ($HTTP_GET_VARS[op]=="run"){
  output("Der Schwanz der Kreatur versperrt den einzigen Ausgang aus der Höhle!");
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
		output("`&Mit einem letzten mächtigen Knall lässt `@der Grüne Drachen`& ein furchtbares Brüllen los und fällt dir vor die Füße, endlich tot.");
		addnews("`&".$session[user][name]."`& hat die abscheuliche, als `@Grüner Drachen`& bekannte Kreatur besiegt. Über alle Länder freuen sich die Völker!");
		addnav("Weiter","dragon.php?op=prologue1&flawless=$flawless");
	}else{
		if($defeat){
			addnav("Tägliche News","news.php");
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
			addnews("`%".$session[user][name]."`5 wurde gefressen, als ".($session[user][sex]?"sie":"er")." dem `@Grünen Drachen`5 begegnete!!!  ".($session[user][sex]?"Ihre":"Seine")." Knochen liegen nun am Eingang der Höhle, genau wie die der Krieger, die vorher kamen.`n$taunt");
			$session[user][alive]=false;
			debuglog("lost {$session['user']['gold']} gold when they were slain");
			$session[user][gold]=0;
			$session[user][hitpoints]=0;
			$session[user][badguy]="";
			output("`b`%$badguy[creaturename]`& hat dich gefressen!!!`n");
			output("`4Du hast dein ganzes Gold verloren!`n");
			output("Du kannst morgen wieder kämpfen.");
			
			page_footer();
		}else{
		  fightnav(true,false);
		}
	}
}
page_footer();
?>
