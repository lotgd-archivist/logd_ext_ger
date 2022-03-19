<?php

// 15082004

if (isset($_POST['template'])){
	setcookie("template",$_POST['template'],strtotime(date("r")."+45 days"));
	$_COOKIE['template']=$_POST['template'];
}
require_once "common.php";
page_header("Einstellungen & Profil");

if ($HTTP_GET_VARS[op]=="suicide" && getsetting("selfdelete",0)!=0) {
	if($session[user][acctid]==getsetting("hasegg",0)) savesetting("hasegg",stripslashes(0));

	// inventar und haus löschen und partner freigeben
	if ((int)$HTTP_GET[userid]==(int)getsetting("hasegg",0)) savesetting("hasegg",stripslashes(0));
    	$sql = "UPDATE items SET owner=0 WHERE owner=$HTTP_GET_VARS[userid]";
	db_query($sql);
	$sql = "UPDATE houses SET owner=0,status=3 WHERE owner=$HTTP_GET_VARS[userid] AND status=1";
	db_query($sql);
	$sql = "UPDATE houses SET owner=0,status=4 WHERE owner=$HTTP_GET_VARS[userid] AND status=0";
	db_query($sql);
	$sql = "UPDATE accounts SET charisma=0,marriedto=0 WHERE marriedto=$HTTP_GET_VARS[userid]";
	db_query($sql);
	$sql = "DELETE FROM pvp WHERE acctid2=$HTTP_GET_VARS[userid] OR acctid1=$HTTP_GET_VARS[userid]";
	db_query($sql) or die(db_error(LINK));
	// user löschen
	$sql = "DELETE FROM accounts WHERE acctid='$HTTP_GET_VARS[userid]'";
	db_query($sql);
	output("Dein Charakter, sein Inventar und alle seine Kommentare wurden gelöscht!");
	addnews("`#{$session['user']['name']} beging Selbstmord.");
	addnav("Login Seite", "index.php");
	$session=array();
	$session[user] = array();
	$session[loggedin] = false;
	$session[user][loggedin] = false;

}else if ($HTTP_GET_VARS[op]=="inventory") {
	$back=$_GET[back];
	if ($back=="") $back="village.php";
	if ($_GET[sorti]=="") $_GET[sorti]="class ASC, name ASC, id";
	output("`c`bDie Besitztümer von ".$session[user][name]."`b`c`n`n");
	output("<table cellspacing='1' cellpadding='2' align='center'><tr><td>`b<a href='prefs.php?op=inventory&sorti=name&back=$back&limit=$_GET[limit]'>Itemname</a>`b</td><td>`b<a href='prefs.php?op=inventory&sorti=class&back=$back&limit=$_GET[limit]'>Klasse</a>`b</td><td>`bWert 1`b</td><td>`bWert 2`b</td><td>`b<a href='prefs.php?op=inventory&sorti=gems&back=$back&limit=$_GET[limit]'>Verkaufswert</a>`b</td><td>`bAktion`b</td></tr>",true);
	addnav("","prefs.php?op=inventory&sorti=name&back=$back&limit=$_GET[limit]");
	addnav("","prefs.php?op=inventory&sorti=class&back=$back&limit=$_GET[limit]");
	addnav("","prefs.php?op=inventory&sorti=gems&back=$back&limit=$_GET[limit]");
	$ppp=25; // Player Per Page to display
	if (!$_GET[limit]){
		$page=0;
	}else{
		$page=(int)$_GET[limit];
		addnav("Vorherige Seite","prefs.php?op=inventory&limit=".($page-1)."&back=$back"."&sorti=$_GET[sorti]");
	}
	$limit="".($page*$ppp).",".($ppp+1);

	$sql = "SELECT * FROM items WHERE owner=".$session[user][acctid]." ORDER BY $_GET[sorti] ASC LIMIT $limit";
	$result = db_query($sql) or die(db_error(LINK));
	if (db_num_rows($result)>$ppp) addnav("Nächste Seite","prefs.php?op=inventory&limit=".($page+1)."&sorti=$_GET[sorti]"."&back=$back");
	if (db_num_rows($result)==0){
  		output("<tr><td colspan=5 align='center'>`&`iDu hast nichts im Inventar`i`0</td></tr>",true);
	}else{
		for ($i=0;$i<db_num_rows($result);$i++){
			$item = db_fetch_assoc($result);
			$bgcolor=($i%2==1?"trlight":"trdark");
			output("<tr class='$bgcolor'><td>`&$item[name]`0</td><td>`!$item[class]`0</td><td align='right'>$item[value1]</td><td align='right'>$item[value2]</td><td>",true);
			if ($item[gold]==0 && $item[gems]==0){
				output("`4Unverkäuflich`0");
			}else{
				output("`^$item[gold]`0 Gold, `#$item[gems]`0 Edelsteine");
			}
			output("</td><td>[",true);
			if ($item['class']=="Waffe" || $item['class']=="Rüstung"){
				output("<a href='invhandler.php?op=fit&id=$item[id]&back=$back'>Ausrüsten</a>",true);
				addnav("","invhandler.php?op=fit&id=$item[id]&back=$back");
			}else if ($item['class']=="Geschenk"){
				output("<a href='invhandler.php?op=throw&id=$item[id]&back=$back'>Wegwerfen</a>",true);
				addnav("","invhandler.php?op=throw&id=$item[id]&back=$back");
				if ($session[user][housekey]>0 && $session[user][house]==$session[user][housekey]){
					output(" | <a href='invhandler.php?op=house&id=$item[id]&back=$back'>Einlagern</a>",true);
					addnav("","invhandler.php?op=house&id=$item[id]&back=$back");	
				}
			}else{
				output(" - ");
			}
			output("]</td></tr><tr class='$bgcolor'><td align='right'>Beschreibung:</td><td colspan=5>$item[description]</td></tr>",true);
		}
	}
	if (getsetting("hasegg",0)==$session[user][acctid]){
			$bgcolor=($i%2==1?"trdark":"trlight");
			output("<tr class='$bgcolor'><td>`^Das goldene Ei`0</td><td></td><td></td><td></td><td>`4Unverkäuflich`0</td><td></td></tr>",true);
	}
	output("</table>",true);
	addnav("Zurück","$back");

} else {

checkday();
if ($session[user][alive]){
	addnav("Zurück zum Dorf","village.php");
}else{
	addnav("Zurück zu den News","news.php");
}
if (count($_POST)==0){
}else{
	if ($_POST[pass1]!=$_POST[pass2]){
		output("`#Deine Passwörter stimmen nicht überein.`n");
	}else{
		if ($_POST[pass1]!=""){
			if (strlen($_POST[pass1])>3){
				$session[user][password]=md5($_POST[pass1]);
				output("`#Dein Passwort wurde geändert.`n");
			}else{
				output("`#Dein Passwort ist zu kurz. Es muss mindestens 4 Zeichen lang sein.`n");
			}
		}
	}
	reset($_POST);
	$nonsettings = array("pass1"=>1,"pass2"=>1,"email"=>1,"template"=>1,"bio"=>1,"avatar"=>1);
	while (list($key,$val)=each($_POST)){
		if (!$nonsettings[$key]) $session['user']['prefs'][$key]=$_POST[$key];
	}
	if (closetags(stripslashes($_POST['bio']),'`i`b`c`H')!=$session['user']['bio']){
		if ($session['user']['biotime']>"9000-01-01"){
			output("`n`\$Du kannst deine Beschreibung nicht ändern. Der Admin hat diese Funktion blockiert!`0`n");
		}else{
			$session['user']['bio']=closetags(stripslashes($_POST['bio']),'`i`b`c`H');
			$session['user']['biotime']=date("Y-m-d H:i:s");
		}
	}
	if (getsetting("avatare",0)==1) {
		if (stripslashes($_POST['avatar'])!=$session['user']['avatar']){
			$session['user']['avatar']=stripslashes(preg_replace("'[\"\'\\><@?*&#; ]'","",$_POST['avatar']));
			$url=$session[user][avatar];
			if ($url>"" && strpos($url,".gif")<1 && strpos($url,".GIF")<1 && strpos($url,".jpg")<1 && strpos($url,".JPG")<1 && strpos($url,".png")<1 && strpos($url,".PNG")<1){
				$session[user][avatar]="";
				$msg.="`\$Ungültiger Avatar! Nur .jpg, .png, oder .gif`0`n";
			}
		}
	}
	if ($_POST[email]!=$session[user][emailaddress]){
		if (is_email($_POST[email])){
			if (getsetting("requirevalidemail",0)==1){
				output("`#Die E-Mail Adresse kann nicht geändert werden, die Systemeinstellungen verbieten es. (E-Mail Adressen können nur geändert werden, wenn der Server mehr als einen Account pro Adresse zulässt.) Sende eine Petition, wenn du deine Adresse ändern willst, weil sie nicht mehr länger gültig ist.`n");
			}else{
				output("`#Deine E-Mail Adresse wurde geändert.`n");
				$session[user][emailaddress]=$_POST[email];
			}
		}else{
			if (getsetting("requireemail",0)==1){
				output("`#Das ist keine gültige E-Mail Adresse.`n");
			}else{
				output("`#Deine E-Mail Adresse wurde geändert.`n");
				$session[user][emailaddress]=$_POST[email];
			}
		}
	}
	output("$msg");
	output("`nEinstellungen gespeichert");
}

	$form=array(
		"Einstellungen,title"
		,"emailonmail"=>"E-Mail senden wenn du eine Ye Olde Mail bekommst?,bool"
		,"systemmail"=>"E-Mail bei Systemmeldungen senden (z.B. Niederlage im PvP)?,bool"
		,"dirtyemail"=>"Kein Wortfilter für Ye Olde Mail?,bool"
		,"nosounds"=>"Die Sounds deaktivieren?,bool"
//		,"language"=>"Sprache (noch nicht wählbar),enum,en,English,de,Deutsch,dk,Danish,es,Español"
		,"bio"=>"Kurzbeschreibung des Charakters (Maximal 255 Zeichen)`n"
		,"avatar"=>"Link auf einen Avatar`n(Bilddatei - maximal 200x200 Pixel)`n"
	);
	output("
	<form action='prefs.php?op=save' method='POST'>",true);
	if ($handle = @opendir("templates")){
		$skins = array();
		while (false !== ($file = @readdir($handle))){
			if (strpos($file,".htm")>0){
				array_push($skins,$file);
			}
		}
		if (count($skins)==0){
			output("`b`@Argh, dein Admin hat entschieden, daß du keine Skins benutzen darfst. Beschwer dich bei ihm, nicht bei mir.`n");
		}else{
			output("<b>Skin:</b><br>",true);
			while (list($key,$val)=each($skins)){
				output("<input type='radio' name='template' value='$val'".($_COOKIE['template']==""&&$val=="yarbrough.htm" || $_COOKIE['template']==$val?" checked":"").">".substr($val,0,strpos($val,".htm"))."<br>",true);
			}
		}
	}else{
		output("`c`b`\$FEHLER!!!`b`c`&Kann den Ordner mit den Skins nicht finden. Bitte benachrichtige den Admin!!");
	}
	
	output("
	Neues Passwort: <input name='pass1' type='password'> (lasse das Feld leer, wenn du es nicht ändern willst)`n
	Wiederholen: <input name='pass2' type='password'>`n
	E-Mail Adresse: <input name='email' value=\"".HTMLEntities($session['user']['emailaddress'])."\">`n
	",true);
	$prefs = $session['user']['prefs'];
	$prefs['bio'] = $session['user']['bio'];
	if (getsetting("avatare",0)==1) {
		$prefs['avatar'] = $session['user']['avatar'];
	} else {
		$prefs['avatar'] = "(kein Avatar erlaubt)";
	}
	showform($form,$prefs);
	output("
	</form>",true);
	addnav("","prefs.php?op=save");
	addnav("Inventar anzeigen","prefs.php?op=inventory");
	$biolink="bio.php?char=".rawurlencode($session[user][login])."&ret=".urlencode($_SERVER['REQUEST_URI']);
	addnav("Bio", $biolink);

	// Stop clueless lusers from deleting their character just because a
	// monster killed them.
	if ($session['user']['alive'] && getsetting("selfdelete",0)!=0) {
		output("`n`n`n<form action='prefs.php?op=suicide&userid={$session['user']['acctid']}' method='POST'>",true);
		output("<input type='submit' class='button' value='Charakter löschen' onClick='return confirm(\"Willst du deinen Charakter wirklich löschen?\");'>", true);
		output("</form>",true);
		addnav("","prefs.php?op=suicide&userid={$session['user']['acctid']}");
	}
}
page_footer();
?>
