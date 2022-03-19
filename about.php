<?php

// 15082004

require_once "common.php";
page_header("Über Legend of the Green Dragon");
$time = (strtotime(date("1981-m-d H:i:s",strtotime(date("r")."-".getsetting("gameoffsetseconds",0)." seconds"))))*getsetting("daysperday",4) % strtotime("1981-01-01 00:00:00"); 
$time = gametime();

// by Moonraven
$tomorrow = mktime(0,0,0,date('m',$time),date('d',$time)+1,date('Y',$time)); 
$today = mktime(0,0,0,date('m',$time),date('d',$time),date('Y',$time)); 
$dayduration = ($tomorrow-$today) / getsetting("daysperday",4); 
$secstotomorrow = $tomorrow-$time; 
$secssofartoday = $time - $today; 
$realsecstotomorrow = round($secstotomorrow / getsetting("daysperday",4),0); 
$realsecssofartoday = round($secssofartoday / getsetting("daysperday",4),0); 

checkday();

if ($_GET[op]==""){
	$order=array("1","2");
	while (list($key,$val)=each($order)){
		switch($val){
		case "2":
			/* NOTICE
			 * NOTICE Server admins may put their own information here, please leave the main about body untouched.
			 * NOTICE
			 */
			$impressum=getsetting("impressum","");
			if ($impressum){
				output("<hr>",true);
				output("`@Impressum/Hoster/Verantwortlich für diesen Server:`n`n`2$impressum");
			}
			output("<hr>",true);
			//output("Put your own information here and uncomment this line by removing the '/' marks.");
			output("You can get this german version including all mods, hacks and addons <a href=\"http://www.anpera.net/forum/index.php?c=12\" target=\"_blank\">here</a>.`n",true);
			output("Visit <a href='http://dragonprime.cawsquad.net/' target='_blank'>DragonPrime</a> for more mods!`n`n",true);
			output("`@Ein dickes Danke an `^MightyE`@ für dieses großartige Spiel!     `2 -anpera-");
			break;
		case "1":
			/* NOTICE
			 * NOTICE This section may not be modified, please modify the Server Specific section above.
			 * NOTICE
			 */
			// Auf Deutsch: Fass die folgende Information an, und wir werden deinen Server
			// wegen Veretzung des Copyrights aus dem Netz nehmen lassen.

			output("`@Legend of the Green Dragon`nBy Eric Stevens`n`n");
			output("`cLoGD version {$logd_version}`c");
			//This section may not be modified, please modify the Server Specific section above.
			output("MightyE tells you, \"`2Legend of the Green Dragon is my remake of the classic");
			output("BBS Door game, Legend of the Red Dragon (aka LoRD) by Seth Able Robinson.  ");
			output("`n`n`@\"`2LoRD is now owned by Gameport (<a href='http://www.gameport.com/bbs/lord.html'>http://www.gameport.com/bbs/lord.html</a>), and ",true);
			output("they retain exclusive rights to the LoRD name and game.  That's why all content in ");
			//This section may not be modified, please modify the Server Specific section above.
			output("Legend of the Green Dragon is new, with only a very few nods to the original game, such ");
			output("as the buxom barmaid, Violet, and the handsome bard, Seth.`n`n");
			output("`@\"`2Although serious effort was made to preserve the original feel of the game, ");
			output("numerous departures were taken from the original game to enhance playability, and ");
			//This section may not be modified, please modify the Server Specific section above.
			output("to adapt it to the web.`n`n");
			output("`@\"`2LoGD is released under the GNU General Public License (GPL), which essentially means ");
			output("that the source code to the game, and all derivatives of the game must remain open and");
			output("available upon request.`n`n");
			//This section may not be modified, please modify the Server Specific section above.
			output("`@\"`2You can download the latest version of LoGD at <a href='http://sourceforge.net/projects/lotgd' target='_blank'>http://sourceforge.net/projects/lotgd</a>",true);
			output(" and you can play the latest version at <a href='http://lotgd.net/'>http://lotgd.net</a>.`n`n",true);
			output("`@\"`2LoGD is programmed in PHP with a MySQL backend.  It is known to run on Windows and Linux with appropriate
				setups.  Most code has been written by Eric Stevens, with some pieces by other authors (denoted in source at these locations), 
				and the code has been released under the 
				<a href=\"http://www.gnu.org/copyleft/gpl.html\">GNU General Public License</a>.  Users of the source
				are bound to the terms therein.`@\"`n`n",true);
			//This section may not be modified, please modify the Server Specific section above.
			output("`@\"`2Users of the source are free to view and modify the source, but original copyright information, and
				original text from the about page must be preserved, though they may be added to.`@\"`n`n");
			output("`@\"`2I hope you enjoy the game!`@\"");
			//This section may not be modified, please modify the Server Specific section above.
			break;

			// Ende der Copyrightinformation
		}
	}
	
	addnav("Spieleinstellungen","about.php?op=setup");
	addnav("Modifikationen","about.php?op=modifications");
	addnav("GNU GPL","about.php?op=gpl");
}elseif($_GET[op]=="setup"){
	addnav("Über LoGD","about.php");
	addnav("Modifikationen","about.php?op=modifications");
	$setup = array(
		"Spieleinstellungen,title",
		"pvp"=>"Spieler gegen Spieler erlaubt,viewonly",
		"pvpday"=>"Erlaubte Anzahl Spielerkämpfe pro Tag,viewonly",
		"pvpimmunity"=>"Tage die Spieler sicher vor PvP sind,viewonly",
		"pvpminexp"=>"Nötige Erfahrungspunkte bevor ein Spieler im PvP angreifbar wird,viewonly",
		"soap"=>"Spielerbeiträge 'bereinigen' (Wortfilter),viewonly",
		"newplayerstartgold"=>"Startmenge an Gold für neue Charaktere,viewonly",
		"avatare"=>"Avatare erlaubt?,viewonly",
		"maxonline"=>"Maximal gleichzeitig online (0 für unbegrenzt),viewonly",

		"Neue Tage,title",
		"fightsforinterest"=>"Um Zinsen zu bekommen muss ein Spieler weniger Waldkämpfe haben als,viewonly",
		"maxinterest"=>"Maximaler Zinssatz (%),viewonly",
		"mininterest"=>"Minimaler Zinssatz (%),viewonly",
		"daysperday"=>"Spieltage pro Kalendertag,viewonly",
		"specialtybonus"=>"Extras des Spezialgebiets täglich einsetzen,viewonly",

		"Handelseinstellungen,title",
		"borrowperlevel"=>"Maximum das ein Spieler pro Level leihen kann,viewonly",
		"transferperlevel"=>"Maximum das ein Spieler pro Level des Empfängers überweisen kann,viewonly",
		"mintransferlev"=>"Mindestlevel für Überweisungen,viewonly",
		"transferreceive"=>"Überweisungen die ein Spieler pro Tag empfangen darf,viewonly",
		"maxtransferout"=>"Absolutes Maximum das ein Spieler pro Tag und Level überweisen darf,viewonly",
		
		"Kopfgeld,title",
		"bountymin"=>"Mindestbetrag pro Level der Zielperson,viewonly",
		"bountymax"=>"Maximalbetrag pro Level der Zielperson,viewonly",
		"bountylevel"=>"Mindestlevel um Opfer sein zu können,viewonly",
		"bountyfee"=>"Gebühr für Dag Durnick in Prozent,viewonly",
		"maxbounties"=>"Anzahl an Kopfgeldern die ein Spieler pro Tag aussetzen darf,viewonly",

		"Wald,title",
		"turns"=>"Waldkämpfe (Züge) pro Tag,viewonly",
		"dropmingold"=>"Waldbewohner lassen wenigstens 1/4 des möglichen Golds fallen,viewonly",
		"lowslumlevel"=>"Mindestlevel zum Herumstreifen,viewonly",
		
		"Mail Einstellungen,title",
		"mailsizelimit"=>"Maximale Nachrichtengröße,viewonly",
		"inboxlimit"=>"Maximale Anzahl an Nachrichten in der Inbox,viewonly",
		"oldmail"=>"Alte Nachrichten werden automatisch gelöscht nach Tagen,viewonly",
	
		"Inhaltsverfallsdatum (0 für keines),title",
		"expirecontent"=>"Tage die Kommentare und Neuigkeiten aufgehoben werden,viewonly",
		"expiretrashacct"=>"Accounts die sich nie eingeloggt haben werden nach x Tagen gelöscht. x =,viewonly",
		"expirenewacct"=>"Level 1 Charaktere ohne Drachenkill werden nach x Tagen gelöscht. x =,viewonly",
		"expireoldacct"=>"Alle anderen Accounts werden nach x Tagen Inaktivität gelöscht. x =,viewonly",
		"LOGINTIMEOUT"=>"Sekunden Inaktivität bis zum automatischen Logout,viewonly",
	
		"Nützliche Infos,title",
		"Tageslänge: ".round(($dayduration/60/60),0)." Stunden,viewonly",
		"aktuelle Serveruhrzeit: ".date("Y-m-d h:i:s a").",viewonly",
		"Letzter neuer Tag: ".date("h:i:s a",strtotime(date("r")."-$realsecssofartoday seconds")).",viewonly",
		"aktuelle Spielzeit: ".getgametime().",viewonly",
		"Nächster neuer Tag: ".date("h:i:s a",strtotime(date("r")."+$realsecstotomorrow seconds"))." (".date("H\\h i\\m s\\s",strtotime("1980-01-01 00:00:00 + $realsecstotomorrow seconds"))."),viewonly",
		"weather"=>"Heutiges Wetter:,viewonly"
		);
	
	output("`@<h3>Einstellungen für diesen Server</h3>`2LoGD 0.9.7+jt komplett auf deutsch, mit Sound und vielen Extras!`n`n",true);
	//output("<table border=1>",true);
	showform($setup,$settings,true);
	//output("</table>",true);

}elseif($_GET[op]=="modifications"){
	addnav("Über LoGD","about.php");
	addnav("Spieleinstellungen","about.php?op=setup");
	output("`#Hier läuft `iLegend of the Green Dragon`i Version 0.9.7+jt mit folgenden Änderungen und Erweiterungen:");
	output("<ul>`#`bDeutsche Übersetzung`b`3<li>Basiert auf translator_de.php von anpera<li>Rätsel und Spott von weasel<li>Monster von anpera, leopolt, Reandor, theklaus ",true);
	output("<li>Waffen und Rüstungen von theklaus<li>Titel von den Spielern aus Pandiswelt</ul>",true);
	output("<ul>`#`bBesondere Ereignisse (Forest Events)`b`3<li>Aphrodite: Erquickender Quicky mit einer Gottheit. (Reznarth, anpera)",true);
	output("<li>Bushes: Dein Weg durch dichtes Gestrüpp kann teuer werden - oder eine grosse Belohnung bieten (bibir)",true);
	output("<li>Cookies: Kekse im Wald? Sehr ungewöhnlich. (Asuka, Warchild, Zelda)",true);
	output("<li>Findtreasure: Finde einen hohlen Baumstamm, der eine Schatzkarte enthalten kann (gefunden auf http://www.lotgd.de, Modifikationen von anpera)<li>GoldenEgg: Finde das einzigartige Goldene Ei, das eine ganz besondere Verbindung mit dem Totenreich hat. Lass es dir nicht im PvP abnehmen! (anpera)",true);
	output("<li>Gladiator: Lasse dich von einem alten Gladiator zu Arenakämpfen antreiben. (anpera)",true);
	output("<li>Graeultat: Eine Leiche dreht dir den Magen um. (Joerg Ledergerber)",true);
	output("<li>jewelrymaker: Ein elfischer Kunsthandwerker schmiedet dir ein Kunstwerk aus deinem Gold. (Warchild)",true);
	output("<li>Lake: Ein kleiner See, aus dem man trinken kann (weasel?)<li>Necromancer: Treffe einen finsteren Totenbeschwörer, der Verbindungen zu Ramius und ins Schattenreich hat (TheDragonReborn, Modifikationen von anpera)",true);
	output("<li>Randdragon: Begegne dem Grünen Drachen im Wald und spüre die Bedrohung hautnah. (Voratus)",true);
	output("<li>Remains: Eine Leiche eines deiner Mitstreiter erweckt deine Neugier. Doch ein mächtiger Fluch lastet auf ihr! (Voratus, anpera)",true);
	output("<li>Sacrificealtar: Ein Opferaltar der Götter (TheDragonReborn)",true);
	output("<li>Slump: Verliere Geld im Wald, welches vom jüngsten Spieler und vom letzten Drachentöter gestohlen wird. (bibir)",true);
	output("<li>Smith: Ein Schmied bietet dir an, deine Rüstung oder Waffe zu verbessern (jtraub)<li>Stonehenge: Finde einen mysteriösen Ort im Wald (TheDragonReborn)<li>Stumble: Rutsche auf Pferdemist aus. (Zanzara, anpera)",true);
	output("<li>Tempel: Spende für die Renovierung eines alten Tempels und erhalte mit etwas Glück ein Geschenk von den Göttern. (Romulus von Grauhaar)",true);
	output("<li>Vampire: Begegne einem Vampir, der mit deiner Lebenskraft handelt und die Lebenskraft auf dem Realm gerecht halten kann. (genmac, lonestrider, anpera)",true);
	output("<li>Wannabe Knight: Ein Möchtegern Ritter schlägt dich im Wald nieder. Möchtest du Rache? (Robert, theklaus)<li>waterfall: Untersuche einen Wasserfall (Kevin Kilgore)</ul>",true);
	output("<ul>`#`bAddons und Erweiterungen`b`3",true);
	output("<li>Avatare: Verlinke ein Bild deines Helden in deiner Bio. (anpera)",true);
	output("<li>Battle Arena: Kämpfe in der Arena gegen verschieden Gladiatoren um die Ehre. (Lonny Luberts)",true);
	output("<li>Bettelstein für Spenden an die Ärmsten der Armen. (ThunderEye)",true);
	output("<li>Diebstahl: Klaue Waffen oder Rüstungen, statt sie zu kaufen. Aber nimm dich vor den Ladenbesitzern in acht! (anpera)",true);
	output("<li>Dorfbrunnen: Zum Entsorgen ungewollter Schlüssel. (Chaosmaker)",true);
	output("<li>Durandils Trampelpfad gibt den Weg zu schönen Orten für Paare frei. (Durandil)",true);
	output("<li>Füttere dein Tier, wenn es nicht mehr kämpfen kann. (anpera)",true);
	output("<li>Edelsteinhandel: Bei der Zigeunerin können begrenzt Edelsteine ge- und verkauft werden. (anpera, lonestrider) Edelstentransfer zwischen Spielern ist in der Bank gegen Gebühr möglich. (anpera)",true);
	output("<li>Gartenflirt: Flirte mit anderen Spielern und heirate sie. (anpera)",true);
	output("<li>Geschenkeshop: Schenke anderen Spieler mehr oder weniger nützliche Dinge. (Lonny Luberts, anpera)",true);
	output("<li>Häuser: Baue oder kaufe ein Haus, wohne und teile Resourcen mit Freunden. Oder breche in die Häuser deiner Feinde ein. Jetzt mit Möbeln! (anpera)",true);
	output("<li>Hexenhaus: Der Spieler kann bestimmte Aktionen zurücksetzen, neue Waldkämpfe kaufen oder seinen letzten Waldkampf in ein besonderes Ereignis verwandeln. Die Hexe weiß auch mit Flüchen umzugehen. (anpera)",true);
	output("<li>Inventar und Itemsystem: Finde mächtige Gegenstände, Artefakte, Flüche, oder einfach Schrott bei besiegten Monstern oder bei Händlern. Verkaufe oder benutze sie! (anpera)",true);
	output("<li>Lodge und Donationpoint-System: Neben Punkten für Spenden kann der Spieler auch während des Spiels Donationspoints sammeln und für diese in der Jägerhütte bestimmte Extras freischalten. Unter anderem farbige Namen, eigene Titel, PvP-Immunität, zur Burg reiten, Heilerin Golinda, mehr Waldkämpfe, ... (anpera, weasel)",true);
	output("<li>PvP-Arena: Kämpfe in der Arena mit allen Spezialfertigkeiten und Buffs online (oder offline) gegen andere Spieler. (anpera)",true);
	output("<li>Schrein: Erwecke andere Spieler aus dem Totenreich. (unbekannt, anpera)",true);
	output("<li>Schwarzes Brett: Hinterlasse Mitteilungen und Suchanzeigen auf einem schwarzen Brett in der Kneipe. (anpera)",true);
	output("<li>Sound und Musik: Bestimmten Ereignissen sind Klänge oder Musik zugeordnet. Sound kann im Spielerprofil individuell deaktiviert werden. (anpera, nTE)",true);
	output("<li>Spendiere Freibier in der Kneipe. (anpera)",true);
	output("<li>Spieldatum: Neben Spielzeiten gibt es auch ein Spieldatum, das aber keinen Einfluss auf das Alter von Spielern hat. (Chaosmaker)",true);
	output("<li>Warchild's Akademie: Lasse dich in deinen besonderen Fertigkeiten ausbilden. (Warchild)",true);
	output("<li>Wetter: Das Wetter hat Auswirkungen auf deine besonderen Fähigkeiten und den Kampfverlauf (Talisman, JT, anpera)",true);
	output("<li>Wiedergeburt: Wenn du alles erreicht hast, kannst du wiedergeboren werden. (Luke, anpera)",true);
	output("<li>Tortenschlacht: Begrüsse neue Spieler und Charaktere, die ihren Spieljahrestag haben, mit einer Geburtstagstorte. (anpera)",true);
	output("<li>Zaubersystem: Schleudere deinen Gegnern mächtige Zauber entgegen, die du kaufen oder finden kannst. (anpera)",true);
	output("</ul><ul>`#`bGameplay / Balancing`b`3",true);
	output("<li>Anzahl der Spieler, die gleichzeitig online sein können, ist begrenzt. (anpera)",true);
	output("<li>AutoChallenge Grüner Drachen: Wie die Meister holt sich der Drachen seine \"Opfer\", sobald sie überfällig sind. (anpera)",true);
	output("<li>AutoFight: Kämpfe jede Kampfrunde selbst, oder lasse die nächsten 5 oder alle Kampfrunden automatisch ablaufen. (anpera)",true);
	output("<li>Ehrenhaftes Verhalten wird belohnt, Schurken werden bestraft. (anpera)",true);
	output("<li>PvP-Schutz kleinerer Charaktere vor Charakteren, die über 5 Drachenkills mehr als ihr Opfer haben. (anpera)",true);
	output("</ul><ul>`#`bSonstiges`b`3",true);
	output("<li>Neue Rasse: Echsenwesen (anpera)",true);
	output("<li>Ruhmeshalle: Sortiere nach Reichtum, Schönheit, Stärke, Schlagkraft, Gefallen, Geschwindigkeit, oder betrachte dir die Liste der Heldenpaare. (anpera, jtraub, MightyE)",true);
	output("<li>Skin 'Jade' als alternatives Design (Josh Canning)",true);
	output("<li>Statusbalken für Lebensenergie, Erfahrung und vieles mehr. (dvd871, anpera)",true);
	output("<li>PHP 5 Kompatibilität (thx to Zarzal!)",true);
	output("<li>Kleinere Modifikationen: 45 Titel (nicegames.de), Aprilscherz deaktiviert, Codeoptimierunge (Chaosmaker), erweiterte F.A.Q., erweiterte Superuserfunktionen, Forumlink, keine Zinsen mehr für Superreiche, konfigurierbares Impressum, Lotterie in der Kneipe (unbekannt, anpera), Multiaccount-Cheat-Schutz (anpera), neuste Schlagzeile auf dem Dorfplatz, neuste Spieler auf der Startseite, Online/Offline-Anzeige in jedem Chat, Rasse in Kämpferliste, Suchfunktion in langen Listen, Uhrzeit im Totenreich, verbessertes Login (nTE), Willkommens-News, Zox Grill in Darkhorse Tavern (Reznarth?), zusätzliche `qF`Ra`ar`9b`8e`vn`r.`V.`T.",true);
	output("</ul>`3...und vieles mehr.",true);
	output("`n`n\"Changes Log\" mit allen Änderungen seit 14.1.2004 gibt es `b<a href='http://www.anpera.net/forum/viewtopic.php?t=317' target='_blank'>hier</a>`b.",true);
}elseif($_GET[op]=="gpl"){
	output("`&`b`cGNU GENERAL PUBLIC LICENSE`b`nVersion 2, June 1991`c
`n`n`0
 Copyright (C) 1989, 1991 Free Software Foundation, Inc.`n
 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA`n
 Everyone is permitted to copy and distribute verbatim copies of this license document, but changing it is not allowed.
`n`n
`c`&Preamble`c`0
`n`n
  The licenses for most software are designed to take away your freedom to share and change it.  By contrast, the GNU General Public License is intended to guarantee your freedom to share and change free
software--to make sure the software is free for all its users.  This General Public License applies to most of the Free Software Foundation's software and to any other program whose authors commit to
using it.  (Some other Free Software Foundation software is covered by the GNU Library General Public License instead.)  You can apply it to your programs, too.
`n`n
  When we speak of free software, we are referring to freedom, not price.  Our General Public Licenses are designed to make sure that you have the freedom to distribute copies of free software (and charge for
this service if you wish), that you receive source code or can get it if you want it, that you can change the software or use pieces of it in new free programs; and that you know you can do these things.
`n`n
  To protect your rights, we need to make restrictions that forbid anyone to deny you these rights or to ask you to surrender the rights.
These restrictions translate to certain responsibilities for you if you distribute copies of the software, or if you modify it.
`n`n
  For example, if you distribute copies of such a program, whether gratis or for a fee, you must give the recipients all the rights that you have.  You must make sure that they, too, receive or can get the
source code.  And you must show them these terms so they know their rights.
`n`n
  We protect your rights with two steps:`n (1) copyright the software, and`n
(2) offer you this license which gives you legal permission to copy, distribute and/or modify the software.
`n`n
  Also, for each author's protection and ours, we want to make certain that everyone understands that there is no warranty for this free
software.  If the software is modified by someone else and passed on, we want its recipients to know that what they have is not the original, so
that any problems introduced by others will not reflect on the original authors' reputations.
`n`n
  Finally, any free program is threatened constantly by software patents.  We wish to avoid the danger that redistributors of a free
program will individually obtain patent licenses, in effect making the program proprietary.  To prevent this, we have made it clear that any
patent must be licensed for everyone's free use or not licensed at all.
`n`n
  The precise terms and conditions for copying, distribution and modification follow.
`n`n`n`b`c`&GNU GENERAL PUBLIC LICENSE`b`n
   TERMS AND CONDITIONS FOR COPYING, DISTRIBUTION AND MODIFICATION`c
`n`n
  `&0.`0 This License applies to any program or other work which contains a notice placed by the copyright holder saying it may be distributed
under the terms of this General Public License.  The \"Program\", below, refers to any such program or work, and a \"work based on the Program\"
means either the Program or any derivative work under copyright law: that is to say, a work containing the Program or a portion of it,
either verbatim or with modifications and/or translated into another language.  (Hereinafter, translation is included without limitation in
the term \"modification\".)  Each licensee is addressed as \"you\".
`n`n
Activities other than copying, distribution and modification are not covered by this License; they are outside its scope.  The act of
running the Program is not restricted, and the output from the Program is covered only if its contents constitute a work based on the
Program (independent of having been made by running the Program). Whether that is true depends on what the Program does.
`n`n
  `&1.`0 You may copy and distribute verbatim copies of the Program's source code as you receive it, in any medium, provided that you
conspicuously and appropriately publish on each copy an appropriate copyright notice and disclaimer of warranty; keep intact all the
notices that refer to this License and to the absence of any warranty; and give any other recipients of the Program a copy of this License
along with the Program.
`n`n
You may charge a fee for the physical act of transferring a copy, and you may at your option offer warranty protection in exchange for a fee.
`n`n
  `&2.`0 You may modify your copy or copies of the Program or any portion of it, thus forming a work based on the Program, and copy and
distribute such modifications or work under the terms of Section 1 above, provided that you also meet all of these conditions:
`n`n
    `ba)`b You must cause the modified files to carry prominent notices stating that you changed the files and the date of any change.
`n`n
    `bb)`b You must cause any work that you distribute or publish, that in whole or in part contains or is derived from the Program or any
    part thereof, to be licensed as a whole at no charge to all third parties under the terms of this License.
`n`n
    `bc)`b If the modified program normally reads commands interactively when run, you must cause it, when started running for such
    interactive use in the most ordinary way, to print or display an announcement including an appropriate copyright notice and a
    notice that there is no warranty (or else, saying that you provide a warranty) and that users may redistribute the program under
    these conditions, and telling the user how to view a copy of this License.  (Exception: if the Program itself is interactive but
    does not normally print such an announcement, your work based on the Program is not required to print an announcement.)
`n`n`nThese requirements apply to the modified work as a whole.  If identifiable sections of that work are not derived from the Program,
and can be reasonably considered independent and separate works in themselves, then this License, and its terms, do not apply to those
sections when you distribute them as separate works.  But when you distribute the same sections as part of a whole which is a work based
on the Program, the distribution of the whole must be on the terms of this License, whose permissions for other licensees extend to the
entire whole, and thus to each and every part regardless of who wrote it.
`n`n
Thus, it is not the intent of this section to claim rights or contest your rights to work written entirely by you; rather, the intent is to
exercise the right to control the distribution of derivative or collective works based on the Program.
`n`n
In addition, mere aggregation of another work not based on the Program with the Program (or with a work based on the Program) on a volume of
a storage or distribution medium does not bring the other work under the scope of this License.
`n`n
  `&3.`0 You may copy and distribute the Program (or a work based on it, under Section 2) in object code or executable form under the terms of
Sections 1 and 2 above provided that you also do one of the following:
`n`n
    `ba)`b Accompany it with the complete corresponding machine-readable source code, which must be distributed under the terms of Sections
    1 and 2 above on a medium customarily used for software interchange; or,
`n`n
    `bb)`b Accompany it with a written offer, valid for at least three years, to give any third party, for a charge no more than your
    cost of physically performing source distribution, a complete machine-readable copy of the corresponding source code, to be
    distributed under the terms of Sections 1 and 2 above on a medium customarily used for software interchange; or,
`n`n
    `bc)`b Accompany it with the information you received as to the offer to distribute corresponding source code.  (This alternative is
    allowed only for noncommercial distribution and only if you received the program in object code or executable form with such
    an offer, in accord with Subsection b above.)
`n`n
The source code for a work means the preferred form of the work for making modifications to it.  For an executable work, complete source
code means all the source code for all modules it contains, plus any associated interface definition files, plus the scripts used to
control compilation and installation of the executable.  However, as a special exception, the source code distributed need not include
anything that is normally distributed (in either source or binary form) with the major components (compiler, kernel, and so on) of the
operating system on which the executable runs, unless that component itself accompanies the executable.
`n`n
If distribution of executable or object code is made by offering access to copy from a designated place, then offering equivalent
access to copy the source code from the same place counts as distribution of the source code, even though third parties are not
compelled to copy the source along with the object code.
`n`n`n  `&4.`0 You may not copy, modify, sublicense, or distribute the Program except as expressly provided under this License.  Any attempt
otherwise to copy, modify, sublicense or distribute the Program is void, and will automatically terminate your rights under this License.
However, parties who have received copies, or rights, from you under this License will not have their licenses terminated so long as such
parties remain in full compliance.
`n`n
  `&5.`0 You are not required to accept this License, since you have not signed it.  However, nothing else grants you permission to modify or
