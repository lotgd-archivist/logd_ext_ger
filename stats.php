<?
require_once "common.php";
isnewday(2);

page_header("Stats");
addnav("G?Zurück zur Grotte","superuser.php");
addnav("W?Zurück zum Weltlichen","village.php");
addnav("Aktualisieren","stats.php");

$sql = "SELECT sum(gentimecount) AS c, sum(gentime) AS t, sum(gensize) AS s, count(*) AS a FROM accounts";
$result = db_query($sql);
$row = db_fetch_assoc($result);
output("`b`%Für existierende Accounts:`b`n");
output("`@Accounts insgesamt: `^".number_format($row['a'])."`n");
output("`@Treffer insgesamt: `^".number_format($row['c'])."`n");
output("`@Seitengenerierungszeit insgesamt: `^".dhms($row['t'])."`n");
output("`@Seitengenerierungsgröße insgesamt: `^".number_format($row['s'])."b`n");
output("`@Durchschnittliche Seitengenerierungszeit: `^".dhms($row['t']/$row['c'],true)."`n");
output("`@Durchschnittliche Seitengröße: `^".number_format($row['s']/$row['c'])."`n");
output("`n`%`bTop Referers:`b`0`n");
output("<table border='0' cellpadding='2' cellspacing='1' bgcolor='#999999'>",true);
output("<tr class='trhead'><td><b>Name</b></td><td><b>Referrals</b></td></tr>",true);
$sql = "SELECT count(*) AS c, acct.acctid,acct.name AS referer FROM accounts INNER JOIN accounts AS acct ON acct.acctid = accounts.referer WHERE accounts.referer>0 GROUP BY accounts.referer DESC ORDER BY c DESC";
$result = db_query($sql);
for ($i=0;$i<db_num_rows($result);$i++){
	$row = db_fetch_assoc($result);
	output("<tr class='".($i%2?"trdark":"trlight")."'><td>",true);
	output("`@{$row['referer']}`0</td><td>`^{$row['c']}:`0  ", true);
	$sql = "SELECT name,refererawarded from accounts WHERE referer = ${row['acctid']} ORDER BY acctid ASC";
	$res2 = db_query($sql);
	for ($j = 0; $j < db_num_rows($res2); $j++) {
		$r = db_fetch_assoc($res2);
		output(($r['refererawarded']?"`&":"`$") . $r['name'] . "`0");
		if ($j != db_num_rows($res2)-1) output(",");
	}
	output("</td></tr>",true);
}
output("</table>",true);
$sql = "SELECT count(*) AS c, substring(laston,1,10) AS d FROM accounts GROUP BY d DESC ORDER BY d DESC";
$result = db_query($sql);
output("`n`%`bDatum des letzten Logins:`b");
$output.="<table border='0' cellpadding='0' cellspacing='5'>";
$class="trlight";
$odate=date("Y-m-d");
$j=0;
for ($i=0;$i<db_num_rows($result);$i++){
	$row = db_fetch_assoc($result);
	$diff = (strtotime($odate)-strtotime($row['d']))/86400;
	for ($x=1;$x<$diff;$x++){
		//if ($j%7==0) $class=($class=="trlight"?"trdark":"trlight");
		//$j++;
		$class=(date("W",strtotime("$odate -$x days"))%2?"trlight":"trdark");
		$output.="<tr class='$class'><td>".date("Y-m-d",strtotime("$odate -$x days"))."</td><td>0</td><td>$cumul</td></tr>";
	}
//	if ($j%7==0) $class=($class=="trlight"?"trdark":"trlight");
//	$j++;
	$class=(date("W",strtotime($row['d']))%2?"trlight":"trdark");
	$cumul+=$row['c'];
	$output.="<tr class='$class'><td>{$row['d']}</td><td><img src='images/trans.gif' width='{$row['c']}' border='1' height='5'>{$row['c']}</td><td>$cumul</td></tr>";
	$odate = $row['d'];
}
$output.="</table>";
page_footer();
?>
