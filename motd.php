<?
require_once "common.php";
addcommentary();
session_write_close();
popup_header("LoGD Message of the Day (MoTD)");
output(($session[user][superuser]>=3?" [<a href='motd.php?op=add'>MoTD erstellen</a>|<a href='motd.php?op=addpoll'>Umfrage erstellen</a>]`n":""),true);
function motditem($subject,$body){
	output("`b$subject`b`n",true);
	output("$body");
	output("<hr>",true);
}
function pollitem($id,$subject,$body){
	global $session;
	$sql = "SELECT count(resultid) AS c, MAX(choice) AS choice FROM pollresults WHERE motditem='$id' AND account='{$session['user']['acctid']}'";
	$result = db_query($sql);
	$row = db_fetch_assoc($result);
	$choice = $row['choice'];
	$body = unserialize($body);
	if ($row['c']==0 && 0){
		output("<form action='motd.php?op=vote' method='POST'>",true);
		output("<input type='hidden' name='motditem' value='$id'>",true);
		output("`bUjmfrage: $subject`b`n",true);
		output(stripslashes($body['body']));
		while (list($key,$val)=each($body['opt'])){
			if (trim($val)!=""){
				output("`n<input type='radio' name='choice' value='$key'>",true);
				output(stripslashes($val));
			}
		}
		output("`n<input type='submit' class='button' value='Abstimmen'>",true);
		output("</form>",true);
	}else{
		output("<form action='motd.php?op=vote' method='POST'>",true);
		output("<input type='hidden' name='motditem' value='$id'>",true);
		output("`bUmfrage: $subject`b`n",true);
		output(stripslashes($body['body']));
		$sql = "SELECT count(resultid) AS c, choice FROM pollresults WHERE motditem='$id' GROUP BY choice ORDER BY choice";
		$result = db_query($sql);
		$choices=array();
		$totalanswers=0;
		$maxitem = 0;
		for ($i=0;$i<db_num_rows($result);$i++){
			$row = db_fetch_assoc($result);
			$choices[$row['choice']]=$row['c'];
			$totalanswers+=$row['c'];
			if ($row['c']>$maxitem) $maxitem = $row['c'];
		}
		while (list($key,$val)=each($body['opt'])){
			if (trim($val)!=""){
				if ($totalanswers<=0) $totalanswers=1;
				$percent = round($choices[$key] / $totalanswers * 100,1);
				output("`n<input type='radio' name='choice' value='$key'".($choice==$key?" checked":"").">",true);
				output(stripslashes($val)." (".(int)$choices[$key]." - $percent%)");
				if ($maxitem==0){ $width=1; } else { $width = round(($choices[$key]/$maxitem) * 400,0); }
				$width = max($width,1);
				output("`n<img src='images/rule.gif' width='$width' height='2' alt='$percent'>",true);
				//output(stripslashes($val)."`n");
			}
		}
		output("`n<input type='submit' class='button' value='Abstimmen'></form>",true);
	}
	output("<hr>",true);
}
if ($_GET[op]=="vote"){
	$sql = "DELETE FROM pollresults WHERE motditem='{$_POST['motditem']}' AND account='{$session['user']['acctid']}'";
	db_query($sql);
	$sql = "INSERT INTO pollresults (choice,account,motditem) VALUES ('{$_POST['choice']}','{$session['user']['acctid']}','{$_POST['motditem']}')";
	db_query($sql);
	header("Location: motd.php");
	exit();
}
if ($_GET[op]=="addpoll"){
	if($session['user']['superuser']>=3){
		if ($_POST['subject']=="" || $_POST['body']==""){
			output("<form action='motd.php?op=addpoll' method='POST'>",true);
			addnav("","motd.php?op=add");
			output("<input type='text' size='50' name='subject' value=\"".HTMLEntities(stripslashes($_POST[subject]))."\">`n",true);
			output("<textarea class='input' name='body' cols='37' rows='5'>".HTMLEntities(stripslashes($_POST[body]))."</textarea>`n",true);
			output("Opt <input name='opt[]'>`n",true);
			output("Opt <input name='opt[]'>`n",true);
			output("Opt <input name='opt[]'>`n",true);
			output("Opt <input name='opt[]'>`n",true);
			output("Opt <input name='opt[]'>`n",true);
			output("Opt <input name='opt[]'>`n",true);
			output("<input type='submit' class='button' value='Hinzufügen'></form>",true);
		}else{
			$body = array("body"=>$_POST['body'],"opt"=>$_POST['opt']);
			$sql = "INSERT INTO motd (motdtitle,motdbody,motddate,motdtype) VALUES (\"$_POST[subject]\",\"".addslashes(serialize($body))."\",now(),1)";
			db_query($sql);
			header("Location: motd.php");
			exit();
		}
	}else{
		if ($session[user][loggedin]){
			//$session[user][hitpoints]=0;
			//$session[user][alive]=0;
			$session[user][experience]=round($session[user][experience]*0.9,0);
			addnews($session[user][name]." wurde für den Versuch, die Götter zu betrügen, bestraft.");
			output("Du hast versucht die Götter zu betrügen. Du wurdest mit Vergessen bestraft. Einiges von dem, was du einmal gewusst hast, weisst du nicht mehr.");
			saveuser();
		}	
	}
}
if ($_GET[op]=="add"){
	if ($session[user][superuser]>=3){
		if ($_POST[subject]=="" || $_POST[body]==""){
			output("<form action='motd.php?op=add' method='POST'>",true);
			addnav("","motd.php?op=add");
			output("<input type='text' size='50' name='subject' value=\"".HTMLEntities(stripslashes($_POST[subject]))."\">`n",true);
			output("<textarea class='input' name='body' cols='37' rows='5'>".HTMLEntities(stripslashes($_POST[body]))."</textarea>`n",true);
			output("<input type='submit' class='button' value='Hinzufügen'></form>",true);
		}else{
			$sql = "INSERT INTO motd (motdtitle,motdbody,motddate) VALUES (\"$_POST[subject]\",\"$_POST[body]\",now())";
			db_query($sql);
			header("Location: motd.php");
			exit();
		}
	}else{
		if ($session[user][loggedin]){
			//$session[user][hitpoints]=0;
			//$session[user][alive]=0;
			$session[user][experience]=round($session[user][experience]*0.9,0);
			addnews($session[user][name]." wurde für den Versuch, die Götter zu betrügen, bestraft.");
			output("Du hast versucht die Götter zu betrügen. Du wurdest mit Vergessen bestraft. Einiges von dem, was du einmal gewusst hast, weisst du nicht mehr.");
			saveuser();
		}
	}
}
if ($_GET[op]=="del"){
	if ($session[user][superuser]>=3){
			$sql = "DELETE FROM motd WHERE motditem=\"$_GET[id]\"";
			db_query($sql);
			header("Location: motd.php");
			exit();
	}else{
		if ($session[user][loggedin]){
			//$session[user][hitpoints]=0;
			//$session[user][alive]=0;
			$session[user][experience]=round($session[user][experience]*0.9,0);
			addnews($session[user][name]." wurde für den Versuch, die Götter zu betrügen, bestraft.");
			output("Du hast versucht die Götter zu betrügen. Du wurdest mit Vergessen bestraft. Einiges von dem, was du einmal gewusst hast, weisst du nicht mehr.");
			saveuser();
		}
	}
}


