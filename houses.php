<?php

// 08092004

/*
* Author:	anpera
* Email:		logd@anpera.de
* 
* Purpose:	Houses for storing gold and gems and for a save place to sleep (logout)
*		
* Features:	Build house, sell house, buy house, share house with others, private chat-area, PvP
*
* Every warrior can have his own house. He can build it with his own hands or buy one that was sold (or left) before.
* In a house he can store some of his gems and gold and houses are the savest place for log out.
* The player can give keys to other players. So he is able to share his gems and gold for example
* with his wife or he can make up a clan house. A player can only have one house but unlimited keys.
* Each house has its own private chat area. 
* Other players can rob a house if they beat the guard and all players that are sleeping in the house.
*
*
* SEE  INSTRUCTIONS  FOR  INSTALLATION  AT  http://www.anpera.net/forum/viewtopic.php?t=323
* English translation available at DragonPrime
*
* Added furniture 05/25/2004
*  (Buy at vendor - vendor.php)
* Added Durandil's hidden path 05/30/2004
*
* Ok, lets do the code...
*/

	
require_once("common.php");
addcommentary();
checkday();

// base values for pricing and chest size:
$goldcost=30000;
$gemcost=50;
// all other values are controlled by banksettings

//not needed v
if ($session[user][slainby]!=""){
	page_header("Du wurdest besiegt!");
		output("`\$Du wurdest in ".$session[user][killedin]."`\$ von `%".$session[user][slainby]."`\$ besiegt und um alles Gold beraubt, das du bei dir hattest. Das kostet dich 5% deiner Erfahrung. Meinst du nicht es ist Zeit für Rache?");
	addnav("Weiter",$REQUEST_URI);
	$session[user][slainby]="";
	$session['user']['donation']+=1;
	page_footer();
}
// ^

page_header("Das Wohnviertel");

