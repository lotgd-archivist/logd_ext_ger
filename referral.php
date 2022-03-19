<?php

// 25052004

require_once "common.php";

if ($session['user']['loggedin']){
	page_header("Empfehlungen");
	addnav("H?Zurück zur Hütte","lodge.php");
	output("Du bekommst automatisch 50 Punkte für jeden geworbenen Charakter, der es bis Level 5 schafft.
	`n`n
	Woher weiss die Seite, dass du eine Person geworben hast?`n
  Kleinigkeit! Wenn du Freunden von dieser Seite erzählst, gib ihnen einfach folgenden Link:`n`n`q
  ".getsetting("serverurl","http://".$_SERVER['SERVER_NAME'].dirname($_SERVER['REQUEST_URI']))."/referral.php?r=". rawurlencode($session['user']['login'])."`n`n
	`0Dadurch wird die Seite wissen, dass du derjenige warst, der ihn hergeschickt hat. Wenn er dann zum ersten Mal Level 5 erreicht, bekommst du deine Punkte!");
	
	$sql = "SELECT name,level,refererawarded FROM accounts WHERE referer={$session['user']['acctid']} ORDER BY dragonkills,level";
  //    output($sql);
	$result = db_query($sql);
  output("`n`nAccounts, die du geworben hast:`n<table border='0' cellpadding='3' cellspacing='0'><tr><td>Name</td><td>Level</td><td>Ausgezahlt?</td></tr>",true);
	for ($i=0;$i<db_num_rows($result);$i++){
		$row = db_fetch_assoc($result);
		output("<tr class='".($i%2?"trlight":"trdark")."'><td>",true);
		output($row['name']);
		output("</td><td>{$row['level']}</td><td>".($row['refererawarded']?"`@Ja!`0":"`\$Nein!`0")."</td></tr>",true);
	}
	if (db_num_rows($result)==0){
		output("<tr><td colspan='3' align='center'>`iKeine!</td><?tr>",true);
	}
	output("</table>",true);
	page_footer();
}else{
	page_header("Willkommen bei Legend of the Green Dragon");
	output("`@Legend of the Green Dragon ist ein Remake des klassischen BBS Spiels `\$Legend of the Red Dragon`@. Es ist ein Multiplayer Browserspiel, das heisst, 
	es muss keinerlei Programm heruntergeladen oder installiert werden.`n
	Komm rein und nehme an einem Abenteuer teil, das eines der ersten Multiplayer Rollenspiele der Welt darstellte!`n`n
	Hier schlüpfst du in die Rolle eines Kriegers in einer Fantasy-Welt, in der eine Legende von einem riesigen grünen Drachen die 
	Bewohner in Angst und Schrecken versetzt. Nunja, zumindest die meisten. Oder wenigstens ein paar.`n`n
	");
	output("`2<li>Kämpfe gegen unzählige böse Kreaturen, die das Dorf bedrohen",true);
	output("<li>Setze unterschiedliche Waffen ein und kaufe dir bessere Rüstungen",true);
	output("<li>Erforsche das Dorf und den Wald und unterhalte dich mit anderen Kriegern",true);
	output("<li>Besiege andere Spieler im Zweikampf - oder heirate sie",true);
	output("<li>Finde und vernichte den grünen Drachen, um im Ansehen zu steigen",true);
	output("<li>Und vieles mehr`n`n",true);
	if ($_GET[r]) output("`@Du wurdest von ".$_GET[r]." hierher eingeladen, damit ihr gemeinsam gegen das Böse kämpfen könnt.");
	output("`@ Melde dich jetzt kostenlos an und werde Teil dieser Welt.");
	addnav("Navigation");
	addnav("Charakter erstellen","create.php?r=".$_GET['r']);
	addnav("F.A.Q.","petition.php?op=faq",false,true);
	addnav("Zum Login","index.php");
	page_footer();
}?>