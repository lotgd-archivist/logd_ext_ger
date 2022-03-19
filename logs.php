<?php

// 09072004

/*

View faillogs and user mail
Find multi accounts and cheaters 

by anpera

*/

	
require_once("common.php");

page_header("Logs und Mail");

if ($_GET[op]=="mail"){
	if ($_GET[in]){
		$ppp=25; // Player Per Page to display
		if (!$_GET[limit]){
			$page=0;
		}else{
			$page=(int)$_GET[limit];
			addnav("Vorherige Seite","logs.php?op=mail&in=$_GET[in]&limit=".($page-1)."");
		}
		$limit="".($page*$ppp).",".($ppp+1);
		output("`qYe Olde Mail (".($_GET[limit]*$ppp)."-".($_GET[limit]*$ppp+$ppp).") -- Alle Mails: Inbox $_GET[in] `0`n`n");
		$sql = "SELECT mail.*,accounts.login AS absender FROM mail LEFT JOIN accounts ON accounts.acctid=mail.msgfrom WHERE msgto=$_GET[in] ORDER BY sent DESC LIMIT $limit";
		$result = db_query($sql) or die(db_error(LINK));
		if (db_num_rows($result)>$ppp) addnav("Nächste Seite","logs.php?op=mail&in=$_GET[in]&limit=".($page+1)."");
		output("<table align='center'><tr><td>`bDatum`b</td><td>`bAbsender`b</td><td>`bBetreff`b</td></tr>",true);
		for ($i=0;$i<db_num_rows($result);$i++){
			$row = db_fetch_assoc($result);
			output("<tr><td>$row[sent]</td><td>$row[absender]</td><td>$row[subject]</td></tr><tr><td colspan='3'>$row[body]</td></tr>",true);
			output("<tr><td colspan='3'><hr></td></tr>",true);
		}
		output("</table>",true);
		addnav("Zurück","logs.php?op=mail");
	}else if ($_GET[out]){
		$ppp=25; // Player Per Page to display
		if (!$_GET[limit]){
			$page=0;
		}else{
			$page=(int)$_GET[limit];
			addnav("Vorherige Seite","logs.php?op=mail&out=$_GET[in]&limit=".($page-1)."");
		}
		$limit="".($page*$ppp).",".($ppp+1);
		output("`qYe Olde Mail (".($_GET[limit]*$ppp)."-".($_GET[limit]*$ppp+$ppp).") -- Alle Mails: Outbox $_GET[out] `0`n`n");
		$sql = "SELECT mail.*,accounts.login AS empfaenger FROM mail LEFT JOIN accounts ON accounts.acctid=mail.msgto WHERE msgfrom=$_GET[out] ORDER BY sent DESC LIMIT $limit";
		$result = db_query($sql) or die(db_error(LINK));
		if (db_num_rows($result)>$ppp) addnav("Nächste Seite","logs.php?op=mail&out=$_GET[out]&limit=".($page+1)."");
		output("<table align='center'><tr><td>`bDatum`b</td><td>`bEmpfänger`b</td><td>`bBetreff`b</td></tr>",true);
		for ($i=0;$i<db_num_rows($result);$i++){
			$row = db_fetch_assoc($result);
			output("<tr><td>$row[sent]</td><td>$row[empfaenger]</td><td>$row[subject]</td></tr><tr><td colspan='3'>$row[body]</td></tr>",true);
			output("<tr><td colspan='3'><hr></td></tr>",true);
		}
		output("</table>",true);
		addnav("Zurück","logs.php?op=mail");
	}else{
		if (!$_GET[subop]) $_GET[subop]="system";
		$ppp=25; // Player Per Page to display
		if (!$_GET[limit]){
			$page=0;
		}else{
			$page=(int)$_GET[limit];
			addnav("Vorherige Seite","logs.php?op=mail&limit=".($page-1)."&subop=$_GET[subop]&order=$sort");
		}
		$limit="".($page*$ppp).",".($ppp+1);
		$sort="sent";
		if ($_GET[order]) $sort=$_GET[order];
		output("`qYe Olde Mail (".($_GET[limit]*$ppp)."-".($_GET[limit]*$ppp+$ppp).") -- ".($_GET[subop]=="all"?"Private Mails":"Systemnachrichten")."`0`n`n");
		//addnav($_GET[subop]=="system"?"Normale Nachrichten":"Systemnachrichten","logs.php?op=mail&subop=".($_GET[subop]=="system"?"all":"system")."&order=$_GET[order]&limit=$_GET[limit]"); // ist möglicherweise nicht erlaubt
		$sql = "SELECT mail.*,accounts.login AS empfaenger FROM mail LEFT JOIN accounts ON accounts.acctid=mail.msgto WHERE ".($_GET[subop]=="system"?"msgfrom=0":"msgfrom<>0")." AND msgto<>0 ORDER BY $sort DESC LIMIT $limit";
		$result = db_query($sql) or die(db_error(LINK));
		if (db_num_rows($result)>$ppp) addnav("Nächste Seite","logs.php?op=mail&limit=".($page+1)."&order=$sort&subop=$_GET[subop]");
		output("<table align='center'><tr><td>`b<a href='logs.php?op=mail&limit=$page&order=sent&subop=$_GET[subop]'>Datum</a>`b</td><td>`b<a href='logs.php?op=mail&limit=$page&order=msgfrom&subop=$_GET[subop]'>Absender</a>`b</td><td>`b<a href='logs.php?op=mail&limit=$page&order=msgto&subop=$_GET[subop]'>Empfänger</a>`b</td><td>`bBetreff`b</td></tr>",true);
		addnav("","logs.php?op=mail&limit=$page&order=sent&subop=$_GET[subop]");
		addnav("","logs.php?op=mail&limit=$page&order=msgto&subop=$_GET[subop]");
		addnav("","logs.php?op=mail&limit=$page&order=msgfrom&subop=$_GET[subop]");
		for ($i=0;$i<db_num_rows($result);$i++){
			$row = db_fetch_assoc($result);
			$row2=db_fetch_assoc(db_query("SELECT acctid,login FROM accounts WHERE acctid=$row[msgfrom]"));
			output("<tr><td>$row[sent]</td><td><a href='logs.php?op=mail&out=$row2[acctid]'>$row2[login]</a></td><td><a href='logs.php?op=mail&in=$row[msgto]'>$row[empfaenger]</a></td><td>$row[subject]</td></tr><tr><td colspan='4'>$row[body]</td></tr>",true);
			output("<tr><td colspan='4'><hr></td></tr>",true);
			addnav("","logs.php?op=mail&in=$row[msgto]");
			addnav("","logs.php?op=mail&out=$row2[acctid]");
		}
		output("</table>",true);
		addnav("Zurück","logs.php");
		output("`n`iUm das Postfach eines Spielers zu sehen, auf seinen Namen unter \"Empfänger\" klicken.`nUm zu sehen, was ein Spieler gesendet hat, auf seinen Namen unter \"Absender\" klicken.`i");
	}
}else if ($_GET[op]=="faillog"){
	if ($_GET[id]){
		$ppp=25; // Player Per Page to display
		if (!$_GET[limit]){
			$page=0;
		}else{
			$page=(int)$_GET[limit];
			addnav("Vorherige Seite","logs.php?op=faillog&id=$_GET[id]&limit=".($page-1)."&order=$sort&pw=$_GET[pw]");
		}
		$limit="".($page*$ppp).",".($ppp+1);
		$sort="date";
		if ($_GET[order]) $sort=$_GET[order];
		$sql = "SELECT * FROM faillog WHERE acctid=$_GET[id] ORDER BY $sort DESC LIMIT $limit";
		$result = db_query($sql) or die(db_error(LINK));
		output("Fehlgeschlagene Logins (".($_GET[limit]*$ppp)."-".($_GET[limit]*$ppp+$ppp).") für ID $_GET[id]`n`n");
		if (db_num_rows($result)>$ppp) addnav("Nächste Seite","logs.php?op=faillog&id=$_GET[id]&limit=".($page+1)."&order=$sort&pw=$_GET[pw]");
		output("<table align='center'><tr><td>`b<a href='logs.php?op=faillog&id=$_GET[id]&limit=$page&order=date'>Datum</a>`b</td><td>`b<a href='logs.php?op=faillog&id=$_GET[id]&limit=$page&order=ip'>IP</a>`b</td>".($_GET[pw]?"<td>`bfalsches PW`b</td>":"")."</tr>",true);
		addnav("","logs.php?op=faillog&id=$_GET[id]&limit=$page&order=date");
		addnav("","logs.php?op=faillog&id=$_GET[id]&limit=$page&order=ip");
		for ($i=0;$i<db_num_rows($result);$i++){
			$row = db_fetch_assoc($result);
			if ($_GET[pw] && $session[user][superuser]>=3) $row[post]=unserialize($row[post]);
			output("<tr><td>$row[date]</td><td>$row[ip]</td>".($_GET[pw]?"<td>".$row[post][password]."</td>":"")."</tr>",true);
		}
		addnav("Zurück","logs.php?op=faillog&order=$_GET[order]");
		output("</table>",true);
		if ($session[user][superuser]>=3) addnav("PWs ".($_GET[pw]?"ausblenden":"einblenden")."","logs.php?op=faillog&id=$_GET[id]".($_GET[pw]?"":"&pw=true")."&order=$_GET[order]&limit=$_GET[limit]");
		if ($_GET[pw] && $i>=3) output("`n`iDie Anzeige der falsch eingegebenen Passwörter dient dazu, um festzustellen, wo Passwörter geraten werden und wo nur ein Tippfehler vorliegt.`i");
	}else if ($_GET[ip]){
		$ppp=25; // Player Per Page to display
		if (!$_GET[limit]){
			$page=0;
		}else{
			$page=(int)$_GET[limit];
			addnav("Vorherige Seite","logs.php?op=faillog&ip=$_GET[ip]&limit=".($page-1)."&pw=$_GET[pw]");
		}
		$limit="".($page*$ppp).",".($ppp+1);
		$sql = "SELECT faillog.*,accounts.name AS absender FROM faillog LEFT JOIN accounts ON accounts.acctid=faillog.acctid WHERE ip='$_GET[ip]' ORDER BY date DESC LIMIT $limit";
		$result = db_query($sql) or die(db_error(LINK));
		output("Fehlgeschlagene Logins (".($_GET[limit]*$ppp)."-".($_GET[limit]*$ppp+$ppp).") von IP $_GET[ip]`n`n");
		if (db_num_rows($result)>$ppp) addnav("Nächste Seite","logs.php?op=faillog&ip=$_GET[ip]&limit=".($page+1)."&pw=$_GET[pw]");
		output("<table align='center'><tr><td>`bDatum`b</td><td>`bName`b</td>".($_GET[pw]?"<td>`bfalsches PW`b</td>":"")."</tr>",true);
		for ($i=0;$i<db_num_rows($result);$i++){
			$row = db_fetch_assoc($result);
			if ($_GET[pw] && $session[user][superuser]>=3) $row[post]=unserialize($row[post]);
			output("<tr><td>$row[date]</td><td>$row[absender]</td>".($_GET[pw]?"<td>".$row[post][password]."</td>":"")."</tr>",true);
		}
		addnav("Zurück","logs.php?op=faillog&order=$_GET[order]");
		output("</table>",true);
		if ($session[user][superuser]>=3) addnav("PWs ".($_GET[pw]?"ausblenden":"einblenden")."","logs.php?op=faillog&ip=$_GET[ip]".($_GET[pw]?"":"&pw=true")."&order=$_GET[order]&limit=$_GET[limit]");
		if ($_GET[pw] && $i>=3) output("`n`iDie Anzeige der falsch eingegebenen Passwörter dient dazu, um festzustellen, wo Passwörter geraten werden und wo nur ein Tippfehler vorliegt.`i");
	}else{
		$ppp=25; // Player Per Page to display
		output("Fehlgeschlagene Logins (".($_GET[limit]*$ppp)."-".($_GET[limit]*$ppp+$ppp).")`n`iSpielername oder IP anklicken, um alle Fehlversuche anzuzeigen.`i`n`n");
		if (!$_GET[limit]){
			$page=0;
		}else{
			$page=(int)$_GET[limit];
			addnav("Vorherige Seite","logs.php?op=faillog&limit=".($page-1)."&order=$sort");
		}
		$limit="".($page*$ppp).",".($ppp+1);
		$sort="date";
		if ($_GET[order]) $sort=$_GET[order];
		$sql = "SELECT faillog.*,accounts.name AS absender FROM faillog LEFT JOIN accounts ON accounts.acctid=faillog.acctid WHERE 1 ORDER BY $sort DESC LIMIT $limit";
		$result = db_query($sql) or die(db_error(LINK));
		if (db_num_rows($result)>$ppp) addnav("Nächste Seite","logs.php?op=faillog&limit=".($page+1)."&order=$sort");
		output("<table align='center'><tr><td>`b<a href='logs.php?op=faillog&limit=$page&order=date'>Datum</a>`b</td><td>`b<a href='logs.php?op=faillog&limit=$page&order=acctid'>Acctid</a>`b</td><td>`bName`b</td><td>`b<a href='logs.php?op=faillog&limit=$page&order=ip'>IP</a>`b</td></tr>",true);
		addnav("","logs.php?op=faillog&limit=$page&order=date");
		addnav("","logs.php?op=faillog&limit=$page&order=acctid");
		addnav("","logs.php?op=faillog&limit=$page&order=ip");
		for ($i=0;$i<db_num_rows($result);$i++){
			$row = db_fetch_assoc($result);
			output("<tr><td>$row[date]</td><td>$row[acctid]</td><td><a href='logs.php?op=faillog&id=$row[acctid]&order=$sort'>$row[absender]</a></td><td><a href='logs.php?op=faillog&ip=$row[ip]&order=$sort'>$row[ip]</a></td></tr>",true);
			addnav("","logs.php?op=faillog&id=$row[acctid]&order=$sort");
			addnav("","logs.php?op=faillog&ip=$row[ip]&order=$sort");
		}
		output("</table>",true);
		addnav("Zurück","logs.php");
	}
} elseif ($_GET['op']=='multi') {
	if (!empty($_POST['setupban']) && count($_POST['userid'])>0) {
		output("<form action='logs.php?op=multi&act=saveban&searchby=$_GET[searchby]' method='POST'>",true);
		if ($_POST['setupban']=='IPs bannen') {
			$sql = 'SELECT lastip FROM accounts WHERE acctid IN ("'.implode('","',$_POST['userid']).'") GROUP BY lastip';
			$result = db_query($sql);
			$ips = array();
			while ($row = db_fetch_assoc($result)) $ips[] = $row['lastip'];
			output('Sperre für die IP '.implode(', ',$ips).'`n');
			output('<input type="hidden" name="type" value="ip"><input type="hidden" name="ip" value="'.implode('|',$ips).'">',true);
		}
		else {
			$sql = 'SELECT uniqueid FROM accounts WHERE acctid IN ("'.implode('","',$_POST['userid']).'") GROUP BY uniqueid';
			$result = db_query($sql);
			$ids = array();
			while ($row = db_fetch_assoc($result)) $ids[] = $row['uniqueid'];
			output('Sperre für die ID '.implode(', ',$ids).'`n');
			output('<input type="hidden" name="type" value="id"><input type="hidden" name="id" value="'.implode('|',$ids).'">',true);
		}
		output("Dauer: <input name='duration' id='duration' size='3' value='14'> days (0 for permanent)`n",true);
		output("Grund für die Verbannung: <input name='reason' value=\"Ärger mich nicht.\">`n",true);
		output("<input type='submit' class='button' value='Post Ban' onClick='if (document.getElementById(\"duration\").value==0) {return confirm(\"Willst du wirklich eine permanente Verbannung aussprechen?\");} else {return true;}'></form>",true);
		addnav("","logs.php?op=multi&act=saveban&searchby=$_GET[searchby]");
	}
	elseif ($_GET['act']=='saveban') {
		if ($_POST['type']=='ip') $vals = explode('|',$_POST['ip']);
		else $vals = explode('|',$_POST['id']);

		foreach ($vals AS $this) {
			$sql = "INSERT INTO bans (";
			if ($_POST[type]=="ip"){
				$sql.="ipfilter";
			}else{
				$sql.="uniqueid";
			}
			$sql.=",banexpire,banreason) VALUES (";
			$sql.="\"$this\"";
			$sql.=",\"".((int)$_POST[duration]==0?"0000-00-00":date("Y-m-d",strtotime("+$_POST[duration] days")))."\",";
			$sql.="\"$_POST[reason]\")";
			if ($_POST[type]=="ip"){
				if (substr($_SERVER['REMOTE_ADDR'],0,strlen($this)) == $this){
					$sql = "";
					output("Du willst dich doch nicht wirklich selbst verbannen, oder?? Das ist deine eigene IP-Adresse!");
				}
			}else{
				if ($_COOKIE[lgi]==$this){
					$sql = "";
					output("Du willst dich doch nicht wirklich selbst verbannen, oder?? Das ist deine eigene ID!");
				}
			}
			if ($sql!=""){
				db_query($sql) or die(db_error(LINK));
				output(db_affected_rows()." Bann eingetragen.`n`n");
				output(db_error(LINK));
			}
		}
		output('`n');
	}
	elseif (!empty($_POST['deleteuser']) && count($_POST['userid'])>0) {
		$sql = "SELECT name FROM accounts WHERE acctid IN (".implode(',',$_POST['userid']).")";
		$result = db_query($sql) or die(sql_error($sql));
		$delnames = array();
		while ($row = db_fetch_assoc($result)) {
			$delnames[] = '`$'.$row['name'].'`0';
		}
		output('User '.implode(', ',$delnames).' aus folgendem Grund löschen: ');
		output("<form action='logs.php?op=multi&act=dodeleteuser&searchby=$_GET[searchby]&userid=".implode(',',$_POST['userid'])."' method='POST'>",true);
		output('Begründung auswählen: ');
		output('<select name="stdreason" size="1">',true);
		output('<option value="0">--- keine Begründung angeben ---</option>',true);
		$sql = 'SELECT reasonid, reason FROM deluser_reasons';
		$result = db_query($sql);
		while ($row = db_fetch_assoc($result)) {
			if (strlen($row['reason'])>50) $row['reason'] = substr($row['reason'],0,47).'...';
			output('<option value="'.$row['reasonid'].'">'.htmlentities($row['reason']).'</option>',true);
		}
		output('</select>',true);
		output(' `n`ioder`i eingeben: ');
		output('<input type="Text" name="reason" value="" maxlength="255" size="50" /> ',true);
		output('<input type="Checkbox" name="savereason" value="1">',true);
		output('Begründung speichern`n');
		output('</option>',true);

		output('<input type="Submit" value="löschen">',true);
		output('</form>',true);
		addnav('','logs.php?op=multi&act=dodeleteuser&searchby='.$_GET['searchby'].'&userid='.implode(',',$_POST['userid']));
	}
	elseif ($_GET['act']=='dodeleteuser') {
		$userid = explode(',',$_GET['userid']);
		foreach ($userid AS $this) {
			deleteuser($this,$_POST['stdreason'],$_POST['reason'],$_POST['savereason']);
		}
		output('`n');
	}
	else output('`n');


	$in_ip = $in_id = '';
	if ($_GET['searchby']!='id') {
		$sql = 'SELECT lastip FROM accounts WHERE lastip!="" GROUP BY lastip HAVING COUNT(*) > 1';
		$result = db_query($sql) or die(db_error(LINK));
		while ($row = db_fetch_assoc($result)) {
			$in_ip .= ',"'.$row['lastip'].'"';
		}
	}
	if ($_GET['searchby']!='ip') {
		$sql = 'SELECT uniqueid FROM accounts WHERE uniqueid!="" GROUP BY uniqueid HAVING COUNT(*) > 1';
		$result = db_query($sql) or die(db_error(LINK));
		while ($row = db_fetch_assoc($result)) {
			$in_id .= ',"'.$row['uniqueid'].'"';
		}
	}

	$ip = $id = $users = array();
	$sql = 'SELECT acctid,name,lastip,uniqueid,dragonkills,level FROM accounts WHERE (lastip IN (-1'.$in_ip.') OR uniqueid IN (-1'.$in_id.')) AND locked="0" ORDER BY dragonkills ASC, level ASC';
	$result = db_query($sql) or die(db_error(LINK));
	while ($row = db_fetch_assoc($result)) {
		if ((!isset($id[$row['uniqueid']]) || $_GET['searchby']=='ip') && (!isset($ip[$row['lastip']]) || $_GET['searchby']=='id')) {
			if ($_GET['searchby']!='id') $ip[$row['lastip']] = count($users);
			if ($_GET['searchby']!='ip') $id[$row['uniqueid']] = count($users);
			$users[] = array($row);
		}
		elseif (isset($id[$row['uniqueid']])) {
			$ip[$row['lastip']] = $id[$row['uniqueid']];
			$users[$id[$row['uniqueid']]][] = $row;
		}
		else {
			$id[$row['uniqueid']] = $ip[$row['lastip']];
			$users[$ip[$row['lastip']]][] = $row;
		}
	}

	output('`n`bMultiaccounts`b`nNaaa, wer spielt denn hier noch wen?`n`n');
	output('Multiaccounts suchen nach: ');
	if ($_GET['searchby']!='ip') {
		output('<a href="logs.php?op=multi&searchby=ip">IP</a> ',true);
		addnav('','logs.php?op=multi&searchby=ip');
	}
	else output('`&`bIP`b`0 ');
	if ($_GET['searchby']!='id') {
		output('<a href="logs.php?op=multi&searchby=id">ID</a> ',true);
		addnav('','logs.php?op=multi&searchby=id');
	}
	else output('`&`bID`b`0 ');
	if (!empty($_GET['searchby'])) {
		output('<a href="logs.php?op=multi&searchby=">Beidem</a> ',true);
		addnav('','logs.php?op=multi&searchby=');
	}
	else output('`&`bBeidem`b`0 ');
	output('<table><tr><td>',true);
	foreach ($users AS $list) {
		if (count($list)<3) continue;
		$tmpstr = '';
		$ips = $ids = $accts = array();
		foreach ($list AS $this) {
			$tmpstr .= ('<tr><td><input type="checkbox" name="userid[]" value="'.$this['acctid'].'"></td>
							<td>'.$this['name'].'</td>
							<td>'.$this['lastip'].'</td>
							<td>'.$this['uniqueid'].'</td>
							<td>'.$this['dragonkills'].'</td>
							<td>'.$this['level'].'</td></tr>');
		}
		output('<form action="logs.php?op=multi&searchby='.$_GET['searchby'].'" method="post">',true);
		addnav('','logs.php?op=multi&searchby='.$_GET['searchby']);
		output("<table align='center' class='input' width='100%'><tr><td>&nbsp;</td>
						<td>`bName`b</td>
						<td>`bIP`b</td>
						<td>`bID`b</td>
						<td>`bDK`b</td>
						<td>`bLevel`b</td>
						</tr>",true);
		output($tmpstr,true);
		output('<tr><td colspan="6" align="left">
						<input type="submit" name="deleteuser" value="löschen">
						<input type="submit" name="setupban" value="IPs bannen">
						<input type="submit" name="setupban" value="IDs bannen">
					</td></tr>',true);
		output('</table>`n`n',true);
		output('</form>',true);
	}
	output('</td></tr></table>',true);
	addnav('Aktualisieren','logs.php?op=multi&searchby='.$_GET['searchby']);
	addnav('Zurück','logs.php');
}else{
	output("Die 5 letzten fehlgeschlagenen Logins:`n`n");
	$sql = "SELECT faillog.*,accounts.name AS absender FROM faillog LEFT JOIN accounts ON accounts.acctid=faillog.acctid WHERE 1 ORDER BY date DESC LIMIT 5";
	$result = db_query($sql) or die(db_error(LINK));
	output("<table align='center'><tr><td>`bDatum`b</td><td>`bAcctid`b</td><td>`bName`b</td><td>`bIP`b</td></tr>",true);
	for ($i=0;$i<db_num_rows($result);$i++){
		$row = db_fetch_assoc($result);
		output("<tr><td>$row[date]</td><td>$row[acctid]</td><td>$row[absender]</td><td>$row[ip]</td></tr>",true);
	}
	output("</table>`n`nDie 5 letzten Systemmails:`n`n",true);
	$sql = "SELECT mail.*,accounts.name AS empfaenger FROM mail LEFT JOIN accounts ON accounts.acctid=mail.msgto WHERE msgfrom=0 ORDER BY sent DESC LIMIT 5";
	$result = db_query($sql) or die(db_error(LINK));
	output("<table align='center'><tr><td>`bDatum`b</td><td>`bEmpfänger`b</td><td>`bBetreff`b</td></tr>",true);
	for ($i=0;$i<db_num_rows($result);$i++){
		$row = db_fetch_assoc($result);
		output("<tr><td>$row[sent]</td><td>$row[empfaenger]</td><td>$row[subject]</td></tr>",true);
	}
	output("</table>`n",true);
	addnav("Faillog","logs.php?op=faillog");
	addnav("Usermails","logs.php?op=mail");
	addnav("Multiaccounts","logs.php?op=multi");
	addnav("Aktualisieren","logs.php");
}
addnav("Zurück zur Grotte","superuser.php");
addnav("Zurück zum Weltlichen","village.php");
output("`n<div align='right'>`)2004 by anpera & Chaosmaker</div>",true);
page_footer();
?>