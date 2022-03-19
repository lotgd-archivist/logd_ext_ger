<?php

// 15082004

require_once "common.php";
isnewday(3);

if ($_GET[op]=="search"){
	$sql = "SELECT acctid FROM accounts WHERE ";
	$where="
	login LIKE '%{$_POST['q']}%' OR 
	acctid LIKE '%{$_POST['q']}%' OR 
	name LIKE '%{$_POST['q']}%' OR 
	emailaddress LIKE '%{$_POST['q']}%' OR 
	lastip LIKE '%{$_POST['q']}%' OR 
	uniqueid LIKE '%{$_POST['q']}%' OR 
	gentimecount LIKE '%{$_POST['q']}%' OR 
	level LIKE '%{$_POST['q']}%'";
	$result = db_query($sql.$where);
	if (db_num_rows($result)<=0){
		output("`\$Keine Ergebnisse gefunden`0");
		$_GET[op]="";
		$where="";
	}elseif (db_num_rows($result)>100){
		output("`\$Zu viele Ergebnisse gefunden. Bitte Suche einengen.`0");
		$_GET[op]="";
		$where="";
	}elseif (db_num_rows($result)==1){
		//$row = db_fetch_assoc($result);
		//redirect("user.php?op=edit&userid=$row[acctid]");
		$_GET[op]="";
		$_GET['page']=0;
	}else{
		$_GET[op]="";
		$_GET['page']=0;
	}
}

page_header("User Editor");
	output("<form action='user.php?op=search' method='POST'>Suche in allen Feldern: <input name='q' id='q'><input type='submit' class='button'></form>",true);
	output("<script language='JavaScript'>document.getElementById('q').focus();</script>",true);
	addnav("","user.php?op=search");
addnav("G?Zurück zur Grotte","superuser.php");
addnav("W?Zurück zum Weltlichen","village.php");
addnav("Verbannung","user.php?op=setupban");
addnav("Verbannungen anzeigen/entfernen","user.php?op=removeban");
//addnav("Benutzereditor","user.php");
$sql = "SELECT count(acctid) AS count FROM accounts";
$result = db_query($sql);
$row = db_fetch_assoc($result);
$page=0;
while ($row[count]>0){
	$page++;
	addnav("$page Seite $page","user.php?page=".($page-1)."&sort=$_GET[sort]");
	$row[count]-=100;
}

