<?php

// 09092004

require_once "common.php";

if ($session[loggedin]){
	redirect("badnav.php");
}
page_header();
output("`cWillkommen bei Legend of the Green Dragon, schamlos abgekupfert von Seth Able's Legend of the Red Dragon.`n");
if (getsetting('activategamedate','0')==1) output("`@Wir schreiben den `%".getgamedate()."`@.`0`n");
output("`@Die gegenwärtige Zeit im Dorf ist `%".getgametime()."`@.`0`n");

//Next New Day in ... is by JT from logd.dragoncat.net
$time = gametime();
// $tomorrow = strtotime(date("Y-m-d H:i:s",$time)." + 1 day");
$tomorrow = mktime(0,0,0,date('m',$time),date('d',$time)+1,date('Y',$time)); 
// $tomorrow = strtotime(date("Y-m-d 00:00:00",$tomorrow));
$secstotomorrow = $tomorrow-$time;
$realsecstotomorrow = round($secstotomorrow / (int)getsetting("daysperday",4));
output("`@Nächster neuer Tag in: `3".date("G \\S\\t\\u\\n\\d\\e\\n, i \\M\\i\\n\\u\\t\\e\\n, s \\S\\e\\k\\u\\n\\d\\e\\n\\ \\(\\E\\c\\h\\t\\z\\e\\i\\t\\)",strtotime("1980-01-01 00:00:00 + $realsecstotomorrow seconds"))."`0`n`n");

$newplayer=stripslashes(getsetting("newplayer",""));
if ($newplayer!="") output("`@Unser jüngster Spieler ist `^$newplayer`@!`0`n");
$newdk=stripslashes(getsetting("newdragonkill","")); 
if ($newdk!="") output("`@Der letzte Drachentöter war: `&$newdk`@!`0`n`n"); 

$result = db_fetch_assoc(db_query("SELECT COUNT(acctid) AS onlinecount FROM accounts WHERE locked=0 AND loggedin=1 AND laston>'".date("Y-m-d H:i:s",strtotime("-".getsetting("LOGINTIMEOUT",900)." seconds"))."'"));
$onlinecount = $result['onlinecount'];

// do not check if playerlimit is not reached!
if ($onlinecount >= getsetting("maxonline",10) && getsetting("maxonline",10)!=0) {
$id=$_COOKIE[lgi]; 
$sql = "SELECT superuser,uniqueid FROM accounts WHERE uniqueid='$id' AND superuser>0"; 
$result = db_query($sql) or die(db_error(LINK)); 
if (db_num_rows($result)>0) $is_superuser=1; 
else $is_superuser=0; 
}
else $is_superuser = 0;

if ($onlinecount<getsetting("maxonline",10) || getsetting("maxonline",10)==0 || $is_superuser){
output("Gib deinen Namen und dein Passwort ein, um diese Welt zu betreten.`n");
if ($_GET['op']=="timeout"){
	$session['message'].=" Deine Sessionzeit ist abgelaufen. Bitte neu einloggen.`n";
	if (!isset($_COOKIE['PHPSESSID'])){
		$session['message'].=" Es scheint, als ob die Cookies dieser Seite von deinem System blockiert werden.  Zumindest Sessioncookies müssen für diese Seite zugelassen werden.`n";
	}
}
if ($session[message]>"") output("`b`\$$session[message]`b`n");
output("<form action='login.php' method='POST'>"
.templatereplace("login",array("username"=>"<u>N</u>ame","password"=>"<u>P</u>asswort","button"=>"Einloggen"))
."</form>`c",true);
// Without this, I had one user constantly get 'badnav.php' :/  Everyone else worked, but he didn't
addnav("","login.php");
} else {
output("`^`bDer Server ist im Moment ausgelastet, die maximale Anzahl an Usern ist bereits online.`b`nBitte warte, bis wieder ein Platz frei ist.`n`n");
if ($_GET['op']=="timeout"){
	$session['message'].=" Deine Sessionzeit ist abgelaufen. Bitte neu einloggen.`n";
	if (!isset($_COOKIE['PHPSESSID'])){
		$session['message'].=" Es scheint, als ob die Cookies dieser Seite von deinem System blockiert werden.  Zumindest Sessioncookies müssen für diese Seite zugelassen werden.`n";
	}
}
if ($session[message]>"") output("`b`\$$session[message]`b`n");
output(templatereplace("full")."`c",true);
}


//output("`n`b`&**BETA**`0 This is a BETA of this website, things are likely to change now and again, as it is under active development (when I have time ;-)) `&**BETA**`0`n");
output("`n`b`&".getsetting("loginbanner","*BETA* This is a BETA of this website, things are likely to change now and again, as it is under active development *BETA*")."`0`b`n");
$session[message]="";
output("`c`2Version auf diesem Gameserver: `@{$logd_version}`0`c");

clearnav();
addnav("Neu hier?");
addnav("Über LoGD","about.php");
addnav("F.A.Q.","petition.php?op=faq",false,true);
addnav("Charakter erstellen","create.php");
addnav("Das Spiel");
addnav("Liste der Kämpfer","list.php");
addnav("Tägliche News", "news.php");
addnav("Spieleinstellungen", "about.php?op=setup");
addnav("Passwort vergessen?","create.php?op=forgot");
// addnav("DAS Spielforum","http://www.logd-welt.de/forum/index.php",false,false,true);
addnav("Die LoGD-Welt");
addnav("LoGD Netz","logdnet.php?op=list");
addnav("DragonPrime","http://www.dragonprime.net",false,false,true);

page_footer();
?>