if ($_GET[op]==""){
	output("`&");
	motditem("Beta!","Bitte beachte die Hinweise ganz unten.");
	output("`%");

	$sql = "SELECT * FROM motd ORDER BY motddate DESC limit 20";
	$result = db_query($sql);
	for ($i=0;$i<db_num_rows($result);$i++){
		$row = db_fetch_assoc($result);
		if ($row['motddate']>$session['user']['lastmotd'] || $i<5){
			if ($row['motdtype']==0){
				motditem($row[motdtitle].($session[user][superuser]>=3?"[<a href='motd.php?op=del&id=$row[motditem]' onClick=\"return confirm('Bist du sicher, dass dieser Eintrag gelöscht werden soll?');\">Del</a>]":""),$row[motdbody]);
			}else{
				pollitem($row['motditem'],$row['motdtitle'].($session[user][superuser]>=3?"[<a href='motd.php?op=del&id=$row[motditem]' onClick=\"return confirm('Bist du sicher, dass dieser Eintrag gelöscht werden soll?');\">Del</a>]":""),$row[motdbody]);
			}
		}
	}
	output("`&");
	motditem("Beta!","Diese Seite ist im BETA Status! Ich bastel daran herum, wenn ich Zeit habe. Auch Änderungen von offizieller Seite (MightyE) werden hier übernommen. Das ist KEIN Freibrief zum Ausnutzen von Bugs, sondern alle Spieler (Teilnehmer am Beta-Test) sind verpflichtet, gefundene Fehler zu melden! Wünsche und Anregungen werden ebenfalls jederzeit gern angenommen. :-)");
	output("`@Kommentare und Fehler:`0`n");
	viewcommentary("motd");
}

$session[needtoviewmotd]=false;

	$sql = "SELECT motddate FROM motd ORDER BY motditem DESC LIMIT 1";
	$result = db_query($sql);
	$row = db_fetch_assoc($result);
	$session[user][lastmotd]=$row[motddate];

popup_footer();
?>
