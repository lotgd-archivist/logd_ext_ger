<?
require_once "common.php";
header("Content-Type: text/xml");

switch($_GET[op]){
case "newssince":
	$since=($_GET[since]==""?"1=1 ORDER BY newsid DESC limit 1 ":" newsid > '$_GET[since]'");
	$sql = "SELECT * FROM news WHERE $since";
	$result = db_query($sql);
	$output.="<recentnews>";
	for ($i=0;$i<db_num_rows($result);$i++){
		$row = db_fetch_assoc($result);
		$output.="<news id=\"$row[newsid]\">".HTMLEntities(preg_replace("'[`].'","",$row[newstext]))."</news>";
	}
	$output.="</recentnews>";
	echo $output;
	break;
case "online":
	$sql = "SELECT name,alive,location,sex,level,laston,loggedin,lastip,uniqueid FROM accounts WHERE locked=0 AND loggedin=1 ORDER BY level DESC";
	$output="<onlineusers>";
	$result = db_query($sql) or die(sql_error($sql));
	for ($i=0;$i<db_num_rows($result);$i++){
		$row = db_fetch_assoc($result);
		$loggedin=(date("U") - strtotime($row[laston]) < getsetting("LOGINTIMEOUT",900) && $row[loggedin]);
		if ($loggedin) {
			$output.="<user name=\"".preg_replace("'[`].'","",$row[name])."\" level=\"$row[level]\" sex=\"$row[sex]\"/>";
			$onlinecount++;
		}
	}
	$output.="</onlineusers>";
	echo $output;
	break;
}
?>