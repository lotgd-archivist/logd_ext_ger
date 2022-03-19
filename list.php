<?php

// 15082004

require_once "common.php";
if ($session[user][loggedin]) {
	checkday();
	if ($session[user][alive]) {
		addnav("Zurück zum Dorf","village.php");
	} else {
		addnav("Zurück zu den Schatten", "shades.php");
	}
	addnav("Gerade Online","list.php");
}else{
	addnav("Login Seite","index.php");
	addnav("Gerade Online","list.php");
}
page_header("Kämpferliste");

$playersperpage=50;

$sql = "SELECT count(acctid) AS c FROM accounts WHERE locked=0";
$result = db_query($sql);
$row = db_fetch_assoc($result);
$totalplayers = $row['c'];

if ($_GET['op']=="search"){
	$search="%";
	for ($x=0;$x<strlen($_POST['name']);$x++){
		$search .= substr($_POST['name'],$x,1)."%";
	}
	$search=" AND name LIKE '".addslashes($search)."' ";
	//addnav("List Warriors","list.php");
}else{
	$pageoffset = (int)$_GET['page'];
	if ($pageoffset>0) $pageoffset--;
	$pageoffset*=$playersperpage;
	$from = $pageoffset+1;
	$to = min($pageoffset+$playersperpage,$totalplayers);
	
	$limit=" LIMIT $pageoffset,$playersperpage ";
}
addnav("Seiten");
for ($i=0;$i<$totalplayers;$i+=$playersperpage){
	addnav("Seite ".($i/$playersperpage+1)." (".($i+1)."-".min($i+$playersperpage,$totalplayers).")","list.php?page=".($i/$playersperpage+1));
}

// Order the list by level, dragonkills, name so that the ordering is total!
// Without this, some users would show up on multiple pages and some users
// wouldn't show up
if ($_GET['page']=="" && $_GET['op']==""){
	output("`c`bDiese Krieger sind gerade online`b`c");
	$sql = "SELECT acctid,name,login,alive,location,sex,level,laston,loggedin,lastip,uniqueid,race FROM accounts WHERE locked=0 AND loggedin=1 AND laston>'".date("Y-m-d H:i:s",strtotime(date("r")."-".getsetting("LOGINTIMEOUT",900)." seconds"))."' ORDER BY level DESC, dragonkills DESC, login ASC";
}else{
	output("`c`bKrieger in dieser Welt (Seite ".($pageoffset/$playersperpage+1).": $from-$to von $totalplayers)`b`c");
	$sql = "SELECT acctid,name,login,alive,location,sex,level,laston,loggedin,lastip,uniqueid,race FROM accounts WHERE locked=0 $search ORDER BY level DESC, dragonkills DESC, login ASC $limit";
}
if ($session[user][loggedin]){
	output("<form action='list.php?op=search' method='POST'>Nach Name suchen: <input name='name'><input type='submit' class='button' value='Suchen'></form>",true);
	addnav("","list.php?op=search");
}

$result = db_query($sql) or die(sql_error($sql));
$max = db_num_rows($result);
if ($max>100) {
	output("`\$Es treffen zu viele Namen auf diese Suche zu. Nur die ersten 100 werden angezeigt.`0`n");
}

output("<table border=0 cellpadding=2 cellspacing=1 bgcolor='#999999'>",true);
output("<tr class='trhead'><td><b>Level</b></td><td><b>Name</b></td><td><b>Rasse</b></td><td><b><img src=\"images/female.gif\">/<img src=\"images/male.gif\"></b></td><td><b>Ort</b></td><td><b>Status</b></td><td><b>Zuletzt da</b></tr>",true);
for($i=0;$i<$max;$i++){
	$row = db_fetch_assoc($result);
	output("<tr class='".($i%2?"trdark":"trlight")."'><td>",true);
	output("`^$row[level]`0");
	output("</td><td>",true);
	if ($session[user][loggedin]) output("<a href=\"mail.php?op=write&to=".rawurlencode($row['login'])."\" target=\"_blank\" onClick=\"".popup("mail.php?op=write&to=".rawurlencode($row['login'])."").";return false;\"><img src='images/newscroll.GIF' width='16' height='16' alt='Mail schreiben' border='0'></a>",true);
	if ($session[user][loggedin]) output("<a href='bio.php?char=".rawurlencode($row['login'])."'>",true);
	if ($session[user][loggedin]) addnav("","bio.php?char=".rawurlencode($row['login'])."");
	output("`".($row[acctid]==getsetting("hasegg",0)?"^":"&")."$row[name]`0");
	if ($session[user][loggedin]) output("</a>",true);
	output("</td><td>",true);
	output($colraces[$row['race']]);
	output("</td><td align=\"center\">",true);
	output($row[sex]?"<img src=\"images/female.gif\">":"<img src=\"images/male.gif\">",true);
	output("</td><td>",true);
	$loggedin=(date("U") - strtotime($row[laston]) < getsetting("LOGINTIMEOUT",900) && $row[loggedin]);
	if ($row[location]==0) output($loggedin?"`#Online`0":"`3Die Felder`0");
	if ($row[location]==1) output("`3Zimmer in Kneipe`0");
	if ($row[location]==2) output("`3Im Haus`0");
	output("</td><td>",true);
	output($row[alive]?"`1Lebt`0":"`4Tot`0");
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
page_footer();
?>
