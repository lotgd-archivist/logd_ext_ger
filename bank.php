<?php

// 22062004

require_once "common.php";
page_header("Die alte Bank");
output("`^`c`bDie alte Stadtbank`b`c`6");
if ($HTTP_GET_VARS[op]==""){
  checkday();
  output("Ein kleiner Mann in einem makellosen Anzug mit Lesebrille grüßt dich.`n`n");
  output("\"`5Hallo guter Mann,`6\" grüßt du zurück, \"`5Kann ich meinen Kontostand an diesem wunderschönen Tag einsehen?`6\"`n`n");
  output("Der Bankier murmelt \"`3Hmm, ".$session[user][name]."`3, mal sehen.....`6\" während er die Seiten in seinem Buch ");
  output("sorgfältig überfliegt.  ");
	if ($session[user][goldinbank]>=0){
		output("\"`3Aah ja, hier ist es. Du hast  `^".$session[user][goldinbank]." Gold`3 bei unserer ");
		output("renommierten Bank.  Kann ich sonst noch etwas für dich tun?`6\"");
	}else{
		output("\"`3Aah ja, hier ist es.  Du hast `&Schulden`3 in Höhe von `^".abs($session[user][goldinbank])." Gold`3 bei unserer ");
		output("renommierten Bank.  Kann ich sonst noch etwas für dich tun?`6\"`n`n(`iSchulden verfallen durch einen Drachenkill nicht!`i)");
	}
}else if($_GET['op']=="transfer"){
	output("`6`bGold überweisen`b:`n");
	if ($session[user][goldinbank]>=0){
		output("Du kannst maximal `^".getsetting("transferperlevel",25)."`6 Gold pro Level des Empfängers überweisen.`n");
		$maxout = $session['user']['level']*getsetting("maxtransferout",25);
		$minfer = round(getsetting("transferperlevel",25)/10*((int)$session['user']['level']/2));
		output("Du musst mindestens `^$minfer`6 Gold überweisen.`n");
		output("Du kannst nicht mehr als insgesamt `^$maxout`6 Gold überweisen.");
		if ($session['user']['amountouttoday'] > 0) {
			output("(Du hast heute schon `^{$session['user']['amountouttoday']}`6 Gold überwiesen.)`n`n");
		} else output("`n`n");
		output("<form action='bank.php?op=transfer2' method='POST'>Wieviel ü<u>b</u>erweisen: <input name='amount' id='amount' accesskey='b' width='5'>`n",true);
		output("A<u>n</u>: <input name='to' accesskey='n'> (Unvollständige Namen werden automatisch ergänzt. Du wirst nochmal zum Bestätigen aufgefordert).`n",true);
		output("<input type='submit' class='button' value='Vorschau'></form>",true);
		output("<script language='javascript'>document.getElementById('amount').focus();</script>",true);
		addnav("","bank.php?op=transfer2");
	}else{
		output("`6Der kleine alte Bankier weigert sich, Geld für jemanden zu überweisen, der Schulden hat.");
	}
}else if($_GET['op']=="transfer2"){
	output("`6`bÜberweisung bestätigen`b:`n");
	$string="%";
	for ($x=0;$x<strlen($_POST['to']);$x++){
		$string .= substr($_POST['to'],$x,1)."%";
	}
	$sql = "SELECT name,login FROM accounts WHERE name LIKE '".addslashes($string)."'";
	$result = db_query($sql);
	$amt = abs((int)$_POST['amount']);
	if (db_num_rows($result)==1){
		$row = db_fetch_assoc($result);
		output("<form action='bank.php?op=transfer3' method='POST'>",true);
		output("`6Überweise `^$amt`6 an `&$row[name]`6.");
		output("<input type='hidden' name='to' value='".HTMLEntities($row['login'])."'><input type='hidden' name='amount' value='$amt'><input type='submit' class='button' value='Überweisung abschließen'></form>",true);
		addnav("","bank.php?op=transfer3");
	}elseif(db_num_rows($result)>100){
		output("Der Bankier schaut dich überfordert an und schlägt dir vor, deine Suche vielleicht etwas mehr einzuengen, indem du den Namen genauer festlegst.`n`n");
		output("<form action='bank.php?op=transfer2' method='POST'>Wieviel ü<u>b</u>erweisen: <input name='amount' id='amount' accesskey='b' width='5' value='$amt'>`n",true);
		output("A<u>n</u>: <input name='to' accesskey='n' value='". $_POST['to'] . "'> (Unvollständige Namen werden automatisch ergänzt. Du wirst nochmal zum Bestätigen aufgefordert).`n",true);
		output("<input type='submit' class='button' value='Vorschau'></form>",true);
		output("<script language='javascript'>document.getElementById('amount').focus();</script>",true);
		addnav("","bank.php?op=transfer2");
	}elseif(db_num_rows($result)>1){
		output("<form action='bank.php?op=transfer3' method='POST'>",true);
		output("`6Überweise `^$amt`6 an <select name='to' class='input'>",true);
		for ($i=0;$i<db_num_rows($result);$i++){
			$row = db_fetch_assoc($result);
			//output($row[name]." ".$row[login]."`n");
			output("<option value=\"".HTMLEntities($row['login'])."\">".preg_replace("'[`].'","",$row['name'])."</option>",true);
		}
		output("</select><input type='hidden' name='amount' value='$amt'><input type='submit' class='button' value='Überweisung abschließen'></form>",true);
		addnav("","bank.php?op=transfer3");
	}else{
		output("`6Es konnte niemand mit diesem Namen gefunden werden. Bitte versuchs nochmal.");
	}
}else if($_GET['op']=="transfer3"){
	$amt = abs((int)$_POST['amount']);
	output("`6`bÜberweisung abschließen`b`n");
	if ($session[user][gold]+$session[user][goldinbank]<$amt){
		output("`6Wie willst du `^$amt`6 Gold überweisen, wenn du nur ".($session[user][gold]+$session[user][goldinbank])."`6 Gold hast?");
	}else{
		$sql = "SELECT name,acctid,level,transferredtoday,lastip,emailaddress,uniqueid FROM accounts WHERE login='{$_POST['to']}'";
		$result = db_query($sql);
		if (db_num_rows($result)==1){
			$row = db_fetch_assoc($result);
			$maxout = $session['user']['level']*getsetting("maxtransferout",25);
			$maxtfer = $row['level']*getsetting("transferperlevel",25);
			$minfer = round(getsetting("transferperlevel",25)/10*((int)$session['user']['level']/2));
			if ($session['user']['amountouttoday']+$amt > $maxout) {
				output("`6Die Überweisung wurde nicht durchgeführt: Du darfst nicht mehr als `^$maxout`6 Gold pro Tag überweisen.");
			}else if ($maxtfer<$amt){
				output("`6Die Überweisung wurde nicht durchgeführt: `&{$row['name']}`6 darf maximal `^$maxtfer`6 Gold empfangen.");
			}else if($row['transferredtoday']>=getsetting("transferreceive",3)){
				output("`&{$row['name']}`6 hat heute schon zu viele Überweisungen oder Edelsteine erhalten. Du wirst bis morgen warten müssen.");
			}else if($amt<$minfer){
				output("`6Du solltest etwas überweisen, das sich auch lohnt. Wenigstens `^$minfer`6 Gold.");
			}else if($row['acctid']==$session['user']['acctid']){
				output("`6Du kannst dir nicht selbst Gold überweisen. Das macht keinen Sinn!");
 			} else if (ac_check($row)){
// 			} else if ($session[user][emailaddress]==$row[emailaddress] && $row[emailaddress]){
				output("`\$`bNicht erlaubt!!`b Du darfst kein Gold an deine eigenen Charaktere überweisen!");
			}else{
				//debuglog("transferred $amt gold to", $row['acctid']);
				$session[user][gold]-=$amt;
				if ($session[user][gold]<0){ //withdraw in case they don't have enough on hand.
					$session[user][goldinbank]+=$session[user][gold];
					$session[user][gold]=0;
				}
				$session['user']['amountouttoday']+= $amt;
				$sql = "UPDATE accounts SET goldinbank=goldinbank+$amt,transferredtoday=transferredtoday+1 WHERE acctid='{$row['acctid']}'";
				db_query($sql);
				output("`6Transfer vollständig!");
				//$session['user']['donation']+=1;
				systemmail($row['acctid'],"`^Du hast eine Überweisung erhalten!`0","`&{$session['user']['name']}`6 hat dir `^$amt`6 Gold auf dein Konto überwiesen!");
			}
		}else{
			output("`6Die Überweisung hat nicht geklappt. Bitte versuchs nochmal.");
		}
	}



}else if($_GET['op']=="gemtrans"){
	output("`6`bEdelstein versenden`b:`n");
	if (($session[user][gold]>=100 || $session[user][gold]+$session[user][goldinbank]>=100) && $session[user][gems]>0){
		output("Du kannst `#1 Edelstein`6 für eine Versandgebühr von `^100 Gold`6 an einen beliebigen Charakter mit mindestens Level 3 verschenken.`n`n");
		output("<form action='bank.php?op=gemtrans2' method='POST'>Einen Edelstein versenden a<u>n</u>: <input name='to' accesskey='n'> (Unvollständige Namen werden automatisch ergänzt. Du wirst nochmal zum Bestätigen aufgefordert).`n",true);
		output("<input type='submit' class='button' value='Vorschau'></form>",true);
		addnav("","bank.php?op=gemtrans2");
	}else if ($session[user][gold]+$session[user][goldinbank]<100){
		output("`6Der kleine alte Bankier weigert sich, einen Edelstein kostenlos zu versenden.`nDu hast keine `^100`6Gold!");
	} else {
		output("`6Der kleine alte Bankier erklärt dir lange und umständlich, dass du keine Edelsteine verschenken kannst, wenn du keine hast!");
	}
}else if($_GET['op']=="gemtrans2"){
	output("`6`bVersand bestätigen`b:`n");
	$string="%";
	for ($x=0;$x<strlen($_POST['to']);$x++){
		$string .= substr($_POST['to'],$x,1)."%";
	}
	$sql = "SELECT name,login FROM accounts WHERE name LIKE '".addslashes($string)."'";
	$result = db_query($sql);
	if (db_num_rows($result)==1){
		$row = db_fetch_assoc($result);
		output("<form action='bank.php?op=gemtrans3' method='POST'>",true);
		output("`6Verschenke `#1 Edelstein`6 für eine Versandgebühr von `^100 Gold`6 an `&$row[name]`6.");
		output("<input type='hidden' name='to' value='".HTMLEntities($row['login'])."'><input type='submit' class='button' value='Versand abschließen'></form>",true);
		addnav("","bank.php?op=gemtrans3");
	}elseif(db_num_rows($result)>100){
		output("Der Bankier schaut dich überfordert an und schlägt dir vor, deine Suche vielleicht etwas mehr einzuengen, indem du den Namen genauer festlegst.`n`n");
		output("<form action='bank.php?op=gemtrans2' method='POST'>Versende einen Edelstein a<u>n</u>: <input name='to' accesskey='n' value='". $_POST['to'] . "'> (Unvollständige Namen werden automatisch ergänzt. Du wirst nochmal zum Bestätigen aufgefordert).`n",true);
		output("<input type='submit' class='button' value='Vorschau'></form>",true);
		addnav("","bank.php?op=gemtrans2");
	}elseif(db_num_rows($result)>1){
		output("<form action='bank.php?op=gemtrans3' method='POST'>",true);
		output("`6Verschenke `#1 Edelstein`6 für eine Versandgebühr von `^100 Gold`6 an <select name='to' class='input'>",true);
		for ($i=0;$i<db_num_rows($result);$i++){
			$row = db_fetch_assoc($result);
			output("<option value=\"".HTMLEntities($row['login'])."\">".preg_replace("'[`].'","",$row['name'])."</option>",true);
		}
		output("</select><input type='submit' class='button' value='Versand abschließen'></form>",true);
		addnav("","bank.php?op=gemtrans3");
	}else{
		output("`6Es konnte niemand mit diesem Namen gefunden werden. Bitte versuchs nochmal.");
	}
}else if($_GET['op']=="gemtrans3"){
	output("`6`bVersand abschließen`b`n");
	$sql = "SELECT name,acctid,level,lastip,emailaddress,transferredtoday,uniqueid FROM accounts WHERE login='{$_POST['to']}'";
	$result = db_query($sql);
	if (db_num_rows($result)==1){
		$row = db_fetch_assoc($result);
		 if($row['level']<3){
			output("`&{$row['name']}`6 kann noch keine Edelsteine in Empfang nehmen. Der Empfänger muss mindestens Level 3 sein.");
		}else if($row['acctid']==$session['user']['acctid']){
			output("`6Du kannst dir nicht selbst einen Edelstein schenken. Das macht keinen Sinn!");
		}else if($row['transferredtoday']>=getsetting("transferreceive",3)){
			output("`&{$row['name']}`6 hat heute schon zu viele Überweisungen oder Edelsteine erhalten. Du wirst bis morgen warten müssen.");
		} else if (ac_check($row)){
//		} else if ($session[user][emailaddress]==$row[emailaddress] && $row[emailaddress]){
			output("`\$`bNicht erlaubt!!`b Du darfst keine Edelsteine an deine eigenen Charaktere versenden!");
		}else{
			//debuglog("transferred 1 gem to", $row['acctid']);
			$session[user][gold]-=100;
			$session[user][gems]-=1;
			if ($session[user][gold]<0){ //withdraw in case they don't have enough on hand.
				$session[user][goldinbank]+=$session[user][gold];
				$session[user][gold]=0;
			}
			$sql = "UPDATE accounts SET gems=gems+1,transferredtoday=transferredtoday+1  WHERE acctid='{$row['acctid']}'";
			db_query($sql);
			output("`6Versand erfolgreich!");
			systemmail($row['acctid'],"`#Du hast einen Edelstein geschenkt bekommen!`0","`&{$session['user']['name']}`6 war so freundlich und hat dir `#1 Edelstein`6 geschenkt!");
		}
	}else{
		output("`6Der Versand hat nicht geklappt. Bitte versuchs nochmal.");
	}




}else if($HTTP_GET_VARS[op]=="deposit"){
  output("<form action='bank.php?op=depositfinish' method='POST'>Du hast ".($session[user][goldinbank]>=0?"ein Guthaben von":"Schulden in Höhe von")." ".abs($session[user][goldinbank])." Gold bei der Bank.`n",true);
	output("`^Wie <u>v</u>iel ".($session[user][goldinbank]>=0?"einzahlen":"zurückzahlen").":  <input id='input' name='amount' width=5 accesskey='v'> <input type='submit' class='button' value='Einzahlen'>`n`iGib 0 oder gar nichts ein, um alles einzuzahlen.`i</form>",true);
	output("<script language='javascript'>document.getElementById('input').focus();</script>",true);
  addnav("","bank.php?op=depositfinish");
}else if($HTTP_GET_VARS[op]=="depositfinish"){
	$_POST[amount]=abs((int)$_POST[amount]);
	if ($_POST[amount]==0){
		$_POST[amount]=$session[user][gold];
	}
	if ($_POST[amount]>$session[user][gold]){
		output("`\$FEHLER: Soviel Gold hast du nicht dabei.`^`n`n");
		output("Du schmeißt deine `&".$session[user][gold]."`^ Gold auf den Schaltertisch und erklärst, dass du die ganzen `&$_POST[amount]`^ Gold einzahlen möchtest.");
		output("`n`nDer kleine alte Mann schaut dich nur verständnislos an. Durch diesen seltsamen Blick verunsichert, zählst du noch einmal nach und erkennst deinen Irrtum. Verdammt, wozu soll ein Krieger rechnen können?");
	}else{
		output("`^`bDu zahlst `&$_POST[amount]`^ Gold auf dein Konto ein. ");
		//debuglog("deposited " . $_POST[amount] . " gold in the bank");
		$session[user][goldinbank]+=$_POST[amount];
		$session[user][gold]-=$_POST[amount];
		output("Du hast damit ".($session[user][goldinbank]>=0?"ein Guthaben von":"Schulden in Höhe von")." `&".abs($session[user][goldinbank])."`^ Gold auf deinem Konto und `&".$session[user][gold]."`^ Gold hast du bei dir.`b");
	}
}else if($_GET[op]=="borrow"){
	if ($session['user']['reputation']<-35){
		output("Misstrauisch schaut dich der kleine Kerl eine Weile an. Dann, als ob er dein Gesicht erkannt hätte, atmet er ein und erklärt dir vorsichtig, dass er es nicht für klug hält, Leuten von deinem Schlag Geld zu leihen. Offenbar ist ihm dein schlechter Ruf zu Ohren gekommen und ist nun um den Ruf (und das Gold) seiner Bank besorgt...");
	}else{
		$maxborrow = $session[user][level]*getsetting("borrowperlevel",20);
	  	output("<form action='bank.php?op=withdrawfinish' method='POST'>Du hast ".($session[user][goldinbank]>=0?"ein Guthaben von":"Schulden in Höhe von")." ".abs($session[user][goldinbank])." Gold bei der Bank.`n",true);
	  	output("`^Wieviel <u>l</u>eihen (mit deinem Level kannst du maximal $maxborrow leihen)? <input id='input' name='amount' width=5 accesskey='l'> <input type='hidden' name='borrow' value='x'><input type='submit' class='button' value='Leihen'>`n(Gold wird abgehoben, bis dein Konto leer ist. Der Restbetrag wird geliehen.)</form>",true);
		output("<script language='javascript'>document.getElementById('input').focus();</script>",true);
	  	addnav("","bank.php?op=withdrawfinish");
	}
}else if($HTTP_GET_VARS[op]=="withdraw"){
  	output("<form action='bank.php?op=withdrawfinish' method='POST'>Du hast ".$session[user][goldinbank]." Gold bei der Bank.`n",true);
  	output("`^Wieviel a<u>b</u>heben? <input id='input' name='amount' width=5 accesskey='b'> <input type='submit' class='button' value='Abheben'>`n`iGib 0 oder gar nichts ein, um alles abzuheben.`i</form>",true);
	output("<script language='javascript'>document.getElementById('input').focus();</script>",true);
  	addnav("","bank.php?op=withdrawfinish");
}else if($HTTP_GET_VARS[op]=="withdrawfinish"){
	$_POST[amount]=abs((int)$_POST[amount]);
	if ($_POST[amount]==0){
		$_POST[amount]=abs($session[user][goldinbank]);
	}
	if ($_POST[amount]>$session[user][goldinbank] && $_POST[borrow]=="") {
		output("`\$FEHLER: Nicht genug auf dem Konto.`^`n`n");
		output("Nachdem du darüber informiert wurdest, dass du `&".$session[user][goldinbank]."`^ Gold auf dem Konto hast, erklärst du dem Männlein mit der Lesebrille, dass du gerne `&$_POST[amount]`^ davon abheben würdest.");
		output("`n`nDer Bankier schaut dich bedauernd an und erklärt dir die Grundlagen der Mathematik. Nach einer Weile verstehst du deinen Fehler und würdest es gerne nochmal versuchen.");
	}else if($_POST[amount]>$session[user][goldinbank]){
		$lefttoborrow = $_POST[amount];
		$maxborrow = $session[user][level]*getsetting("borrowperlevel",20);
		if ($lefttoborrow<=$session[user][goldinbank]+$maxborrow){
			if ($session[user][goldinbank]>0){
				output("`6Du nimmst deine verbleibenden `^".$session[user][goldinbank]."`6 Gold und ");
				$lefttoborrow-=$session[user][goldinbank];
				$session[user][gold]+=$session[user][goldinbank];
				$session[user][goldinbank]=0;
				//debuglog("withdrew " . $_POST[amount] . " gold from the bank");
			}else{
				output("`6Du ");
			}
			if ($lefttoborrow-$session[user][goldinbank] > $maxborrow){
				output("fragst, ob du `^$lefttoborrow`6 Gold leihen kannst. Der kleine Mann informiert dich darüber, dass er dir in deiner gegenwärtigen Situation nur `^$maxborrow`6 Gold geben kann.");
			}else{
				output("leihst dir `^$lefttoborrow`6 Gold.");
				$session[user][goldinbank]-=$lefttoborrow;
				$session[user][gold]+=$lefttoborrow;
				//debuglog("borrows $lefttoborrow gold from the bank");
			}
		}else{
			output("`6Mit den schlappen `^{$session[user][goldinbank]}`6 Gold auf deinem Konto bittest du um einen Kredit von `^".($lefttoborrow-$session[user][goldinbank])."`6 Gold, aber
			der kurze kleine Mann informiert dich darüber, dass du mit deinem Level höchstens `^$maxborrow`6 Gold leihen kannst.");
		}
	}else{
		output("`^`bDu hast `&$_POST[amount]`^ Gold von deinem Bankkonto abgehoben. ");
		$session[user][goldinbank]-=$_POST[amount];
		$session[user][gold]+=$_POST[amount];
		//debuglog("withdrew " . $_POST[amount] . " gold from the bank");
		output("Du hast damit `&".$session[user][goldinbank]."`^ Gold auf deinem Konto und `&".$session[user][gold]."`^ Gold hast du bei dir.`b");
	}
}
addnav("Zurück zum Dorf","village.php");
if ($session[user][goldinbank]>=0){
	addnav("Abheben","bank.php?op=withdraw");
	addnav("Einzahlen","bank.php?op=deposit");
	if (getsetting("borrowperlevel",20)) addnav("Kredit aufnehmen","bank.php?op=borrow");
}else{
	addnav("Schulden begleichen","bank.php?op=deposit");
	if (getsetting("borrowperlevel",20)) addnav("Mehr leihen","bank.php?op=borrow");
}
if (getsetting("allowgoldtransfer",1)){
	if ($session[user][level]>=getsetting("mintransferlev",3) || $session[user][dragonkills]>0){
		addnav("Gold überweisen","bank.php?op=transfer");
	}
	addnav("Edelstein versenden","bank.php?op=gemtrans");
}

page_footer();

?>
