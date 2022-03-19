<?php

// 18062004

require_once "common.php";
checkday();

page_header("Dag Durnick's Tisch");
output("<span style='color: #9900FF'>",true);
output("`c`bDag Durnick's Tisch`b`c");

if ($HTTP_GET_VARS['op']=="list"){
	output("Dag fischt ein kleines, ledergebundenes Buch unter seinem Mantel hervor, blättert es zu einer bestimmten Seite durch und hält es dir zum Lesen hin.`n`n");
	output("`c`bDie Kopfgeldliste`b`c`n");
	$sql = "SELECT name,alive,sex,level,laston,loggedin,bounty,location FROM accounts WHERE bounty>0 ORDER BY bounty DESC";
	$result = db_query($sql) or die(sql_error($sql));
	output("<table border=0 cellpadding=2 cellspacing=1 bgcolor='#999999'>",true);
	output("<tr class='trhead'><td><b>Kopfgeld</b></td><td><b>Level</b></td><td><b>Name</b></td><td><b>Ort</b></td><td><b>Geschlecht</b></td><td><b>Zuletzt online</b></tr>",true);
	for($i=0;$i<db_num_rows($result);$i++){
		$row = db_fetch_assoc($result);
		output("<tr class='".($i%2?"trdark":"trlight")."'><td>",true);
		output("`^$row[bounty]`0");
		output("</td><td>",true);
		output("`^$row[level]`0");
		output("</td><td>",true);
		output("`&$row[name]`0");
		if ($session['user']['loggedin']) output("</a>",true);
		output("</td><td>",true);
		$loggedin=(date("U") - strtotime($row[laston]) < getsetting("LOGINTIMEOUT",900) && $row[loggedin]);
	if ($row[location]==0) output($loggedin?"`#Online`0":"`3Die Felder`0");
	if ($row[location]==1) output("`3Zimmer in Kneipe`0");
	if ($row[location]==2) output("`3Im Haus`0");
		output("</td><td>",true);
		output($row[sex]?"`!Weiblich`0":"`!Männlich`0");
		output("</td><td>",true);
		//$laston=round((strtotime("0 days")-strtotime($row[laston])) / 86400,0)." Tage";
		$laston=round((strtotime(date("r"))-strtotime($row[laston])) / 86400,0)." Tage";
		if (substr($laston,0,2)=="1 ") $laston="1 Tag";
		if (date("Y-m-d",strtotime($row[laston])) == date("Y-m-d")) $laston="Heute";
		if (date("Y-m-d",strtotime($row[laston])) == date("Y-m-d",strtotime(date("r")."-1 day"))) $laston="Gestern";
		if ($loggedin) $laston="Jetzt";
		output($laston);
		output("</td></tr>",true);
	}
	output("</table>",true);
}else if ($HTTP_GET_VARS['op']=="addbounty"){
	if ($session['user']['bounties'] >= getsetting("maxbounties",5)) {
		output("Dag durchbohrt dich fast mit seinem Blick. \"`7Hältst du mich für nen Meuchelmörder oder was? Du hast heut schon genuch Kopfgelder ausgesetzt. Jetz hau ab, bevor ich n Kopfgeld auf deinen Kopf aussetz, weil du mir auf die Nerven gehst.\"`n`n");
	} else {
		$fee = getsetting("bountyfee",10);
		if ($fee < 0 || $fee > 100) {
			$fee = 10;
			savesetting("bountyfee",$fee);
		}
		$min = getsetting("bountymin",50);
		$max = getsetting("bountymax",400);
		output("Dag Durnick blickt zu dir auf und rückt seine Pfeife mit den Zähnen zurecht.`n\"`7So, wen willst'n tot sehen? Du sollst aber wissen, dass wir keine Kinder killn, deswegen muss dein Opfer mindestens Level " . getsetting("bountylevel",3) . " sein und der Preis darf nicht zu hoch sein. Außerdem dürfen die Opfer nicht zu oft getroffen werdn. Also wer in meinem Buch nicht gelistet is, kann nicht zum Abschuss freigegeben werdn! Wir betreiben hier kein Schlachthaus, sondern 'n ... Unternehmen. Ich verlang " . getsetting("bountyfee",10) . "% Bearbeitungsgebühren für jeden Namen, den ich auf die Liste setzn soll.\"`n`n");
		output("<form action='dag.php?op=finalize' method='POST'>",true);
		output("`2Zielperson: <input name='contractname'>`n", true);
		output("`2Betrag aussetzen: <input name='amount' id='amount' width='5'>`n`n",true);
		output("<input type='submit' class='button' value='Vertrag abschlie&szlig;en'></form>",true);
		addnav("","dag.php?op=finalize");
	 	if ($session['user']['pvpflag']=="5013-10-06 00:42:00") output("`0Dag schaut dich fordernd an. \"`7Petersen hat mir erzählt, dass er dich vor Killern schützt - jetzt willst du selber jemanden tot sehen? Du würdest seinen Schutz verlieren, wenn ich jemanden für dich auf die Liste hier setze, ist dir das klar?`0\"`n`n");
	}
}elseif ($HTTP_GET_VARS['op']=="finalize") {
	//$name = "%" . rawurldecode($_POST['contractname']) . "%";
	if ($_GET['subfinal']==1){
		$sql = "SELECT acctid,name,login,level,locked,age,dragonkills,pk,experience,bounty,pvpflag,lastip,emailaddress,reputation,uniqueid  FROM accounts WHERE name='".addslashes(rawurldecode(stripslashes($_POST['contractname'])))."' AND locked=0";
		//output($sql);
	}else{
		$contractname = stripslashes(rawurldecode($_POST['contractname']));
		$name="%";
		for ($x=0;$x<strlen($contractname);$x++){
			$name.=substr($contractname,$x,1)."%";
		}
		$sql = "SELECT acctid,name,login,level,locked,age,dragonkills,pk,experience,bounty,pvpflag,lastip,emailaddress,reputation,uniqueid FROM accounts WHERE name LIKE '".addslashes($name)."' AND locked=0";
	}
	$result = db_query($sql);
	if (db_num_rows($result) == 0) {
		output("Dag Durnick sagt höhnisch lachend: `7\"Es gibt nicht einen den ich mit so einem Namen kenne. Vielleicht kommst' wieder, wenn du 'n echtes Opfer hast.\"");
	} elseif(db_num_rows($result) > 100) {
		output("Dag Durnick kratzt sich verwirrt am Kopf. `7\"Du beschreibst hier fast die Hälfte der Stadt, du Narr. Warum gibst du mir jetzt nicht mal ne genauere Beschreibung?\"");
	} elseif(db_num_rows($result) > 1) {
		output("Dag Durnick durchsucht seine Liste für einen Moment. `7\"Da sind ein paar, die du meinen könntest. Wer genau soll's denn sein?\"`n");
		output("<form action='dag.php?op=finalize&subfinal=1' method='POST'>",true);
		output("`2Zielperson: <select name='contractname'>",true);
		for ($i=0;$i<db_num_rows($result);$i++){
			$row = db_fetch_assoc($result);
			output("<option value=\"".rawurlencode($row['name'])."\">".preg_replace("'[`].'","",$row['name'])."</option>",true);
		}
		output("</select>`n`n",true);
		output("`2Betrag aussetzen: <input name='amount' id='amount' width='5' value='{$_POST['amount']}'>`n`n",true);
		output("<input type='submit' class='button' value='Vertrag abschlie&szlig;en'></form>",true);
		addnav("","dag.php?op=finalize&subfinal=1");
	} else {
		// Now, we have just the one, so check it.
		$row  = db_fetch_assoc($result);
		if ($row['locked']) {
			output("Dag Durnick sagt höhnisch lachend: `7\"Es gibt nicht einen den ich mit so einem Namen kenne. Vielleicht kommst' wieder, wenn du 'n echtes Opfer hast.\"");
		} elseif ($row['login'] == $session['user']['login']) {
			output("Dag Durnick schlägt sich brüllend lachend auf die Schenkel: `7\"Du willst n Kopfgeld auf dich selbst aussetzen? Ich helf doch keinem Selbstmörder!\"");
		} elseif ($row['level'] < getsetting("bountylevel",3) ||
				  ($row['age'] < getsetting("pvpimmunity",5) &&
				   $row['dragonkills'] == 0 && $row['pk'] == 0 &&
				   $row['experience'] < getsetting("pvpminexp",1500))) {
			output("Dag Durnick starrt dich ärgerlich an: `7\"Hab ich dir nicht gesagt, dass ich kein Meuchler bin? Das ist kein Opfer, das ein Kopfgeld wert wäre. Jetzt geh mir aus den Augen!\"");
		} elseif ($row['pvpflag']=="5013-10-06 00:42:00") {
			output("`7\"Diese Person steht unter dem persönlichn Schutz von J. C. Petersen! Glaubst du echt, ich will's mir mit dem verscherzn? Hau bloß ab!\"");
 	//	} elseif ($session['user']['lastip'] == $row['lastip'] || ($session['user']['emailaddress'] == $row['emailaddress'] && $row[emailaddress])){
 		} elseif (ac_check($row)){
			output("`\$`bKeine Chance!!`b Du darfst kein Kopfgeld auf deinen eigenen Charakter aussetzen!");
		} else {
			// All good!
			$amt = abs((int)$_POST['amount']);
			$min = getsetting("bountymin", 50) * $row['level'];
			$max = getsetting("bountymax", 400) * $row['level'];
			$fee = getsetting("bountyfee",10);
			if ($amt < $min) {
				output("Dag Durnick blickt finster: `7\"Glaubst im Ernst, ich arbeite für so nen Hungerlohn? Denk ma drüber nach und komm wieder, wenn du bereit bist hartes Bares zu bezahlen. Für dein Opfer brauchste mindestens " . $min . " Gold, damit's meine Zeit wert is.\"");
			} elseif ($session[user][gold] <round($amt*1.1,0)) {
				output("Dag Durnick schaut dich finster an: `7\"Du hast nicht genug Gold für diesen Vertrag. Wenn du meine Zeit so vergeudest, sollte ich stattdessen vielleicht n Kopfgeld auf DICH aussetzen!\"");
			} elseif ($amt + $row['bounty'] > $max) {
				output("Dag schaut auf den Berg Münzen und lässt ihn unberührt liegen. `7\"Ich werde diesen Vertrag ablehnen. Das is viel mehr, als `^{$row['name']} `7Wert is und das weißt du. Ich bin kein verdammter Auftragskiller. N Kopfgeld von {$row['bounty']} is schon auf diesen Kopf ausgesetzt. Ich wär bereit, es auf $max zu erhöhen, nach meinen $fee% Bearbeitungsgebühren natürlich\"`n`n");
			} else {
				output("Du schiebst die Münzen zu Dag Durnick, der sie flink einstreicht. `7\"Ich werd mir nur meine $fee% Gebühr einbehalten. Ich werd die Nachricht verbreiten, dass sich jemand um `^{$row['name']} `7kümmern soll. Hab Geduld und hab ein Auge auf die News.");
				$session['user']['bounties']++;
				$session['user']['donation']+=1;
				$cost = round($amt*(1+($fee/100)),0);
				if ($row['reputation']>$session['user']['reputation']){
					$session['user']['reputation']--;
					output(" Du sollst aber wissen, dass $row[name]`7 mehr Ehre besitzt als du! $row[name]`7 würde dich lieber selbst erwürgen, als feige ein Kopfgeld auszusetzen.");
				}else if ($row['reputation']<-25){
					$session['user']['reputation']+=2;
					$cost=round($cost/2);
					output(" Oder ... och weißt du was? Dieser schmierige Feigling $row[name]`7 geht mir schon lang auf den Keks. Ich setz ihn für dich für den halben Preis auf die Liste!");
				}
				output("\"`n`n");
				$session['user']['gold']-=$cost;
				if ($session['user']['pvpflag']=="5013-10-06 00:42:00"){
					$session['user']['pvpflag']="1986-10-06 00:42:00";
					output("`n`4`bDeine Immunität ist hiermit verfallen!`b`0`n");
				}
				//debuglog("spent $cost gold for a $amt bounty on", $row['acctid']);
				$sql = "UPDATE accounts SET bounty=bounty+$amt WHERE login='{$row['login']}'";
				db_query($sql);
			}
		}
	}
}else{
	output("Du schlenderst rüber zu Dag Durnick, der es nichtmal für nötig hält, zu dir aufzuschauen. Er nimmt einen langen Zug aus seiner Pfeife.`n");
	output("`7\"Du willst wohl wissn, ob n Preis auf deinen Kopf ausgesetzt is, richtig?\"`n`n");
	if ($session[user][bounty]>0){
		output("`3\"Nun, es sieht so aus als ob da `^".$session[user][bounty]." Gold`3 auf deinen Kopf ausgesetzt is. Du solltest gut auf dich aufpassen.\"");
	}else{
		output("`3\"Da is kein Kopfgeld auf dich ausgesetzt. Ich schlag vor, du tust alles, damit das auch so bleibt.\"");
	}
	if ($session[user][reputation]<-25) output("`n\"Und das meine ich ernst! Mach so weiter, und ich jag dich höchstpersönlich!\"");
	if ($session[user][reputation]<-10) output("`n`0Verächtlich schnaubend wendet er sich von dir ab.");
	if ($session[user][reputation]>25) output("`n\"Wäre echt 'ne Schande, wenn ich so ehrenwerte Leute auf meine Liste setzen müsste.\"");
	addnav("Kopfgeldliste","dag.php?op=list");
	addnav("Kopfgeld aussetzen","dag.php?op=addbounty");
}
if ($HTTP_GET_VARS['op'] != '')
	addnav("Rede mit Dag Durnick", "dag.php");
	addnav("Zurück zur Kneipe","inn.php");

// Whoops, forgot this when you changed from <font> to <span>
output("</span>",true);

page_footer();
?>
