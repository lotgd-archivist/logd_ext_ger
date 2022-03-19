<?php

// 22062004

require_once "common.php";
checkday();
$result = db_query("SELECT login,name,level,sex,title,specialty,hashorse,acctid,age,marriedto,pvpflag,charisma,resurrections,bio,dragonkills,race,avatar,housekey,punch,reputation,birthday FROM accounts WHERE login='$_GET[char]'");
$row = db_fetch_assoc($result);
$row[login] = rawurlencode($row[login]);

page_header("Charakter Biographie: ".preg_replace("'[`].'","",$row[name]));
$specialty=array(0=>"nicht spezifiziert","Dunkle Künste","Mystische Kräfte","Diebeskunst");
//$horses=array(0=>"None","Pony","Gelding","Stallion");
output("`^Biographie für $row[name]");
if ($session[user][loggedin]) output("<a href=\"mail.php?op=write&to=$row[login]\" target=\"_blank\" onClick=\"".popup("mail.php?op=write&to=$row[login]").";return false;\"><img src='images/newscroll.GIF' width='16' height='16' alt='Mail schreiben' border='0'></a>",true);
if (getsetting("avatare",0)==1){
	if ($row[avatar]){
		$pic_size = @getimagesize($row[avatar]);
		$pic_width = $pic_size[0];
		$pic_height = $pic_size[1];
		output("<table><tr><td valign='top'>`n`n<img src=\"$row[avatar]\" ",true);
		if ($pic_width > 200) output("width=\"200\" ",true );
		if ($pic_height > 200) output("height=\"200\" ",true );
		output("alt=\"".preg_replace("'[`].'","",$row[name])."\">&nbsp;</td><td valign='top'>",true);
	} else {
		output("<table><tr><td>(kein Bild)&nbsp;&nbsp;&nbsp;</td><td>",true);
	}
}
output("`n`n`^Titel: `@$row[title]`n");
if (getsetting("activategamedate","0")==1 && $row[birthday]!="") output("`^Geburtstag: `@$row[birthday]`n");
output("`^Level: `@$row[level]`n");
output("`^Alter seit DK: `@$row[age]`^ Tage`n");
output("`^Wiedererweckt: `@$row[resurrections]x`n");
output("`^Rasse: `@{$races[$row['race']]}`n");
output("`^Geschlecht: `@".($row[sex]?"Weiblich":"Männlich")."`n");
output("`^Spezialgebiet: `@".$specialty[$row[specialty]]."`n");

$sql = "SELECT mountname FROM mounts WHERE mountid='{$row['hashorse']}'";
$result = db_query($sql);
$mount = db_fetch_assoc($result);
if ($mount['mountname']=="")
       $mount['mountname'] = "`iKeines`i";
output("`^Tier: `@{$mount['mountname']}`n");

if ($row['dragonkills']>0) output("`^Drachenkills: `@{$row['dragonkills']}`n");

output("`^Bester Angriff: `@$row[punch]`n");
output("<table border='0' cellspacing='0' cellpadding='0'><tr><td>`^Ansehen:&nbsp;</td><td>".grafbar(100,($row['reputation']+50),100,12)."</td></tr></table>",true);
if ($row[housekey]) output("`^Hausnummer: `@$row[housekey]`n");
if ($row[marriedto]){
	if ($row[marriedto]==4294967295){
		output("`^Verheiratet mit: `@".($row[sex]?"Seth":"Violet")."`n");
	}elseif ($row[charisma]==4294967295){
		$sql = "SELECT name FROM accounts WHERE acctid='{$row['marriedto']}'";
		$result = db_query($sql);
		$partner = db_fetch_assoc($result);
		output("`^Verheiratet mit: `@{$partner['name']}`n");
	}
}
if ($row['pvpflag']=="5013-10-06 00:42:00") output("`4`iSteht unter besonderem Schutz`i");
if (getsetting("avatare",0)==1)output ("</td></tr></table>",true);
if ($row['bio']>"")
	output("`n`^Bio: `@`n".soap($row['bio'])."`n");
output("`n`^Letzte Leistungen (und Niederlagen) von $row[name]`^");
$result = db_query("SELECT * FROM news WHERE accountid=$row[acctid] ORDER BY newsdate DESC,newsid ASC LIMIT 100");
$odate="";
for ($i=0;$i<db_num_rows($result);$i++){
	$row = db_fetch_assoc($result);
	if ($odate!=$row[newsdate]){
		output("`n`b`@".date("D, M d",strtotime($row[newsdate]))."`b`n");
		$odate=$row[newsdate];
	}
	output($row[newstext]."`n");
}

if ($_GET[ret]==""){
	addnav("Zur Liste der Krieger","list.php");
}else{
	$return = preg_replace("'[&?]c=[[:digit:]-]+'","",$_GET[ret]);
	$return = substr($return,strrpos($return,"/")+1);
	addnav("Zurück",$return);
}
page_footer();

?>