if ($_GET[op]=="newday"){
	output("`2Gut erholt wachst du im Haus auf und bist bereit für neue Abenteuer.");
	$session[user][location]=0;
	$sql = "UPDATE items SET hvalue=0 WHERE hvalue>0 AND owner=".$session[user][acctid]." AND class='Schlüssel'";
	db_query($sql) or die(sql_error($sql));
	addnav("Tägliche News","news.php");
	addnav("Wohnviertel","houses.php?op=enter");
	addnav("Zurück ins Dorf","village.php");
}else if ($_GET[op]=="bio"){
	if (!$_GET[id]) redirect("houses.php");
	$sql="SELECT houses.*,accounts.name AS besitzer FROM houses LEFT JOIN accounts ON accounts.acctid=houses.owner WHERE houseid=$_GET[id]";
	$result = db_query($sql) or die(db_error(LINK));
	$row = db_fetch_assoc($result);
	output("`c`b`@Infos über Haus Nummer $row[houseid]`b`c`n`n`2Du näherst dich Haus Nummer $row[houseid], um es aus der Nähe zu betrachten. ");
	if($row[description]){
		output("Über dem Eingang  von $row[housename]`2 steht geschrieben:`n`& $row[description]`n`n");
	}else{
		output("Das Haus trägt den Namen \"`&$row[housename]`2\".`n");
	}
	$sql="SELECT * FROM items WHERE class='Möbel' AND value1={$_GET['id']} ORDER BY id ASC";
	$result = db_query($sql);
	if ($row[besitzer]=="") $row[besitzer]="niemandem";
	output("`2Das Haus gehört `^$row[besitzer]`2 und ist ");
	if ($row[status]==0) output("noch im Bau. ");
	if ($row[status]==1) output("bewohnt. ");
	if ($row[status]==2) output("zum Verkauf angeboten. ");
	if ($row[status]==3) output("verlassen. ");
	if ($row[status]==4) output("eine Bauruine. ");
	output("`2Du riskierst einen Blick durch eines der Fenster");
	if (db_num_rows($result)>0){
		output(" und erkennst ");
		for ($i=0;$i<db_num_rows($result);$i++){
			$row2 = db_fetch_assoc($result);
			output("`@$row2[name]");
			if($i+1<db_num_rows($result)) output(", ");
		}
		output(".`n");
	}else{
		output(", aber das Haus hat sonst nichts weiter zu bieten.");
	}
	if (getsetting("dailyspecial",0)=="Waldsee"){
		output("`n`n`@Während du dir das Haus genau ansiehst, fällt dir ein kleiner Trampelpfad auf...");
		addnav("Trampelpfad","paths.php?ziel=forestlake");
	}
	if ($_GET[id]==$session[user][housekey]) addnav("Haus betreten","houses.php?op=drin&id=$_GET[id]");
	addnav("Zurück","houses.php");
}else if ($_GET[op]=="build"){
	if ($_GET[act]=="start") {
		$sql = "INSERT INTO houses (owner,status,gold,gems,housename) VALUES (".$session[user][acctid].",0,0,0,'".$session[user][login]."s Haus')";
		db_query($sql) or die(db_error(LINK));
		if (db_affected_rows(LINK)<=0) redirect("houses.php");
		$sql = "SELECT * FROM houses WHERE status=0 AND owner=".$session[user][acctid]." ORDER BY houseid DESC";
		$result = db_query($sql) or die(db_error(LINK));
		$row = db_fetch_assoc($result);
		$session[user][house]=$row[houseid];
		output("`@Du erklärst das Fleckchen Erde zu deinem Besitz und kannst mit dem Bau von Hausnummer `^$row[houseid]`@ beginnen.`n`n");
		output("`0<form action=\"houses.php?op=build&act=build2\" method='POST'>",true);
		output("`nGebe einen Namen für dein Haus ein: <input name='housename' maxlength='25'>`n",true);
		output("`nWieviel Gold anzahlen? <input type='gold' name='gold'>`n",true);
		output("`nWieviele Edelsteine? <input type='gems' name='gems'>`n",true);
		output("<input type='submit' class='button' value='Bauen'>",true);
		addnav("","houses.php?op=build&act=build2");
	}else if ($_GET[act]=="build2") {
		$sql = "SELECT * FROM houses WHERE status=0 AND owner=".$session[user][acctid]." ORDER BY houseid DESC";
		$result = db_query($sql) or die(db_error(LINK));
		$row = db_fetch_assoc($result);
		$paidgold=(int)$_POST['gold'];
		if ($_POST['housename']>""){
			$housename=stripslashes($_POST['housename']);
		}else{
			$housename=stripslashes($row[housename]);
		}
		$paidgems=(int)$_POST['gems'];
		if ($session[user][gold]<$paidgold || $session[user][gems]<$paidgems) {
			output("`@Du hast nicht genug dabei!");
			addnav("Nochmal","houses.php?op=build");
		} else if ($session[user][turns]<1){
			output("`@Du bist zu müde, um heute noch an deinem Haus zu arbeiten!");
		} else if ($paidgold<0 || $paidgems<0){
			output("`@Versuch hier besser nicht zu beschummeln.");
		} else {
			output("`@Du baust für `^$paidgold`@ Gold und `#$paidgems`@ Edelsteine an deinem Haus \"`&$housename`@\"...`n");
			$row[gold]+=$paidgold;
			$session[user][gold]-=$paidgold;
			output("`nDu verlierst einen Waldkampf.");
			$session[user][turns]--;
			if ($row[gold]>$goldcost) {
				output("`nDu hast die kompletten Goldkosten bezahlt und bekommst das überschüssige Gold zurück.");
				$session[user][gold]+=$row[gold]-$goldcost;
				$row[gold]=$goldcost;
			}
			$row[gems]+=$paidgems;
			$session[user][gems]-=$paidgems;
			if ($row[gems]>$gemcost) {
				output("`nDu hast die kompletten Edelsteinkosten bezahlt und bekommst überschüssige Edelsteine zurück.");
				$session[user][gems]+=$row[gems]-$gemcost;
				$row[gems]=$gemcost;
			}
			$goldtopay=$goldcost-$row[gold];
			$gemstopay=$gemcost-$row[gems];
			$done=round(100-((100*$goldtopay/$goldcost)+(100*$gemstopay/$gemcost))/2);
			output("`nDein Haus ist damit zu `\$$done%`@ fertig. Du musst noch `^$goldtopay`@ Gold und `#$gemstopay `@Edelsteine bezahlen, bis du einziehen kannst.");
			if ($row[gems]>=$gemcost && $row[gold]>=$goldcost) {
				output("`n`n`bGlückwunsch!`b Dein Haus ist fertig. Du bekommst `b10`b Schlüssel überreicht, von denen du 9 an andere weitergeben kannst, und besitzt nun deine eigene kleine Burg.");
				$row[gems]=0;
				$row[gold]=0;
				$session[user][housekey]=$row[houseid];
				$row[status]=1;
				addnews("`2".$session[user][name]."`3 hat das Haus `2$row[housename]`3 fertiggestellt.");
				//$sql="";
				for ($i=1;$i<10;$i++){
					$sql = "INSERT INTO items (name,owner,class,value1,value2,gold,gems,description) VALUES ('Hausschlüssel',".$session[user][acctid].",'Schlüssel',$row[houseid],$i,0,0,'Schlüssel für Haus Nummer $row[houseid]')";
					db_query($sql);
					if (db_affected_rows(LINK)<=0) output("`\$Fehler`^: Dein Inventar konnte nicht aktualisiert werden! Bitte benachrichtige den Admin. ");
				}
			}
			$sql = "UPDATE houses SET gold=$row[gold],gems=$row[gems],housename='".addslashes($housename)."',status=".(int)$row[status]." WHERE houseid=$row[houseid]";
			db_query($sql);
		}
	} else {
		if ($session[user][housekey]>0) {
			output("`@Du hast bereits Zugang zu einem fertigen Haus und brauchst kein zweites. Wenn du ein neues oder ein eigenes Haus bauen willst, musst du erst aus deinem jetzigen Zuhause ausziehen.");
		} else if ($session[user][dragonkills]<1 || ($session[user][dragonkills]==1 && $session[user][level]<5)) {
			output("`@Du hast noch nicht genug Erfahrung, um ein eigenes Haus bauen zu können. Du kannst aber bei einem Freund einziehen, wenn er dir einen Schlüssel für sein Haus gibt.");
		} else if ($session[user][turns]<1) {
			output("`@Du bist zu erschöpft, um heute noch irgendetwas zu bauen. Warte bis morgen.");
		} else if ($session[user][house]>0) {
			$sql = "SELECT * FROM houses WHERE status=0 AND owner=".$session[user][acctid]." ORDER BY houseid DESC";
			$result = db_query($sql) or die(db_error(LINK));
			$row = db_fetch_assoc($result);
			output("`@Du besichtigst die Baustelle deines neuen Hauses mit der Hausnummer `3$row[houseid]`@.`n`n");
			$goldtopay=$goldcost-$row[gold];
			$gemstopay=$gemcost-$row[gems];
			$done=round(100-((100*$goldtopay/$goldcost)+(100*$gemstopay/$gemcost))/2);
			output(grafbar(100,$done,"100%",20),true);
			output("`nEs ist zu `\$$done%`@ fertig. Du musst noch `^$goldtopay`@ Gold und `#$gemstopay `@Edelsteine bezahlen.`nWillst du jetzt weiter bauen?`n`n");
			output("`0<form action=\"houses.php?op=build&act=build2\" method='POST'>",true);
			output("`nWieviel Gold zahlen? <input type='gold' name='gold'>`n",true);
			output("`nWieviele Edelsteine? <input type='gems' name='gems'>`n",true);
			output("<input type='submit' class='button' value='Bauen'>",true);
			addnav("","houses.php?op=build&act=build2");
		} else {
			output("`@Du siehst ein schönes Fleckchen für ein Haus und überlegst dir, ob du nicht selbst eines bauen solltest, anstatt ein vorhandenes zu kaufen oder noch länger in Kneipe und Feldern zu übernachten.");
			output(" Ein Haus zu bauen würde dich `^$goldcost Gold`@ und `#$gemcost Edelsteine`@ kosten. Du mußt das nicht auf einmal bezahlen, sondern könntest immer wieder mal für einen kleineren Betrag ein Stück ");
			output("weiter bauen. Wie schnell du zu deinem Haus kommst, hängt also davon ab, wie oft und wieviel du bezahlst.`n");
			output("Du kannst in deinem zukünftigen Haus alleine wohnen, oder es mit anderen teilen. Es bietet einen sicheren Platz zum Übernachten und einen Lagerplatz für einen Teil deiner Reichtümer.");
			output(" Ein gestartetes Bauvorhaben kann nicht abgebrochen werden.`n`nWillst du mit dem Hausbau beginnen?");
			addnav("Hausbau beginnen","houses.php?op=build&act=start");
		}
	}
	addnav("Zurück zum Wohnviertel","houses.php");
	addnav("Zurück zum Dorf","village.php");
}else if ($_GET[op]=="einbruch"){
	if (!$_GET[id]){
		if ($_POST['search']>"" || $_GET['search']>""){
			if ($_GET['search']>"") $_POST['search']=$_GET['search'];
			if (strcspn($_POST['search'],"0123456789")<=1){
				$search="houseid=".intval($_POST[search])." AND ";
			}else{
				$search="%";
				for ($x=0;$x<strlen($_POST['search']);$x++){
					$search .= substr($_POST['search'],$x,1)."%";
				}
				$search="housename LIKE '".$search."' AND ";
			}
		}else{
			$search="";
		}
		$ppp=25; // Player Per Page to display
		if (!$_GET[limit]){
			$page=0;
		}else{
			$page=(int)$_GET[limit];
			addnav("Vorherige Strasse","houses.php?op=einbruch&limit=".($page-1)."&search=$_POST[search]");
		}
		$limit="".($page*$ppp).",".($ppp+1);
		$sql = "SELECT * FROM houses WHERE $search status=1 AND owner<>".$session[user][acctid]." ORDER BY houseid ASC LIMIT $limit";
		output("`c`b`^Einbruch`b`c`0`n");
		output("`@Du siehst dich um und suchst dir ein bewohntes Haus für einen Einbruch aus. ");
		output("Leider kannst du nicht erkennen, wieviele Bewohner sich gerade darin aufhalten und wie stark diese sind. So ein Einbruch ist also sehr riskant.`nFür welches Haus entscheidest du dich?`n`n");
		output("<form action='houses.php?op=einbruch' method='POST'>Nach Hausname oder Nummer <input name='search' value='$_POST[search]'> <input type='submit' class='button' value='Suchen'></form>",true);
		addnav("","houses.php?op=einbruch");
		if ($session['user']['pvpflag']=="5013-10-06 00:42:00") output("`n`&(Du hast PvP-Immunität gekauft. Diese verfällt, wenn du jetzt angreifst!)`0`n`n");
		output("<table cellspacing=0 cellpadding=2 align='center'><tr><td>`bHausNr.`b</td><td>`bName`b</td><td>`bEigentümer`b</td></tr>",true);
		$result = db_query($sql) or die(db_error(LINK));
		if (db_num_rows($result)>$ppp) addnav("Nächste Strasse","houses.php?op=einbruch&limit=".($page+1)."&search=$_POST[search]");
		if (db_num_rows($result)==0){
  			output("<tr><td colspan=4 align='center'>`&`iEs gibt momentan keine bewohnten Häuser`i`0</td></tr>",true);
		}else{
			for ($i=0;$i<db_num_rows($result);$i++){
	  			$row = db_fetch_assoc($result);
				$bgcolor=($i%2==1?"trlight":"trdark");
				output("<tr class='$bgcolor'><td align='right'>$row[houseid]</td><td><a href='houses.php?op=einbruch&id=$row[houseid]'>$row[housename]</a></td><td>",true);
				$sql = "SELECT name FROM accounts WHERE acctid=$row[owner] ORDER BY acctid DESC";
			 	$result2 = db_query($sql) or die(db_error(LINK));
				$row2 = db_fetch_assoc($result2);
				output("$row2[name]</td></tr>",true);
				addnav("","houses.php?op=einbruch&id=$row[houseid]");
			}
		}
		output("</table>",true);
		addnav("Umkehren","houses.php");
	}else{
		if ($session[user][turns]<1 || $session[user][playerfights]<=0){
			output("`nDu bist wirklich schon zu müde, um ein Haus zu überfallen.");
			addnav("Zurück","houses.php");
		}else{
			output("`2Du näherst dich vorsichtig Haus Nummer $_GET[id].");
			$session[housekey]=$_GET[id];
			// Abfrage, ob Schlüssel vorhanden!!
			$sql = "SELECT id FROM items WHERE owner=".$session[user][acctid]." AND class='Schlüssel' AND value1=".(int)$_GET[id]." ORDER BY id DESC";
			$result2 = db_query($sql) or die(db_error(LINK));
			$row2 = db_fetch_assoc($result2);
			if (db_num_rows($result2)>0) {
				output(" An der Haustür angekommen suchst du etwas, um die Tür möglichst unauffällig zu öffnen. Am besten dürfte dafür der Hausschlüssel geeignet sein, ");
				output(" den du einstecken hast.`nWolltest du wirklich gerade in ein Haus einbrechen, für das du einen Schlüssel hast?");
				addnav("Haus betreten","houses.php?op=drin&id=$_GET[id]");
				addnav("Zurück zum Dorf","village.php");
			} else {
				// Wache besiegen
				output("Deine gebückte Haltung und der schleichende Gang machen eine Stadtwache aufmerksam...`n");
				$pvptime = getsetting("pvptimeout",600);
				$pvptimeout = date("Y-m-d H:i:s",strtotime(date("r")."-$pvptime seconds"));
				$days = getsetting("pvpimmunity", 5);
				$exp = getsetting("pvpminexp", 1500);
				$sql = "SELECT acctid,level,maxhitpoints,login,housekey FROM accounts WHERE 
				(locked=0) AND 
				(alive=1 AND location=2) AND 
				(laston < '".date("Y-m-d H:i:s",strtotime(date("r")."-".getsetting("LOGINTIMEOUT",900)." sec"))."' OR loggedin=0) AND
				(age > $days OR dragonkills > 0 OR pk > 0 OR experience > $exp) AND
				(acctid <> ".$session[user][acctid].") AND 
				(pvpflag <> '5013-10-06 00:42:00') AND 
				(pvpflag < '$pvptimeout') ORDER BY maxhitpoints DESC";
				$result = db_query($sql) or die(db_error(LINK));
				$hp=0;
				$count=0;
				// count chars at home and find strongest
				if(db_num_rows($result)){
					for ($i=0;$i<db_num_rows($result);$i++){
						$row = db_fetch_assoc($result);
						$sql = "SELECT value1 FROM items WHERE value1=".(int)$session[housekey]." AND owner=$row[acctid] AND class='Schlüssel' AND hvalue=".(int)$session[housekey]." ORDER BY id";
						$result2 = db_query($sql) or die(db_error(LINK));
						if (db_num_rows($result2)>0 || ((int)$row[housekey]==(int)$session[housekey] && 0==db_num_rows(db_query("SELECT hvalue FROM items WHERE hvalue<>0 AND class='Schlüssel' AND value1<>$session[housekey] AND owner=$row[acctid]")))){
							if ($row[maxhitpoints]>$hp){
								$hp=(int)$row[maxhitpoints];
								$count++;
							}
						}
						db_free_result($result2);
					}
				}
				if ($count>0){
					$badguy = array("creaturename"=>"Stadtwache","creaturelevel"=>$session[user][level],"creatureweapon"=>"Holzknüppel","creatureattack"=>$session[user][attack],"creaturedefense"=>$session[user][defence],"creaturehealth"=>abs($session[user][maxhitpoints]-$hp)+1, "diddamage"=>0);
				}else{
					$badguy = array("creaturename"=>"Stadtwache","creaturelevel"=>$session[user][level],"creatureweapon"=>"starker Holzknüppel","creatureattack"=>$session[user][attack],"creaturedefense"=>$session[user][defence],"creaturehealth"=>abs(max($session[user][maxhitpoints], $session[user][hitpoints])), "diddamage"=>0);
					$session[user][playerfights]--;
					$session[user][reputation]-=7;
				} 
				$session[user][badguy]=createstring($badguy); 
				$fight=true;
			}
		}
	} 
}elseif ($_GET['op'] == "fight") { 
	$fight=true; 
} elseif ($_GET['op'] == "run") { 
	output("`%Die Wache lässt dich nicht entkommen!`n");
	$session[user][reputation]--;
	$fight=true; 
}else if ($_GET[op]=="einbruch2"){
	// Spieler besiegen
	$pvptime = getsetting("pvptimeout",600);
	$pvptimeout = date("Y-m-d H:i:s",strtotime(date("r")."-$pvptime seconds"));
	$days = getsetting("pvpimmunity", 5);
	$exp = getsetting("pvpminexp", 1500);
	$sql = "SELECT acctid,name,maxhitpoints,defence,attack,level,laston,loggedin,login,housekey FROM accounts WHERE 
	(locked=0) AND 
	(alive=1 AND location=2) AND 
	(laston < '".date("Y-m-d H:i:s",strtotime(date("r")."-".getsetting("LOGINTIMEOUT",900)." sec"))."' OR loggedin=0) AND
	(age > $days OR dragonkills > 0 OR pk > 0 OR experience > $exp) AND
	(acctid <> ".$session[user][acctid].") AND 
	(pvpflag <> '5013-10-06 00:42:00') AND 
	(pvpflag < '$pvptimeout') ORDER BY maxhitpoints DESC";
	$result = db_query($sql) or die(db_error(LINK));
	$athome=0;
	$name="";
	$hp=0;
	// count chars at home and find strongest
	for ($i=0;$i<db_num_rows($result);$i++){
		$row = db_fetch_assoc($result);
		$sql = "SELECT value1 FROM items WHERE value1=".(int)$session[housekey]." AND class='Schlüssel' AND owner=$row[acctid] AND hvalue=".(int)$session[housekey]." ORDER BY id";
		$result2 = db_query($sql) or die(db_error(LINK));
		if (db_num_rows($result2)>0 || ((int)$row[housekey]==(int)$session[housekey] && 0==db_num_rows(db_query("SELECT hvalue FROM items WHERE hvalue<>0 AND class='Schlüssel' AND value1<>$session[housekey] AND owner=$row[acctid]")))){
			$athome++;
			if ($row[maxhitpoints]>$hp){
				$hp=$row[maxhitpoints];
				$name=$row[login];
			}
		}
		db_free_result($result2);
	}
	addnav("Flüchte","village.php");
	if ($athome>0){
		output("`n Dir kommen $athome misstrauische Bewohner schwer bewaffnet entgegen. Der wahrscheinlich Stärkste von ihnen wird sich jeden Augenblick auf dich stürzen, ");
		output(" wenn du die Situation nicht sofort entschärfst.");
		addnav("Kämpfe","pvp.php?act=attack&bg=2&name=".rawurlencode($name));
	} else {
		output(" Du hast Glück, denn es scheint niemand daheim zu sein. Das wird sicher ein Kinderspiel.");
		addnav("Einsteigen","houses.php?op=klauen&id=$session[housekey]");
	}
}else if ($_GET[op]=="klauen"){
	if (!$_GET[id]){
		output("Und jetzt? Bitte benachrichtige den Admin. Ich weiß nicht, was ich jetzt tun soll...");
		addnav("Zurück zum Dorf","village.php");
	} else {
		addnav("Zurück zum Dorf","village.php");
		$sql = "SELECT * FROM houses WHERE houseid=".$session[housekey]." ORDER BY houseid ASC";
		$result = db_query($sql) or die(db_error(LINK));
		$row = db_fetch_assoc($result);
		$wasnu=e_rand(1,3);
		switch($wasnu){
			case 1:
			$getgems=0;
			$getgold=e_rand(0,round($row[gold]/10));
			$sql = "UPDATE houses SET gold=gold-$getgold WHERE houseid=$row[houseid]";
			break;
			case 2:
			$getgems=e_rand(0,round($row[gems]/10));
			$getgold=e_rand(0,round($row[gold]/10));
			$sql = "UPDATE houses SET gold=gold-$getgold,gems=gems-$getgems WHERE houseid=$row[houseid]";
			break;
			case 3:
			$getgems=e_rand(0,round($row[gems]/10));
			$getgold=0;
			$sql = "UPDATE houses SET gems=gems-$getgems WHERE houseid=$row[houseid]";
			break;
		}
		db_query($sql) or die(db_error(LINK));
		$session[user][gold]+=$getgold;
		$session[user][gems]+=$getgems;
		output("`@Es gelingt dir, `^$getgold `@Gold und  `#$getgems `@Edelsteine aus dem Schatz zu klauen!");
		addnews("`6".$session[user][name]."`6 erbeutet `#$getgems`6 Edelsteine und `^$getgold`6 Gold bei einem Einbruch!");
		systemmail($row[owner],"`\$Einbruch!`0","`\${$session['user']['name']}`\$ ist in dein Haus eingebrochen und hat `^$getgold`\$ Gold und `#$getgems`\$ Edelsteine erbeutet!");
	}
}else if ($HTTP_GET_VARS[op]=="fight"){
		$battle=true;
}else if ($HTTP_GET_VARS[op]=="run"){
	output("`\$Dein Stolz verbietet es dir, vor diesem Kampf wegzulaufen!`0");
	$HTTP_GET_VARS[op]="fight";
	$battle=true;

}else if ($_GET[op]=="buy"){
/*
	if ( $session[user][house]>0) {
		output("`@Du hast bereits ein Haus und brauchst kein zweites. Wenn du ein anderes Haus haben willst, musst du erst aus deinem jetzigen Zuhause ausziehen.");
	} else
*/
	 if (!$_GET[id]){
		$ppp=10; // Player Per Page to display
		if (!$_GET[limit]){
			$page=0;
		}else{
			$page=(int)$_GET[limit];
			addnav("Vorherige Seite","houses.php?op=buy&limit=".($page-1)."");
		}
		$limit="".($page*$ppp).",".($ppp+1);
		$sql = "SELECT * FROM houses WHERE status=2 OR status=3 OR status=4 ORDER BY houseid ASC LIMIT $limit";
		output("`c`b`^Unbewohnte Häuser`b`c`0`n");
		output("<table cellspacing=0 cellpadding=2 align='center'><tr><td>`bHausNr.`b</td><td>`bName`b</td><td>`bGold`b</td><td>`bEdelsteine`b</td><td>`bBemerkung`b</td></tr>",true);
		$result = db_query($sql) or die(db_error(LINK));
		if (db_num_rows($result)>$ppp) addnav("Nächste Seite","houses.php?op=buy&limit=".($page+1)."");
		if (db_num_rows($result)==0){
  			output("<tr><td colspan=4 align='center'>`&`iEs stehen momentan keine Häuser zum Verkauf`i`0</td></tr>",true);
		}else{
			for ($i=0;$i<db_num_rows($result);$i++){
	  			$row = db_fetch_assoc($result);
				$bgcolor=($i%2==1?"trlight":"trdark");
				output("<tr class='$bgcolor'><td align='right'>$row[houseid]</td><td><a href='houses.php?op=buy&id=$row[houseid]'>$row[housename]</a></td><td align='right'>$row[gold]</td><td align='right'>$row[gems]</td><td>",true);
				if ($row[status]==3){
					output("`4Verlassen`0");
				}else if ($row[status]==4){
					output("`\$Bauruine`0");
				}else if ($row[owner]==0){
					output("`^Maklerverkauf`0");
				}else{
					output("`6Privatverkauf`0");
				}
				output("</td></tr>",true);
				addnav("","houses.php?op=buy&id=$row[houseid]");
			}
		}
		output("</table>",true);
	} else {
		$sql = "SELECT * FROM houses WHERE houseid=".(int)$_GET[id]." ORDER BY houseid DESC";
		$result = db_query($sql) or die(db_error(LINK));
		$row = db_fetch_assoc($result);
		if ($session[user][acctid]==$row[owner]) {
			output("`@du hängst doch zu sehr an deinem Haus und beschließt, es noch nicht zu verkaufen.");
			$session[user][housekey]=$row[houseid];
			$sql = "UPDATE houses SET gold=0,gems=0,status=1 WHERE houseid=$row[houseid]";
			db_query($sql);
		}else if ($session[user][gold]<$row[gold] || $session[user][gems]<$row[gems]){
			output("`@Dieses edle Haus übersteigt wohl deine finanziellen Mittel.");
		}else {
			output("`@Glückwunsch zu deinem neuen Haus!`n`n");
			$session[user][gold]-=$row[gold];
			$session[user][gems]-=$row[gems];
			$session[user][house]=$row[houseid];
			output("Du übergibst `^$row[gold]`@ Gold und `#$row[gems]`@ Edelsteine an den Verkäufer, und dieser händigt dir dafür einen Satz Schlüssel für Haus `b$row[houseid]`b aus."); 
			if ($row[owner]>0){
				$sql = "UPDATE accounts SET goldinbank=goldinbank+$row[gold],gems=gems+$row[gems],house=0,housekey=0 WHERE acctid=$row[owner]";
				db_query($sql);
				systemmail($row[owner],"`@Haus verkauft!`0","`&{$session['user']['name']}`2 hat dein Haus gekauft. Du bekommst `^$row[gold]`2 Gold auf die Bank und `#$row[gems]`2!");
				$session[user][housekey]=$row[houseid];
			}
			if ($row[status]==3){
				$sql = "UPDATE houses SET status=1,owner=".$session[user][acctid]." WHERE houseid=$row[houseid]";
				db_query($sql);
				$sql = "UPDATE items SET owner=".$session[user][acctid]." WHERE owner=0 and class='Schlüssel' AND value1=$row[houseid]";
				output(" Bitte bedenke, dass du ein verlassenes Haus gekauft hast, zu dem vielleicht noch andere einen Schlüssel haben!");
				$session[user][housekey]=$row[houseid];
			}else if ($row[status]==4){
				$sql = "UPDATE houses SET status=0,owner=".$session[user][acctid]." WHERE houseid=$row[houseid]";
				output(" Bitte bedenke, dass du eine Bauruine gekauft hast, die du erst fertigbauen musst!");
			}else{
				$sql = "UPDATE houses SET gold=0,gems=0,status=1,owner=".$session[user][acctid]." WHERE houseid=$row[houseid]";
				db_query($sql);
				$sql = "UPDATE items SET owner=".$session[user][acctid]." WHERE class='Schlüssel' AND value1=$row[houseid]";
				$session[user][housekey]=$row[houseid];
			}
			db_query($sql);
		}
	}
	addnav("W?Zurück zum Wohnviertel","houses.php");
	addnav("Zurück zum Dorf","village.php");
}else if ($_GET[op]=="sell"){
	$sql = "SELECT * FROM houses WHERE houseid=".$session[user][housekey]." ORDER BY houseid DESC";
	$result = db_query($sql) or die(db_error(LINK));
	$row = db_fetch_assoc($result);
	$halfgold=round($goldcost/3);
	$halfgems=round($gemcost/3);
	if ($_GET[act]=="sold"){
		if (!$_POST[gold] && !$_POST[gems]){
			output("`@Du denkst ernsthaft darüber nach, dein Häuschen zu verkaufen. Wenn du selbst einen Preis festlegst, bedenke, daß er auf einmal bezahlt werden muss ");
			output(" und vom Käufer nicht in Raten abgezahlt werden kann. Außerdem kannst du weder ein neues Haus bauen, noch in diesem Haus wohnen, bis es verkauft ist.");
			output(" Du bekommst dein Geld erst, wenn das Haus verkauft ist. Der Verkauf läßt sich abbrechen, indem du selbst das Haus von dir kaufst.");
			output("`nWenn du sofort Geld sehen willst, musst du dein Haus für `^$halfgold`@ Gold und `#$halfgems`@ Edelsteine an einen Makler verkaufen.");
			output("`0<form action=\"houses.php?op=sell&act=sold\" method='POST'>",true);
			output("`nWieviel Gold willst du verlangen? <input type='gold' name='gold'>`n",true);
			output("`nWieviele Edelsteine soll das Haus kosten? <input type='gems' name='gems'>`n",true);
			output("<input type='submit' class='button' value='Anbieten'>",true);
			addnav("","houses.php?op=sell&act=sold");
			addnav("An den Makler","houses.php?op=sell&act=makler");
		}else{
			$halfgold=(int)$_POST[gold];
			$halfgems=(int)$_POST[gems];
			if (($halfgold<$goldcost/40 && $halfgems<$gemcost/10) || ($halfgold==0 && $halfgems<$gemcost/2) || ($halfgold<$goldcost/20 && $halfgems==0)){
				output("`@Du solltest vielleicht erst deinen Ale-Rausch ausschlafen, bevor du über einen Preis nachdenkst. Wie? Du bist nüchtern? Das glaubt dir so kein Mensch.");
				addnav("Neuer Preis","houses.php?op=sell&act=sold");
			}else if ($halfgold>$goldcost*2 || $halfgems>$gemcost*4){
				output("`@Bei so einem hohen Preis bist du dir nicht sicher, ob du wirklich verkaufen sollst. Überlege es dir nochmal.");
				addnav("Neuer Preis","houses.php?op=sell&act=sold");
			}else{
				output("`@Dein Haus steht ab sofort für `^$halfgold`@ Gold und `#$halfgems`@ Edelsteine zum Verkauf. Du und alle Mitbewohner habt den Schatz des Hauses gleichmäßig ");
				output(" unter euch aufgeteilt und deine Untermieter haben ihre Schlüssel abgegeben.");
				// Gold und Edelsteine an Bewohner verteilen und Schlüssel einziehen
				$sql = "SELECT owner FROM items WHERE value1=$row[houseid] AND class='Schlüssel' AND owner<>$row[owner] ORDER BY id ASC";
				$result = db_query($sql) or die(db_error(LINK));
				$amt=db_num_rows($result);
				$goldgive=round($row[gold]/($amt+1));
				$gemsgive=round($row[gems]/($amt+1));
				$session[user][gold]+=$goldgive;
				$session[user][gems]+=$gemsgive;
				// $sql="";
				for ($i=0;$i<db_num_rows($result);$i++){
					$item = db_fetch_assoc($result);
					$sql = "UPDATE accounts SET goldinbank=goldinbank+$goldgive,gems=gems+$gemsgive WHERE acctid=$item[owner]";
					db_query($sql);
					systemmail($item[owner],"`@Rauswurf!`0","`&{$session['user']['name']}`2 hat das Haus `b$row[housename]`b`2 verkauft, in dem du als Untermieter gewohnt hast. Du bekommst `^$goldgive`2 Gold auf die Bank und `#$gemsgive`2 Edelsteine aus dem gemeinsamen Schatz ausbezahlt!");
				}
				$sql = "UPDATE items SET owner=$row[owner] WHERE class='Schlüssel' AND value1=$row[houseid]";
				db_query($sql);
				// Variablen setzen und Datenbank updaten
				$row[gold]=$halfgold;
				$row[gems]=$halfgems;
				$session[user][housekey]=0;
				$sql = "UPDATE houses SET gold=$row[gold],gems=$row[gems],status=2 WHERE houseid=$row[houseid]";
				db_query($sql);
			}
		}
	}else if ($_GET[act]=="makler"){
		output("`@Dem Makler entfährt ungewollt ein freudiges Glucksen, als er dir `^$halfcost`@ Gold und die `#$halfcost`@ Edelsteine vorzählt.`n`n");
		output("Ab sofort steht dein Haus zum Verkauf und du kannst ein neues bauen, woanders mit einziehen, oder ein anderes Haus kaufen.");
		// Gold und Edelsteine an Bewohner verteilen und Schlüssel einziehen
		$sql = "SELECT owner FROM items WHERE value1=$row[houseid] AND class='Schlüssel' AND owner<>$row[owner] ORDER BY id ASC";
		$result = db_query($sql) or die(db_error(LINK));
		$goldgive=round($row[gold]/(db_num_rows($result)+1));
		$gemsgive=round($row[gems]/(db_num_rows($result)+1));
		$session[user][gold]+=$goldgive;
		$session[user][gems]+=$gemsgive;
		//$sql="";
		for ($i=0;$i<db_num_rows($result);$i++){
			$item = db_fetch_assoc($result);
			$sql = "UPDATE accounts SET goldinbank=goldinbank+$goldgive,gems=gems+$gemsgive WHERE acctid=$item[owner]";
			db_query($sql);
			systemmail($item[owner],"`@Rauswurf!`0","`&{$session['user']['name']}`2 hat das Haus `b$row[housename]`b`2 verkauft, in dem du als Untermieter gewohnt hast. Du bekommst `^$goldgive`2 Gold auf die Bank und `#$gemsgive`2 Edelsteine aus dem gemeinsamen Schatz ausbezahlt!");

		}
		$sql = "UPDATE items SET owner=0 WHERE class='Schlüssel' AND value1=$row[houseid]";
		db_query($sql);
		// Variablen setzen und Datenbank updaten
		$row[gold]=$goldcost-$halfgold;
		$row[gems]=$gemcost;
		$session[user][gold]+=$halfgold;
		$session[user][gems]+=$halfgems;
		$session[user][house]=0;
		$session[user][housekey]=0;
		$session[user][donation]+=1;
		$sql = "UPDATE houses SET owner=0,gold=$row[gold],gems=$row[gems],status=2 WHERE houseid=$row[houseid]";
		db_query($sql);
	} else {
		output("`@Gib einen Preis für dein Haus ein, oder lass einen Makler den Verkauf übernehmen. Der schmierige Makler würde dir sofort `^$halfgold`@ Gold und `#$halfgems`@ Edelsteine geben. ");
		output("Wenn du selbst verkaufst, kannst du vielleicht einen höheren Preis erzielen, musst aber auf dein Geld warten, bis jemand kauft.`nAlles, was sich noch im Haus befindet, wird ");
		output("gleichmässig unter allen Bewohnern aufgeteilt.`n`n");
		output("`0<form action=\"houses.php?op=sell&act=sold\" method='POST'>",true);
		output("`nWieviel Gold verlangen? <input type='gold' name='gold'>`n",true);
		output("`nWieviele Edelsteine? <input type='gems' name='gems'>`n`n",true);
		output("<input type='submit' class='button' value='Für diesen Preis verkaufen'></form>",true);
		addnav("","houses.php?op=sell&act=sold");
		addnav("An den Makler","houses.php?op=sell&act=makler");
	}
	addnav("W?Zurück zum Wohnviertel","houses.php");
	addnav("Zurück zum Dorf","village.php");
}else if ($_GET[op]=="drin"){
	if ($_GET[id]) $session[housekey]=(int)$_GET[id];
	if (!$session[housekey]) redirect("houses.php");
	$sql = "SELECT * FROM houses WHERE houseid=".$session[housekey]." ORDER BY houseid DESC";
	$result = db_query($sql) or die(db_error(LINK));
	$row = db_fetch_assoc($result);
	if ($_GET[act]=="takekey"){
		if (!$_POST[ziel]){
			$sql = "SELECT owner FROM items WHERE value1=$row[houseid] AND class='Schlüssel' ORDER BY value2 ASC";
			$result = db_query($sql) or die(db_error(LINK));
			output("<form action='houses.php?op=drin&act=takekey' method='POST'>",true);
			output("`2Wem willst du den Schlüssel wegnehmen? <select name='ziel'>",true);
			for ($i=0;$i<db_num_rows($result);$i++){
				$item = db_fetch_assoc($result);
				$sql = "SELECT acctid,name,login FROM accounts WHERE acctid=$item[owner] ORDER BY login DESC";
				$result2 = db_query($sql) or die(db_error(LINK));
				$row2 = db_fetch_assoc($result2);
				if ($amt!=$row2[acctid] && $row2[acctid]!=$row[owner]) output("<option value=\"".rawurlencode($row2['name'])."\">".preg_replace("'[`].'","",$row2['name'])."</option>",true);
				$amt=$row2[acctid];
			}
			output("</select>`n`n",true);
			output("<input type='submit' class='button' value='Schlüssel abnehmen'></form>",true);
			addnav("","houses.php?op=drin&act=takekey");
		}else{
			$sql = "SELECT acctid,name,login,gold,gems FROM accounts WHERE name='".addslashes(rawurldecode(stripslashes($_POST['ziel'])))."' AND locked=0";
			$result2 = db_query($sql);
			$row2  = db_fetch_assoc($result2);
			output("`2Du verlangst den Schlüssel von `&$row2[name]`2 zurück.`n");
			$sql = "SELECT owner FROM items WHERE value1=$row[houseid] AND class='Schlüssel' AND owner<>$row[owner] ORDER BY id ASC";
			$result = db_query($sql) or die(db_error(LINK));
			$goldgive=round($row[gold]/(db_num_rows($result)+1));
			$gemsgive=round($row[gems]/(db_num_rows($result)+1));
			systemmail($row2[acctid],"`@Schlüssel zurückverlangt!`0","`&{$session['user']['name']}`2 hat den Schlüssel zu Haus Nummer `b$row[houseid]`b ($row[housename]`2) zurückverlangt. Du bekommst `^$goldgive`2 Gold auf die Bank und `#$gemsgive`2 Edelsteine aus dem gemeinsamen Schatz ausbezahlt!");
			output("$row2[name]`2 bekommt `^$goldgive`2 Gold und `#$gemsgive`2 Edelsteine aus dem gemeinsamen Schatz.");
			$sql = "UPDATE items SET owner=$row[owner],hvalue=0 WHERE owner=$row2[acctid] AND class='Schlüssel' AND value1=$row[houseid]";
			db_query($sql);
			$sql = "UPDATE accounts SET goldinbank=goldinbank+$goldgive,gems=gems+$gemsgive WHERE acctid=$row2[acctid]";
			db_query($sql);
			$sql = "UPDATE houses SET gold=gold-$goldgive,gems=gems-$gemsgive WHERE houseid=$row[houseid]";
			db_query($sql);
			$sql = "INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),'house-".$row[houseid]."',".$session[user][acctid].",'/me `^nimmt $row2[name]`^ einen Schlüssel ab. $row2[name]`^ bekommt einen Teil aus dem Schatz.')";
			db_query($sql) or die(db_error(LINK));
		}
		addnav("Zurück zum Haus","houses.php?op=drin");
	}else if ($_GET[act]=="givekey"){
		if (!$_POST['ziel']){
			output("`2Einen Schlüssel für dieses Haus hat:`n`n");
			$sql = "SELECT items.*,accounts.name AS besitzer FROM items LEFT JOIN accounts ON accounts.acctid=items.owner WHERE value1=$row[houseid] AND class='Schlüssel' AND owner<>".$session[user][acctid]." ORDER BY value2 ASC";
			$result = db_query($sql) or die(db_error(LINK));
			for ($i=0;$i<db_num_rows($result);$i++){
				$item = db_fetch_assoc($result);
				output("`c`& $item[besitzer]`0`c");
			}
			$sql = "SELECT value2 FROM items WHERE value1=$row[houseid] AND class='Schlüssel' AND owner=$row[owner] ORDER BY id ASC";
			$result = db_query($sql) or die(db_error(LINK));
			if (db_num_rows($result)>0) {
				output("`n`2Du kannst noch `b".db_num_rows($result)."`b Schlüssel vergeben.");
				output("<form action='houses.php?op=drin&act=givekey' method='POST'>",true);
				output("An wen willst du einen Schlüssel übergeben? <input name='ziel'>`n", true);
				output("<input type='submit' class='button' value='Übergeben'></form>",true);
				output("`n`nWenn du einen Schlüssel vergibst, wird der Schatz des Hauses gemeinsam genutzt. Du kannst einem Mitbewohner zwar jederzeit den Schlüssel wieder wegnehmen, ");
				output("aber er wird dann einen gerechten Anteil aus dem gemeinsamen Schatz bekommen.");
				addnav("","houses.php?op=drin&act=givekey");
			}else{
				output("`n`2Du hast keine Schlüssel mehr übrig. Vielleicht kannst du in der Jägerhütte noch einen nachmachen lassen?");
			} 
		} else {
			if ($_GET['subfinal']==1){
				$sql = "SELECT acctid,name,login,lastip,emailaddress,dragonkills,level,sex FROM accounts WHERE name='".addslashes(rawurldecode(stripslashes($_POST['ziel'])))."' AND locked=0";
			}else{
				$ziel = stripslashes(rawurldecode($_POST['ziel']));
				$name="%";
				for ($x=0;$x<strlen($ziel);$x++){
					$name.=substr($ziel,$x,1)."%";
				}
				$sql = "SELECT acctid,name,login,lastip,emailaddress,dragonkills,level,sex FROM accounts WHERE name LIKE '".addslashes($name)."' AND locked=0";
			}
			$result2 = db_query($sql);
			if (db_num_rows($result2) == 0) {
				output("`2Es gibt niemanden mit einem solchen Namen. Versuchs nochmal.");
			} elseif(db_num_rows($result2) > 100) {
				output("`2Es gibt über 100 Krieger mit einem ähnlichen Namen. Bitte sei etwas genauer.");
			} elseif(db_num_rows($result2) > 1) {
				output("`2Es gibt mehrere mögliche Krieger, denen du einen Schlüssel übergeben kannst.`n");
				output("<form action='houses.php?op=drin&act=givekey&subfinal=1' method='POST'>",true);
				output("`2Wen genau meinst du? <select name='ziel'>",true);
				for ($i=0;$i<db_num_rows($result2);$i++){
					$row2 = db_fetch_assoc($result2);
					output("<option value=\"".rawurlencode($row2['name'])."\">".preg_replace("'[`].'","",$row2['name'])."</option>",true);
				}
				output("</select>`n`n",true);
				output("<input type='submit' class='button' value='Schlüssel übergeben'></form>",true);
				addnav("","houses.php?op=drin&act=givekey&subfinal=1");
				//addnav("","houses.php?op=drin&act=givekey"); // why the hell was this in there?
			} else {
				$row2  = db_fetch_assoc($result2);
				$sql = "SELECT owner FROM items WHERE owner=$row2[acctid] AND value1=$row[houseid] AND class='Schlüssel' ORDER BY id ASC";
				$result = db_query($sql) or die(db_error(LINK));
				$item = db_fetch_assoc($result);
				if ($row2[login] == $session[user][login]) {
					output("`2Du kannst dir nicht selbst einen Schlüssel geben.");
				} elseif ($item[owner]==$row[owner]) {
					output("`2$row2[name]`2 hat bereits einen Schlüssel!");
		 		} elseif ($session['user']['lastip'] == $row2['lastip'] || ($session['user']['emailaddress'] == $row2['emailaddress'] && $row2[emailaddress])){
					output("`2Deine Charaktere dürfen leider nicht miteinander interagieren!");
				} elseif ($row2['level']<5 && $row2['dragonkills']<1){
					output("`2$row2[name]`2 ist noch nicht lange genug um Dorf, als dass du ".($row2['sex']?"ihr":"ihm")." vertrauen könntest. Also beschließt du, noch eine Weile zu beobachten.");
				} else {
					$sql = "SELECT value2 FROM items WHERE value1=$row[houseid] AND class='Schlüssel' AND owner=$row[owner] ORDER BY id ASC LIMIT 1";
					$result = db_query($sql) or die(db_error(LINK));
					$knr = db_fetch_assoc($result);
					$knr=$knr[value2];
					output("`2Du übergibst `&$row2[name]`2 einen Schlüssel für dein Haus. Du kannst den Schlüssel zum Haus jederzeit wieder wegnehmen, aber $row2[name]`2 wird dann ");
					output("einen gerechten Anteil aus dem gemeinsamen Schatz des Hauses bekommen.`n");
					systemmail($row2[acctid],"`@Schlüssel erhalten!`0","`&{$session['user']['name']}`2 hat dir einen Schlüssel zu Haus Nummer `b$row[houseid]`b ($row[housename]`2) gegeben!");
					$sql = "UPDATE items SET owner=$row2[acctid],hvalue=0 WHERE owner=$row[owner] AND class='Schlüssel' AND value1=$row[houseid] AND value2=$knr";
					db_query($sql);
					$sql = "INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),'house-".$row[houseid]."',".$session[user][acctid].",'/me `^gibt $row2[name]`^ einen Schlüssel.')";
					db_query($sql) or die(db_error(LINK));
				}
			}
		}
		addnav("Zurück zum Haus","houses.php?op=drin");
	}else if ($_GET[act]=="takegold"){
		$maxtfer = $session[user][level]*getsetting("transferperlevel",25);
		if (!$_POST[gold]){
			$transleft = getsetting("transferreceive",3) - $session[user][transferredtoday];
			output("`2Es befindet sich `^$row[gold]`2 Gold in der Schatztruhe des Hauses.`nDu darfst heute noch $transleft x bis zu `^$maxtfer`2 Gold mitnehmen.`n");
			output("`2<form action=\"houses.php?op=drin&act=takegold\" method='POST'>",true);
			output("`nWieviel Gold mitnehmen? <input type='gold' name='gold'>`n`n",true);
			output("<input type='submit' class='button' value='Mitnehmen'>",true);
			addnav("","houses.php?op=drin&act=takegold");
		}else{
			$amt=abs((int)$_POST[gold]);
			if ($amt>$row[gold]){
				output("`2So viel Gold ist nicht mehr da.");
			}else if ($maxtfer<$amt){
				output("`2Du darfst maximal `^$maxtfer`2 Gold auf einmal nehmen.");
			}else if ($amt<0){
				output("`2Wenn du etwas in den Schatz legen willst, versuche nicht, etwas negatives herauszunehmen.");
			}else if($session[user][transferredtoday]>=getsetting("transferreceive",3)){
				output("`2Du hast heute schon genug Gold bekommen. Du wirst bis morgen warten müssen.");
			}else{
				$row[gold]-=$amt;
				$session[user][gold]+=$amt;
				$session[user][transferredtoday]+=1;
				$sql = "UPDATE houses SET gold=$row[gold] WHERE houseid=$row[houseid]";
				db_query($sql) or die(db_error(LINK));
				output("`2Du hast `^$amt`2 Gold genommen. Insgesamt befindet sich jetzt noch `^$row[gold]`2 Gold im Haus.");
				$sql = "INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),'house-".$row[houseid]."',".$session[user][acctid].",'/me `\$nimmt `^$amt`\$ Gold.')";
				db_query($sql) or die(db_error(LINK));
			}
		}
		addnav("Zurück zum Haus","houses.php?op=drin");
	}else if ($_GET[act]=="givegold"){
		$maxout = $session[user][level]*getsetting("maxtransferout",25);
		if (!$_POST[gold]){
			$transleft = $maxout - $session[user][amountouttoday];
			output("`2Du darfst heute noch `^$transleft`2 Gold deponieren.`n");
			output("`2<form action=\"houses.php?op=drin&act=givegold\" method='POST'>",true);
			output("`nWieviel Gold deponieren? <input type='gold' name='gold'>`n`n",true);
			output("<input type='submit' class='button' value='Deponieren'>",true);
			addnav("","houses.php?op=drin&act=givegold");
		}else{
			$amt=abs((int)$_POST[gold]);
			if ($amt>$session[user][gold]){
				output("`2So viel Gold hast du nicht dabei.");
			}else if($row[gold]>round($goldcost/2)){
				output("`2Der Schatz ist voll.");
			}else if($amt>(round($goldcost/2)-$row[gold])){
				output("`2Du gibst alles, aber du bekommst beim besten Willen nicht so viel in den Schatz.");
			}else if ($amt<0){
				output("`2Wenn du etwas aus dem Schatz nehmen willst, versuche nicht, etwas negatives hineinzutun.");
			}else if ($session[user][amountouttoday]+$amt > $maxout) {
				output("`2Du darfst nicht mehr als `^$maxout`2 Gold pro Tag deponieren.");
			}else{
				$row[gold]+=$amt;
				$session[user][gold]-=$amt;
				$session[user][amountouttoday]+= $amt;
				output("`2Du hast `^$amt`2 Gold deponiert. Insgesamt befinden sich jetzt `^$row[gold]`2 Gold im Haus.");
				$sql = "UPDATE houses SET gold=$row[gold] WHERE houseid=$row[houseid]";
				db_query($sql) or die(db_error(LINK));
				$sql="INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),'house-".$row[houseid]."',".$session[user][acctid].",'/me `@deponiert `^$amt`@ Gold.')";
				db_query($sql) or die(db_error(LINK));
			}
		}
		addnav("Zurück zum Haus","houses.php?op=drin");
	}else if ($_GET[act]=="takegems"){
		if (!$_POST[gems]){
			output("`2Es befinden sich `#$row[gems]`2 Edelsteine in der Schatztruhe des Hauses.`n`n");
			output("`2<form action=\"houses.php?op=drin&act=takegems\" method='POST'>",true);
			output("`nWieviele Edelsteine mitnehmen? <input type='gems' name='gems'>`n`n",true);
			output("<input type='submit' class='button' value='Mitnehmen'>",true);
			addnav("","houses.php?op=drin&act=takegems");
		}else{
			$amt=abs((int)$_POST[gems]);
			if ($amt>$row[gems]){
				output("`2So viele Edelsteine sind nicht mehr da.");
			}else if ($amt<0){
				output("`2Wenn du etwas in den Schatz legen willst, versuche nicht, etwas negatives herauszunehmen.");
			}else{
				$row[gems]-=$amt;
				$session[user][gems]+=$amt;
				$sql = "UPDATE houses SET gems=$row[gems] WHERE houseid=$row[houseid]";
				db_query($sql);
				output("`2Du hast `#$amt`2 Edelsteine genommen. Insgesamt befinden sich jetzt noch `#$row[gems]`2 Edelsteine im Haus.");
				$sql = "INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),'house-".$row[houseid]."',".$session[user][acctid].",'/me `\$nimmt `#$amt`\$ Edelsteine.')";
				db_query($sql) or die(db_error(LINK));
			}
		}
		addnav("Zurück zum Haus","houses.php?op=drin");
	}else if ($_GET[act]=="givegems"){
		if (!$_POST[gems]){
			output("`2<form action=\"houses.php?op=drin&act=givegems\" method='POST'>",true);
			output("`nWieviele Edelsteine deponieren? <input type='gems' name='gems'>`n`n",true);
			output("<input type='submit' class='button' value='Deponieren'>",true);
			addnav("","houses.php?op=drin&act=givegems");
		}else{
			$amt=abs((int)$_POST[gems]);
			if ($amt>$session[user][gems]){
				output("`2So viele Edelsteine hast du nicht.");
			}else if($row[gems]>=round($gemcost/2)){
				output("`2Der Schatz ist voll.");
			}else if($amt>(round($gemcost/2)-$row[gems])){
				output("`2Du gibst alles, aber du bekommst beim besten Willen nicht so viel in den Schatz.");
			}else if ($amt<0){
				output("`2Wenn du etwas aus dem Schatz nehmen willst, versuche nicht, etwas negatives hineinzutun.");
			}else{
				$row[gems]+=$amt;
				$session[user][gems]-=$amt;
				$sql = "UPDATE houses SET gems=$row[gems] WHERE houseid=$row[houseid]";
				db_query($sql);
				output("`2Du hast `#$amt`2 Edelsteine deponiert. Insgesamt befinden sich jetzt `#$row[gems]`2 Edelsteine im Haus.");
				$sql = "INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),'house-".$row[houseid]."',".$session[user][acctid].",'/me `@deponiert `#$amt`@ Edelsteine.')";
				db_query($sql) or die(db_error(LINK));
			}
		}
		addnav("Zurück zum Haus","houses.php?op=drin");
	}else if ($_GET[act]=="rename"){
		if (!$_POST[housename]){
			output("`2Das Haus umbenennen kostet `^1000`2 Gold und `#1`2 Edelstein.`n`n");
			output("`0<form action=\"houses.php?op=drin&act=rename\" method='POST'>",true);
			output("`nGebe einen neuen Namen für dein Haus ein: <input name='housename' maxlength='25'>`n",true);
			output("<input type='submit' class='button' value='Umbenennen'>",true);
			addnav("","houses.php?op=drin&act=rename");
		}else{
			if ($session[user][gold]<1000 || $session[user][gems]<1){
				output("`2Das kannst du nicht bezahlen.");
			}else{
				output("`2Dein Haus `@$row[housename]`2 heißt jetzt `@".stripslashes($_POST[housename])."`2.");
				$sql = "UPDATE houses SET housename='".$_POST[housename]."' WHERE houseid=$row[houseid]";
				db_query($sql);
				$session[user][gold]-=1000;
				$session[user][gems]-=1;
			}
		}
		addnav("Zurück zum Haus","houses.php?op=drin");
	}else if ($_GET[act]=="desc"){
		if (!$_POST[desc]){
			output("`2Hier kannst du die Beschreibung für dein Haus ändern.`n`nDie aktuelle Beschreibung lautet:`0$row[description]`0`n");
			output("`0<form action=\"houses.php?op=drin&act=desc\" method='POST'>",true);
			output("`n`2Gebe eine Beschreibung für dein Haus ein:`n<input name='desc' maxlength='250' size='50'>`n",true);
			output("<input type='submit' class='button' value='Abschicken'>",true);
			addnav("","houses.php?op=drin&act=desc");
		}else{
			output("`2Die Beschreibung wurde geändert.`n`0".stripslashes($_POST[desc])."`2.");
			$sql = "UPDATE houses SET description='".$_POST[desc]."' WHERE houseid=$row[houseid]";
			db_query($sql);
		}
		addnav("Zurück zum Haus","houses.php?op=drin");
	}else if ($_GET[act]=="logout"){
		if ($session[user][housekey]!=$session[housekey]){
			$sql = "UPDATE items SET hvalue=".$session[housekey]." WHERE value1=".(int)$session[housekey]." AND owner=".$session[user][acctid]." AND class='Schlüssel'";
			db_query($sql) or die(sql_error($sql));
		}
		debuglog("logged out in a house ");
		$session['user']['location']=2;
		$session['user']['loggedin']=0;
		$sql = "UPDATE accounts SET loggedin=0,location=2 WHERE acctid = ".$session[user][acctid];
		db_query($sql) or die(sql_error($sql));
		$session=array();
		redirect("index.php");
// actions by items/furniture
	}else if ($_GET[act]=="mirror"){
		switch(e_rand(1,5)){
			case 1:
			output("`2Du blickst in den Spiegel, in der Hoffnung etwas erfreuliches zu sehen. ");
			break;
			case 2:
			output("`2Dir starrt ".($session[user][sex]?"eine völlig unbekannte Frau":"ein völlig unbekannter Mann")." entgegen. ");
			break;
			case 3:
			if ($session[user][charm]<10) output("`2Du fragst dich, warum dieses häßliche Bild im Haus hängt. ");
			if ($session[user][charm]>=10 and $session[user][charm]<50) output("`2Naja, wer auch immer dieses Bild gemalt hat, hat Talent, sollte aber noch etwas üben. ");
			if ($session[user][charm]>=50) output("`2Das ist ein wirklich tolles Bild von ".($session[user][sex]?"einer Frau":"einem Mann")."! ");
			break;
			case 4:
			output("`2Erstaunlicher Apparat. ");
			break;
			case 5:
			output("`2Du verbringst eine ganze Weile vor dem Spiegel. ");
			if (e_rand(1,3)==2 && $sesion[user][turns]>0){
				output("Dabei merkst du nicht, wie die Zeit vergeht. Du vertrödelst einen Waldkampf! ");
				$session[user][turns]--;
			}
			break;
		}
		$was=e_rand(1,3);
		if ($was==1 && $session[user][turns]>0){
			$session[user][charm]--;
			if ($session[user][charm]<=0) $session[user][charm]=0;
			output("`nDu `4verlierst`2 einen Charmepunkt!");
		}else if ($was==3 && $session[user][turns]>0){
			$session[user][charm]++;
			output("`nDu `@bekommst`2 einen Charmepunkt.");
		}else if ($session[user][turns]<=0){
			output("`nDu hast heute keine Zeit mehr, dich um dein Äußeres zu kümmern.");
		}else{
		}
		addnav("Zurück zum Haus","houses.php?op=drin");
// end items
	}else{
		output("`2`b`c$row[housename]`c`b`n");
		if ($row[description]) output("`0`c$row[description]`c`n");
		output("`2Du und deine Mitbewohner haben `^$row[gold]`2 Gold und `#$row[gems]`2 Edelsteine im Haus gelagert.`n");
		if (getsetting('activategamedate','0')==1) output("Wir schreiben den `^".getgamedate()."`2.`n");
		output ("Es ist jetzt `^".getgametime()."`2 Uhr.`n`n");
		viewcommentary("house-".$row[houseid],"Mit Mitbewohnern reden:",30,"sagt");
		output("`n`n`n<table border='0'><tr><td>`2`bDie Schlüssel:`b `0</td><td>`2`bExtra Ausstattung`b</td></tr><tr><td valign='top'>",true);
		$sql = "SELECT items.*,accounts.acctid AS aid,accounts.name AS besitzer FROM items LEFT JOIN accounts ON accounts.acctid=items.owner WHERE value1=$row[houseid] AND class='Schlüssel' ORDER BY id ASC";
		$result = db_query($sql) or die(db_error(LINK));
		for ($i=1;$i<=db_num_rows($result);$i++){
			$item = db_fetch_assoc($result);
			if ($item[besitzer]==""){
				output("`n`2$i: `4`iVerloren`i`0");
			}else{
				output("`n`2$i: `&$item[besitzer]`0");
			}
			if ($item[aid]==$row[owner]) output(" (der Eigentümer) ");
			if ($item['hvalue']>0 && $item['owner']>0) output(" `ischläft hier`i");
		}
		if (db_num_rows(db_query("SELECT id FROM items WHERE owner=$row[owner] AND value1<>$row[houseid] AND hvalue>0 AND class='Schlüssel'"))==0 && db_num_rows(db_query("SELECT acctid FROM accounts WHERE acctid=$row[owner] AND location=2"))>0) output("`nDer Eigentümer schläft hier");
		output("</td><td valign='top'>",true);
		$sql = "SELECT * FROM items WHERE value1=$row[houseid] AND class='Möbel' ORDER BY class,id ASC";
		$result = db_query($sql) or die(db_error(LINK));
		for ($i=1;$i<=db_num_rows($result);$i++){
			$item = db_fetch_assoc($result);
			output("`n`&$item[name]`0 (`i$item[description]`i)");
			if ($item[name]=="Großer Spiegel") addnav("Spiegel","houses.php?op=drin&act=mirror");
		}
		output("</td></tr></table>",true);
		addnav("Gold");
		addnav("Deponieren","houses.php?op=drin&act=givegold");
		addnav("Mitnehmen","houses.php?op=drin&act=takegold");
		addnav("Edelsteine");
		addnav("Deponieren","houses.php?op=drin&act=givegems");
		addnav("Mitnehmen","houses.php?op=drin&act=takegems");
		if ($session[user][house]==$session[housekey]){
			addnav("Schlüssel");
			addnav("Vergeben","houses.php?op=drin&act=givekey");
			addnav("n?Zurücknehmen","houses.php?op=drin&act=takekey");
		}
		addnav("Sonstiges");
		if ($session[user][house]==$session[housekey]){
			addnav("Haus umbenennen","houses.php?op=drin&act=rename");
			addnav("Beschreibung ändern","houses.php?op=drin&act=desc");
		}
		addnav("Log Out","houses.php?op=drin&act=logout");
		addnav("W?Zurück zum Wohnviertel","houses.php");
		addnav("Zurück zum Dorf","village.php");
	}
}else if ($_GET[op]=="enter"){
	output("`@Du hast Zugang zu folgenden Häusern:`n`n");
	$sql = "UPDATE items SET hvalue=0 WHERE hvalue>0 AND owner=".$session[user][acctid]." AND class='Schlüssel'";
	db_query($sql) or die(sql_error($sql));
	$sql = "SELECT * FROM items WHERE owner=".$session[user][acctid]." AND class='Schlüssel' ORDER BY id ASC";
	$result = db_query($sql) or die(db_error(LINK));
	output("<table cellpadding=2 align='center'><tr><td>`bHausNr.`b</td><td>`bName`b</td></tr>",true);
	$ppp=25; // Player Per Page +1 to display
	if (!$_GET[limit]){
		$page=0;
	}else{
		$page=(int)$_GET[limit];
		addnav("Vorherige Straße","houses.php?op=enter&limit=".($page-1)."");
	}
	$limit="".($page*$ppp).",".($ppp+1);
	if ($session[user][house]>0 && $session[user][housekey]>0){
		$sql = "SELECT houseid,housename FROM houses WHERE houseid=".$session[user][house]." ORDER BY houseid DESC LIMIT $limit";
		$result2 = db_query($sql) or die(db_error(LINK));
		$row2 = db_fetch_assoc($result2);
		output("<tr><td align='center'>$row2[houseid]</td><td><a href='houses.php?op=drin&id=$row2[houseid]'>$row2[housename]</a> (dein eigenes)</td></tr>",true);
		addnav("","houses.php?op=drin&id=$row2[houseid]");
	}else if ($session[user][house]>0 && $session[user][housekey]==0){
		output("<tr><td colspan=2 align='center'>`&`iDein Haus ist noch im Bau oder steht zum Verkauf`i`0</td></tr>",true);
	}
	if (db_num_rows($result)>$ppp) addnav("Nächste Seite","houses.php?op=enter&limit=".($page+1)."");
	if (db_num_rows($result)==0){
		output("<tr><td colspan=4 align='center'>`&`iDu hast keinen Schlüssel`i`0</td></tr>",true);
	}else{
		$rebuy=0;
		for ($i=0;$i<db_num_rows($result);$i++){
			$item = db_fetch_assoc($result);
			if ($item[value1]==$session[user][house] && $session[user][housekey]==0) $rebuy=1;
			$bgcolor=($i%2==1?"trlight":"trdark");
			$sql = "SELECT houseid,housename FROM houses WHERE houseid=$item[value1] ORDER BY houseid DESC";
			$result2 = db_query($sql) or die(db_error(LINK));
			$row2 = db_fetch_assoc($result2);
			if ($amt!=$item[value1] && $item[value1]!=$session[user][house]){
				output("<tr class='$bgcolor'><td align='center'>$row2[houseid]</td><td><a href='houses.php?op=drin&id=$row2[houseid]'>$row2[housename]</a></td></tr>",true);
				addnav("","houses.php?op=drin&id=$row2[houseid]");
			}
			$amt=$item[value1];
		}
	}
	output("</table>",true);
	if ($rebuy==1) addnav("Verkauf rückgängig","houses.php?op=buy&id=".$session[user][house]."");
	if (getsetting("dailyspecial",0)=="Waldsee"){
		output("`n`n`@Während du deine Schlüssel suchst, fällt dir ein kleiner Trampelpfad auf...");
		addnav("Trampelpfad","paths.php?ziel=forestlake");
	}
	addnav("Zurück zum Dorf","village.php");
	addnav("W?Zurück zum Wohnviertel","houses.php");
}else{
	output("`@`b`cDas Wohnviertel`c`b`n`n");
	$session[housekey]=0;
	$sql = "SELECT * FROM items WHERE owner=".$session[user][acctid]." AND class='Schlüssel' ORDER BY id ASC";
	$result2 = db_query($sql) or die(db_error(LINK));
	if (db_num_rows($result2)>0 || $session[user][housekey]>0) addnav("Haus betreten","houses.php?op=enter");
	output("Du verlässt den Dorfplatz und schlenderst Richtung Wohnviertel. In diesem schön angelegten Teil des Dorfes siehst du einige Baustellen zwischen bewohnten ");
	output("und unbewohnten Häusern. Hier wohnen also die Helden...`n`n");
	if ($_POST['search']>""){
		if ($_GET['search']>"" || $_GET['search']>"") $_POST['search']=$_GET['search'];
		if (strcspn($_POST['search'],"0123456789")<=1){
			$search="houseid=".intval($_POST[search])." AND ";
		}else{
			$search="%";
			for ($x=0;$x<strlen($_POST['search']);$x++){
				$search .= substr($_POST['search'],$x,1)."%";
			}
			$search="housename LIKE '".$search."' AND ";
		}
	}else{
		$search="";
	}
	$ppp=30; // Player Per Page +1 to display
	if (!$_GET[limit]){
		$page=0;
	}else{
		$page=(int)$_GET[limit];
		addnav("Vorherige Straße","houses.php?limit=".($page-1)."&search=$_POST[search]");
	}
	$limit="".($page*$ppp).",".($ppp+1);
	//$sql = "SELECT * FROM houses WHERE status<100 ORDER BY houseid ASC LIMIT $limit";
	
	//DOC JOKUS/TWEANS: Performancekillenden Schleifenquery entsorgt
	$sql = "SELECT houses.*,accounts.name AS schluesselinhaber FROM houses 
	LEFT JOIN accounts ON accounts.acctid=houses.owner 
	WHERE $search status<100 ORDER BY houseid ASC LIMIT $limit";
	output("<form action='houses.php' method='POST'>Nach Hausname oder Nummer <input name='search' value='$_POST[search]'> <input type='submit' class='button' value='Suchen'></form>",true);
	addnav("","houses.php");
	output("<table cellpadding=2 cellspacing=1 bgcolor='#999999' align='center'><tr class='trhead'><td>`bHausNr.`b</td><td>`bName`b</td><td>`bEigentümer`b</td><td>`bStatus`b</td></tr>",true);
	$result = db_query($sql) or die(db_error(LINK));
	if (db_num_rows($result)>$ppp) addnav("Nächste Straße","houses.php?limit=".($page+1)."&search=$_POST[search]");
	if (db_num_rows($result)==0){
  		output("<tr><td colspan=4 align='center'>`&`iEs gibt noch keine Häuser`i`0</td></tr>",true);
	}else{
		for ($i=0;$i<db_num_rows($result);$i++){
			$row = db_fetch_assoc($result);
			$bgcolor=($i%2==1?"trlight":"trdark");
			output("<tr class='$bgcolor'><td align='right'>$row[houseid]</td><td><a href='houses.php?op=bio&id=$row[houseid]'>$row[housename]</a></td><td>",true);
			addnav("","houses.php?op=bio&id=$row[houseid]");
	//DOC JOKUS/TWEANS: Performancekillenden Schleifenquery entsorgt

			output("$row[schluesselinhaber]</td><td>",true);
			if ($row[status]==0) output("`6im Bau`0");
			if ($row[status]==1) output("`!bewohnt`0");
			if ($row[status]==2) output("`^zum Verkauf`0");
			if ($row[status]==3) output("`4Verlassen`0");
			if ($row[status]==4) output("`\$Bauruine`0");
			output("</tr>",true);
		}
	}
	output("</table>",true);
	if ($session[user][housekey]) {
		output("`nStolz schwingst du den Schlüssel zu deinem Haus im Gehen hin und her.");
	}
	if ($session[user][house] && $session[user][housekey]) {
		addnav("Haus verkaufen","houses.php?op=sell");
	} else {
		if (!$session[user][house] ) addnav("Haus kaufen","houses.php?op=buy");
		addnav("Haus bauen","houses.php?op=build");
	}
	if (getsetting("pvp",1)==1) addnav("Einbrechen","houses.php?op=einbruch");
	if (@file_exists("well.php")) addnav("Dorfbrunnen","well.php");
	if ($session[user][superuser]) addnav("Admin Grotte","superuser.php");
	addnav("Zurück zum Dorf","village.php");
}
if ($fight){ 
	if (count($session[bufflist])>0 && is_array($session[bufflist]) || $HTTP_GET_VARS[skill]!=""){ 
		$HTTP_GET_VARS[skill]=""; 
		if ($HTTP_GET_VARS['skill']=="") $session['user']['buffbackup']=serialize($session['bufflist']); 
		$session[bufflist]=array(); 
		output("`&Die ungewohnte Umgebung verhindert den Einsatz deiner besonderen Fähigkeiten!`0"); 
	} 
	include "battle.php"; 
	if ($victory){ 
		output("`n`#Du hast die Stadtwache besiegt und der Weg zum Haus ist frei!`nDu bekommst ein paar Erfahrungspunkte."); 
		addnav("Weiter zum Haus","houses.php?op=einbruch2&id=$session[housekey]");
		addnav("Zurück zum Dorf","village.php");
		$session[user][experience]+=$session[user][level]*10;
		$session[user][turns]--;
		$badguy=array();
	}elseif ($defeat){ 
		output("`n`\$Die Stadtwache hat dich besiegt. Du bist tot!`nDu verlierst 10% deiner Erfahrungspunkte, aber kein Gold.`nDu kannst morgen wieder kämpfen."); 
		$session[user][hitpoints]=0; 
		$session[user][alive]=false;
		$session[user][experience]=round($session[user][experience]*0.9);
		$session[user][badguy]="";
		addnews("`%".$session[user][name]."`3 wurde von der Stadtwache bei einem Einbruch besiegt.");
		addnav("Tägliche News","news.php");
	}else{ 
		fightnav(false,true); 
	} 
}

page_footer();
?>