$mounts=",0,Keins";
$sql = "SELECT mountid,mountname,mountcategory FROM mounts ORDER BY mountcategory";
$result = db_query($sql);
while ($row = db_fetch_assoc($result)){
	$mounts.=",{$row['mountid']},{$row['mountcategory']}: {$row['mountname']}";
}
$userinfo = array(
	"Account Info,title",
	"acctid"=>"User ID,viewonly",
	"login"=>"Login",
	"newpassword"=>"Neues Passwort",
	"emailaddress"=>"Email Adresse",
	"locked"=>"Account gesperrt,bool",
	"banoverride"=>"Verbannungen übergehen,bool",
	"superuser"=>"Superuser,enum,0,Standard Spieltage pro Kalendertag,1,Unbegrenzt Spieltage pro Kalendertag,2,Kreaturen und Spott administrieren,3,User administrieren",
	
	"User Infos,title",
	"name"=>"Display Name",
	"title"=>"Titel (muss auch in Display Name)",
	"ctitle"=>"Eigener Titel (muss auch in Display Name)",
	"sex"=>"Geschlecht,enum,0,Männlich,1,Weiblich",
// we can't change this this way or their stats will be wrong.
//	"race"=>"Race,enum,0,Unknown,1,Troll,2,Elf,3,Human,4,Dwarf,5,Echse",
	"age"=>"Tage seit Level 1,int",
	"dragonkills"=>"Drachenkills,int",
	"dragonage"=>"Alter beim letzten Drachenkill,int",
	"bestdragonage"=>"Jüngstes Alter bei einem Drachenkill,int",
	"bio"=>"Bio",
	
	"Werte,title",
	"level"=>"Level,int",
	"experience"=>"Erfahrung,int",
	"hitpoints"=>"Lebenspunkte (aktuell),int",
	"maxhitpoints"=>"Maximale Lebenspunkte,int",
	"turns"=>"Runden übrig,int",
	"playerfights"=>"Spielerkämpfe übrig,int",
	"attack"=>"Angriffswert (inkl. Waffenschaden),int",
	"defence"=>"Verteidigung (inkl. Rüstung),int",
	"spirits"=>"Stimmung (nur Anzeige),enum,-2,Sehr schlecht,-1,Schlecht,0,Normal,1,Gut,2,Sehr gut",
	"resurrections"=>"Auferstehungen,int",
	"alive"=>"Lebendig,int",
	"reputation"=>"Ansehen (-50 - +50),int",
	
	"Spezialitäten,title",
	"specialty"=>"Spezialität,enum,0,Unspezifiziert,1,Dunkle Künste,2,Mystische Kräfte,3,Diebeskunst",
	"darkarts"=>"`4Stufe  in Dunklen Künsten`0,int",
	"darkartuses"=>"`4^--heute übrig`0,int",
	"magic"=>"`%Stufe in Mystischen Kräften`0,int",
	"magicuses"=>"`%^--heute übrig`0,int",
	"thievery"=>"`^Stufe in Diebeskunst`0,int",
	"thieveryuses"=>"`^^--heute übrig`0,int",

	"Grabkämpfe,title",
	"deathpower"=>"Gefallen bei Ramius,int",
	"gravefights"=>"Grabkämpfe übrig,int",
	"soulpoints"=>"Seelenpunkte (HP im Tod),int",

	
	"Ausstattung,title",
	"gems"=>"Edelsteine,int",
	"gold"=>"Bargold,int",
	"goldinbank"=>"Gold auf der Bank,int",
	"transferredtoday"=>"Anzahl Transfers heute,int",
	"amountouttoday"=>"Heute ausgegengener Wert der Überweisungen,int",
	"weapon"=>"Name der Waffe",
	"weapondmg"=>"Waffenschaden,int",
	"weaponvalue"=>"Kaufwert der Waffe,int",
	"armor"=>"Name der Rüstung",
	"armordef"=>"Verteidigungswert,int",
	"armorvalue"=>"Kaufwert der Rüstung,int",
	
	"Sonderinfos,title",
	"house"=>"Haus-ID,int",
	"housekey"=>"Hausschlüssel?,int",
	"marriedto"=>"Partner-ID (4294967295 = Violet/Seth),int",
	"charisma"=>"Flirts (4294967295 = verheiratet mit Partner),int",
	"seenlover"=>"Geflirtet,bool",
	"seenbard"=>"Barden gehört,bool",
	"charm"=>"Charme,int",
	"seendragon"=>"Drachen heute gesucht,bool",
	"seenmaster"=>"Meister befragt,bool",
	"usedouthouse"=>"Plumpsklo besucht,bool",
	"fedmount"=>"Tier gefüttert,bool",
	"gotfreeale"=>"Frei-Ale (MSB: getrunken - LSB: spendiert),int",
	"hashorse"=>"Tier,enum$mounts",
	"boughtroomtoday"=>"Zimmer für heute bezahlt,bool",
	"drunkenness"=>"Betrunken (0-100),int",
	"avatar"=>"Avatar:",
	
	"Weitere Infos,title",
	"beta"=>"Nimmt am Betatest teil,viewonly",
	"slainby"=>"Gekillt von Spieler,viewonly",
	"laston"=>"Zuletzt Online,viewonly",
	"lasthit"=>"Letzter neuer Tag,viewonly",
	"lastmotd"=>"Datum der letzten MOTD,viewonly",
	"lastip"=>"Letzte IP,viewonly",
	"uniqueid"=>"Unique ID,viewonly",
	"gentime"=>"Summe der Seitenerzeugungszeiten,viewonly",
	"gentimecount"=>"Seitentreffer,viewonly",
	"allowednavs"=>"Zulässige Navigation,viewonly",
	"dragonpoints"=>"Eingesetzte Drachenpunkte,viewonly",
	"bufflist"=>"Spruchliste,viewonly",
	"prefs"=>"Einstellungen,viewonly",
	"lastwebvote"=>"Zuletzt bei Top Wep Games gewählt,viewonly",
	"donationconfig"=>"Spendenkäufe,viewonly"
	);

