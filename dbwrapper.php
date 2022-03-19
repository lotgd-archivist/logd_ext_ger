<?php

// 21072004

//do some cleanup here to make sure magic_quotes_gpc is ON, and magic_quotes_runtime is OFF, and error reporting is all but notice.
error_reporting (E_ALL ^ E_NOTICE);
if (!get_magic_quotes_gpc()){
	set_magic_quotes($_GET);
	set_magic_quotes($_POST);
	set_magic_quotes($_SESSION);
	set_magic_quotes($_COOKIE);
	set_magic_quotes($HTTP_GET_VARS);
	set_magic_quotes($HTTP_POST_VARS);
	set_magic_quotes($HTTP_COOKIE_VARS);
	ini_set("magic_quotes_gpc",1);
}
set_magic_quotes_runtime(0);

function set_magic_quotes(&$vars) {
	//eval("\$vars_val =& \$GLOBALS[$vars]$suffix;");
	if (is_array($vars)) {
		reset($vars);
		while (list($key,$val) = each($vars))
			set_magic_quotes($vars[$key]);
	}else{
		$vars = addslashes($vars);
		//eval("\$GLOBALS$suffix = \$vars_val;");
	}
}

define('DBTYPE',"mysql");

$dbqueriesthishit=0;
$dbtimethishit = 0;

function db_query($sql){
global $session,$dbqueriesthishit,$dbtimethishit;
	$dbqueriesthishit++;
$dbtimethishit -= getmicrotime();
	$fname = DBTYPE."_query";
	$r = $fname($sql) or die(($session[user][superuser]>=3 || 1?"<pre>".HTMLEntities($sql)."</pre>":"").db_error(LINK));
$dbtimethishit += getmicrotime();
	//$x = strpos($sql,"WHERE");
	//if ($x!==false) {
	//	$where = substr($sql,$x+6);
	//	$x = strpos($where,"ORDER BY");
	//	if ($x!==false) $where = substr($where,0,$x);
	//	$x = strpos($where,"LIMIT");
	//	if ($x!==false) $where = substr($where,0,$x);
	//	$where = preg_replace("/'[^']*'/","",$where);
	//	$where = preg_replace('/"[^"]*"/',"",$where);
	//	$where = preg_replace("/[^a-zA-Z ]/","",$where);
	//	mysql_query("INSERT DELAYED INTO queryanalysis VALUES (0,\"".addslashes($where)."\",0)");
	//}
	return $r;
}

function db_insert_id($link=false) {
global $dbtimethishit;
$dbtimethishit -= getmicrotime();
	$fname = DBTYPE."_insert_id";	
	if ($link===false) {
		$r = $fname();
	}else{
		$r = $fname($link);
	}
	$dbtimethishit += getmicrotime();
	return $r;
}

function db_error($link){
	$fname = DBTYPE."_error";
	$r = $fname($link);
	return $r;
}

function db_fetch_assoc($result){
	global $dbtimethishit;
	$dbtimethishit -= getmicrotime();
	$fname = DBTYPE."_fetch_assoc";
	$r = $fname($result);
	$dbtimethishit += getmicrotime();
	return $r;
}

function db_num_rows($result){
	global $dbtimethishit;
	$dbtimethishit -= getmicrotime();
	$fname = DBTYPE."_num_rows";
	$r = $fname($result);
	$dbtimethishit += getmicrotime();
	return $r;
}

function db_affected_rows($link=false){
	global $dbtimethishit;
	$dbtimethishit -= getmicrotime();
	$fname = DBTYPE."_affected_rows";
	if ($link===false) {
		$r = $fname();
	}else{
		$r = $fname($link);
	}
	$dbtimethishit += getmicrotime();
	return $r;
}

function db_pconnect($host,$user,$pass){
	global $dbtimethishit;
	$dbtimethishit -= getmicrotime();
	$fname = DBTYPE."_connect";
	$r = $fname($host,$user,$pass);
	$dbtimethishit += getmicrotime();
	return $r;
}

function db_select_db($dbname){
	global $dbtimethishit;
	$dbtimethishit -= getmicrotime();
	$fname = DBTYPE."_select_db";
	$r = $fname($dbname);
	$dbtimethishit += getmicrotime();
	return $r;
}
function db_free_result($result){
	global $dbtimethishit;
	$dbtimethishit -= getmicrotime();
	$fname = DBTYPE."_free_result";
	$r = $fname($result);
	$dbtimethishit += getmicrotime();
	return $r;
}
?>
