<?php

// 15082004

require_once "common.php";
isnewday(3);

if ($_GET[op]=="save"){
	if ($_POST[blockdupeemail]==1) $_POST[requirevalidemail]=1;
	if ($_POST[requirevalidemail]==1) $_POST[requireemail]=1;
	reset($_POST);
	while (list($key,$val)=each($_POST)){
		savesetting($key,stripslashes($val));
		output("Setze $key auf ".stripslashes($val)."`n");
	}
}

page_header("Spieleinstellungen");
addnav("G?Zurück zur Grotte","superuser.php");
addnav("W?Zurück zum Weltlichen","village.php");
addnav("",$REQUEST_URI);
//$nextnewday = ((gametime()%86400))/4 ; //abs(((86400- gametime())/getsetting("daysperday",4))%86400 );
//echo date("h:i:s a",strtotime("-$nextnewday seconds"))." (".($nextnewday/60)." minutes) ".date("h:i:s a",gametime()).gametime();
$time = (strtotime(date("1981-m-d H:i:s",strtotime(date("r")."-".getsetting("gameoffsetseconds",0)." seconds"))))*getsetting("daysperday",4) % strtotime("1981-01-01 00:00:00"); 
$time = gametime();
/*
$tomorrow = strtotime(date("Y-m-d H:i:s",$time)." + 1 day");
$tomorrow = strtotime(date("Y-m-d 00:00:00",$tomorrow));
$today = strtotime(date("Y-m-d 00:00:00",$time));
$dayduration = ($tomorrow-$today) / getsetting("daysperday",4);
$secstotomorrow = $tomorrow-$time;
$secssofartoday = $time - $today;
$realsecstotomorrow = $secstotomorrow / getsetting("daysperday",4);
$realsecssofartoday = $secssofartoday / getsetting("daysperday",4);
*/
$tomorrow = mktime(0,0,0,date('m',$time),date('d',$time)+1,date('Y',$time)); 
$today = mktime(0,0,0,date('m',$time),date('d',$time),date('Y',$time)); 
$dayduration = ($tomorrow-$today) / getsetting("daysperday",4); 
$secstotomorrow = $tomorrow-$time; 
$secssofartoday = $time - $today; 
$realsecstotomorrow = round($secstotomorrow / getsetting("daysperday",4),0); 
$realsecssofartoday = round($secssofartoday / getsetting("daysperday",4),0); 
$enum="enum";
for ($i=0;$i<=86400;$i+=900){
	$enum.=",$i,".((int)($i/60/60)).":".($i/60 %60)."";
}
$setup = array(
	"Spieleinstellungen,title",
	"loginbanner"=>"Login Banner (unterhalb der Login-Aufforderung; 255 Zeichen)",
	"impressum"=>"Server betrieben von: (255 Zeichen)",
	"soap"=>"Userbeiträge säubern (filtert Gossensprache und trennt Wörter mit über 45 Zeichen),bool",
	"maxonline"=>"Maximal gleichzeitig online (0 für unbegrenzt),int",
	"maxcolors"=>"Maximale # erlaubter Farbwechsel in Userkommentaren,int",
	"gameadminemail"=>"Admin Email",
	"paypalemail"=>"E-Mail Adresse für den PayPal Account des Admins",
	"defaultlanguage"=>"Voreingestellte Sprache (z. Zt nur de),enum,en,English,dk,Danish,de,Deutsch,es,Espanol,fr,French",
	"forum"=>"Link (URL) zum Forum",
	"automaster"=>"Meister jagt säumige Lehrlinge,bool",
	"multimaster"=>"Meister kann mehrmals pro Tag herausgefordert werden?,bool",
	"topwebid"=>"ID für Top Web Games (wenn du dort registriert bist),int",
	"beta"=>"Beta-Features für alle Spieler aktivieren?,bool",
	"paidales"=>"Ale das als 'Runde' spendiert wurde (Wert-1),int",
	"maxales"=>"Maximale Anzahl Ale die bei einer 'Runde' spendiert werden kann,int", 
	"limithp"=>"Lebenpunkte maximal Level*12+5*DPinHP+x*DK (0=deaktiviert),int",
	"autofight"=>"Automatische Kampfrunden ermöglichen,bool",
	"witchvisits"=>"Erlaubte Besuche bei der Hexe,int",
	"dailyspecial"=>"Heutiges besonderes Ereignis",

	"Account Erstellung,title",
	"superuser"=>"Default superuser level,enum,0,Standard play days per calendar day,1,Unlimited play days per calendar day,2,Admin creatures and taunts,3,Admin users",
	"newplayerstartgold"=>"Gold mit dem ein neuer Char startet,int",
	"requireemail"=>"E-Mail Adresse beim Anmelden verlangen,bool",
	"requirevalidemail"=>"E-Mail Adresse bestätigen lassen,bool",
	"blockdupeemail"=>"Nur ein Account pro E-Mail Adresse,bool",
	"spaceinname"=>"Erlaube Leerzeichen in Benutzernamen,bool",
	"selfdelete"=>"Erlaube den Spielern ihren Charakter zu löschen,bool",
	"avatare"=>"Erlaube den Spielern Avatare zu verlinken,bool",
	
	"Neue Tage,title",
	"fightsforinterest"=>"Höchste Anzahl an übrigen Waldkämpfen um Zinsen zu bekommen,int",
	"maxinterest"=>"Maximaler Zinssatz (%),int",
	"mininterest"=>"Minimaler Zinssatz (%),int",
	"daysperday"=>"Spieltage pro Kalendertag,int",
	"dispnextday"=>"Zeit zum nächsten Tag in Vital Info,bool",
	"specialtybonus"=>"Zusätzliche Einsätze der Spezialfertigkeit am Tag,int",
	"activategamedate"=>"Spieldatum aktiv,bool",	
	"gamedateformat"=>"Datumsformat (zusammengesetzt aus: %Y; %y; %m; %n; %d; %j)",
	"gametimeformat"=>"Zeitformat",
	
	"Wald,title",
	"turns"=>"Waldkämpfe pro Tag,int",
	"dropmingold"=>"Waldkreaturen lassen mindestens 1/4 des möglichen Goldes fallen,bool",
	"lowslumlevel"=>"Mindestlevel bei dem perfekte Kämpfe eine Extrarunde geben,int",

	"Kopfgeld,title",
	"bountymin"=>"Mindestbetrag pro Level der Zielperson,int",
	"bountymax"=>"Maximalbetrag pro Level der Zielperson,int",
	"bountylevel"=>"Mindestlevel um Opfer sein zu können,int",
	"bountyfee"=>"Gebühr für Dag Durnick in Prozent,int",
	"maxbounties"=>"Anzahl an Kopfgeldern die ein Spieler pro Tag aussetzen darf,int",
	
	"Handelseinstellungen,title",	
	"borrowperlevel"=>"Maximalwert den ein Spieler pro Level leihen kann (Bank),int",
	"maxinbank"=>"+/- Maximalbetrag für den noch Zinsen bezahlt/verlangt werden,int",
	"allowgoldtransfer"=>"Erlaube Überweisungen (Gold und Edelsteine),bool",
	"transferperlevel"=>"Maximalwert den ein Spieler pro Level empfangen oder nehmen kann,int",
	"transferreceive"=>"Anzahl an Überweisungen die ein Spieler pro Tag empfangen kann,int",
	"mintransferlev"=>"Mindestlevel für Überweisungen (bei 0 DKs),int",
	"maxtransferout"=>"Menge die ein Spieler an andere überweisen kann (Wert x Level),int",
	"innfee"=>"Gebühr für Expressbezahlung in der Kneipe (x oder x%),int",
	"selledgems"=>"Edelsteine die Vessa vorrätig hat,int",
	"vendor"=>"Händler heute in der Stadt?,bool",
	"paidgold"=>"Gold das in Bettlergasse spendiert wurde,int",
	"cakevip"=>"Login-Name des Spielers der mit Torte beworfen werden kann", 

	"Mail Einstellungen,title",
	"mailsizelimit"=>"Maximale Anzahl an Zeichen in einer Nachricht,int",
	"inboxlimit"=>"Anzahl an Nachrichten in der Inbox,int",
	"oldmail"=>"Alte Nachrichten automatisch löschen nach x Tagen. x =,int",
	
	"PvP,title",
	"pvp"=>"Spieler gegen Spieler aktivieren,bool",
	"pvpday"=>"Spielerkämpfe pro Tag,int",
	"hasegg"=>"Aktueller Besitzer des goldene Eis (Account-ID - 0=Niemand),int",
	"pvpimmunity"=>"Tage die neue Spieler vor PvP sicher sind,int",
	"pvpminexp"=>"Mindest-Erfahrungspunkte für PvP-Opfer,int",
	"pvpattgain"=>"Prozentsatz der Erfahrung des Opfers den der Angreifer bei Sieg bekommt,int",
	"pvpattlose"=>"Prozentsatz an Erfahrung den der Angreifer bei Niederlage verliert,int",
	"pvpdefgain"=>"Prozentsatz an Erfahrung des Angreifers den der Verteiger bei einem Sieg gewinnt,int",
	"pvpdeflose"=>"Prozentsatz an Erfahrung den der Verteidiger bei Niederlage verliert,int",
	"pvpmindkxploss"=>"DKs Unterschied zwischen Täter und Opfer bis zu dem noch 0% XP abgezogen werden,int",

	"Inhalte löschen (0 für nie löschen),title",
	"expirecontent"=>"Tage die Kommentare und News aufgehoben werden,int",
	"expiretrashacct"=>"Tage die Accounts gespeichert werden die nie eingeloggt waren,int",
	"expirenewacct"=>"Tage die Level 1 Accounts ohne Drachenkill aufgehoben werden,int",
	"expireoldacct"=>"Tage die alle anderen Accounts aufgehoben werden,int",
	"LOGINTIMEOUT"=>"Sekunden Inaktivität bis zum automatischen Logout,int",
	
	"Nützliche Informationen,title",
	"weather"=>"Heutiges Wetter:,viewonly",
	"newplayer"=>"Neuster Spieler",
	"Letzter neuer Tag: ".date("h:i:s a",strtotime(date("r")."-$realsecssofartoday seconds")).",viewonly",
	"Nächster neuer Tag: ".date("h:i:s a",strtotime(date("r")."+$realsecstotomorrow seconds")).",viewonly",
	"Aktuelle Spielzeit: ".getgametime().",viewonly",
	"Tageslänge: ".($dayduration/60/60)." Stunden,viewonly",
	"Aktuelle Serveruhrzeit: ".date("Y-m-d h:i:s a").",viewonly",
	"gameoffsetseconds"=>"Offset der Spieltage,$enum", 
	"gamedate"=>"aktuelles Spieldatum (Y-m-d)",
	
	"LoGD-Netz Einstellungen (file wrappers müssen in der PHP Konfiguration aktiviert sein!!),title",
	"logdnet"=>"Beim LoGD-Netz eintragen?,bool",
	"serverurl"=>"Server URL",
	"serverdesc"=>"Serverbeschreibung (255 Zeichen)",
	"logdnetserver"=>"LoGD-Netz Zentralserver (Default: http://lotgd.net)",
	
	"Spieleinstellungen Ende,title"
	);
	
if ($_GET[op]==""){
	loadsettings();
	output("<form action='configuration.php?op=save' method='POST'>",true);
	addnav("","configuration.php?op=save");
	showform($setup,$settings);
	output("</form>",true);
}
page_footer();
?>
