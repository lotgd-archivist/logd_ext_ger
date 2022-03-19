<?php

// 13082004

if (!isset($session)) exit();
// The addition of the commentary is handled by the forest.php
// addcommentary();
output("`c`b<span style='color: #787878'>Dark Horse Taverne`b`c",true);
$session[user][specialinc]="darkhorse.php";
switch($HTTP_GET_VARS[op]){
case "tavern":
	checkday();
	output("Du stehst in der Nähe des Eingangs und beobachtest das Geschehen vor dir. Während es in den meisten Tavernen ");
	output("laut und rauh zugeht, ist diese hier erstaunlich ruhig und fast leer. In einer Ecke spielt ein alter Mann mit ");
	output("einigen Würfeln. Du bemerkst, dass Abenteurer, die diesen Platz gefunden haben, Botschaften in die Tische ");
	output("geritzt haben. Hinter der Theke humpelt ein alter Mann herum und poliert Gläser - ");
	output("obwohl niemand hier zu sein scheint, der sie benutzen würde.`nSeitlich in einer Nische siehst du einen rundlichen Kerl hinter einem knisternden Grill stehen. ");
	
	addnav("Rede mit dem alten Mann","forest.php?op=oldman");
	addnav("Rede mit dem Barkeeper","forest.php?op=bartender");
	addnav("Untersuche die Tische","forest.php?op=tables");
	addnav("Zox's Grill","forest.php?op=grill");
	addnav("Verlasse die Taverne","forest.php?op=leave");
	$session[user][specialinc]="darkhorse.php";
	break;
case "tables":
	output("Du untersuchst die Schnitzereien in den Tischen:`n`n");
	viewcommentary("darkhorse","Ritze etwas in die Tische:",10,"ritzte");
	addnav("Zurück zur Taverne","forest.php?op=tavern");
	break;
case "grill":
	addnav("Zurück zur Taverne","forest.php?op=tavern");
	$scost = $session[user][level]*25;
	$ccost = $session[user][level]*11;
	$zcost = $session[user][level]*8;
	if ($HTTP_GET_VARS[what]=="steak"){
		if ($session[user][gold] >= $scost && $session[user][turns]>0){
			switch(e_rand(1,2)){ 
				case 1:
				output("`@Das hat gut getan! Du fühlst dich erfrischt. Du erhältst einen Waldkampf und deine Wunden heilen etwas.`n`n"); 
				$session['user']['turns']+=1;
				$session['user']['hitpoints'] += e_rand(2,5*$session['user']['level']);
				if ($session['user']['hitpoints'] > $session['user']['maxhitpoints']) $session['user']['hitpoints'] = $session['user']['maxhitpoints'];
				$session['user']['gold']-=$scost;
				break;
				case 2:
				output("`@Das hat gut getan! Aber jetzt fühlst du dich voll. Du verlierst einen Waldkampf aber deine Wunden heilen etwas.`n`n"); 
				$session['user']['turns']-=1; 
				$session['user']['hitpoints'] += e_rand(2,5*$session['user']['level']);
				if ($session['user']['hitpoints'] > $session['user']['maxhitpoints']) $session['user']['hitpoints'] = $session['user']['maxhitpoints'];
				$session['user']['gold']-=$scost;
				break;
			}
		}else if ($session[user][turns]<=0){
			output("So kurz vor dem Schlafengehen solltest du nichts mehr essen. Komm morgen wieder vorbei.");
			addnav("Zurück zum Grill","forest.php?op=grill");
		}else {
			output("Das kannst du dir nicht leisten.");
			addnav("Zurück zum Grill","forest.php?op=grill");
		}
	} else if ($HTTP_GET_VARS[what]=="cheese"){
		if ($session[user][gold] >= $ccost && $session[user][turns]>0){
			switch(e_rand(1,3)){ 
				case 1:
				output("`@Das hat gut getan! Du fühlst dich erfrischt. Du erhältst einen Waldkampf.`n`n"); 
				$session['user']['turns']+=1;
				$session['user']['gold']-=$ccost;
				break;
				case 2:
				output("`@Das hat gut getan! Aber jetzt fühlst du dich voll. Du verlierst einen Waldkampf.`n`n"); 
				$session['user']['turns']-=1; 
				$session['user']['gold']-=$ccost;
				break;
				case 3:
				output("`@Das hat gut getan!`n`n"); 
				$session['user']['gold']-=$ccost;
				break;
			}
		}else if ($session[user][turns]<=0){
			output("So kurz vor dem Schlafengehen solltest du nichts mehr essen. Komm morgen wieder vorbei.");
			addnav("Zurück zum Grill","forest.php?op=grill");
		}else {
			output("Das kannst du dir nicht leisten.");
			addnav("Zurück zum Grill","forest.php?op=grill");
		}
	} else if ($HTTP_GET_VARS[what]=="soda"){
		if ($session[user][gold] >= $zcost && $session[user][turns]>0){
			switch(e_rand(1,2)){ 
				case 1:
				output("`@Das hat gut getan!`n`n"); 
				$session['user']['gold']-=$zcost;
				break;
				case 2:
				output("`@Das hat gut getan! Deine Wunden heilen etwas.`n`n"); 
				$session['user']['hitpoints'] += e_rand(2,3*$session['user']['level']);
				if ($session['user']['hitpoints'] > $session['user']['maxhitpoints']) $session['user']['hitpoints'] = $session['user']['maxhitpoints'];
				$session['user']['gold']-=$zcost;
				break;
			}
		}else if ($session[user][turns]<=0){
			output("So kurz vor dem Schlafengehen solltest du nichts mehr trinken. Komm morgen wieder vorbei.");
			addnav("Zurück zum Grill","forest.php?op=grill");
		}else {
			output("Das kannst du dir nicht leisten.");
			addnav("Zurück zum Grill","forest.php?op=grill");
		}
	}else{
		output("`7Der Geruch von Käse und Steaks steigt dir in die Nase, als du näher kommst. Beim Lesen des Menüs läuft dir das Wasser im Mund zusammen.`n`n");
		addnav("Menü");
		addnav("Cheeseburger (`^$ccost Gold`0)","forest.php?op=grill&what=cheese");
		addnav("Steak im Brötchen (`^$scost Gold`0)","forest.php?op=grill&what=steak");
		addnav("Zox-Soda (`^$zcost Gold`0)","forest.php?op=grill&what=soda");
	}
	break;
case "bartender":
	if ($HTTP_GET_VARS[what]==""){
		output("Der runzelige alte Mann an der Bar erinnert dich stark an eine Scheibe Rindfleisch.`n`n");
		output("\"`7Schag, wasch kann ich für dich tun, ".($session[user][sex]?"Mädchen":"mein Schohn")."?`0\"  spricht der Zahnlose. ");
		output(" \"`7Schehe die Wünsche deineschgleichen nicht alltschuoft hier.`0\"");
		addnav("Etwas über die Feinde lernen","forest.php?op=bartender&what=enemies");
		addnav("Farbenlehre","forest.php?op=bartender&what=colors");
		//addnav("Buy swill","forest.php?op=bartender&what=swill");
		addnav("Das goldene Ei","forest.php?op=bartender&what=egg");
	}else if($HTTP_GET_VARS[what]=="egg"){
		output("\"`7Schoscho, du willscht alscho etwasch über dasch goldene Ei wischen.`nNun, dasch ischt eine uralte Legende. Esch heischt, wer ein goldnesch Ei beschitscht, kann dem Tod entkommen. ");
		output("Auscherdem scholl dieschesch Ei der Schlüschel tschu einer Heilerin namensch Golinda schein. Ich glaube ja nicht daran.");
		if (getsetting("hasegg",0)==0){
	  		output(" Niemand hat dasch Ei jemalsch gefunden.");
		} else {
			$sql = "SELECT acctid,name FROM accounts WHERE acctid = '".getsetting("hasegg",0)."'";
			$result = db_query($sql) or die(db_error(LINK));
			$row = db_fetch_assoc($result);
			output("`0\" Er beginnt zu flüstern: \"`7Aber esch geht dasch Gerücht um, dasch `^$row[name] `7genau dieschesch Ei gefunden haben scholl. Wenn du mich fragscht, ich würde $row[name] `7schogar töten und ihm dasch Ei wegnehmen, ");
			output(" um dasch herauschtschufinden, wenn ich könnte...");
		}
		output("`0\"");
		if ($session['user']['acctid']==getsetting("hasegg",0)){
			output("`n`nDu ziehst dich zurück, ohne den Mann in Versuchung zu bringen, dir das Ei wegnehmen zu wollen. An ");
			output("einem Tisch ausser Sichtweite untersuchst du das Ei und entdeckst seltsame Botschaften...`n`n`n");
			viewcommentary("goldenegg","Botschaft hinterlassen:",10,"");
		}
	}else if($HTTP_GET_VARS[what]=="swill"){
		
	}else if($HTTP_GET_VARS[what]=="colors"){
			  output("Der alte Mann stützt sich auf die Theke. \"`%Scho, du willscht alscho wasch über Farben wischen?`0\"");
				output("  Du willst gerade antworten, als du gerade noch merkst, daß das eine rhetorische Frage war.  ");
				output("Er fährt fort: \"`%Um farbig tschu sprechen, mache folgendesch. Zuerscht benutsche das &#0096; Tscheichen ",true);
				output("(Shift und die Taste links neben Backspace), gefolgt von 1, 2, 3, 4, 5, 6, 7, 8, 9, !, @, #, $, %, ^, Q oder &. Jedesch diescher Tscheichen entspricht ");
				output("einer Farbe, die folgendermaschenen ausschschieht: `n`1&#0096;1 `2&#0096;2 `3&#0096;3 `4&#0096;4 `5&#0096;5 `6&#0096;6 `7&#0096;7 `8&#0096;8 `9&#0096;9 ",true);
				output("`n`!&#0096;! `@&#0096;@ `#&#0096;# `\$&#0096;\$ `%&#0096;% `^&#0096;^ `q&#0096;q `Q&#0096;Q `&&#0096;& `n",true);
				output("`T&#0096;T `t&#0096;t `R&#0096;R `r&#0096;r `V&#0096;V `v&#0096;v `g&#0096;g`n",true);
				output("`% kapiert?`0\" Hier kannst du testen:");
				output("<form action=\"$REQUEST_URI\" method='POST'>",true);
				output("Deine Eingabe: ".str_replace("`","&#0096;",HTMLEntities($HTTP_POST_VARS[testtext]))."`n",true);
				output("Sieht so aus: ".$HTTP_POST_VARS[testtext]." `n");
				output("<input name='testtext'><input type='submit' class='button' value='Test'></form>",true);
				output("`0`n`nDu kannst diese Farben in jedem Text verwenden, den du eingibst.");
				addnav("",$REQUEST_URI);
	}else if($HTTP_GET_VARS[what]=="enemies"){
		if ($HTTP_GET_VARS[who]==""){
			output("\"`7Scho, du willscht alscho etwasch über deine Feinde erfahren, ja? Über wen willscht du wasch wischen? Nun? Schag schon! Esch koschtet nur `^100`7 Gold pro Information über eine Perschon.`0\"");
			if ($_GET['subop']!="search"){
				output("<form action='forest.php?op=bartender&what=enemies&subop=search' method='POST'><input name='name'><input type='submit' class='button' value='Suchen'></form>",true);
				addnav("","forest.php?op=bartender&what=enemies&subop=search");
			}else{
				addnav("Neue Suche","forest.php?op=bartender&what=enemies");
				$search = "%";
				for ($i=0;$i<strlen($_POST['name']);$i++){
					$search.=substr($_POST['name'],$i,1)."%";
				}
				$sql = "SELECT name,alive,location,sex,level,laston,loggedin,login FROM accounts WHERE (locked=0 AND name LIKE '$search') ORDER BY level DESC";
				//output($sql);
				$result = db_query($sql) or die(db_error(LINK));
				$max = db_num_rows($result);
				if ($max > 100) {
					output("`n`n\"`7Hey, wasch glaubscht du tuscht du hier? Dasch schind tschu viele Namen. Ich werd dir nur über ein paar davon wasch ertschählen.`0`n");
					$max = 100;
				}
				output("<table border=0 cellpadding=0><tr><td>Name</td><td>Level</td></tr>",true);
				for ($i=0;$i<$max;$i++){
					$row = db_fetch_assoc($result);
					output("<tr><td><a href='forest.php?op=bartender&what=enemies&who=".rawurlencode($row[login])."'>$row[name]</a></td><td>$row[level]</td></tr>",true);
					addnav("","forest.php?op=bartender&what=enemies&who=".rawurlencode($row[login]));
				}
				output("</table>",true);
			}
		}else{
			if ($session[user][gold]>=100){
				$sql = "SELECT acctid,name,alive,location,maxhitpoints,gold,sex,level,weapon,armor,attack,defence,race,charm FROM accounts WHERE login=\"$HTTP_GET_VARS[who]\"";
				$result = db_query($sql) or die(db_error(LINK));
				if (db_num_rows($result)>0){
					$row = db_fetch_assoc($result);
					$row[name]=str_replace("s","sch",$row[name]);
					$row[armor]=str_replace("s","sch",$row[armor]);
					$row[weapon]=str_replace("s","sch",$row[weapon]);	
					output("\"`7Nun ... mal schehen wasch ich über ".str_replace("s","sch",$row[name])."`7 weisch...`0\"`n`n");
					output("`4`bName:`b`6 $row[name]`n");
					output("`4`bRasse:`b");
					switch($row['race']){
						case 0:
						output("`7Unbekannt`0");
						break;
						case 1:
						output("`2Troll`0");
						break;
						case 2:
						output("`^Elf`0");
						break;
						case 3:
						output("`0Mensch`0");
						break;
						case 4:
						output("`#Zwerg`0");
						break;
						case 5:
						output("`5Echse`0");
						break;
					}
					output("`n`4`bLevel:`b`6 $row[level]`n");
					output("`4`bLebenspunkte:`b`6 $row[maxhitpoints]`n");
					output("`4`bGold:`b`6 $row[gold]`n");
					output("`4`bWaffe:`b`6 $row[weapon]`n");
					output("`4`bRüstung:`b`6 $row[armor]`n");
					output("`4`bAngriffswert:`b`6 $row[attack]`n");
					output("`4`bVerteidigung:`b`6 $row[defence]`n");
					output("`n`7Auscherdem ischt $row[name] `7");
					$amt=$session[user][charm];
					if ($amt==$row[charm]) { output("dir schehr ähnlich.`n");}
					else if ($amt-10>$row[charm]) { output("`bviel`b häschlicher alsch du!`n");}
					else if ($amt>$row[charm]) { output("häschlicher alsch du.`n");}
					else if ($amt+10<$row[charm]) { output("`bviel`b schöner alsch du!`n");}
					else {output("schöner alsch du.`n");}
					$session[user][gold]-=100;
					//debuglog("spent 100 gold to learn about an enemy");
					if ($session[user][gold]>150){
						output("`7Wenn du nochmal `^150`7 Gold drauflegscht, schag ich dir schogar, wo schich $row[name]`7 aufhält.`0\"");
						addnav("Aufenthaltsort (`^150`0 Gold)","forest.php?op=bartender&what=where&who=$row[acctid]");
					}
					
				}else{
					output("\"`7Hä? Ich kenne niemanden mit dieschem Namen.`0\"");
				}
			}else{
				output("\"`7Nun ... mal schehen wasch ich über Geitschkragen wie dich allesch weisch...`0\"`n`n");
				output("`4`bName:`b`6 Ohnemo Snixlos`n");
				output("`4`bLevel:`b`6 Ganz unten`n");
				output("`4`bLebenspunkte:`b`6 Möglicherweische mehr alsch du`n");
				output("`4`bGold:`b`6 Gantsch schicher mehr alsch du`n");
				output("`4`bWaffe:`b`6 Etwasch, dasch gut genug ischt, dich ungespitscht in den Boden tschu rammen`n");
				output("`4`bRüstung:`b`6 Kleidschamer alsch deine`n");
				output("`4`bAngriffswert:`b`6 Drölf Milliarden`n");
				output("`4`bVerteidigung:`b`6 Super Duper`n");
			}
		}
	}else if($HTTP_GET_VARS[what]=="where"){
		$sql="SELECT acctid,name,location,loggedin,laston,alive,housekey FROM accounts WHERE acctid=".$HTTP_GET_VARS[who];
		$result = db_query($sql) or die(db_error(LINK));
		$row = db_fetch_assoc($result);
		$loggedin=(date("U") - strtotime($row[laston]) < getsetting("LOGINTIMEOUT",900) && $row[loggedin]);
		if ($row[location]==0) $loc=($loggedin?"Online":"in den Feldern");
		if ($row[location]==1) $loc="in einem Zimmer in der Kneipe";
		// part from houses.php
		if ($row[location]==2){
			$sql="SELECT hvalue FROM items WHERE class='Schlüssel' AND owner=$row[acctid] AND hvalue>0";
			$result = db_query($sql) or die(db_error(LINK));
			$row2 = db_fetch_assoc($result);
			$loc="im Hausch Nummer ".($row2[hvalue]?$row2[hvalue]:$row[housekey])."";
		}
		// end houses
		output("\"`7$row[name]`7 wurde zuletzt $loc geschehen".($row[alive]?"":", ischt inzwischen aber ... geschtorben ... worden")."!`0\"");
		$session[user][gold]-=150;
	}
	addnav("Zurück zur Taverne","forest.php?op=tavern");
	break;
case "oldman":
	addnav("Alter Mann");
	switch($HTTP_GET_VARS[game]){
	case "":
		checkday();
		output("Der alte Mann schaut mit tiefliegenden und hohlen Augen zu dir auf. Seine roten Augen lassen darauf schließen, dass er erst kürzlich geweint hat,");
		output("deswegen fragst du ihn, was geschehen ist. \"`7Ach, Ich hab nen Abenteurer im Wald getroffen und hab gedacht ich spiel n kleines Spiel ");
		output("mit ".($session[user][sex]?"ihr":"ihm").  ", aber ".($session[user][sex]?"sie":"er")." hat immer gewonnen und mir fast ");
		output("mein ganzen Gold aus der Tasche gezogen.");
		output("`n`n`nSag, würdest du einem alten Mann den Gefallen tun und ihn versuchen lassen, etwas von dem Gold von dir zurückzugewinnen? Ich kenne mehrere");
		output(" Spiele!`0\"");
		$session[user][specialinc]="darkhorse.php";
		$session['user']['specialmisc']="";
		addnav("W?Würfelspiel","forest.php?op=oldman&game=dice");
		addnav("S?Steinchenspiel","forest.php?op=oldman&game=stones");
		addnav("Glücksspiel","stonesgame.php");
	
		addnav("Zurück zur Taverne","forest.php?op=tavern");
		break;
	case "stones":
		$stones = unserialize($session['user']['specialmisc']);
		if (!is_array($stones)) $stones = array();
		if ($_GET['side']=="likepair") $stones['side']="likepair";
		if ($_GET['side']=="unlikepair") $stones['side']="unlikepair";
		if (isset($_POST['bet'])) $stones['bet']=min($session['user']['gold'],abs((int)$_POST['bet']));
		if ($stones['side']==""){
			output("`3Der alte Mann erklärt sein Spiel: \"`7Ich habe einen Beutel mit 6 roten und 10 blauen Steinen darin. Du kannst zwischen 'gleiches Paar' und 'ungleiches Paar' wählen. Ich werde");
			output("dann jedesmal 2 Steine ziehen. Wenn sie die selbe Farbe haben, bekommt der die Steine, der 'gleiches Paar' getippt hat, ");
			output("ansonsten bekommt sie der von uns, der 'ungleiches Paar' getippt hat. Wer am ende die meisten Steine hat, gewinnt. Wenn wir Gleichstand haben,");
			output("gewinnt keiner von uns.`3\"");
			addnav("Lieber nicht","forest.php?op=oldman");
			addnav("Gleiches Paar","forest.php?op=oldman&game=stones&side=likepair");
			addnav("Ungleiches Paar","forest.php?op=oldman&game=stones&side=unlikepair");
			$stones['red']=6;
			$stones['blue']=10;
			$stones['player']=0;
			$stones['oldman']=0;
		}elseif ($stones['bet']==0){
			output("`3\"`7".($stones['side']=="likepair"?"Gleiches Paar für dich, dann ist ein ungleiches ":"Ungleiches Paar für dich, dann ist ein gleiches ")." Paar für mich!");
			output("Wie hoch ist dein Einsatz?`3\"");
			output("<form action='forest.php?op=oldman&game=stones' method='POST'><input name='bet' id='bet'><input type='submit' class='button' value='Setzen'></form>",true);
			output("<script language='JavaScript'>document.getElementById('bet').focus();</script>",true);
			addnav("","forest.php?op=oldman&game=stones");
			addnav("Lieber nicht","forest.php?op=oldman");
		}elseif ($stones['red']+$stones['blue'] > 0 && $stones['oldman']<=8 && $stones['player']<=8){
			$s1=""; $s2="";
			$rstone = "`\$rot`3";
			$bstone = "`!blau`3";
			while ($s1=="" || $s2==""){
				$s1 = e_rand(1,($stones['red']+$stones['blue']));
				if ($s1<=$stones['red']) {
					$s1=$rstone;
					$stones['red']--;
				}else{
					$s1=$bstone;
					$stones['blue']--;
				}
				if ($s2=="") {$s2=$s1; $s1="";}
			}
			output("`3Der alte Mann greift in seinen Beutel und nimmt 2 Steine heraus. Sie sind $s1 und $s2.  Deine Wette ist `^{$stones['bet']}`3.`n`n");
			if ($stones['side']=="likepair"){
				output("Da du auf ein gleiches Paar getippt hast, ");
				if ($s1==$s2) {
					output("schiebt der alte Mann dir die Steine zu.");
					$stones['player']+=2;
				}else{
					output("legt der alte Mann die Steine auf seine Seite.");
					$stones['oldman']+=2;
				}
			}else{
				output("Da du auf ein ungleiches Paar getippt hast, ");
				if ($s1==$s2) {
					output("legt der alte Mann die Steine auf seine Seite.");
					$stones['oldman']+=2;
				}else{
					output("schiebt der alte Mann dir die Steine zu.");
					$stones['player']+=2;
				}
			}
			output("`n`nDu hast momentan `^{$stones['player']}`3 Steine in deinem Haufen und der alte Mann hat `^{$stones['oldman']}`3 Steine.");
			output("`n`nEs sind noch {$stones['red']} {$rstone}e Steine und {$stones['blue']} {$bstone}e Steine im Beutel.");
			addnav("Weiter","forest.php?op=oldman&game=stones");
		}else{
			if ($stones['player']>$stones['oldman']){
				output("`3Du hast den alten Mann besiegt und verlangst deine `^{$stones['bet']}`3 Gold.");
				$session['user']['gold']+=$stones['bet'];
				//debuglog("won {$stones['bet']} gold in the stones game");
			}elseif ($stones['player']<$stones['oldman']){
				output("`3Der alte Mann hat dich besiegt und fordert nun seine `^{$stones['bet']}`3 Gold.");
				$session['user']['gold']-=$stones['bet'];
				//debuglog("lost {$stones['bet']} gold in the stones game");
			}else{
				output("`3Ihr habt einen Gleichstand erreicht. Niemand gewinnt.");
			}
			$stones=array();
			addnav("Nochmal spielen?","forest.php?op=oldman&game=stones");
			addnav("Andere Spiele","forest.php?op=oldman");
			addnav("Zurück zur Taverne","forest.php?op=tavern");
		}
		$session['user']['specialmisc']=serialize($stones);
		break;
	case "guess":
		if ($session[user][gold]>0){
			$bet = abs((int)$HTTP_GET_VARS[bet] + (int)$HTTP_POST_VARS[bet]);
			if ($bet<=0){
				output("`3\"`!Ich denke mir eine Zahl aus und du hast 6 Versuche, diese Zahl zwischen 1 und 100 zu erraten. Ich werde dir immer sagen, ob dein Versuch zu hoch oder zu niedrig war.`3\"`n`n");
				output("`3\"`!Wie hoch ist ein Einsatz, ".($session[user][sex]?"junge Dame":"junger Mann")."?`3\"");
				output("<form action='forest.php?op=oldman&game=guess' method='POST'><input name='bet'><input type='submit' class='button' value='Setzen'></form>",true);
				addnav("","forest.php?op=oldman&game=guess");
				$session[user][specialmisc]=e_rand(1,100);
			}else if($bet>$session[user][gold]){
				output("`3Der alte Mann streckt seinen Stock aus und klopft damit deinen Goldbeutel ab. \"`!Ich glaub nicht, daß da `^$bet`! Gold drin ist!`3\", erklärt er.`n`n");
				output("Verzweifelt versuchst du ihm deinen guten Willen zu zeigen und kippst den Beutelinhalt auf den Tisch: `^".$session[user][gold]."`3 Gold.");
				output("`n`nVerlegen kehrst du zur Taverne zurück.");
				addnav("Zurück zur Taverne","forest.php?op=tavern");
			}else{
				if ($HTTP_POST_VARS[guess]!==NULL){
					$try = (int)$HTTP_GET_VARS['try'];
					if ($HTTP_POST_VARS[guess]==$session[user][specialmisc]){
						if ($try == 1) {
							output("`3\"`!UNGLAUBLICH`3\", schreit der alte Mann, \"`!Du hast meine Zahl mit nur `^einem Versuch`! erraten! Nun, ich gratuliere dir. Ich bin stark beeindruckt. Es ist gerade so, als ob du meine Gedanken lesen könntest.`3\" Er schaut dich misstrauisch eine Weile an und überlegt, ob er sich mit deinem Gewinn einfach aus dem Staub machen soll, erinnert sich dann aber an deine scheinbaren geistigen Kräfte und händigt dir deine `^$bet`3 Gold aus.");
						} else {
							output("`3\"`!AAAH!!!!`3\", schreit der alte Mann, \"`!Du hast die Zahl mit nur $try Versuchen erraten!  Es war `^".$session[user][specialmisc]."`!!!  Nun, ich gratuliere dir  ");
							output("und denke ich werde jetzt besser gehen... `3\" Er steht auf und will zur Tür gehen, doch mit einem sanften Schlag mit ".$session[user][weapon]);
							output(" drückst du ihn zurück auf seinen Stuhl. Du hilfst dem Mann dabei, dir die `^$bet`3 Goldmünzen zu geben, die er dir schuldet.");
						}
						$session[user][gold]+=$bet;
						//debuglog("won $bet gold in the guessing game");
						$session[user][specialinc]="darkhorse.php";
						addnav("Zurück zur Taverne","forest.php?op=tavern");
					}else{
						if ($HTTP_GET_VARS['try']>=6){
							output("`3Der Mann gluckst vor Freude: \"`!Die Zahl war `^".$session[user][specialmisc]."`!`3\"  Als der ehrenwerte Bürger, der du bist, ");
							output("gibst du dem Mann die `^$bet`3 Goldmünzen, die du ihm schuldest, bereit, ihn zu verlassen.");
							$session[user][specialinc]="darkhorse.php";
							$session[user][gold]-=$bet;
							//debuglog("lost $bet gold in the guessing game");
							addnav("Zurück zur Taverne","forest.php?op=tavern");
						}else{
							if ((int)$HTTP_POST_VARS[guess]>$session[user][specialmisc]){
								output("`3\"`!Nop, nicht `^".(int)$HTTP_POST_VARS[guess]."`!,meine Zahl ist kleiner als das!  Das war dein `^$try`!ter Versuch.`3\"`n`n");
							}else{
								output("`3\"`!Nop, nicht `^".(int)$HTTP_POST_VARS[guess]."`!, meine Zahl ist größer als das!  Das war dein `^$try`!ter Versuch.`3\"`n`n");
							}
							output("`3Du hast `^$bet`3 Gold gesetzt.  Was schätzt du?");
							output("<form action='forest.php?op=oldman&game=guess&bet=$bet&try=".(++$try)."' method='POST'><input name='guess'><input type='submit' class='button' value='Rate'></form>",true);
							addnav("","forest.php?op=oldman&game=guess&bet=$bet&try=$try");
						}
					}
				}else{
					output("`3Du hast `^$bet`3 Gold gesetzt.  Was schätzt du?");
					output("<form action='forest.php?op=oldman&game=guess&bet=$bet&try=1' method='POST'><input name='guess'><input type='submit' class='button' value='Rate'></form>",true);
					addnav("","forest.php?op=oldman&game=guess&bet=$bet&try=1");
				}
			}
		}else{
			output("`3Der alte Mann streckt seinen Stock aus und klopft deinen Goldbeutel ab. \"`!Leer?!?! Wie kannst du etwas setzen ohne Gold??`3\", brüllt er.");
			output("  Damit wendet er sich wieder seinen Würfeln zu. Seinen Ärger hat er offensichtlich schon wieder vergessen.");
			addnav("Zurück zur Taverne","forest.php?op=tavern");
			//$session[user][specialinc]="darkhorse.php";
		}
		break;
	case "dice":
		if ($session[user][gold]>0){
			$bet = abs((int)$HTTP_GET_VARS[bet] + (int)$HTTP_POST_VARS[bet]);
			if ($bet<=0){
				output("`3\"`!Du würfelst und wählst dann, ob du die Zahl behalten willst, oder ob du nochmal würfeln willst. Du hast insgesamt 3 Versuche. ");
				output(" Danach, oder nachdem du eine Zahl behalten hast, werde ich das selbe machen.  ");
				output("Wer am Ende die höhere Zahl gewürfelt und behalten hat, gewinnt. Bei Gleichstand ");
				output("gewinnt keiner von uns.`3\"`n`n");
				output("`3\"`!Wieviel willst du setzen, ".($session[user][sex]?"junge Dame":"junger Mann")."?`3\"");
				output("<form action='forest.php?op=oldman&game=dice' method='POST'><input name='bet'><input type='submit' class='button' value='Setzen'></form>",true);
				addnav("","forest.php?op=oldman&game=dice");
			}else if($bet>$session[user][gold]){
				output("`3Der alte Mann streckt seinen Stock aus und klopft damit deinen Golsbeutel ab. \"`!Ich glaub nicht, daß da `^$bet`! Gold drin ist!`3\", erklärt er.`n`n");
				output("Verzweifelt versuchst du ihm deinen guten Willen zu zeigen und kippst den Beutelinhalt auf den Tisch: `^".$session[user][gold]."`3 Gold.");
				output("`n`nVerlegen kehrst du zur Taverne zurück.");
				addnav("Zurück zur Taverne","forest.php?op=tavern");
			}else{
				if ($HTTP_GET_VARS[what]!="keep"){
					$session[user][specialmisc]=e_rand(1,6);
					$try=$HTTP_GET_VARS['try'];
					$try++;
					output("Du würfelst deinen ".($try==1?"ersten":($try==2?"zweiten":"dritten"))." Versuch und es erscheint die `b".$session[user][specialmisc]."`b`n`n");
					output("`3Du hast `^$bet`3 Gold gesetzt.  Was machst du?");
					addnav("Behalten","forest.php?op=oldman&game=dice&what=keep&bet=$bet");
					if ($try<3) addnav("Neu würfeln","forest.php?op=oldman&game=dice&what=pass&try=$try&bet=$bet");
				}else{
					output("Dein endgültiger Wurf war `b".$session[user][specialmisc]."`b, der alte Mann wird jetzt versuchen, das zu überbieten:`n`n");
					$r = e_rand(1,6);
					output("Der alte Mann wirft eine $r...`n");
					if ($r>$session[user][specialmisc] || $r==6){
						output("\"`7Ich glaub ich behalte diesen Wurf!`0\"`n");
					}else{
						$r = e_rand(1,6);
						output("Der alte Mann würfelt nochmal und bekommt eine $r...`n");
						if ($r>=$session[user][specialmisc]){
							output("\"`7Ich glaub ich behalte diesen Wurf!`0\"`n");
						}else{
							$r = e_rand(1,6);
							output("Der alte Mann wirft seinen letzten Versuch und bekommt eine $r...`n");
						}
					}
					if ($r>$session[user][specialmisc]){
						output("`n\"`7Juuhuu! Ich wusste, dass deinesgleichen niemals über meinesgleichen siegen kann!`0\", ruft der Mann, während du ihm gibst, was du ihm schuldest: `^$bet`0 Gold.");
						$session[user][gold]-=$bet;
						//debuglog("lost $bet gold at dice");
					}else{
						if ($r==$session[user][specialmisc]){
							output("`n\"`7Tja... nun, sieht so aus, als ob wir ein Unentschieden hätten.`0\"");
						}else{
							output("`n\"`7Waaaaaah!! Wie konnte jemand wie du mich besiegen?!?!?`0\" schreit der alte Mann, während er dir gibt, was er dir schuldet.");
							$session[user][gold]+=$bet;
							//debuglog("won $bet gold at dice");
						}
					}
					addnav("Zurück zur Taverne","forest.php?op=tavern");
				}
			}
		}else{
			output("`3Der alte Mann streckt seinen Stock aus und klopft deinen Goldbeutel ab. \"`!Leer?!?! Wie kannst du etwas setzen ohne Gold??`3\", brüllt er.");
			output("  Damit wendet er sich wieder seinen Würfeln zu. Seinen Ärger hat er offensichtlich schon wieder vergessen.");
			addnav("Zurück zur Taverne","forest.php?op=tavern");
		}
		break;	
	}
	break; //end of old man.
case "leave":
case "leaveleave":
	output("Du verlässt die Taverne und läufst durch das dichte Blattwerk. Der seltsame Nebel ");
	output("ist wieder aufgezogen und verwirrt deine Sinne. Als du den Nebel verläßt, befindest du dich wieder im Wald an einer ");
	output("dir vertrauten Stelle. Aber wie genau du zu dieser Taverne gekommen bist, ist dir nicht klarer geworden.");
	$session[user][specialinc]="";
	$session[user][specialmisc]="";
	addnav("Zurück in den Wald","forest.php");
	break;
/*
case "leaveleave":
	$session[user][specialinc]="";
	$session[user][specialmisc]=""; 
	redirect("forest.php");
*/
default:
	checkday();
	output("Eine Baumgruppe in der Nähe kommt dir bekannt vor... Du bist dir sicher, diesen Platz schoneinmal gesehen zu haben.  ");
	output("Als du näher kommst, steigt ein seltsamer Nebel auf. Dein Gedächtnis spielt dir Streiche ");
	output("und du bist dir plötzlich gar nicht mehr sicher, wie du hier her gekommen bist. ");
	if ($playermount['tavern']>0) output("Aber dein Pferd scheint den Weg zu kennen."); // BoarVolk's idea
	output("`n`nDer Nebel verschwindet und vor dir siehst du ");
	output("eine Holzhütte, bei der Rauch aus dem Kamin aufsteigt. Auf einem Schild über der Tür steht: \"`7Dark Horse Taverne`0\".");
	addnav("T?Betrete die Taverne","forest.php?op=tavern");
	addnav("Verlasse diesen Ort","forest.php?op=leaveleave");
	$session[user][specialinc]="darkhorse.php";
	break;	
}
output("</span>",true);
?>
