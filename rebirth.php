<?php

// 15082004

// Altar of Rebirth
// Idea by Luke
// recoding and german version by anpera

require_once("common.php");
checkday();
page_header("Schrein der Erneuerung");
output("`b`c`6Der Schrein der Erneuerung`0`c`b");
if ($_GET[op]=="rebirth1"){
	$what=$_GET[full];
	$n=$session[user][name];
	$neu=substr($n,strlen($session[user][title]));
	if ($what=="true"){
		output("`n`6Du legst alle deine Besitztümer ab und beginnst mit dem beschriebenen Ritual. Noch einmal wollen die Götter von dir die Bestätigung, dass du dir ");
		output("diesen Schritt gut überlegt hast. Du wirst `balles`b verlieren, wenn du fortfährst. Du wirst zu:`n`n");
		if ($session[user][ctitle]){
			output("`6Name: `4$n`n");
		}else{
			output("`6Name: `4".($session[user][sex]?"Bauernmädchen":"Bauernjunge")." $neu`n");
		}
		output("`6Lebenspunkte: `410`n");
		output("`6Level: `41`n");
		output("`6Angriff: `41`n");
		output("`6Verteidigung: `41`n");
		output("`6Erfahrung: `40`n");
		output("`6Gold: `4".getsetting("newplayerstartgold",10)."`n");
		output("`6Edelsteine: `40`n");
		output("`6Du verlierst deine Waffe, deine Rüstung und dein gesamtes Inventar.`n");
		output("`6Du vergisst deine Rasse und alle besonderen Fähigkeiten.`n");
		if ($session[user][house]) output("Du verlierst dein Haus.`n");
		if ($session[user][hashorse]) output("Du verlierst dein Tier.`n");
		output("Du verlierst alle Drachenpunkte.`n`n`bBist du zu diesem Schritt wirklich bereit?`b");
		output("`n`n`n<form action='rebirth.php?op=rebirth2&full=$what' method='POST'>",true);
		output("<input type='submit' class='button' value='Charakter neu beginnen' onClick='return confirm(\"Willst du deinen Charakter wirklich neu starten?\");'>", true);
		output("</form>",true);
		addnav("","rebirth.php?op=rebirth2&full=$what");
	}
	if ($what=="false"){
		output("`n`6Du legst alle deine Besitztümer ab und beginnst mit dem beschriebenen Ritual. Noch einmal wollen die Götter von dir die Bestätigung, dass du dir ");
		output("diesen Schritt gut überlegt hast. Du wirst `beiniges`b verlieren, wenn du fortfährst. Du wirst zu:`n`n");
		output("`6Name: `4".$session[user][name]."`n");
		output("`6Lebenspunkte: `4".($session[user][level]*10)."`n");
		output("`6Level: `4".$session[user][level]."`n");
		output("`6Angriff: `4".$session[user][level]."`n");
		output("`6Verteidigung: `4".$session[user][level]."`n");
		output("`6Erfahrung: `4".$session[user][experience]."`n");
		output("`6Gold: `40`n");
		output("`6Edelsteine: `40`n");
		output("`6Du verlierst deine Waffe, deine Rüstung und dein gesamtes Inventar.`n");
		output("`6Du vergisst deine Rasse und alle besonderen Fähigkeiten.`n");
		if ($session[user][house]) output("Du verlierst dein Haus.`n");
		if ($session[user][hashorse]) output("Du verlierst dein Tier.`n");
		output("Du kannst alle Drachenpunkte neu vergeben.`n`n`bBist du zu diesem Schritt wirklich bereit?`b");
		output("`n`n`n<form action='rebirth.php?op=rebirth2&full=$what' method='POST'>",true);
		output("<input type='submit' class='button' value='Charakter zurücksetzen' onClick='return confirm(\"Willst du die Werte deines Charakters wirklich neu verteilen?\");'>", true);
		output("</form>",true);
		addnav("","rebirth.php?op=rebirth2&full=$what");
		addnav("*?Erneuerung bestätigen","rebirth.php?op=rebirth2&full=$what");
	}
	addnav("Zurück zum Club","rock.php");
}else if($_GET[op]=="rebirth2"){
	$what=$_GET[full];
	if ($what=="true"){
		addnews("`#".$session[user][name]."`# hat seinem bisherigen Leben ein Ende gesetzt und einen Neuanfang beschlossen.");
		if (!$session[user][ctitle]){
			$n=$session[user][name];
			$session[user][name]=($session[user][sex]?"Bauernmädchen":"Bauernjunge").substr($n,strlen($session[user][title]));
		}
		$session[user][title]=($session[user][sex]?"Bauernmädchen":"Bauernjunge");
		$session[user][level]=1;
		$session[user][maxhitpoints]=10;
		$session[user][attack]=1;
		$session[user][defence]=1;
		$session[user][gold]=getsetting("newplayerstartgold",0);
		$session[user][goldinbank]=0;
		$session[user][experience]=0;
		$session[user][gems]=0;
		$session[user][age]=0;
		$session[user][battlepoints]=0;
		$session[user][reputation]+=25;
		$session[user][dragonpoints]="";
		$session[user][dragonkills]=0;
		$session[user][drunkenness]=0;
		$session[user][specialty]=0;
		$session[user][darkarts]=0;
		$session[user][thievery]=0;
		$session[user][magic]=0;
		$session[user][weapon]="Fists";
		$session[user][armor]="T-Shirt";
		$session[user][hashorse]=0;
		$session[user][bufflist]="";
		if ($session[user][marriedto]>0 && $session[user][marriedto]<4294967295 && $session[user][charisma]==4294967295){
			$sql="UPDATE accounts SET marriedto=0,charisma=0 WHERE acctid=".$session[user][marriedto]."";
			db_query($sql);
			systemmail($session[user][marriedto],"`6".$session[user][name]." ist nicht mehr der selbe`0","`6{$session['user']['name']}`6 hat sich ein neues Leben gegeben. Ihr seid nicht länger verheiratet.");
		}
		$session[user][charisma]=0;
		$session[user][marriedto]=0;
		$session[user][weaponvalue]=0;
		$session[user][armorvalue]=0;
		$session[user][resurrections]=0;
		$session[user][weapondmg]=0;
		$session[user][armordef]=0;
		$session[user][charm]=0;
		$session[user][race]=0;
		$session[user][dragonage]=0;
		$session[user][deathpower]=0;
		$session[user][punch]=1;
		debuglog("REBIRTH ".date("Y-m-d H:i:s")."");
		$session[user][bounty]=0;
		if ($session[user][house]){
			if ($session[user][housekey]){
				$sql="UPDATE houses SET owner=0,status=3 WHERE owner=".$session[user][acctid]."";
			}else{
				$sql="UPDATE houses SET owner=0,status=4 WHERE owner=".$session[user][acctid]."";
			}
		db_query($sql);
		}
		$session[user][house]=0;
		$session[user][housekey]=0;
		$sql="UPDATE items SET owner=0 WHERE owner=".$session[user][acctid]." AND class='Schlüssel'";
		db_query($sql);
		$sql="DELETE FROM items WHERE owner=".$session[user][acctid]." AND class<>'Schlüssel'";
		db_query($sql);
		$session[user][laston]="";
		$session[user][lasthit]=date("Y-m-d H:i:s",strtotime(date("r")."-".(86500/getsetting("daysperday",4))." seconds")); 
		output("`n`6Du stimmst zu.`nWährend du das Ritual durchführst und dich von deinem Besitz löst, spürst du auch deine Lebenkraft, deine Erfahrung und schließlich all deine Fähigkeiten ");
		output("schwinden. Du vergisst dein ganzes bisheriges Leben. Du fällst in eine lange Ohnmacht...");
	}
	if ($what=="false"){
		addnews("`#".$session[user][name]."`# hat einen radikalen Lebenswandel beschlossen.");
		$session[user][maxhitpoints]=$session[user][level]*10;
		$session[user][attack]=$session[user][level];
		$session[user][defence]=$session[user][level];
		$session[user][gold]=0;
		$session[user][goldinbank]=0;
		$session[user][battlepoints]=0;
		$session[user][reputation]-=25;
		$session[user][dragonpoints]="";
		$session[user][drunkenness]=0;
		$session[user][specialty]=0;
		$session[user][darkarts]=0;
		$session[user][thievery]=0;
		$session[user][magic]=0;
		$session[user][weapon]="Fäuste der Erneuerung";
		$session[user][armor]="Haut der Erneuerung";
		$session[user][hashorse]=0;
		$session[user][bufflist]="";
		$session[user][weaponvalue]=0;
		$session[user][armorvalue]=0;
		$session[user][weapondmg]=$session[user][level];
		$session[user][armordef]=$session[user][level];
		$session[user][charm]=1;
		$session[user][race]=0;
		$session[user][deathpower]=0;
		$session[user][punch]=1;
		debuglog("RENEWAL ".date("Y-m-d H:i:s")."");
		$session[user][bounty]=0;
		if ($session[user][house]){
			if ($session[user][housekey]){
				$sql="UPDATE houses SET owner=0,status=3 WHERE owner=".$session[user][acctid]."";
			}else{
				$sql="UPDATE houses SET owner=0,status=4 WHERE owner=".$session[user][acctid]."";
			}
		db_query($sql);
		}
		$session[user][house]=0;
		$session[user][housekey]=0;
		$sql="UPDATE items SET owner=0 WHERE owner=".$session[user][acctid]." AND class='Schlüssel'";
		db_query($sql);
		$sql="DELETE FROM items WHERE owner=".$session[user][acctid]." AND class<>'Schlüssel'";
		db_query($sql);
		$session[user][lasthit]=date("Y-m-d H:i:s",strtotime(date("r")."-".(86500/getsetting("daysperday",4))." seconds")); 
		output("`n`6Du stimmst zu.`nWährend du das Ritual durchführst und dich von deinem Besitz löst, spürst du auch deine Lebenkraft und all deine Fähigkeiten ");
		output("schwinden. Du vergisst vieles aus deinem bisherigen Leben und fällst in eine lange Ohnmacht...");
	}
}else{
	output("`n`6Du gehst zu einer bedrohlich wirkenden Tür im hinteren Bereich des Clubs. ");
	if ($session[user][dragonkills]>=10){
		addnav("Vollständige Wiedergeburt","rebirth.php?op=rebirth1&full=true");
		addnav("Erneuerung","rebirth.php?op=rebirth1&full=false");
		output("Wie von selbst öffnet sich die Tür. Dahinter siehst du einen mächtigen Altar der Götter. Du spürst förmlich, dass sich hier dein Leben grundlegend ändern kann.");
		output(" Eine Tafel vor dem Altar bestätigt dieses Gefühl: \"`4Hier kannst du die Fehler deiner Vergangenheit rückgängig machen und um einen Neuanfang bitten. Wisse aber, dass diese ");
		output("Entscheidung dazu die letzte deines Lebens darstellt. Du wirst morgen ohne deine weltlichen Güter und ohne Erinnerung auf dem Dorfplatz aufwachen. Nur mit ");
		output(" Chance ausgerüstet, es noch einmal besser zu machen.`6\"`n`nWillst du neu beginnen?`n`n");
		output("`bVollständige Wiedergeburt:`b`n");
		output("Du würdest wieder als ".($session[user][sex]?"Bauernmädchen":"Bauernjunge")." mit nichts als den gesammelten Donationpoints im Dorf aufwachen. Dein Leben "); 
		output("würde beendet und im selben Moment von vorne beginnen.`n`\$Diese Option ist für Krieger gedacht, die bereits alles erreicht haben, ");
		output("oder die keinen Sinn mehr in ihrem einsamen Leben oberhalb der normalen Gesellschaft sehen.`n`n");
// Bad idea for balance...?
		output("`bErneuerung:`b`n");
		output("Drachenkills, Titel, Ehepartner und deine Erinnerung bleiben dir erhalten, jedoch legst du alle anderen weltlichen Besitztümer ab und wirst es sehr schwer haben, dich wieder ");
		output(" an das knallharte Leben mit dem Drachen zu gewöhnen. Dafür kannst du alle Drachenpunkte neu vergeben.");

	}else{
		output("Doch alle Versuche, diese Tür zu öffnen, schlagen fehl. Du erkundigst dich im Club nach dieser Tür und bekommst tatsächlich eine Antwort: \"`4");
		output("Hinter dieser Tür steht ein mächtiger Altar der Götter. Es ist ein Altar des Vergesssens, des Todes und der Erneuerung. Nur sehr mächtigen Kriegern ");
		output("ist es gestattet, diesen Altar zu benutzen. Dort können sie über ihr bisheriges Leben nachdenken und um einen Neuanfang bitten. Du wirst noch ");
		if ($session[user][dragonkills]<5) output("sehr viele");
		if ($session[user][dragonkills]>=5 && $session[user][dragonkills]<9) output("ein paar");
		if ($session[user][dragonkills]>=9) output("einen");
		output(" Drachen erschlagen müssen, bevor du den Schrein betreten kannst.`6\"");
	}
	addnav("Zurück zum Club","rock.php");
}
addnav("Zurück zum Dorf","village.php");

page_footer();
?>
