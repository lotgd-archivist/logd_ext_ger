<?php

// 27062004

require_once "common.php";

isnewday(2);

if ($_GET['op']=="block"){
    $sql = "UPDATE accounts SET avatar='' WHERE acctid=$_GET[userid]";
	systemmail($_GET['userid'],"Dein Avatar wurde entfernt","Der Administrator hat beschlossen, dass dein Avatar unangebracht ist, oder nicht funktionierte, und hat ihn entfernt.`n`nWenn du darüber diskutieren willst, benutze bitte den Link zur Hilfeanfrage.");
	db_query($sql);
}
$ppp=25; // Player Per Page to display
if (!$_GET[limit]){
	$page=0;
}else{
	$page=(int)$_GET[limit];
	addnav("Vorherige Seite","avatars.php?limit=".($page-1)."");
}
$limit="".($page*$ppp).",".($ppp+1);
$sql = "SELECT name,acctid,avatar FROM accounts WHERE avatar>'' ORDER BY acctid DESC LIMIT $limit";
$result = db_query($sql);
if (db_num_rows($result)>$ppp) addnav("Nächste Seite","avatars.php?limit=".($page+1)."");
page_header("Spieleravatare");
output("`b`&Spieler Avatare - Seite $page:`0`b`n");
for ($i=0;$i<db_num_rows($result);$i++){
    $row = db_fetch_assoc($result);
    output("`![<a href='avatars.php?op=block&userid={$row['acctid']}'>Entfernen</a>]",true);
    addnav("","avatars.php?op=block&userid={$row['acctid']}");
    output("`&{$row['name']}: `^");
	$pic_size = @getimagesize($row[avatar]);
	$pic_width = $pic_size[0];
	$pic_height = $pic_size[1];
	output("<img src=\"$row[avatar]\" ",true);
	if ($pic_width > 200) output("width=\"200\" ",true );
	if ($pic_height > 200) output("height=\"200\" ",true );
	output("alt=\"$row[name]\">&nbsp;`n`n",true);

}
db_free_result($result);
addnav("G?Zurück zur Grotte","superuser.php");
addnav("W?Zurück zum Weltlichen","village.php");
addnav("Aktualisieren","avatars.php");
page_footer();
?>
