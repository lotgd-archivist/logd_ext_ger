<?php

// 24062004

require_once "common.php";
page_header("Mericks St�lle");

$repaygold = round($playermount['mountcostgold']*2/3,0);
$repaygems = round($playermount['mountcostgems']*2/3,0);
$futtercost = $session[user][level]*20;

addnav("Zur�ck zum Dorf","village.php");
if ($session['user']['hashorse']>0 && $session[user][fedmount]==0) addnav("f?{$playermount['mountname']} f�ttern (`^$futtercost`0 Gold)","stables.php?op=futter");

if ($_GET[op]==""){
	checkday();
	output("`7Hinter der Kneipe, etwas links von Pegasus' R�stungen, befindet sich ein Stall, 
	wie man ihn in jedem Dorf erwartungsgem�� findet. 
	Darin k�mmert sich Merick, ein st�mmig wirkender Zwerg, um verschiedene Tiere.
	`n`n
	Du n�herst dich ihm, als er pl�tzlich herumwirbelt und seine Heugabel in deine ungef�hre Richtung streckt. \"`&Ach, 
	'tschuldigung min ".($session[user][sex]?"M�dl":"Jung").", heb dich nit kommen h�rn un heb gedenkt,
	du bischt sicha Cedrik, der ma widda sein Zwergenweitwurf ufbessern will. Naaahw, wat 
	kann ich f�r disch tun?`7\"");
}elseif($_GET['op']=="examine"){
	$sql = "SELECT * FROM mounts WHERE mountid='{$_GET['id']}'";
	$result = db_query($sql);
	if (db_num_rows($result)<=0){
		output("`7\"`&Ach, ich heb keen solches Tier da!`7\" ruft der Zwerg!");
	}else{
		output("`7\"`&Ai, ich heb wirklich n paar feine Viecher hier!`7\" kommentiert der Zwerg.`n`n");
		$mount = db_fetch_assoc($result);
		output("`7Kreatur: `&{$mount['mountname']}`n");
		output("`7Beschreibung: `&{$mount['mountdesc']}`n");
		output("`7Preis: `^{$mount['mountcostgold']}`& Gold, `%{$mount['mountcostgems']}`& Edelstein".($mount['mountcostgems']==1?"":"e")."`n");
		output("`n");
		addnav("Dieses Tier kaufen","stables.php?op=buymount&id={$mount['mountid']}");
	}
}elseif($_GET['op']=='buymount'){
	$sql = "SELECT * FROM mounts WHERE mountid='{$_GET['id']}'";
	$result = db_query($sql);
	if (db_num_rows($result)<=0){
		output("`7\"`&Ach, ich heb keen solches Tier da!`7\" ruft der Zwerg!");
	}else{
		$mount = db_fetch_assoc($result);
		if ( 
			($session['user']['gold']+$repaygold) < $mount['mountcostgold']
			 || 
			($session['user']['gems']+$repaygems) < $mount['mountcostgems']
		){
			output("`7Merick schaut dich schief von der Seite an. \"`&�hm, was gl�ubst du was du hier machst? Kanns u nich sehen, dass {$mount['mountname']} `^{$mount['mountcostgold']}`& Gold und `%{$mount['mountcostgems']}`& Edelsteine kostet?`7\"");
		}else{
			if ($session['user']['hashorse']>0){
				output("`7Du �bergibst dein(e/n) {$playermount['mountname']} und bezahlst den Preis f�r dein neues Tier. Merick f�hrt ein(e/n) sch�ne(n/s) neue(n/s) `&{$mount['mountname']}`7  f�r dich heraus!`n`n");
				$session[user][reputation]--;
			}else{
				output("`7Du bezahlst den Preis f�r dein neues Tier und Merick f�hrt ein(e/n) sch�ne(n/s) neue(n/s) `&{$mount['mountname']}`7 f�r dich heraus!`n`n");
		    }
			$session['user']['hashorse']=$mount['mountid'];
			$goldcost = $repaygold-$mount['mountcostgold'];
			$session['user']['gold']+=$goldcost;
			$gemcost = $repaygems-$mount['mountcostgems'];
			$session['user']['gems']+=$gemcost;
			debuglog(($goldcost <= 0?"spent ":"gained ") . abs($goldcost) . " gold and " . ($gemcost <= 0?"spent ":"gained ") . abs($gemcost) . " gems trading for a new mount");
			$session['bufflist']['mount']=unserialize($mount['mountbuff']);
			// Recalculate so the selling stuff works right
			$playermount = getmount($mount['mountid']);
			$repaygold = round($playermount['mountcostgold']*2/3,0);
			$repaygems = round($playermount['mountcostgems']*2/3,0);
		}
	}
}elseif($_GET['op']=='sellmount'){
	$session['user']['gold']+=$repaygold;
	$session['user']['gems']+=$repaygems;
	debuglog("gained $repaygold gold and $repaygems gems selling their mount");
	unset($session['bufflist']['mount']);
	$session['user']['hashorse']=0;
	output("`7So schwer es dir auch f�llt, dich von dein(er/em) {$playermount['mountname']} zu trennen, tust du es doch und eine einsame Tr�ne entkommt deinen Augen.`n`n");
	output("Aber in dem Moment, in dem du die ".($repaygold>0?"`^$repaygold`7 Gold ".($repaygems>0?" und ":""):"").($repaygems>0?"`%$repaygems`7 Edelsteine":"")." erblickst, f�hlst du dich gleich ein wenig besser.");
	$session[user][reputation]-=2;
}elseif($_GET['op']=='futter'){
	if ($session[user][gold]>=$futtercost) {
        		$buff = unserialize($playermount['mountbuff']);
        		if ($session['bufflist']['mount']['rounds'] == $buff['rounds']) {
			output("Dein {$playermount['mountname']} ist satt und r�hrt das vorgesetzte Futter nicht an. Darum gibt Merick dir dein Gold zur�ck.");
		}else if ($session['bufflist']['mount']['rounds'] > $buff['rounds']*.5) {
			$futtercost=$futtercost/2;
			output("Dein {$playermount['mountname']} nascht etwas von dem vorgesetzten Futter und l�sst den Rest stehen. {$playermount['mountname']} ist voll regeneriert. ");
			output("Da aber noch �ber die H�lfte des Futters �brig ist, gibt dir Merick 50% Preisnachlass.`nDu bezahlst nur $futtercost Gold.");
			$session[user][gold]-=$futtercost;
			$session[user][reputation]--;
		}else{
			$session[user][gold]-=$futtercost;
			output("Dein {$playermount['mountname']} macht sich gierig �ber das Futter her und frisst es bis auf den letzten Kr�mel.`n");
			output("Dein {$playermount['mountname']} ist vollst�ndig regeneriert und du gibst Merick die $futtercost Gold."); 
			$session[user][reputation]--;
		}
       		$session['bufflist']['mount']=$buff;
		$session[user][fedmount]=1;
	} else {
		output("`7Du hast nicht genug Gold dabei, um das Futter zu bezahlen. Merick weigert sich dein Tier f�r dich durchzuf�ttern und empfiehlt dir, im Wald nach einer grasbewachsenen Lichtung zu suchen.");
	}
}

$sql = "SELECT mountname,mountid,mountcategory FROM mounts WHERE mountactive=1 ORDER BY mountcategory,mountcostgems,mountcostgold";
$result = db_query($sql);
$category="";
for ($i=0;$i<db_num_rows($result);$i++){
	$row = db_fetch_assoc($result);
	if ($category!=$row['mountcategory']){
		addnav($row['mountcategory']);
		$category = $row['mountcategory'];
	}
	addnav("Betrachte {$row['mountname']}`0","stables.php?op=examine&id={$row['mountid']}");
}
if ($session['user']['hashorse']>0){
	output("`n`nMerick bietet dir `^$repaygold`& Gold und `%$repaygems`& Edelsteine f�r dein(e/n) {$playermount['mountname']}.");
	addnav("Sonstiges");
	addnav("Verkaufe {$playermount['mountname']}","stables.php?op=sellmount");
}

page_footer();
?>