distribute the Program or its derivative works.  These actions are prohibited by law if you do not accept this License.  Therefore, by
modifying or distributing the Program (or any work based on the Program), you indicate your acceptance of this License to do so, and
all its terms and conditions for copying, distributing or modifying the Program or works based on it.
`n`n
  `&6.`0 Each time you redistribute the Program (or any work based on the Program), the recipient automatically receives a license from the
original licensor to copy, distribute or modify the Program subject to these terms and conditions.  You may not impose any further
restrictions on the recipients' exercise of the rights granted herein. You are not responsible for enforcing compliance by third parties to
this License.
`n`n
  `&7.`0 If, as a consequence of a court judgment or allegation of patent infringement or for any other reason (not limited to patent issues),
conditions are imposed on you (whether by court order, agreement or otherwise) that contradict the conditions of this License, they do not
excuse you from the conditions of this License.  If you cannot distribute so as to satisfy simultaneously your obligations under this
License and any other pertinent obligations, then as a consequence you may not distribute the Program at all.  For example, if a patent
license would not permit royalty-free redistribution of the Program by all those who receive copies directly or indirectly through you, then
the only way you could satisfy both it and this License would be to refrain entirely from distribution of the Program.
`n`n
If any portion of this section is held invalid or unenforceable under any particular circumstance, the balance of the section is intended to
apply and the section as a whole is intended to apply in other circumstances.
`n`n
It is not the purpose of this section to induce you to infringe any patents or other property right claims or to contest validity of any
such claims; this section has the sole purpose of protecting the integrity of the free software distribution system, which is
implemented by public license practices.  Many people have made generous contributions to the wide range of software distributed
through that system in reliance on consistent application of that system; it is up to the author/donor to decide if he or she is willing
to distribute software through any other system and a licensee cannot impose that choice.
`n`n
This section is intended to make thoroughly clear what is believed to be a consequence of the rest of this License.
`n`n`n  `&8.`0 If the distribution and/or use of the Program is restricted in certain countries either by patents or by copyrighted interfaces, the
original copyright holder who places the Program under this License may add an explicit geographical distribution limitation excluding
those countries, so that distribution is permitted only in or among countries not thus excluded.  In such case, this License incorporates
the limitation as if written in the body of this License. 
`n`n
  `&9.`0 The Free Software Foundation may publish revised and/or new versions of the General Public License from time to time.  Such new versions will
be similar in spirit to the present version, but may differ in detail to address new problems or concerns.
`n`n
Each version is given a distinguishing version number.  If the Program specifies a version number of this License which applies to it and \"any
later version\", you have the option of following the terms and conditions either of that version or of any later version published by the Free
Software Foundation.  If the Program does not specify a version number of this License, you may choose any version ever published by the Free Software
Foundation.
`n`n
  `&10.`0 If you wish to incorporate parts of the Program into other free programs whose distribution conditions are different, write to the author
to ask for permission.  For software which is copyrighted by the Free Software Foundation, write to the Free Software Foundation; we sometimes
make exceptions for this.  Our decision will be guided by the two goals of preserving the free status of all derivatives of our free software and
of promoting the sharing and reuse of software generally.
`n`n
`cNO WARRANTY`c

  `&11.`0 BECAUSE THE PROGRAM IS LICENSED FREE OF CHARGE, THERE IS NO WARRANTY FOR THE PROGRAM, TO THE EXTENT PERMITTED BY APPLICABLE LAW.  EXCEPT WHEN
OTHERWISE STATED IN WRITING THE COPYRIGHT HOLDERS AND/OR OTHER PARTIES PROVIDE THE PROGRAM \"AS IS\" WITHOUT WARRANTY OF ANY KIND, EITHER EXPRESSED
OR IMPLIED, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE.  THE ENTIRE RISK AS
TO THE QUALITY AND PERFORMANCE OF THE PROGRAM IS WITH YOU.  SHOULD THE PROGRAM PROVE DEFECTIVE, YOU ASSUME THE COST OF ALL NECESSARY SERVICING,
REPAIR OR CORRECTION.
`n`n
  `&12.`0 IN NO EVENT UNLESS REQUIRED BY APPLICABLE LAW OR AGREED TO IN WRITING WILL ANY COPYRIGHT HOLDER, OR ANY OTHER PARTY WHO MAY MODIFY AND/OR
REDISTRIBUTE THE PROGRAM AS PERMITTED ABOVE, BE LIABLE TO YOU FOR DAMAGES, INCLUDING ANY GENERAL, SPECIAL, INCIDENTAL OR CONSEQUENTIAL DAMAGES ARISING
OUT OF THE USE OR INABILITY TO USE THE PROGRAM (INCLUDING BUT NOT LIMITED TO LOSS OF DATA OR DATA BEING RENDERED INACCURATE OR LOSSES SUSTAINED BY
YOU OR THIRD PARTIES OR A FAILURE OF THE PROGRAM TO OPERATE WITH ANY OTHER PROGRAMS), EVEN IF SUCH HOLDER OR OTHER PARTY HAS BEEN ADVISED OF THE
POSSIBILITY OF SUCH DAMAGES.
`n`n
`cEND OF TERMS AND CONDITIONS`n`n`n
How to Apply These Terms to Your New Programs`c
`n`n
  If you develop a new program, and you want it to be of the greatest possible use to the public, the best way to achieve this is to make it