if ($_GET[op]=="lasthit"){
	$output="";
	$sql = "SELECT output FROM accounts WHERE acctid='{$_GET['userid']}'";
	$result = db_query($sql);
	$row = db_fetch_assoc($result);
	echo str_replace("<iframe src=","<iframe Xsrc=",$row['output']);
	exit();
}elseif ($_GET[op]=="edit"){
	$result = db_query("SELECT * FROM accounts WHERE acctid='$_GET[userid]'") or die(db_error(LINK));
	$row = db_fetch_assoc($result) or die(db_error(LINK));
	output("<form action='user.php?op=special&userid=$_GET[userid]".($_GET['returnpetition']!=""?"&returnpetition={$_GET['returnpetition']}":"")."' method='POST'>",true);
	addnav("","user.php?op=special&userid=$_GET[userid]".($_GET['returnpetition']!=""?"&returnpetition={$_GET['returnpetition']}":"")."");
	output("<input type='submit' class='button' name='newday' value='Neuen Tag gewähren'>",true);
	output("<input type='submit' class='button' name='fixnavs' value='Defekte Navs reparieren'>",true);
	output("<input type='submit' class='button' name='clearvalidation' value='E-Mail als gültig markieren'>",true);
	output("</form>",true);

	if ($_GET['returnpetition']!=""){
		addnav("Zurück zur Anfrage","viewpetition.php?op=view&id={$_GET['returnpetition']}");
	}
	
	addnav("Letzten Treffer anzeigen","user.php?op=lasthit&userid={$_GET['userid']}",false,true);
	output("<form action='user.php?op=save&userid=$_GET[userid]".($_GET['returnpetition']!=""?"&returnpetition={$_GET['returnpetition']}":"")."' method='POST'>",true);
	addnav("","user.php?op=save&userid=$_GET[userid]".($_GET['returnpetition']!=""?"&returnpetition={$_GET['returnpetition']}":"")."");
	addnav("","user.php?op=edit&userid=$_GET[userid]".($_GET['returnpetition']!=""?"&returnpetition={$_GET['returnpetition']}":"")."");
	addnav("Verbannen","user.php?op=setupban&userid=$row[acctid]");
	addnav("Debug-Log anzeigen","user.php?op=debuglog&userid={$_GET['userid']}");
	output("<input type='submit' class='button' value='Speichern'>",true);
	showform($userinfo,$row);
	output("</form>",true);
	output("<iframe src='user.php?op=lasthit&userid={$_GET['userid']}' width='100%' height='400'>Dein Browser muss iframes unterstützen, um die letzte Seite des Users anzeigen zu können. Benutze den Link im Menü stattdessen.</iframe>",true);
	addnav("","user.php?op=lasthit&userid={$_GET['userid']}");
}elseif ($_GET[op]=="special"){
	if ($_POST[newday]!=""){
		$sql = "UPDATE accounts SET lasthit='".date("Y-m-d H:i:s",strtotime(date("r")."-".(86500/getsetting("daysperday",4))." seconds"))."' WHERE acctid='$_GET[userid]'";
	}elseif($_POST[fixnavs]!=""){
		$sql = "UPDATE accounts SET allowednavs='',output='' WHERE acctid=$_GET[userid]";
	}elseif($_POST[clearvalidation]!=""){
		$sql = "UPDATE accounts SET emailvalidation='' WHERE acctid='$_GET[userid]'";
	}
	db_query($sql);
	if ($_GET['returnpetition']==""){
		// redirect("user.php?".db_affected_rows());
		redirect("user.php");
	}else{
		redirect("viewpetition.php?op=view&id={$_GET['returnpetition']}");
	}
}elseif ($_GET[op]=="save"){
	$sql = "UPDATE accounts SET ";
	// Ein paar Sicherheiten für Änderungen
	// Gesamtname geändert
	if ($_POST['oldname']!=$_POST['name']) {
		$clearedname = preg_replace('/`./','',$_POST['name']);
		// Login bleibt gleich
		if (substr_count($clearedname,$_POST['login'])) {
			// Titel rausfinden
			$replace = '(`.)*';
			for ($i=0;$i<strlen($_POST['login']);$i++) {
				$replace .= $_POST['login']{$i}.'(`.)*';
			}
			$_POST['ctitle'] = rtrim(preg_replace('/'.$replace.'/','',$_POST['name']));
			if ($_POST['ctitle']=='') $_POST['title'] = '';
			elseif ($_POST['ctitle']==$_POST['title']) $_POST['ctitle'] = '';
		}
		// Neuer Login
		else {
			// Leerzeichen vorhanden
			if ($login = strrchr($_POST['name'],' ')) {
				$_POST['login'] = trim(strrchr($clearedname,' '));
				$_POST['ctitle'] = str_replace($login,'',$_POST['name']);
				if ($_POST['ctitle']==$_POST['title']) $_POST['ctitle'] = '';
			}
			// Kein Leerzeichen vorhanden
			else {
				$_POST['login'] = $clearedname;
				$_POST['title'] = $_POST['ctitle'] = '';
			}
		}
	}
	// Login geändert
	elseif ($_POST['oldlogin']!=$_POST['login']) {
		if ($_POST['ctitle']!='') $_POST['name'] = $_POST['ctitle'].' '.$_POST['login'];
		else $_POST['name'] = $_POST['title'].' '.$_POST['login'];
	}
	// Titel geändert
	elseif ($_POST['oldtitle']!=$_POST['title'] && $_POST['ctitle']=='') {
		if ($_POST['oldctitle']!='') $colname = str_replace($_POST['oldctitle'],'',$_POST['name']);
		else $colname = str_replace($_POST['oldtitle'],'',$_POST['name']);
		$_POST['name'] = $_POST['title'].$colname;
	}
	// Usertitel geändert
	elseif ($_POST['oldctitle']!=$_POST['ctitle']) {
		if ($_POST['oldctitle']!='') $colname = str_replace($_POST['oldctitle'],'',$_POST['name']);
		else $colname = str_replace($_POST['oldtitle'],'',$_POST['name']);
		if ($_POST['ctitle']=='') $_POST['name'] = $_POST['title'].$colname;
		else $_POST['name'] = $_POST['ctitle'].$colname;
	}

	reset($_POST);
	while (list($key,$val)=each($_POST)){
		if (isset($userinfo[$key])){
			if ($key=="newpassword" ){
				if ($val>"") $sql.="password = MD5(\"$val\"),";
			}else{
				$sql.="$key = \"$val\",";
			}
		}
	}
	$sql=substr($sql,0,strlen($sql)-1);
	$sql.=" WHERE acctid=\"$_GET[userid]\"";
	//output("<pre>$sql</pre>");
	//echo "<pre>$sql</pre>";
	//redirect("user.php");
	//output( db_affected_rows()." rows affected");
	
	//we must manually redirect so that our changes go in to effect *after* our user save.
	addnav("","viewpetition.php?op=view&id={$_GET['returnpetition']}");
	addnav("","user.php");
	saveuser();
	db_query($sql) or die(db_error(LINK));
	if ($_GET['returnpetition']!=""){
		header("Location: viewpetition.php?op=view&id={$_GET['returnpetition']}");
	}else{
		header("Location: user.php");
	}

	exit();
}elseif ($_GET[op]=="del"){
	$sql = "SELECT name from accounts WHERE acctid='$_GET[userid]'";
	$res = db_query($sql);
	// inventar und haus löschen und partner und ei freigeben
	if ($_GET[userid]==getsetting("hasegg",0)) savesetting("hasegg",stripslashes(0));
    	$sql = "UPDATE items SET owner=0 WHERE owner=$_GET[userid]";
	db_query($sql);
	$sql = "UPDATE houses SET owner=0,status=3 WHERE owner=$_GET[userid] AND status=1";
	db_query($sql);
	$sql = "UPDATE houses SET owner=0,status=4 WHERE owner=$_GET[userid] AND status=0";
	db_query($sql);
	$sql = "UPDATE accounts SET charisma=0,marriedto=0 WHERE marriedto=$_GET[userid]";
	db_query($sql);
	$sql = "DELETE FROM pvp WHERE acctid2=$_GET[userid] OR acctid1=$_GET[userid]";
	db_query($sql) or die(db_error(LINK));
	$sql = "DELETE FROM accounts WHERE acctid='$_GET[userid]'";
	db_query($sql);
	output( db_affected_rows()." Benutzer gelöscht.");
	while ($row = db_fetch_assoc($res)) {
		addnews("`#{$row['name']} wurde von den Göttern aufgelöst.");
	}
}elseif($_GET[op]=="setupban"){
	$sql = "SELECT name,lastip,uniqueid FROM accounts WHERE acctid=\"$_GET[userid]\"";
	$result = db_query($sql) or die(db_error(LINK));
	$row = db_fetch_assoc($result);
	if ($row[name]!="") output("Setting up ban information based on `\$$row[name]`0");
	output("<form action='user.php?op=saveban' method='POST'>",true);
	output("Verbannung über IP oder ID (IP bevorzugt. Bei Usern hinter NAT kannst du eine Verbannung über ID versuchen, die aber leicht ungangen werden kann)`n");
	output("<input type='radio' value='ip' name='type' checked> IP: <input name='ip' value=\"".HTMLEntities($row[lastip])."\">`n",true);
	output("<input type='radio' value='id' name='type'> ID: <input name='id' value=\"".HTMLEntities($row[uniqueid])."\">`n",true);
	output("Dauer: <input name='duration' id='duration' size='3' value='14'> days (0 for permanent)`n",true);
	output("Grund für die Verbannung: <input name='reason' value=\"Ärger mich nicht.\">`n",true);
	output("<input type='submit' class='button' value='Post Ban' onClick='if (document.getElementById(\"duration\").value==0) {return confirm(\"Willst du wirklich eine permanente Verbannung aussprechen?\");} else {return true;}'></form>",true);
	output("Bei einem IP-Bann gib entweder eine komplette IP ein, oder gebe nur den Anfang der IP ein, wenn du einen IP-Bereich sperren willst.");
	addnav("","user.php?op=saveban");
}elseif($_GET[op]=="saveban"){
	$sql = "INSERT INTO bans (";
	if ($_POST[type]=="ip"){
		$sql.="ipfilter";
	}else{
		$sql.="uniqueid";
	}
	$sql.=",banexpire,banreason) VALUES (";
	if ($_POST[type]=="ip"){
		$sql.="\"$_POST[ip]\"";
	}else{
		$sql.="\"$_POST[id]\"";
	}
	$sql.=",\"".((int)$_POST[duration]==0?"0000-00-00":date("Y-m-d",strtotime(date("r")."+$_POST[duration] days")))."\",";
	$sql.="\"$_POST[reason]\")";
	if ($_POST[type]=="ip"){
		if (substr($_SERVER['REMOTE_ADDR'],0,strlen($_POST[ip])) == $_POST[ip]){
			$sql = "";
			output("Du willst dich doch nicht wirklich selbst verbannen, oder?? Das ist deine eigene IP-Adresse!");
		}
	}else{
		if ($_COOKIE[lgi]==$_POST[id]){
			$sql = "";
			output("Du willst dich doch nicht wirklich selbst verbannen, oder?? Das ist deine eigene ID!");
		}
	}
	if ($sql!=""){
		db_query($sql) or die(db_error(LINK));
		output(db_affected_rows()." Bann eingetragen.`n`n");
		output(db_error(LINK));
	}
}elseif($_GET[op]=="delban"){
	$sql = "DELETE FROM bans WHERE ipfilter = '$_GET[ipfilter]' AND uniqueid = '$_GET[uniqueid]'";
	db_query($sql);
	//output($sql);
	redirect("user.php?op=removeban");
}elseif($_GET[op]=="removeban"){	
	db_query("DELETE FROM bans WHERE banexpire < \"".date("Y-m-d")."\" AND banexpire>'0000-00-00'");
	
	$sql = "SELECT * FROM bans ORDER BY banexpire";
	$result = db_query($sql) or die(db_error(LINK));
	output("<table><tr><td>Ops</td><td>IP/ID</td><td>Dauer</td><td>Text</td><td>Betrifft:</td></tr>",true);
	for ($i=0;$i<db_num_rows($result);$i++){
		$row = db_fetch_assoc($result);
		output("<tr class='".($i%2?"trlight":"trdark")."'><td><a href='user.php?op=delban&ipfilter=".URLEncode($row[ipfilter])."&uniqueid=".URLEncode($row[uniqueid])."'>Bann&nbsp;aufheben</a>",true);
		addnav("","user.php?op=delban&ipfilter=".URLEncode($row[ipfilter])."&uniqueid=".URLEncode($row[uniqueid]));
		output("</td><td>",true);
		output($row[ipfilter]);
		output($row[uniqueid]);
		output("</td><td>",true);
		$expire=round((strtotime($row[banexpire])-strtotime(date("r"))) / 86400,0)." Tage";
		if (substr($expire,0,2)=="1 ") $expire="1 Tag";
		if (date("Y-m-d",strtotime($row[banexpire])) == date("Y-m-d")) $expire="Heute";
		if (date("Y-m-d",strtotime($row[banexpire])) == date("Y-m-d",strtotime("1 day"))) $expire="Morgen";
		if ($row[banexpire]=="0000-00-00") $expire="Nie";
		output($expire);
		output("</td><td>",true);
		output($row[banreason]);
		output("</td><td>",true);
		$sql = "SELECT DISTINCT accounts.name FROM bans, accounts WHERE (ipfilter='".addslashes($row['ipfilter'])."' AND bans.uniqueid='".addslashes($row['uniqueid'])."') AND ((substring(accounts.lastip,1,length(ipfilter))=ipfilter AND ipfilter<>'') OR (bans.uniqueid=accounts.uniqueid AND bans.uniqueid<>''))";
		$r = db_query($sql);
		for ($x=0;$x<db_num_rows($r);$x++){
			$ro = db_fetch_assoc($r);
			output("`0{$ro['name']}`n");
		}
		output("</td></tr>",true);
	}
	output("</table>",true);
}elseif ($_GET[op]=="debuglog"){
	$id = $_GET['userid'];
	$sql = "SELECT debuglog.*,a1.name as actorname,a2.name as targetname FROM debuglog LEFT JOIN accounts as a1 ON a1.acctid=debuglog.actor LEFT JOIN accounts as a2 ON a2.acctid=debuglog.target WHERE debuglog.actor=$id OR debuglog.target=$id ORDER by debuglog.date DESC,debuglog.id ASC LIMIT 500";
	addnav("User Info bearbeiten","user.php?op=edit&userid=$id");
	$result = db_query($sql);
	$odate = "";
	for ($i=0; $i<db_num_rows($result); $i++) {
		$row = db_fetch_assoc($result);
		$dom = date("D, M d",strtotime($row['date']));
		if ($odate != $dom){
			output("`n`b`@".$dom."`b`n");
			$odate = $dom;
		}
		$time = date("H:i:s", strtotime($row['date']));
		output("$time - {$row['actorname']} {$row['message']}");
		if ($row['target']) output(" {$row['targetname']}");
		output("`n");
	}
}elseif ($_GET[op]==""){
	if (isset($_GET['page'])){
		$order = "acctid";
		if ($_GET[sort]!="") $order = "$_GET[sort]";
		$offset=(int)$_GET['page']*100;
		$sql = "SELECT acctid,login,name,level,laston,gentimecount,lastip,uniqueid,emailaddress FROM accounts ".($where>""?"WHERE $where ":"")."ORDER BY \"$order\" LIMIT $offset,100";
		$result = db_query($sql) or die(db_error(LINK));
		output("<table>",true);
		output("<tr>
		<td>Ops</td>
		<td><a href='user.php?sort=login'>Login</a></td>
		<td><a href='user.php?sort=name'>Name</a></td>
		<td><a href='user.php?sort=level'>Lev</a></td>
		<td><a href='user.php?sort=laston'>Zuletzt da</a></td>
		<td><a href='user.php?sort=gentimecount'>Treffer</a></td>
		<td><a href='user.php?sort=lastip'>IP</a></td>
		<td><a href='user.php?sort=uniqueid'>ID</a></td>
		<td><a href='user.php?sort=emailaddress'>E-Mail</a></td>
		</tr>",true);
		addnav("","user.php?sort=login");
		addnav("","user.php?sort=name");
		addnav("","user.php?sort=level");
		addnav("","user.php?sort=laston");
		addnav("","user.php?sort=gentimecount");
		addnav("","user.php?sort=lastip");
		addnav("","user.php?sort=uniqueid");
		$rn=0;
		for ($i=0;$i<db_num_rows($result);$i++){
			$row=db_fetch_assoc($result);
			$laston=round((strtotime(date("r"))-strtotime($row[laston])) / 86400,0)." Tage";
			if (substr($laston,0,2)=="1 ") $laston="1 Tag";
			if (date("Y-m-d",strtotime($row[laston])) == date("Y-m-d")) $laston="Heute";
			if (date("Y-m-d",strtotime($row[laston])) == date("Y-m-d",strtotime(date("r")."-1 day"))) $laston="Gestern";
			if ($loggedin) $laston="Jetzt";
			$row[laston]=$laston;
			if ($row[$order]!=$oorder) $rn++;
			$oorder = $row[$order];
			output("<tr class='".($rn%2?"trlight":"trdark")."'>",true);
			
			output("<td>",true);
			output("[<a href='user.php?op=edit&userid=$row[acctid]'>Edit</a>|".
				"<a href='user.php?op=del&userid=$row[acctid]' onClick=\"return confirm('Willst du diesen User wirklich löschen?');\">Del</a>|".
				"<a href='user.php?op=setupban&userid=$row[acctid]'>Ban</a>|".
				"<a href='user.php?op=debuglog&userid=$row[acctid]'>Log</a>]",true);
			addnav("","user.php?op=edit&userid=$row[acctid]");
			addnav("","user.php?op=del&userid=$row[acctid]");
			addnav("","user.php?op=setupban&userid=$row[acctid]");
			addnav("","user.php?op=debuglog&userid=$row[acctid]");
			output("</td><td>",true);
			output($row[login]);
			output("</td><td>",true);
			output($row[name]);
			output("</td><td>",true);
			output($row[level]);
			output("</td><td>",true);
			output($row[laston]);
			output("</td><td>",true);
			output($row[gentimecount]);
			output("</td><td>",true);
			output($row[lastip]);
			output("</td><td>",true);
			output($row[uniqueid]);
			output("</td><td>",true);
			output($row[emailaddress]);
			output("</td>",true);
			$gentimecount+=$row[gentimecount];
			$gentime+=$row[gentime];
	
			output("</tr>",true);
		}
		output("</table>",true);
		output("Treffer gesamt: $gentimecount`n");
		output("CPU-Zeit gesamt: ".round($gentime,3)."s`n");
		output("Durchschnittszeit für Seitenerzeugung: ".round($gentime/max($gentimecount,1),4)."s`n");
	}
}
page_footer();
?>
