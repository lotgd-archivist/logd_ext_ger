<?php

// 01072004

/**
* Version:	0.4
* Author:	anpera
* Email:		logd@anpera.de
* 
* Purpose:	Additional functions for hard working players
* Program Flow:	The witchhouse appears if a player has 1 or less forest fights left.
*		 In it he can buy additional forest fights or use his last forest fight to get a 'special event'.
*		He also can reset some variables to get more tries for example with flirting or finding the dragon...
*
* Nav added in function forest in common.php:
* if ($session[user][turns]<=1 ) addnav("Hexenhaus","hexe.php");
*
* in newday.php find: $session['user']['seenlover'] = 0;
* after add: $session['user']['witch'] = 0;
*
* SQL: ALTER TABLE `accounts` ADD `witch` INT( 4 ) DEFAULT '0' NOT NULL ;
*/

require_once("common.php");
page_header("Hexenhaus");
$wkcost=$session[user][level]*300;
$spcost=$session[user][level]*100;
if ($_GET[op] == "wkkauf"){
	if ($session[user][gold]<$wkcost){
		output("`!\"`%Du hast gar nicht so viel Gold! `bRAUS HIER!`b`!\" Mit diesen Worten trifft dich ein magischer Schlag mit voller Wucht und wirft dich aus der Hütte.`nDu hast ein paar Lebenspunkte verloren.`n`n");
		$session[user][hitpoints]=round($session[user][hitpoints]*0.9);
	} else {
		$session[user][gold]-=$wkcost;
		$session[user][turns]++;
		output("`!Du gibst der Hexe`^ $wkcost `!Gold. Blitzschnell greift sie mit der einen Hand die Kelle im Kessel und mit der anderen deinen Unterkiefer. ");
		output(" Mit einem hohen Kichern flöst sie dir drei Portionen dieser braunen Brühe ein. Obwohl du gerade noch unfähig warst, dich aus dem Griff der Hexe zu befreien, fühlst du ");
		output("dich plötzlich wieder stark und bereit, einen weiteren Gegner im Wald zu bekämpfen.`nDu blickst dich noch einmal zu der immer noch kichernden Hexe um, ");
		output("aber die beugt sich schon wieder über ihren Punsch, ohne dir weitere Beachtung zu schenken. So gehst du zurück in den Wald.`n`n");
	}
	forest(true);
} else if ($_GET[op] == "besonders"){
	if ($session[user][gold]<$spcost){
		output("`!\"`%Du hast gar nicht so viel Gold! `bRAUS HIER!`b`!\" Mit diesen Worten trifft dich ein magischer Schlag mit voller Wucht und wirft dich aus der Hütte.`nDu hast ein paar Lebenspunkte verloren.`n`n");
		$session[user][hitpoints]=round($session[user][hitpoints]*0.9);
		forest(true);
	}else{
		$session[user][gold]-=$spcost;
		output("`!Du bezahlst die Hexe und sie spricht einen Zauber auf dich, der zugegebenermaßen mehr wie ein Fluch klingt. Dann verlässt das Hexenhaus und wie versprochen triffst du auf...`n`n");
		output("`^`c`bEtwas Besonderes!`c`b`0");
		if ($handle = opendir("special")){
			  $events = array();
			  while (false !== ($file = readdir($handle))){
				  if (strpos($file,".php")>0){
			  	  	// Skip the darkhorse if the horse knows the way
				  	if ($session['user']['hashorse'] > 0 && $playermount['tavern'] > 0 && strpos($file, "darkhorse") !== false) continue;
				 	array_push($events,$file);
				}
			}
			$x = e_rand(0,count($events)-1);
			if (count($events)==0){
				output("`b`@Arrr, dein Administrator hat entschieden, dass es dir nicht erlaubt ist, besondere Ereignisse zu haben.  Beschwer dich bei ihm, nicht beim Programmierer.");
			}else{
				$y = $HTTP_GET_VARS[op];
				$HTTP_GET_VARS[op]="";
				include("special/".$events[$x]);
				$HTTP_GET_VARS[op]=$y;
			}
		}else{
			output("`c`b`\$ERROR!!!`b`c`&Es ist nicht möglich die Speziellen Ereignisse zu öffnen! Bitte benachrichtige den Administrator!!");
		}
		if ($nav==""){
			$session[user][turns]=0;
			 forest(true);
		}
	}
} else if ($_GET[op] == "verwirren"){
	output("`!Die Hexe nimmt deinen Edelstein und holt eine Puppe aus einer Truhe in der Ecke, die genauso aussieht wie dein Meister. Sie sticht der Puppe eine krumme, rostige Nadel ");
	output("in den Kopf und sagt: \"`%Gehe ruhig zu deinem Meister. Du hast heute eine zweite Chance, ihn zu schlagen. Es muß aber bald geschehen und bereite dich gut vor!`!\"`nDu weißt nicht, ");
	output("ob jetzt tatsächlich der Meister oder du selbst verwirrt sein soll. Auf jeden Fall aber hast du wieder den Mut, deinen Meister heute doch noch einmal herauszufordern.`n`n");
	$session[user][gems]--;
	$session[user][seenmaster]=0;
	forest(true);
} else if ($_GET[op] == "drachen"){
	output("`!Du nimmst 3 deiner schwer verdienten Edelsteine und streckst sie der Hexe auf der flachen Hand entgegen. Die Hexe nimmt deine Hand und drückt so fest zu, daß dir schwindelig wird.");
	output("\"`%Hiermit erhältst du die Möglichkeit, des Drachen erneut aufzuspüren. Doch diesmal mache es richtig!`!\" Sie läßt deine Hand los und die Edelsteine sind verschwunden.`n");
	output("Du kannst deinen letzten Waldkampf jetzt dem Kampf gegen den Drachen widmen...`n`n");
	$session[user][gems]-=3;
	$session[user][seendragon]=0;
	forest(true);
} else if ($_GET[op] == "liebe"){
	output("`!Die Hexe nimmt deinen Edelstein und holt eine Puppe aus einer Truhe in der Ecke, die genauso aussieht wie ".($session[user][sex]?"Seth":"Violet").". Sie wirft die Puppe in ihren Kessel, rührt ein paar mal um und sagt: ");
	output("\"`%Was erwartest du jetzt von mir? Geh einfach zu deinem Liebhaber und flirte. Du brauchst dazu keinen weiteren Rat einer alten Frau.`!\"`n`n");
	$session[user][gems]--;
	$session[user][seenlover]=0; 
	forest(true);
} else if ($_GET[op] == "blase"){
	output("`!Die Hexe nimmt deinen Edelstein und lädt dich auf ein Ale ein. Und noch eines. Und noch eines. Nach einer Weile spürst du Druck auf der Blase und denkst, obwohl du schon ziemlich angetrunken bist, daß dich die ");
	output("olle Hexe reingelegt hat und hier gar keine Magie am Werk war... *hic* ...`n`n");
	$session[user][drunkenness]+=30;
	$session[user][gems]--;
	$session[user][usedouthouse]=0; 
	forest(true);
} else if ($_GET[op] == "barde"){
	output("`!\"`%Soso, der Barde will nicht mehr für dich singen. Hättest du ihm diesen Edelstein gegeben statt mir, hätte er sicher gesungen. Weißt du was? Ich werde ihm diesen Edelstein vor die Füße zaubern und ihn ");
	output(" wissen lassen, daß er von dir ist. So wie ich ihn kenne, steckt er ihn sich in die löchrige Hosentasche und verliert ihn in der Kneipe wieder ... aber was solls.`!\" Damit legt die Hexe ");
	output("den Edelstein auf den Tisch und schüttet etwas von ihrem Punsch darüber. \"`%Schon gut, du kannst gehen.`!\" sagt sie noch zu dir und während du dich ");
	output("Richtung Wald umdrehst, siehst du den Edelstein verschwinden... `n`n");
	$session[user][gems]--;
	$session[user][seenbard]=0;
	forest(true);
} else if ($_GET[op] == "lotto"){
	output("`!\"`%Nach schnellem Reichtum steht dir der Sinn? Weshalb verpulverst du dann deine Edelsteine auf diese Weise? ");
	output("Nunja, dein alter Lottoschein ist ungültig, du kannst dein Glück nochmal versuchen. Aber jammer mir nicht die Ohren voll, wenn es nicht klappt. Den Edelstein geb ich nicht wieder her!`!\"");
	output(" Die Hexe wendet sich von dir ab, ohne ein weiteres Wort zu sagen. `n");
	output("Richtung Wald umdrehst, siehst du den Edelstein verschwinden... `n`n");
	$session[user][gems]--;
	$session[user][lottery]=0;
	forest(true);
} else if ($_GET[op] == "freeale"){
	output("`!Du erzählst der Hexe davon, dass Cedrik dir bei deiner Freiale-Politik einen Strich durch die Rechnung macht. Sie nimmt dir deine 350 Gold ab und sagt: \"`%Jaja, der olle Cedrik. ");
	output(" Ich glaube, er hat beim Zwergenweitwurf gerade einen Zwerg an den Schädel bekommen und kann sich nicht mehr an dich erinnern.`!\" Dabei schnippt sie ");
	output(" einen Kieselstein vom Tisch in Richtung einer Puppe, die dir merkwürdig vertraut vorkommt, und trifft sie am Kopf. \"`%So, und jetzt verschwinde, bevor ich ");
	output("mit dir auch sowas mach.`!\" `n`n");
	$session[user][gold]-=350;
	$session[user][gotfreeale]=0;
	forest(true);
} else if ($_GET[op] == "cursep"){
	if ($_GET[id]>"" && $_GET[pid]>""){
		$sql="SELECT * FROM items WHERE id=$_GET[id]";
		$row = db_fetch_assoc(db_query($sql));
		$goldcost=$row[gold]*$session[user][level];
		$klappt=e_rand(1,10);
		$sql="SELECT * FROM items WHERE owner=$_GET[pid] AND name='$row[name]' AND class='Fluch'";
		$result2=db_query($sql);
		if (db_num_rows($result2)>0){
			output("`!Die Hexe sucht aus einem Regal voller Puppen eine heraus, die wie dein Opfer aussieht. Sie stutzt kurz, dann dreht sie sich zu dir um: \"`%");
			output("Jaja, es ist alles in Ordnung. Dein Opfer leidet bereits unter $row[name]`%. Behalte dein Geld. Einen schönen Tag noch. Und jetzt ... lass mich alleine.`!\"");
		}else if ($session[user][gold]<$goldcost || $session[user][gems]<$row[gems]){
			output("`!Als du deine Reichtümer vor der Hexe ausbreitest, musst du leider feststellen, dass du nicht genug hast, um die Hexe zu bezahlen. Du rechnest mit ");
			output("einem Donnerwetter aus Beschimpfungen, doch stattdessen geleitet dich die Hexe erstaunlich ruhig und freundlich zum Ausgang. Du bist verwirrt und ");
			output("lässt es geschehen.`nDoch schon bald sollst du herausfinden, wie die Hexe zu ihrem Geld kommen will: Sie hat den Fluch auf dich gesprochen!");
			$sql="INSERT INTO items (name,owner,class,value1,value2,gold,gems,description,hvalue,buff) VALUES ('".$row[name]."',".$session[user][acctid].",'Fluch',$row[value1],$row[value2],$row[gold],$row[gems],'".$row[description]."',$row[hvalue],'".$row[buff]."')";
			$session[user][reputation]--;
		}else if ($klappt>=9){
			output("`!HOPPLA! Das ging gewaltig schief. Statt dein Opfer zu treffen, ist der Fluch auf dich gesprungen. Du weißt nicht, ob das Absicht der Hexe war, oder ein Versehen, ");
			output("aber sie verlangt ihren Lohn nicht, während sie dich scheinbar leicht verwirrt aus dem Haus schiebt.");
			$sql="INSERT INTO items (name,owner,class,value1,value2,gold,gems,description,hvalue,buff) VALUES ('".$row[name]."',".$session[user][acctid].",'Fluch',$row[value1],$row[value2],".round($row[gold]/2).",".round($row[gems]/2).",'".$row[description]."',$row[hvalue],'".$row[buff]."')";
		}else{
			output("`!Die Hexe sucht aus einem Regal voller Puppen eine heraus, die wie dein Opfer aussieht. Sie legt die Puppe auf den Tisch zwischen euch. Mit einer Hand fährt sie kurz über die Puppe, während sie ");
			output("mit der anderen Hand deine $goldcost Gold und $row[gems] einstreicht. Dann nickt sie dir kurz zufrieden zu und weist dir den Weg zur Tür.`nDein Opfer wird an $row[name]`! eine Weile seine Freude haben.");
			$session[user][gold]-=$goldcost;
			$session[user][gems]-=$row[gems];
			$session[user][reputation]-=3;
			$sql="INSERT INTO items (name,owner,class,value1,value2,gold,gems,description,hvalue,buff) VALUES ('".$row[name]."',".$_GET[pid].",'Fluch',$row[value1],$row[value2],$row[gold],".round($row[gems]/2).",'".$row[description]."',$row[hvalue],'".$row[buff]."')";
			systemmail($_GET[pid],"`\$Verflucht!`0","`9{$session['user']['name']}`9 hat dir den Fluch `T'$row[name]'`9 angehext!`n$row[description]");
		}
		db_query($sql);
		$session[user][witch]++;
		forest(true);
	}else if ($_GET[id]>""){
		$id=$_GET[id];
		if (isset($_POST['search']) || $_GET['search']>""){
			if ($_GET['search']>"") $_POST['search']=$_GET['search'];
			$search="%";
			for ($x=0;$x<strlen($_POST['search']);$x++){
				$search .= substr($_POST['search'],$x,1)."%";
			}
			$search="name LIKE '".$search."' AND ";
			if ($_POST['search']=="weiblich") $search="sex=1 AND ";
			if ($_POST['search']=="männlich") $search="sex=0 AND ";
		}else{
			$search="";
		}
		$ppp=25; // Player Per Page to display
		if (!$_GET[limit]){
			$page=0;
		}else{
			$page=(int)$_GET[limit];
			addnav("Vorherige Seite","hexe.php?op=cursep&id=$id&limit=".($page-1)."&search=$_POST[search]");
		}
		$limit="".($page*$ppp).",".($ppp+1);
		$sql = "SELECT login,name,level,sex,acctid FROM accounts WHERE $search locked=0 AND alive=1 AND acctid<>".$session[user][acctid]." AND lastip<>'".$session[user][lastip]."' AND dragonkills > 0 ORDER BY login,level LIMIT $limit";
		$result = db_query($sql);
		if (db_num_rows($result)>$ppp) addnav("Nächste Seite","hexe.php?op=cursep&id=$id&limit=".($page+1)."&search=$_POST[search]");
		output("`%Und wer darf das Opfer sein?`n`!");
		output("<form action='hexe.php?op=cursep&id=$id' method='POST'>Nach Name suchen: <input name='search' value='$_POST[search]'><input type='submit' class='button' value='Suchen'></form>",true);
		addnav("","hexe.php?op=cursep&id=$id");
		output("<table cellpadding='3' cellspacing='0' border='0'><tr class='trhead'><td>Name</td><td>Level</td><td>Geschlecht</td></tr>",true);
		for ($i=0;$i<db_num_rows($result);$i++){
			$row = db_fetch_assoc($result);
			output("<tr class='".($i%2?"trlight":"trdark")."'><td><a href='hexe.php?op=cursep&id=$id&pid=$row[acctid]'>",true);
			output("$row[name]");
			output("</a></td><td>",true);
			output("$row[level]");
			output("</td><td align='center'><img src='images/".($row['sex']?"female":"male").".gif'></td></tr>",true);
			addnav("","hexe.php?op=cursep&id=$id&pid=$row[acctid]");
		}
		output("</table>",true);
		addnav("Lieber nicht","hexe.php");
	}else{
		$sql="SELECT * FROM items WHERE class='Fluch.Prot' ORDER BY name,id ASC";
		$result = db_query($sql);
		output("`!Die alte Hexe reibt sich die knochigen Finger. \"`%Sehr schön, sehr schön! Womit kann ich deinen größten Feind quälen?`!\" Sie erzählt dir, welche Flüche sie gerne mal an jemandem ausprobieren würde. ");
		output("`nWähle ein Schicksal für dein Opfer:`4");
		output("<ul>",true);
		for ($i=0;$i<db_num_rows($result);$i++){
			$row = db_fetch_assoc($result);
			$goldcost=$row[gold]*$session[user][level];
			output("<li><a href='hexe.php?op=cursep&id=$row[id]'>$row[name]</a>`4: ".htmlentities($row[description])."`4`nDauer: ".($row[hvalue]>0?"$row[hvalue] Tage":"unbegrenzt")."`nPreis: `^$goldcost`4 Gold, `#$row[gems]`4 Edelsteine.`n`n",true);
			addnav("","hexe.php?op=cursep&id=$row[id]");
		}
		output("</ul>",true);
		$session[user][witch]--;
		addnav("Lieber nicht","hexe.php");
	}
} else if ($_GET[op] == "fluch1"){
	$sql="SELECT * FROM items WHERE class='Fluch' AND owner=".$session[user][acctid]." ORDER BY id ASC";
	$result = db_query($sql);
	output("`!Die alte Hexe murmelt ein paar unverständliche Worte, bevor sie dir mit leicht arroganter Miene wie ein Arzt nach erfolgreicher Diagnose erzählt, was du wieder alles falsch gemacht hast.");
	output("\"`%Also, dich von deinen Übeln zu befreien wird dich eine Kleinigkeit kosten. ");
	for ($i=0;$i<db_num_rows($result);$i++){
		$row = db_fetch_assoc($result);
		output("`n$row[name] zu entfernen, kostet dich `^$row[gold] `%Gold und `#$row[gems]`% Edelsteine. ");
		if ($row[hvalue]) output("Dieser Fluch hält noch $row[hvalue] Tage. ");
		addnav("$row[name] entfernen","hexe.php?op=fluch2&id=$row[id]");
	}
	output("Wovon soll ich dich befreien?`!\"`n");
	addnav("Vergiss es","forest.php");
} else if ($_GET[op] == "fluch2"){
	$sql="SELECT * FROM items WHERE id=$_GET[id]";
	$row = db_fetch_assoc(db_query($sql));
	output("`!Die knochigen Finger der Hexe scheinen plötzlich überall an dir zu sein und du fühlst etwas Ekel bei dieser merkwürdigen Behandlung. Aber du hast keine Ahnung, wie man Flüche normalerweise ");
	output("behandelt und hältst deswegen die Klappe.");
	if ($session[user][gold]<$row[gold] || $session[user][gems]<$row[gems]){
		output("\"`%Aha! Dachte ichs mir doch. Ich soll dich von einem Fluch befreien und du willst nicht einmal dafür bezahlen? Scher dich hier raus, bevor ich dir noch einen schlimmeren Fluch dazu hexe!`!\"");
		output("`nOhne dich wehren zu können, schwebst du nach draußen und die Tür der Hexenhütte knallt hinter dir ins Schloss. Tja, du hättest vielleicht genug Kleingold mitnehmen sollen.`n`n");
		forest();
	}else{
		output(" Schließlich scheint die Hexe gefunden zu haben, was sie offenbar gesucht hat, lässt $row[gold] Gold und $row[gems] Edelsteine von dir in ihrer Schatzkiste verschwinden und schenkt dir keine weitere Beachtung mehr.");
		output("Gerade, als du den Mund zum Protestieren öffnen willst, fühlst du die Veränderung: `bDer Fluch wurde aufgehoben!`b. Glücklich verlässt du die Hütte der Hexe.");
		if ($row[name]=="Der Ring") $session[user][maxhitpoints]+=$row[value1];
		db_query("DELETE FROM items WHERE id=$_GET[id]");
		$session[user][gold]-=$row[gold];
		$session[user][gems]-=$row[gems];
		addnav("Zurück in den Wald","forest.php");
	}
}else{
	output("`!Du betrittst das alte Hexenhaus im Wald. Über dem Kaminfeuer hängt ein großer Kessel, in dem eine seltsame braune Flüssigkeit vor sich hin blubbert. Eine typische Hexe, lang und dünn mit langer Hakennase und einem spitzen schwarzen Hut kommt dir grinsend entgegen. ");
	if ($session[user][witch]<getsetting("witchvisits",3)){
		output("`n\"`%Na, mein".($session[user][sex]?"e Kleine":" Kleiner")."? Hast du dich verlaufen? Oder kann ich sonst etwas für dich tun? Du siehst erschöpft aus! ");
		output("Wenn du mir`^  $wkcost `%von deinem Gold gibst, lasse ich dich von meinem Aufputschpunsch kosten und du könntest noch ein paar Monster mehr erschlagen. ");
		addnav("Waldkampf kaufen","hexe.php?op=wkkauf");
		if ($session[user][turns]>0) {
			addnav("Besonderes Ereignis","hexe.php?op=besonders");
			output("Oder du gibst mir deine restliche Kraft und`^ $spcost `% Gold und ich verspreche dir ein besonderes Ereignis im Wald, sobald du meine Hütte verlässt.");
		}
		addnav("Zurück in den Wald", "forest.php");
		addnav("Sonstige Hexereien");
		if ($session[user][seenmaster] && $session[user][gems]) addnav("Meister verwirren (1 Edelstein)","hexe.php?op=verwirren");
		if ($session[user][seendragon] && $session[user][gems]>2 && $session[user][turns]>0 && $session[user][level]>=15) addnav("Neue Drachensuche (3 Edelsteine)","hexe.php?op=drachen");
		if ($session[user][seenlover] && $session[user][gems]) addnav("Nochmal flirten (1 Edelstein)","hexe.php?op=liebe");
		if ($session[user][usedouthouse] && $session[user][gems]) addnav("Druck auf die Blase (1 Edelstein)","hexe.php?op=blase");
		if ($session[user][seenbard] && $session[user][gems]) addnav("Bardenhals befeuchten (1 Edelstein)","hexe.php?op=barde");
		if ($session[user][lottery] && $session[user][gems]) addnav("Nochmal Lotto (1 Edelstein)","hexe.php?op=lotto");
		if ($session[user][gotfreeale] && $session[user][gold]>350) addnav("Cedrik verwirren (350 Gold)","hexe.php?op=freeale");
		output("`!\"");
		$result=db_query("SELECT * FROM items WHERE class='Fluch' AND owner=".$session[user][acctid]." ORDER BY id");
		if (db_num_rows($result)>0){
			output(" Sie macht eine kurze Pause, als ob sie etwas an deiner Erscheinung stören würde, und fährt dann fort: \"`%Ich spüre, es liegt ein `\$Fluch`% auf dir. Soll ich dir dabei behilflich sein, diesen Fluch los zu werden?`!\"");
			addnav("Fluch beseitigen","hexe.php?op=fluch1");
		}
		if ($session[user][playerfights]>0 && $session[user][gems]>1){
			addnav("Spieler verfluchen","hexe.php?op=cursep");
			output("`n`!Die Hexe bietet dir mehr nebenbei noch an, für Edelsteine jemanden für dich zu verfluchen. ");
		}
		$session[user][witch]++;
	}else{
		output("`n\"`%Hey mein".($session[user][sex]?"e Kleine":" Kleiner").", du gehst mir auf die Nerven! Hast du mich heute nicht schon oft genug gestört? Mach dass du fort kommst und wage es nicht, heute nochmal zu kommen.`!\" ");
		output(" Das war deutlich genug für dich.");
		addnav("Zurück in den Wald", "forest.php");
	}
}
page_footer();
?>