free software which everyone can redistribute and change under these terms.
`n`n
  To do so, attach the following notices to the program.  It is safest to attach them to the start of each source file to most effectively
convey the exclusion of warranty; and each file should have at least the \"copyright\" line and a pointer to where the full notice is found.
`n`n
    <one line to give the program's name and a brief idea of what it does.>`n
    Copyright (C) <year>  <name of author>
`n`n
    This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
`n`n
    This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
`n`n
    You should have received a copy of the GNU General Public License along with this program; if not, write to the`n
Free Software Foundation, Inc.,`n
59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
`n`n`n
Also add information on how to contact you by electronic and paper mail.
`n`n
If the program is interactive, make it output a short notice like this when it starts in an interactive mode:
`n`n
    `iGnomovision version 69, Copyright (C) year name of author`n
    Gnomovision comes with ABSOLUTELY NO WARRANTY; for details type 'show w'.`n
    This is free software, and you are welcome to redistribute it under certain conditions; type 'show c' for details.`i
`n`n
The hypothetical commands 'show w' and 'show c' should show the appropriate parts of the General Public License.  Of course, the commands you use may
be called something other than `show w' and \`show c'; they could even be mouse-clicks or menu items--whatever suits your program.
`n`n
You should also get your employer (if you work as a programmer) or your school, if any, to sign a \"copyright disclaimer\" for the program, if
necessary.  Here is a sample; alter the names:
`n`n
`i  Yoyodyne, Inc., hereby disclaims all copyright interest in the program 'Gnomovision' (which makes passes at compilers) written by James Hacker.
`n`n`i
 <signature of Ty Coon>, 1 April 1989`n
  Ty Coon, President of Vice
`n`n
This General Public License does not permit incorporating your program into proprietary programs.  If your program is a subroutine library, you may
consider it more useful to permit linking proprietary applications with the library.  If this is what you want to do, use the GNU Library General
Public License instead of this License.");
	addnav("Über LoGD","about.php");
}else{

}
if ($session[user][loggedin]) {
	addnav("Zurück zu den News","news.php");
}else{
	addnav("Login","index.php");
}
page_footer();
?>
