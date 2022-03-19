<?php

// 11092004

require_once "common.php";
isnewday(2);
if (addcommentary()) {
	$sql = 'UPDATE petitions SET lastact=NOW() WHERE petitionid="'.$_GET['id'].'"';
	db_query($sql);
}

$statuses=array(0=>"`bUngelesen`b","Gelesen","Geschlossen");

if ($_GET[op]=="del"){
	$sql = "DELETE FROM petitions WHERE petitionid='$_GET[id]'";
	db_query($sql);
	$sql = "DELETE FROM commentary WHERE section='pet-{$_GET['id']}'";
	db_query($sql);
	$sql = 'DELETE FROM petitionmail WHERE petitionid="'.$_GET['id'].'"';
	db_query($sql);
	$_GET[op]="";
}
elseif ($_GET['op']=='sendmessage') {
	$sql = 'SELECT author,body FROM petitions WHERE petitionid="'.$_GET['id'].'"';
	$row = db_fetch_assoc(db_query($sql));
	$_POST['subject']=closetags(str_replace("`n","",$_POST['subject']),'`c`i`b');
	$_POST['body']=str_replace("`n","\n",$_POST['body']);
	$_POST['body']=str_replace("\r\n","\n",$_POST['body']);
	$_POST['body']=str_replace("\r","\n",$_POST['body']);
	$_POST['body']=substr($_POST['body'],0,(int)getsetting("mailsizelimit",1024));
	$_POST['body'] = closetags($_POST['body'],'`c`i`b');
	systemmail($row['author'],$_POST['subject'],$_POST['body']);
	petitionmail($_POST['subject'],$_POST['body'],$_GET['id'],$session['user']['acctid'],1,$row['author'],db_insert_id(LINK));
	redirect('viewpetition.php?op=view&id='.$_GET['id']);
}
page_header("Petition Viewer");
addnav("G?Zurück zur Grotte","superuser.php");
addnav("W?Zurück zum Weltlichen","village.php");
if ($_GET[op]==""){
	$sql = "SELECT petitionid FROM petitions WHERE status=2 AND date<'".date("Y-m-d H:i:s",strtotime(date("r")."-7 days"))."'";
	$result = db_query($sql);
	while ($row = db_fetch_assoc($result)) db_query('DELETE FROM petitionmail WHERE petitionid="'.$row['petitionid'].'"');
	$sql = "DELETE FROM petitions WHERE status=2 AND date<'".date("Y-m-d H:i:s",strtotime("-7 days"))."'";
	db_query($sql);
	if ($_GET[setstat]!=""){
		$sql = "UPDATE petitions SET status='{$_GET['setstat']}' WHERE petitionid='{$_GET['id']}'";
		db_query($sql);
		//output($sql);
	}
	$sql = "SELECT petitions.petitionid,petitions.lastact,accounts.name,petitions.date,petitions.status,petitions.body, IF(petitionmail.petitionid > 0,COUNT(*),0) AS petmails FROM petitions LEFT JOIN petitionmail USING(petitionid) LEFT JOIN accounts ON accounts.acctid=petitions.author GROUP BY petitions.petitionid ORDER BY petitions.status ASC, petitions.lastact DESC, petitions.date DESC";
	$result = db_query($sql);
	addnav("Aktualisieren","viewpetition.php");
	output("<table border='0'><tr class='trhead'><td>Num</td><td>Ops</td><td>Von</td><td>Sent</td><td>Status</td><td>Kommentare</td></tr>",true);
	for ($i=0;$i<db_num_rows($result);$i++){
		$row = db_fetch_assoc($result);
		$sql = "SELECT count(commentid) AS c FROM commentary WHERE section='pet-{$row['petitionid']}'";
		$res = db_query($sql);
		$counter = db_fetch_assoc($res);
		output("<tr class='".($i%2?"trlight":"trdark")."'><td>{$row['petitionid']}</td><td>[<a href='viewpetition.php?op=view&id={$row['petitionid']}'>Anzeigen</a>|<a href='viewpetition.php?op=del&id={$row['petitionid']}' onClick='return confirm(\"Diese Anfrage wirklich löschen?\");'>Del</a>|<a href='viewpetition.php?setstat=0&id={$row['petitionid']}'>Ungelesen</a>|<a href='viewpetition.php?setstat=1&id={$row['petitionid']}'>Gelesen</a>|<a href='viewpetition.php?setstat=2&id={$row['petitionid']}'>Geschlossen</a>]</td>",true);
		output("<td>",true);
		if ($row['name']==""){
			output(preg_replace("'[^a-zA-Z0-91234567890\\[\\]= @.!,?-]'","",substr($row['body'],0,strpos($row['body'],"[email"))));
		}else{
			output($row['name']);
		}
		output("</td><td>{$row['date']}</td><td>{$statuses[$row['status']]}".($row['lastact']>max($session['lastlogoff'],$session['petitions'][$row['petitionid']])?'`4*`0':'')."</td><td>{$counter['c']}</td></tr>",true);
		addnav("","viewpetition.php?op=view&id=$row[petitionid]");
		addnav("","viewpetition.php?op=del&id=$row[petitionid]");
		addnav("","viewpetition.php?setstat=0&id=$row[petitionid]");
		addnav("","viewpetition.php?setstat=1&id=$row[petitionid]");
		addnav("","viewpetition.php?setstat=2&id=$row[petitionid]");
	}
	output("</table>",true);
	output("`i(Geschlossene Anfragen werden nach 7 Tagen automatisch gelöscht)`i");
	output("`n`bSchlüssel:`b`nUngelesen: Niemand arbeitet bisher an diesem Problem.
	`nGelesen: Es arbeitet jemand an diesem Problem.
	`nGeschlossen: Diese Anfrage wurde bearbeitet. Es sollte keine weitere Arbeit mehr nötig sein.`n`n
	Wenn eine Anfrage gelesen wird, wird sie automatisch als gelesen markiert, wenn sie nicht schon als geschlossen markiert war. 
	Wenn du ein Problem nicht lösen kannst, markiere die Anfrage wieder als ungelesen, damit 
	ein anderer dem Spieler helfen kann.`n
	Wenn eine Anfrage erfolgreich bearbeitet wurde, markiere sie als geschlossen. Sie wird nach 7 Tagen dann automatisch gelöscht.");
}elseif($_GET[op]=="view"){
	if ($_GET['viewpageinfo']==1){
		addnav("Details ausblenden","viewpetition.php?op=view&id={$_GET['id']}");
	}else{
		addnav("D?Details anzeigen","viewpetition.php?op=view&id={$_GET['id']}&viewpageinfo=1");
	}
	addnav("Anfragen anzeigen","viewpetition.php");

	addnav("Operationen");
	addnav("Anfrage schliessen","viewpetition.php?setstat=2&id=$_GET[id]");
	addnav("U?Als Ungelesen markieren","viewpetition.php?setstat=0&id=$_GET[id]");
	addnav("S?Als GeleSen markieren","viewpetition.php?setstat=1&id=$_GET[id]");
	
	$sql = "SELECT accounts.name,accounts.login,accounts.acctid,petitions.date,petitions.status,petitionid,body,pageinfo FROM petitions LEFT JOIN accounts ON accounts.acctid=petitions.author WHERE petitionid='$_GET[id]' ORDER BY date ASC";
	$result = db_query($sql);
	$row = db_fetch_assoc($result);
	$session['petitions'][$_GET['id']] = date('Y-m-d H:i:s');
	if ($row['acctid']>0){
		addnav("Usereintrag bearbeiten","user.php?op=edit&userid={$row['acctid']}&returnpetition={$_GET['id']}");
	}
	output("`@Von: ");
	$row[body]=stripslashes($row[body]);
	if ($row['login']>"") output("<a href=\"mail.php?op=write&to=".rawurlencode($row[login])."&body=".URLEncode("\n\n----- Deine Anfrage -----\n".$row[body])."&subject=RE:+Hilfeanfrage\" target=\"_blank\" onClick=\"".popup("mail.php?op=write&to=".rawurlencode($row[login])."&body=".URLEncode("\n\n----- Deine Anfrage -----\n".$row[body])."&subject=RE:+Hilfeanfrage").";return false;\"><img src='images/newscroll.GIF' width='16' height='16' alt='Mail schreiben' border='0'></a>",true);
	output("`^`b$row[name]`b`n");
	output("`@Datum: `^`b$row[date]`b`n");
	output("`@Body:`^`n");
	$body = HTMLEntities($row[body]);
	$body = preg_replace("'([[:alnum:]_.-]+[@][[:alnum:]_.-]{2,}([.][[:alnum:]_.-]{2,})+)'i","<a href='mailto:\\1?subject=RE: Hilfeanfrage&body=".str_replace("+"," ",URLEncode("\n\n----- Deine Anfrage -----\n".$row[body]))."'>\\1</a>",$body);
	$body = preg_replace("'([\\[][[:alnum:]_.-]+[\\]])'i","<span class='colLtRed'>\\1</span>",$body);
	$output.="<span style='font-family: fixed-width'>".nl2br($body)."</span>";
	output("`n`@Kommentare:`n");
	viewcommentary("pet-{$_GET['id']}","Hinzufügen",200);
	if ($row['login']!='') {
		$answerbody = "\n\n----- Deine Anfrage -----\n".$row['body'];
		$answersubject = 'RE: Hilfeanfrage';
		output('`n`n`@Mailverkehr:`n<table><tr><td>',true);
		$sql = 'SELECT petitionmail.*, accounts.login FROM petitionmail LEFT JOIN accounts ON petitionmail.msgfrom=accounts.acctid WHERE petitionid="'.$_GET['id'].'" ORDER BY sent ASC';
		$result = db_query($sql);
		while ($row2 = db_fetch_assoc($result)) {
			output('<table class="input" width="100%"><tr><td>',true);
			output('`4Datum:`& '.$row2['sent'].'`n`4Von:`& '.$row2['login'].'`n`4Betreff:`& '.$row2['subject'].'`n`4Text:`& ');
			output(str_replace("\n","`n",$row2['body']));
			output('</td></tr></table>`n',true);
			$answerbody = "\n\n----- Deine Anfrage -----\n".$row2['body'];
			$answersubject = 'RE: '.$row2['subject'];
		}
		output('</td></tr></table>',true);
		output('<form action="viewpetition.php?op=sendmessage&id='.$_GET['id'].'" method="post">',true);
		output('`@Ingame-Mail schreiben`n');
		output('Betreff: <input type="text" name="subject" value="'.$answersubject.'">`nText:`n<textarea name="body" class="input" cols="40" rows="9">'.$answerbody.'</textarea>`n<input type="submit" class="button" value="Senden"></form>`n',true);
		addnav('','viewpetition.php?op=sendmessage&id='.$_GET['id']);
		$sql = 'UPDATE petitionmail SET seen=1 WHERE petitionid="'.$_GET['id'].'"';
		db_query($sql);
	}	
	if ($_GET['viewpageinfo']){
		output("`n`n`@Seiten Info:`&`n");
		$row[pageinfo]=stripslashes($row[pageinfo]);
		$body = HTMLEntities($row[pageinfo]);
		$body = preg_replace("'([[:alnum:]_.-]+[@][[:alnum:]_.-]{2,}([.][[:alnum:]_.-]{2,})+)'i","<a href='mailto:\\1?subject=RE: Hilfeanfrage&body=".str_replace("+"," ",URLEncode("\n\n----- Deine Anfrage -----\n".$row[body]))."'>\\1</a>",$body);
		$body = preg_replace("'([\\[][[:alnum:]_.-]+[\\]])'i","<span class='colLtRed'>\\1</span>",$body);
		$output.="<span style='font-family: fixed-width'>".nl2br($body)."</span>";
	}	
	if ($row[status]==0) {
		$sql = "UPDATE petitions SET status=1 WHERE petitionid='$_GET[id]'";
		$result = db_query($sql);
	}
}
page_footer();
?>