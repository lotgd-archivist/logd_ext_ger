<?php

// 15082004

require_once "common.php";
addcommentary();
checkday();

if ($session[user][slainby]!=""){
	page_header("Du wurdest besiegt!");
		output("`\$Du wurdest in ".$session[user][killedin]."`\$ von `%".$session[user][slainby]."`\$ besiegt und um alles Gold beraubt, das du bei dir hattest. Das kostet dich 5% deiner Erfahrung. Meinst du nicht, es ist Zeit für Rache?");
	addnav("Weiter",$REQUEST_URI);
	$session[user][slainby]="";
	$session['user']['donation']+=1;
	page_footer();
}

$buff = array("name"=>"`!Schutz der Liebe","rounds"=>60,"wearoff"=>"`!Du vermisst ".($session['user']['sex']?"Seth":"`5Violet`")."!.`0","defmod"=>1.2,"roundmsg"=>"Deine große Liebe lässt dich an deine Sicherheit denken!","activate"=>"defense");

page_header("Schenke zum Eberkopf");
output("<span style='color: #9900FF'>",true);
output("`c`bSchenke zum Eberkopf`b`c");
if ($HTTP_GET_VARS[op]=="strolldown"){
	output("Wiedermal bereit für's Abenteuer schlenderst du die Treppen der Schenke runter!  ");
}
if ($HTTP_GET_VARS[op]==""){
	output("Du tauchst in eine schummerige Schenke ab, die du sehr gut kennst. Der stechende Geruch von Pfeifentabak erfüllt ");
	output("die Luft.");
}
if ($HTTP_GET_VARS[op]=="" || $HTTP_GET_VARS[op]=="strolldown"){
	output("  Du winkst einigen deiner Kumpels und zwinkerst ".
				($session[user][sex]?
				"`^Seth`0 zu, der seine Harfe beim Feuer stimmt.":
				"`5Violet`0 zu, die ein paar einheimischen Ale serviert.").
				" Der Barkeeper Cedrik steht hinter seiner Theke und quatscht mit irgendjemandem. Du kannst nicht genau verstehen "
				." was er sagt, aber es ist irgendwas über ");

	switch (e_rand(1,16)){
		case 1:
			output("Drachen.");
			break;
		case 2:
			output("Seth.");
			break;
		case 3:
			output("Violet.");
			break;
		case 4:
			output("MightyE.");
			break;
		case 5:
			output("leckeres Ale.");
			break;
		case 6:
			output("anpera.");
			break;
		case 7:
			output("Reandor.");
			break;
		case 8:
			output("Kala.");
			break;
		case 9:
			output("Zwergenweitwurf.");
			break;
		case 10:
			output("das elementare Zerwürfnis des Seins.");
			break;
		case 11:
			output("häufig gestellte Fragen.");
			break;
		case 12:
			output("Manwe und bibir.");
			break;
		default:
			$row = db_fetch_assoc(db_query("SELECT name FROM accounts WHERE locked=0 ORDER BY rand(".e_rand().") LIMIT 1"));
			output("`%$row[name]`0.");
			break;
	}
	if (getsetting("pvp",1)) {
		output(" Dag Durnick sitzt übel gelaunt mit einer Pfeife fest im Mund in der Ecke. ");
	}
	output("`n`nDie Uhr am Kamin zeigt `6".getgametime()."`0.");
	
	$sql = "UPDATE accounts SET message='',msgdate='0000-00-00 00:00:00' WHERE message>'' AND msgdate<'".date("Y-m-d H:i:s")."'";
	db_query($sql);
	output("`n`n");
	$sql = "SELECT acctid,login,name,message,msgdate FROM accounts WHERE message>'' ORDER BY msgdate DESC";
	$result = db_query($sql) or die(db_error(LINK));
	if (db_num_rows($result)<=0){
		output("Am schwarzen Brett neben der Tür ist nicht eine einzige Nachricht zu sehen.");
	}else{
		output("Am schwarzen Brett neben der Tür flattern einige Nachrichten im Luftzug:");
		for ($i=0;$i<db_num_rows($result);$i++){
			$row = db_fetch_assoc($result);
			output("`n`n<a href=\"mail.php?op=write&to=".rawurlencode($row['login'])."\" target=\"_blank\" onClick=\"".popup("mail.php?op=write&to=".rawurlencode($row['login'])."").";return false;\"><img src='images/newscroll.GIF' width='16' height='16' alt='Mail schreiben' border='0'></a>",true);
			output("`& $row[name]`&:`n`^$row[message]`0");
			if ($row[acctid]==$session[user][acctid]){
				output("[<a href='inn.php?op=msgboard&act=del'>entfernen</a>]",true);
				addnav("","inn.php?op=msgboard&act=del");
			}
		}
	}

	addnav("Was machst du?");
	if ($session[user][sex]==0) addnav("V?Flirte mit Violet","inn.php?op=violet");
	if ($session[user][sex]==1) addnav("V?Quatsche mit Violet","inn.php?op=violet");
	addnav("S?Rede mit dem Barden Seth","inn.php?op=seth");
	addnav("Mit Freunden unterhalten","inn.php?op=converse");
	addnav("B?Spreche mit Barkeeper Cedrik","inn.php?op=bartender");
	if (getsetting("pvp",1)) {
		addnav("D?Mit Dag Durnick sprechen","dag.php");
	}
                addnav("O?Mit Old Drawl sprechen","olddrawl.php"); 
	addnav("Sonstiges");
	// addnav("Lotterie","lottery.php"); // siehe old drawl
	addnav("n?Zimmer nehmen (Log out)","inn.php?op=room");
	addnav("Zurück zum Dorf","village.php");
}else{
  switch($HTTP_GET_VARS[op]){
	case "msgboard":
	if ($_GET[act]=="del"){
		$session[user][message]="";
		$session[user][msgdate]="0000-00-00 00:00:00";
		output("Du reisst deine eigene Nachricht vom schwarzen Brett ab. Der Fall hat sich für dich erledigt.");
		addnav("Neue Nachricht","inn.php?op=msgboard"); 
	}else if ($_GET[act]=="add1"){
		$msgprice=$session[user][level]*6*(int)$_GET[amt];
		output("Cedrik kramt einen Zettel und einen Stift unter der Theke hervor und schaut dich fragend an, was er für dich schreiben soll. Offenbar ");
		output("sind viele seiner Kunden der Kunst des Schreibens nicht mächtig. \"`%Das macht dann `^$msgprice`% Gold. Wie soll die Nachricht lauten?`0\"`n`n");
		output("<form action=\"inn.php?op=msgboard&act=add2&amt=$_GET[amt]\" method='POST'>",true);
		output("`nGib deine Nachricht ein:`n<input name='msg' maxlength='250' size='50'>`n",true);
		output("<input type='submit' class='button' value='Ans schwarze Brett'>",true);
		addnav("","inn.php?op=msgboard&act=add2&amt=$_GET[amt]");
	}else if ($_GET[act]=="add2"){
		$msgprice=$session[user][level]*6*(int)$_GET[amt];
		$msgdate=date("Y-m-d H:i:s",strtotime(date("r")."+$_GET[amt] days"));
		if ($session[user][gold]<$msgprice){
			output("Als Cedrik bemerkt, dass du offensichtlich nicht genug Gold hast, schnauzt er dich an: \"`%So kommen wir nicht ins Geschäft, Kleine".($session[user][sex]?"":"r").". Sieh zu, dass du Land gewinnst. Oder im Lotto.`0\"");
		}else{
			output("Mürrisch nimmt Cedrik dein Gold, schreibt deinen Text auf den Zettel und ohne ihn nochmal durchzulesen, heftet er ihn zu den anderen an das schwarze Brett neben der Eingangstür.");
			$session[user][message]=stripslashes($_POST[msg]);
			$session[user][msgdate]=$msgdate;
			$session[user][gold]-=$msgprice;
		}
	}else{
		$msgprice=$session[user][level]*6;
		$msgdays=(int)getsetting("daysperday",4);
		output("\"`%Du möchtest eine Nachricht am schwarzen Brett hinterlassen, ja? Wie lang soll die Nachricht denn dort zu sehen sein?`0\" fragt dich Cedrik fordernd und nennt die Preise.");
		addnav("$msgdays Tage (`^$msgprice`0 Gold)","inn.php?op=msgboard&act=add1&amt=1");
		addnav("".($msgdays*3)." Tage (`^".($msgprice*3)."`0 Gold)","inn.php?op=msgboard&act=add1&amt=3");
		addnav("".($msgdays*10)." Tage (`^".($msgprice*10)."`0 Gold)","inn.php?op=msgboard&act=add1&amt=10");
		if ($session[user][message]>"") output("`nEr macht dich noch darauf aufmerksam, dass er deine alte Nachricht entfernen wird, wenn du jetzt eine neue anbringen willst.");
	}
	break;
	  case "violet":
			/*
			Wink
			Kiss her hand
			Peck her on the lips
			Sit her on your lap
			Grab her backside
			Carry her upstairs
			Marry her
			*/
			if ($session[user][sex]==1){
				if ($HTTP_GET_VARS[act]==""){
					addnav("Tratsch","inn.php?op=violet&act=gossip");
					addnav("Frage, ob dich dein ".$session[user][armor]." dick aussehen lässt","inn.php?op=violet&act=fat");
					output("Du gehst rüber zu `5Violet`0 und hilfst ihr dabei, ein paar Ales zu servieren. Als sie alle ausgeteilt sind, ");
					output("wischt sie sich mit einem Lappen den Schweiß von der Stirn und dankt dir herzlich. Natürlich war es ");
					output("für dich selbstverständlich, schließlich ist sie eine deiner ältesten und besten Freundinnen!");
				}else if($HTTP_GET_VARS[act]=="gossip"){
					output("Für ein paar Minuten tratschst du mit `5Violet`0 über alles und nichts. Sie bietet dir eine Essiggurke an. ");
					output("Das liegt in ihrer Natur, da sie früher Gurken angebaut und verkauft hat. Du nimmst an. Nach ein paar Minuten ");
					output("bemerkst du die brennenden Blicke, die Cedrik immer häufiger in eure Richtung wirft und du beschließt, dass es besser ist, Violet wieder ihre Arbeit machen zu lassen. ");
				}else if($HTTP_GET_VARS[act]=="fat"){
					$charm = $session[user][charm]+e_rand(-1,1);
					output("Violet schaut dich ernst von oben bis unten an. Nur ein echter Freund kann wirklich ehrlich sein und genau deswegen ");
					output("hast du sie gefragt. Schließlich fasst sie einen Entschluss und sagt: \"`%");
					switch($charm){
						case -3:
						case -2:
						case -1:
						case 0:
							output("Dein Outfit lässt nicht viel Spielraum für Fantasie, aber über manche Dinge sollte man auch wirklich nicht nachdenken. Du solltest etwas weniger freizügige Kleidung in der Öffentlichkeit tragen!");
							break;
						case 1:
						case 2:
						case 3:
							output("Ich habe schon einige reizvolle Damen gesehn, aber ich fürchte du bist keine davon.");
							break;
						case 4:
						case 5:
						case 6:

							output("Ich habe schon schlimmeres gesehen, aber nur beim Verfolgen eines Pferdes.");
							break;
						case 7:
						case 8:
						case 9:
							output("Du bist ziemlicher Durchschnitt, meine Gute.");
							break;
						case 10:
						case 11:
						case 12:
							output("Du bist schon etwas zum Anschauen, aber lass dir das nicht zu sehr zu Kopfe steigen, ja?");
							break;
						case 13:
						case 14:
						case 15:
							output("Du siehst schon ein bisschen besser aus als der Durchschnitt!");
							break;
						case 16:
						case 17:
						case 18:
							output("Nur wenige Frauen können von sich behaupten, sich mit dir messen zu können!");
							break;
						case 19:
						case 20:
						case 21:
						case 22:
							output("Willst du mich mit dieser Frage neidisch machen? Oder mich einfach nur ärgern?");
							break;
						case 23:
						case 24:
						case 25:
							output("Ich bin von deiner Schönheit geblendet.");
							break;
						case 26:
						case 27:
						case 28:
						case 29:
						case 30:
							output("Ich hasse dich. Warum? Weil du einfach die schönste Frau aller Zeiten bist!");
							break;
						default:
							output("Vielleicht solltest du langsam etwas gegen deine überirdische Schönheit tun. Du bist unerreichbar!");
					}
					output("`0\"");
				}
			}
			if ($session[user][sex]==0){
				  //$session[user][seenlover]=0;
			  if ($session[user][seenlover]==0){
			  	  if ($session['user']['marriedto']==4294967295){
				  	if (e_rand(1, 4)==1){
					  output("Du gehst rüber zu Violet um sie zu knuddeln und sie auf Gesicht und Hals zu küssen, aber sie brummelt nur etwas ");
					  switch(e_rand(1,4)){
					  case 1:
					  	output("davon, dass sie zu beschäftigt damit ist, diese Schweine zu bedienen. ");
						break;
					  case 2:
					  	output("wie \"diese Zeit des Monats\".");
						break;
					  case 3:
					  	output("wie \"eine   leichte   Erkältung...  *hust hust* .. siehst du?\". ");
					  	break;
					  case 4:
					  	output("darüber, dass alle Männer Schweine sind.");
						break;
					  }
					  output(" Nach so einem Kommentar lässt du sie stehen und haust ab!");
					  $session['user']['charm']--;
					  output("`n`n`^Du VERLIERST einen Charmepunkt!");
					} else {
						output("Du und `5Violet`0 nehmt euch etwas Zeit für euch selbst und du verlässt die Schenke zuversichtlich strahlend!");
						$session['bufflist']['lover']=$buff;
						$session['user']['charm']++;
						output("`n`n`^Du erhältst einen Charmepunkt!");
					}
					$session['user']['seenlover']=1;
				  } elseif ($HTTP_GET_VARS[flirt]==""){
						output("Du starrst verträumt durch den Raum auf `5Violet`0, die sich über einen Tisch beugt, ");
						output("um einem Gast einen Drink zu servieren. Dabei zeigt sie vielleicht etwas mehr Haut als ");
						output("nötig, aber du fühlst absolut keinen Drang danach, ihr das vorzuhalten.");
						addnav("Flirt");
						addnav("Zwinkern","inn.php?op=violet&flirt=1");
						addnav("Handkuss","inn.php?op=violet&flirt=2");
						addnav("Küsschen auf die Lippen","inn.php?op=violet&flirt=3");
						addnav("Setze sie auf deinen Schoß","inn.php?op=violet&flirt=4");
						addnav("Greif ihr an den Hintern","inn.php?op=violet&flirt=5");
						addnav("Trag sie nach oben","inn.php?op=violet&flirt=6");
						if ($session[user][charisma]!=4294967295) addnav("Heirate sie","inn.php?op=violet&flirt=7");
					}else{
					  $c = $session[user][charm];
						$session[user][seenlover]=1;
					  switch($HTTP_GET_VARS[flirt]){
						  case 1:
							  if (e_rand($c,2)>=2){
								  output("Du zwinkerst `5Violet`0 zu und sie gibt dir ein warmes Lächeln zurück.");
									if ($c<4) $c++;
								}else{
								  output("Du zwinkerst `5Violet`0 zu, doch sie tut so, als ob sie es nicht bemerkt hätte.");
								}
							  break;
						  case 2:
							  if (e_rand($c,4)>=4){
								  output("Selbstsicher schlenderst du Richtung `5Violet`0 durch den Raum. Du nimmst ihre Hand, ");
									output("küsst sie sanft und hältst so für einige Sekunden inne. `5Violet`0 ");
									output("errötet und streift eine Haarsträhne hinter ihr Ohr. Während du dich zurückziehst, presst sie ");
									output("die Rückseite ihrer Hand sehnsüchtig an ihre Wange.");
									if ($c<7) $c++;
								}else{
								  output("Selbstsicher schlenderst du Richtung `5Violet`0 durch den Raum und greifst nach ihrer Hand.  ");
									output("`n`nAber `5Violet`0 zieht ihre Hand rasch zurück und fragt dich, ob du vielleicht ein Ale haben willst.");
								}
							  break;
						  case 3:
							  if (e_rand($c,7)>=7){
								  output("Du lehnst mit deinem Rücken an einer hölzernen Säule und wartest, bis `5Violet`0 in ");
									output("deine Richtung läuft. Dann rufst du sie zu dir. Sie nähert sich dir mit der Andeutung eines Lächelns im Gesicht. ");
									output("Du fasst ihr Kinn, hebst es etwas und presst ihr einen schnellen Kuss auf ihre prallen ");
									output("Lippen.");
									if ($session[user][charisma]==4294967295) {
										output(" Deine Frau wird gar nicht begeistert sein, wenn sie davon erfährt!");
										$c--;
									} else {
										if ($c<11) $c++;
									}
								}else{
								  output("Du lehnst mit deinem Rücken an einer hölzernen Säule und wartest, bis `5Violet`0 in ");
									output("deine Richtung läuft. Dann rufst du sie zu dir. Sie lächelt und bedauert, dass sie ");
									output("mit ihrer Arbeit einfach zu beschäftigt ist, um sich einen Moment für dich Zeit zu nehmen.");
								}
							  break;
						  case 4:
							  if (e_rand($c,11)>=11){
	if (!$session['user']['prefs']['nosounds']) output("<embed src=\"media/giggle.wav\" width=10 height=10 autostart=true loop=false hidden=true volume=100>",true);	
								  output("Du sitzt an einem Tisch und lauerst auf deine Gelegenheit. Als `5Violet`0 bei dir vorbei kommt, ");
									output("umarmst du sie an der Hüfte und ziehst sie auf deinen Schoss. Sie lacht ");
									output("und wirft dir ihre Arme in einer warmen Umarmung um den Hals. Schließlich klopft sie dir auf die Brust ");
									output("und besteht darauf, dass sie wirklich wieder an die Arbeit gehen sollte.");
									if ($session[user][charisma]==4294967295) {
										output(" Deine Frau wird gar nicht begeistert sein, wenn sie davon erfährt!");
										$c--;
									} else {
										if ($c<14) $c++;
									}
								}else{
								  output("Du sitzt an einem Tisch und lauerst auf deine Gelegenheit. Als `5Violet`0 bei dir vorbei kommt, ");
									output("grapschst du nach ihrer Hüfte, aber sie weicht geschickt aus, ohne auch nur einen Tropfen von ");
									output("dem Ale zu verschütten, das sie trägt.");
									if ($c>0 && $c<10) $c--;
								}
							  break;
						  case 5:
							  if (e_rand($c,14)>=14){
								  output("Du wartest, bis `5Violet`0 an dir vorbeirauscht und gibst ihr einen Klaps auf den Hintern. Sie dreht sich um und ");
									output("gibt dir ein warmes, wissendes Lächeln.");
									if ($session[user][charisma]==4294967295) {
										$c--;
									} else {
										if ($c<18) $c++;
									}
								}else{
								  output("Du wartest, bis `5Violet`0 an dir vorbeirauscht und gibst ihr einen Klaps auf den Hintern. Sie dreht sich um und ");
									output("verpasst dir eine Ohrfeige. Eine kräftige! Vielleicht solltest du es etwas langsamer angehen.");
									//$session[user][hitpoints]=1;
									if ($c>0 && $c<13) $c--;
								}
							if ($session[user][charisma]==4294967295) output(" Deine Frau wird gar nicht begeistert sein, wenn sie davon erfährt!");
							  break;
						  case 6:
							  if (e_rand($c,18)>=18){
  							  output("Wie ein Wirbelwind braust du durch die Schenke, schnappst dir `5Violet`0, die dir ihre Arme ");
									output("um den Hals wirft, und trägst sie in ihren Raum nach oben. Kaum 10 Minuten später ");
									output("stolzierst du, eine Pfeife rauchend und bis zu den Ohren grinsend, die Treppe wieder runter.  ");
									if ($session['user']['turns']>0){
									  output("Du fühlst dich ausgelaugt!  ");
										$session['user']['turns']-=2;
										if ($session['user']['turns']<0) $session['user']['turns']=0;
									}
									addnews("`@Es wurde beobachtet, wie ".$session[user][name]."`@ und `5Violet`@ gemeinsam die Treppen in der Schenke nach oben gingen.");
									if ($session[user][charisma]==4294967295 && e_rand(1,3)==2) {
										  $sql = "SELECT acctid,name FROM accounts WHERE locked=0 AND acctid=".$session[user][marriedto]."";
  										$result = db_query($sql) or die(db_error(LINK));
										$row = db_fetch_assoc($result);
										$partner=$row[name];
										addnews("`\$$partner hat ".$session[user][name]."`\$ wegen eines Seitensprungs mit `5Violet`\$ verlassen!");
										output("`nDas war zu viel für $partner! Sie reicht die Scheidung ein. Die Hälfte deines Goldes auf der Bank wird ihr zugesprochen. Ab sofort bist du wieder solo!");
										$session[user][charisma]=0;
										$session[user][marriedto]=0;
										if ($session[user][goldinbank]>0) $getgold=round($session[user][goldinbank]/2);
										$session[user][goldinbank]-=$getgold;
										$sql = "UPDATE accounts SET charisma=0,marriedto=0,goldinbank=goldinbank+$getgold WHERE acctid='$row[acctid]'";
										db_query($sql);
										systemmail($row['acctid'],"`\$Seitensprung!`0","`&{$session['user']['name']}`6 geht mit Violet fremd!`nDas ist Grund genug für dich, die Scheidung einzureichen. Ab sofort bist du wieder solo.`nDu bekommst `^$getgold`6 von seinem Vermögen auf dein Bankkonto.");
									}else if ($session[user][charisma]==4294967295) {
										output(" Deine Frau wird gar nicht begeistert sein, wenn sie davon erfährt!");
										$c--;
									}else{
										if ($c<25) $c++;
									}
								}else{
								  output("Wie ein Wirbelwind fegst du durch die Schenke und schnappst nach `5Violet`0. Sie dreht sich um und ");
									output("schlägt dir ins Gesicht! \"`%Für was hältst du mich eigentlich?`0\" brüllt sie dich an! ");
									if ($c>0) $c--;
								}
							  break;
							case 7:
								output("`5Violet`0 arbeitet fieberhaft, um einige Gäste der Schenke zu bedienen. Du schlenderst zu ihr rüber, ");
								output("nimmst ihr die Becher aus der Hand und stellst sie auf den nächsten Tisch. Unter ihrem Protest kniest du dich auf einem Knie vor sie hin und nimmst ihre Hand. ");
								output("Sie verstummt plötzlich. Du starrst zu ihr hoch ");
								output("und äußerst die Frage, von der du nie für möglich gehalten hast, dass du sie einmal stellen wirst. ");
								output("Sie starrt dich an und du liest sofort die Antwort aus ihrem Gesicht. ");
							  if ($c>=22){
								  output("`n`nEs ist ein Ausdruck überschäumender Freude. \"`%Ja! Ja, ja, ja!`0\" sagt sie. ");
									output(" Ihre letzten Bestätigungen gehen dabei in einem Sturm von Küssen auf dein Gesicht und deinen Hals unter. ");
									output("`n`5Violet`0 und du heiraten in der Kirche am Ende der Strasse in ");
									output("einer prachtvollen Feier mit vielen rausgeputzten Mädels.");
									addnews("`&".$session[user][name]." und `%Violet`& haben heute feierlich den Bund der Ehe geschlossen!!!");
									$session['user']['marriedto']=4294967295; // int max. I very much doubt that anyone is going to be
									$session['bufflist']['lover']=$buff;
									$session['user']['donation']+=1;
								}else{
								  output("`n`nEs ist ein sehr trauriger Blick. Sie sagt: \"`%Nein, ich bin noch nicht bereit für eine feste Bindung.`0\"");
									output("`n`nEntmutigt und enttäuscht hast du heute keine Lust mehr auf irgendwelche Abenteuer im Wald.");
									$session[user][turns]=0;
								}
						}
						if ($c > $session[user][charm]) output("`n`n`^Du erhältst einen Charmepunkt!");
						if ($c < $session[user][charm]) output("`n`n`\$Du VERLIERST einen Charmepunkt!");
						$session[user][charm]=$c;
					}
				}else{
				  output("Du denkst, es ist besser, dein Glück mit `5Violet`0 heute nicht mehr herauszufordern.");
				}
			}else{
			  //sorry, no lezbo action here.
			}

			break;
		case "seth":
			/*
			Wink
			Flutter Eyelashes
			Drop Hankey
			Ask the bard to buy you a drink
			Kiss the bard soundly
			Completely seduce the bard
			Marry him
			*/
      if ($HTTP_GET_VARS[subop]=="" && $HTTP_GET_VARS[flirt]==""){
        output("Seth schaut dich erwartungsvoll an.");
        addnav("Fordere Seth auf, dich zu unterhalten","inn.php?op=seth&subop=hear");
        if ($session[user][sex]==1){
			if ($session['user']['marriedto']==4294967295) {
				addnav("Flirte mit Seth", "inn.php?op=seth&flirt=1");
			} else {
				addnav("Flirt");
				addnav("Zwinkern","inn.php?op=seth&flirt=1");
				addnav("Mit den Wimpern klimpern","inn.php?op=seth&flirt=2");
				addnav("Taschentuch fallenlassen","inn.php?op=seth&flirt=3");
				addnav("Frage ihn nach einem Drink","inn.php?op=seth&flirt=4");
				addnav("Küsse ihn geräuschvoll","inn.php?op=seth&flirt=5");
				addnav("Den Barden komplett verführen","inn.php?op=seth&flirt=6");
				if ($session[user][charisma]!=4294967295) addnav("Heirate ihn","inn.php?op=seth&flirt=7");
			}
		} else {
			addnav("Frage Seth nach seiner Meinung über dein(e/n) ".$session[user][armor],"inn.php?op=seth&act=armor");
		}
      }
			if ($HTTP_GET_VARS[act]=="armor"){
				$charm = $session[user][charm]+e_rand(-1,1);
				output("Seth schaut dich ernst von oben bis unten an. Nur wahre Freunde können wirklich ehrlich sein, das ist der Grund, weshalb du ");
				output("ihn gefragt hast. Schließlich kommt er zu einem Schluss und sagt: \"`%");
				switch($charm){
					case -3:
					case -2:
					case -1:
					case 0:
						output("Du machst mich glücklich, dass ich nicht schwul bin!");
						break;
					case 1:
					case 2:
					case 3:
						output("Ich habe einige hübsche Männer in meinem Leben gesehen, aber ich fürchte du bist keiner von ihnen.");
						break;
					case 4:
					case 5:
					case 6:
						output("Ich habe schon schlimmeres gesehen, aber nur beim Verfolgen eines Pferdes.");
						break;
					case 7:
					case 8:
					case 9:
						output("Du bist ziemlicher Durchschnitt, mein Freund.");
						break;
					case 10:
					case 11:
					case 12:
						output("Du bist schon etwas zum Anschauen, aber lass dir das nicht zu sehr zu Kopfe steigen, ja?");
						break;
					case 13:
					case 14:
					case 15:
						output("Du siehst schon ein bisschen besser aus als der Durchschnitt!");
						break;
					case 16:
					case 17:
					case 18:
						output("Nur wenige Frauen könnten dir widerstehen!");
						break;
					case 19:
					case 20:
					case 21:
					case 22:
						output("Willst du mich mit dieser Frage neidisch machen? Oder mich einfach nur ärgern?");
						break;
					case 23:
					case 24:
					case 25:
						output("Ich bin von deiner Schönheit geblendet.");
						break;
					case 26:
					case 27:
					case 28:
					case 29:
					case 30:
						output("Ich hasse dich. Warum? Weil du einfach der schönste Mann aller Zeiten bist!");
						break;
					default:
						output("Vielleicht solltest du langsam etwas gegen deine überirdische Schönheit tun. Du bist unerreichbar!");
				}
				output("`0\"");
			}
      if ($HTTP_GET_VARS[subop]=="hear"){
        //$session[user][seenbard]=0;
        if($session[user][seenbard]){
          output("Seth räuspert sich und trinkt einen Schluck Wasser. \"Tut mir Leid, mein Hals ist einfach zu trocken.\"");
         // addnav("Return to the inn","inn.php");
        }else{
          $rnd = e_rand(0,18);
          output("Seth räuspert sich und fängt an:`n`n`^");
          $session[user][seenbard]=1;
          switch ($rnd){
            case 0:
              output("`@Grüner Drache`^ ist grün.`n`@Grüner Drache`^ ist wild.`n`@Grünen Drachen`^ wünsch ich mir gekillt. ");
              output("`n`n`0Du erhältst ZWEI zusätzliche Waldkämpfe für heute!");
              $session[user][turns]+=2;
              break;
            case 1:
              output("Mireraband, ich spotte euch und spuck auf euren Fuß.`nDenn er verströmt fauligen Gestank mehr als er muß! ");
              output("`n`n`0Du fühlst dich erheitert und bekommst einen extra Waldkampf.");
              $session[user][turns]++;
              break;
            case 2:
	if (!$session['user']['prefs']['nosounds']) output("<embed src=\"media/ragtime.mid\" width=10 height=10 autostart=true loop=false hidden=true volume=100>",true);	
              output("Membrain Mann Membrain Mann.`nMembrain Mann hasst ".$session[user][name]."`^ Mann.`nSie haben einen Kampf, Mambrain gewinnt.`nMembrain Mann. ");
              output("`n`n`0Du bist dir nicht ganz sicher, was du davon halten sollst... du gehst lieber wieder weg und denkst, es ist besser, Seth wieder zu besuchen, wenn er sich besser fühlt. ");
							output("Nach einer kurzen Verschnaufpause könntest du wieder ein paar böse Jungs verprügeln.");
							$session[user][turns]++;
              break;
            case 3:
              output("Für eine Geschichte versammelt euch hier`neine Geschichte so schrecklich und hart`nüber Cedrik und sein gepanschtes Bier`nund wie sehr er ihn hasst, mich, den Bard'! ");
              output("`n`n`0Du stellst fest, dass er Recht hat, Cedriks Bier ist wirklich eklig. Das dürfte der Grund dafür sein, warum die meisten Gäste sein Ale bevorzugen. Du kannst der Geschichte von Seth nicht wirklich etwas abgewinnnen, aber du findest dafür etwas Gold auf dem Boden!");
			  $gain = e_rand(10,50);
              $session[user][gold]+=$gain;
			  //debuglog("found $gain gold near Seth");
              break;
            case 4:
	output("Der große grüne Drache hatte eine Gruppe Zwerge entdeckt und sie *schlurps* einfach aufgefuttert. Sein Kommentar später war: \"Die schmecken ja toll... aber... kleiner sollten sie wirklich nicht sein!\" ");
	if ($session[user][race]==4){
		output("Als Zwerg kannst du darüber nicht lachen. Mit grimmigem Gesichtsausdruck, der auch Seths Lachen zu ersticken scheint, schlägst du ihn zu Boden.");
		output ("Du bist so wütend, dass dich heute wohl nichts mehr erschrecken kann.");
	}else{
             		output("`n`n`0Mit einem guten, herzlichen Kichern in deiner Seele rückst du wieder aus, bereit für was auch immer da kommen mag!");
	}
              $session[user][hitpoints]=round($session[user][maxhitpoints]*1.2,0);
              break;
            case 5:
              output("Hört gut zu und nehmt es euch zu Herzen: Mit jeder Sekunde rücken wir dem Tod etwas näher. *zwinker*");
              output("`n`n`0Deprimiert wendest du dich ab... und verlierst einen Waldkampf!");
              $session[user][turns]--;
							if ($session[user][turns]<0) $session[user][turns]=0;
	$session['user']['donation']+=1;
              break;
            case 6:
	if (!$session['user']['prefs']['nosounds']) output("<embed src=\"media/matlock.mid\" width=10 height=10 autostart=true loop=false hidden=true volume=100>",true);
              output("Ich liebe MightyE, die Waffen von MightyE, ich liebe MightyE, die Waffen von MightyE, ich liebe MightyE, die Waffen von MightyE, nichts tötet so gut wie die WAFFEN von ... MightyE!");
              output("`n`n`0Du denkst, Seth ist ganz in Ordnung... jetzt willst du los, um irgendwas zu töten. Aus irgendeinem Grund denkst du an Bienen und Fisch.");
              $session[user][turns]++;
              break;
            case 7:
	if (!$session['user']['prefs']['nosounds']) output("<embed src=\"media/burp.wav\" width=10 height=10 autostart=true loop=false hidden=true volume=100>",true);
              output("`0Seth richtet sich auf und scheint sich auf etwas eindrucksvolles vorzubereiten. Dann rülpst er dir laut ins Gesicht. \"`^War das unterhaltsam genug?`0\"");
              output("`n`n`0Der Gestank nach angedautem Ale ist überwältigend. Dir wird etwas übel und du verlierst ein paar Lebenspunkte.");
              $session[user][hitpoints]-= round($session[user][maxhitpoints] * 0.1,0);
              if ($session[user][hitpoints]<=0) $session[user][hitpoints]=1;
	$session['user']['donation']+=1;
              break;
            case 8:
				if ($session['user']['gold'] >= 5) {
	              output("`0\"`^Welches Geräusch macht es, wenn man mit einer Hand klatscht?`0\", fragt Seth. Während du über diese Scherzfrage nachgrübelst, \"befreit\" Seth eine kleine Unterhaltungsgebühr aus deinem Goldsäckchen.");
	              output("`n`nDu verlierst 5 Gold!");
	              $session[user][gold]-=5;
				  //debuglog("lost 5 gold to Seth");
				} else {
	              output("`0\"`^Welches Geräusch macht es, wenn man mit einer Hand klatscht?`0\", fragt Seth. Während du über diese Scherzfrage nachgrübelst, versucht Seth eine kleine Unterhaltungsgebühr aus deinem Goldsäckchen zu befreien, findet aber nicht, was er sich erhofft hat.");
		$session['user']['donation']+=1;
				}
              break;
            case 9:
              output("Welcher Fuss muss immer zittern?`n`nDer Hasenfuss.");
              output("`n`nDu gröhlst und Seth lacht herzlich. Kopfschüttelnd bemerkst du einen Edelstein im Staub.");
              $session[user][gems]++;
			  //debuglog("got 1 gem from Seth");
              break;
            case 10:
              output("Seth spielt eine sanfte, aber mitreißende Melodie.");
	if (!$session['user']['prefs']['nosounds']) output("<embed src=\"media/indianajones.mid\" width=10 height=10 autostart=true loop=false hidden=true volume=100>",true);
              output("`n`nDu fühlst dich entspannt und erholt und deine Wunden scheinen sich zu schließen.");
              if ($session['user']['hitpoints']<$session['user']['maxhitpoints']) $session[user][hitpoints]=$session[user][maxhitpoints];
              break;
            case 11:
              output("Seth spielt dir ein düsteres Klagelied vor.");
	if (!$session['user']['prefs']['nosounds']) output("<embed src=\"media/eternal.mid\" width=10 height=10 autostart=true loop=false hidden=true volume=100>",true);
              output("`n`nDeine Stimmung fällt und du wirst heute nicht mehr so viele Bösewichte erschlagen.");
              $session[user][turns]--;
              if ($session[user][turns]<0) $session[user][turns]=0;
              break;
            case 12:
	if (!$session['user']['prefs']['nosounds']) output("<embed src=\"media/babyphan.mid\" width=10 height=10 autostart=true loop=false hidden=true volume=100>",true);
              output("Die Ameisen marschieren in Einerreihen, Hurra, Hurra!`nDie Ameisen marschieren in Einerreihen, Hurra, Hurra!`nDie Ameisen marschieren in Einerreihen, Hurra, Hurra, und die kleinste stoppt und nuckelt am Daumen.`nUnd sie alle marschieren in den Bau um vorm Regen abzuhaun.`nBumm, bumm, bumm.`nDie Ameisen marschieren in Zweierreihen, Hurra, Hurra! ....");
              output("`n`n`0Seth singt immer weiter, aber du hast nicht den Wunsch herauszufinden, wie weit Seth zählen kann, deswegen verschwindest du leise. Nach dieser kurzen Rast fühlst du dich erholt.");
							$session[user][hitpoints]=$session[user][maxhitpoints];
              break;
            case 13:
              output("Es war ein mal eine Dame von der Venus, ihr Körper war geformt wie ein ...");
              if ($session[user][sex]==1){
                output("`n`n`0Seth wird durch einen barschen Schlag ins Gesicht unterbrochen. Du fühlst dich rauflustig und gewinnst einen Waldkampf dazu.");
              }else{
                output("`n`n`0Seth wird durch dein plötzliches lautes Gelächter unterbrochen, das du ausstößt, ohne seinen Reim vollständig gehört haben zu müssen. So angespornt erhältst du einen zusätzlichen Waldkampf.");
              }
              $session[user][turns]++;
              break;
            case 14:
	if (!$session['user']['prefs']['nosounds']) output("<embed src=\"media/knightrider.mid\" width=10 height=10 autostart=true loop=false hidden=true volume=100>",true);
              output("Seth spielt einen stürmischen Schlachtruf für dich, der den Kriegergeist in dir erweckt.");
              output("`n`n`0Du bekommst einen zusätzlichen Waldkampf!");
              $session[user][turns]++;
              break;
            case 15:
              output("Seth scheint in Gedanken völlig woanders zu sein ... bei deinen ... Augen.");
              if ($session[user][sex]==1){
                output("`n`n`0Du erhältst einen Charmepunkt!");
                $session[user][charm]++;
              }else{
                output("`n`n`0Aufgebracht stürmst du aus der Bar! In deiner Wut bekommst du einen Waldkampf dazu.");
                $session[user][turns]++;
              }
              break;
            case 16:
	if (!$session['user']['prefs']['nosounds']) output("<embed src=\"media/boioing.wav\" width=10 height=10 autostart=true loop=false hidden=true volume=100>",true);
              output("Seth fängt an zu spielen, aber eine Saite seiner Laute reißt plötzlich und schlägt dir flach ins Auge.`n`n`0\"`^Uuuups! Vorsicht, du wirst dir noch deine Augen ausschießen, Mensch!`0\"");
              output("`n`nDu verlierst einige Lebenspunkte!");
              $session[user][hitpoints]-=round($session[user][maxhitpoints]*.1,0);
							if ($session[user][hitpoints]<1) $session[user][hitpoints]=1;
              break;
            case 17:
              output("Er fängt an zu spielen, als ein rauflustiger Gast vorbeistolpert und Bier auf dich verschüttet. Du verpasst die ganze Vorstellung während du das Gesöff von deine(r/m) ".$session[user][armor]." putzt.");
	$session['user']['donation']+=1;     
         break;
            case 18:
              output("`0Seth starrt dich gedankenvoll an. Offensichtlich komponiert er gerade ein episches Gedicht...`n`n`^H-Ä-S-S-L-I-C-H, du kannst dich nicht verstecken -- Du bist hässlich, yeah, yeah, so hässlich!");
              $session[user][charm]--;
              if ($session[user][charm]<0){
                output("`n`n`0Wenn du einen Charmepunkt hättest, hättest du ihn jetzt verloren. Aber so reißt Seth nur eine Saite seiner Laute.");
              }else{
                output("`n`n`0Deprimiert verlierst du einen Charmepunkt.");
              }
              break;
          }
        }
      }
			if ($session[user][sex]==1 && $HTTP_GET_VARS[flirt]<>""){
			  //$session[user][seenlover]=0;
			  if ($session[user][seenlover]==0){
			  	  if ($session['user']['marriedto']==4294967295){
				    if (e_rand(1,4)==1){
					  output("Du gehst rüber zu Seth, um ihn zu knuddeln und mit Küssen zu überhäufen, aber er brummelt nur etwas ");
					  switch(e_rand(1,4)){
					  case 1:
					    output("darüber, dass er damit beschäftigt ist, seine Laute zu stimmen. ");
						break;
					  case 2:
					    output("wie \"um diese Zeit?\" ");
						break;
				 	  case 3:
					    output("wie \"leicht erkältet...  *hust hust* siehst du?\" ");
						break;
					  case 4:
					    output("darüber, dass er sich ein Bier holen will. ");
						break;
					  }
					  output("Nach so einem Kommentar lässt du ihn stehen und haust ab!");
					  $session['user']['charm']--;
					  output("`n`n`^Du VERLIERST einen Charmepunkt!");
					}else{
					  output("Du und Seth nehmt euch etwas Zeit füreinander und du verlässt die Schenke mit einem zuversichtlichen Strahlen!");
					  $session['bufflist']['lover']=$buff;
					  $session['user']['charm']++;
					  output("`n`n`^Du erhältst einen Charmepunkt!");
					}
				    $session['user']['seenlover']=1;
				  } elseif ($HTTP_GET_VARS[flirt]==""){
					}else{
					  $c = $session[user][charm];
						$session[user][seenlover]=1;
					  switch($HTTP_GET_VARS[flirt]){
						  case 1:
							  if (e_rand($c,2)>=2){
								  output("Seth grinst ein breites Grinsen. Hach, ist dieses Grübchen in seinem Kinn nicht süß??");
									if ($c<4) $c++;
								}else{
								  output("Seth hebt eine Augenbraue und fragt dich, ob du etwas im Auge hast.");
								}
							  break;
						  case 2:
							  if (e_rand($c,4)>=4){
								  output("Seth lächelt dich an und sagt: \"`^Du hast wunderschöne Augen`0\"");
									if ($c<7) $c++;
								}else{
									output("Seth lächelt und winkt ... zu der Person hinter dir.");
								}
							  break;
						  case 3:
							  if (e_rand($c,7)>=7){
								  output("Während Seth sich bückt, um dir dein Taschentuch zurückzugeben, bewunderst du seinen knackigen Hintern.");
									if ($session[user][charisma]==4294967295) {
										output(" Dein Mann wird gar nicht begeistert sein, wenn er davon erfährt!");
										$c--;
									} else {
										if ($c<11) $c++;
									}
								}else{
									output("Seth hebt das Taschentuch auf, putzt sich damit die Nase und gibt es dir zurück.");
								}
							  break;
						  case 4:
							  if (e_rand($c,11)>=11){
								  output("Seth platziert seinen Arm um deine Hüfte, geleitet dich an die Bar und kauft dir eines der köstlichsten Getränke, die es in der Schenke gibt.");
									if ($session[user][charisma]==4294967295) {
										output(" Dein Mann wird gar nicht begeistert sein, wenn er davon erfährt!");
										$c--;
									} else {
										if ($c<14) $c++;
									}
								}else{
								  output("Seth bedauert: \"`^Tut mir Leid, meine Dame, ich habe kein Geld zu verschenken.`0\" Dabei stülpt er seine mottenzerfressenen Taschen nach außen.");
									if ($c>0 && $c<10) $c--;
								}
							  break;
						  case 5:
							  if (e_rand($c,14)>=14){
								  output("Du läufst auf Seth zu, packst ihn am Hemd, stellst ihn auf die Beine und drückst ihm einen kräftigen, langen Kuss direkt auf seine attraktiven Lippen. Seth bricht fast zusammen - mit zerzausten Haaren und ziemlich atemlos.");
									if ($session[user][charisma]==4294967295) {
										$c--;
									} else {
										if ($c<18) $c++;
									}
								}else{
								  output("Du bückst dich zu Seth herunter, um ihn auf die Lippen zu küssen, doch als sich eure Lippen gerade berühren wollen, bückt sich Seth, um sich den Schuh zuzubinden.");
									// $session[user][hitpoints]=1; //why the heck was this here???
									if ($c>0 && $c<13) $c--;
								} 
							if ($session[user][charisma]==4294967295) output(" Dein Mann wird gar nicht begeistert sein, wenn er davon erfährt!");
							  break;
						  case 6:
							  if (e_rand($c,18)>=18){
  							  output("Du stehst auf der ersten Treppenstufe und gibst Seth ein 'komm hierher' Zeichen. Er folgt dir wie ein Schoßhündchen.");
									if ($session['user']['turns']>0){
									  output("Du fühlst dich ausgelaugt!  ");
									  $session['user']['turns']-=2;
									  if ($session['user']['turns']<0) $session['user']['turns']=0;
									}
									addnews("`@Es wurde beobachtet, wie ".$session[user][name]."`@ und `^Seth`@ gemeinsam die Treppen in der Schenke nach oben gingen.");
									if ($session[user][charisma]==4294967295 && e_rand(1,3)==2) {
										$sql = "SELECT acctid,name FROM accounts WHERE locked=0 AND acctid=".$session[user][marriedto]."";
  										$result = db_query($sql) or die(db_error(LINK));
										$row = db_fetch_assoc($result);
										$partner=$row[name];
										addnews("`\$$partner hat ".$session[user][name]."`\$ wegen eines Seitensprungs mit `^Seth`\$ verlassen!");
										output("`nDas war zu viel für $partner! Er reicht die Scheidung ein. Die Hälfte deines Goldes auf der Bank wird ihm zugesprochen. Ab sofort bist du wieder solo!");
										$session[user][charisma]=0;
										$session[user][marriedto]=0;
										if ($session[user][goldinbank]>0) $getgold=round($session[user][goldinbank]/2);
										$session[user][goldinbank]-=$getgold;
										$sql = "UPDATE accounts SET charisma=0,marriedto=0,goldinbank=goldinbank+$getgold WHERE acctid='$row[acctid]'";
										db_query($sql);
										systemmail($row['acctid'],"`\$Seitensprung!`0","`&{$session['user']['name']}`6 geht mit Seth fremd!`nDas ist Grund genug für dich, die Scheidung einzureichen. Ab sofort bist du wieder solo.`nDu bekommst `^$getgold`6 von ihrem Vermögen auf dein Bankkonto.");
									}else if ($session[user][charisma]==4294967295) {
										output(" Dein Mann wird gar nicht begeistert sein, wenn er davon erfährt!");
										$c--;
									}else{
										if ($c<25) $c++;
									}
								}else{
								  output("\"`^Tut mir Leid meine Dame, aber ich habe in 5 Minuten einen Auftritt.`0\"");
									if ($c>0) $c--;
								}
							  break;
							case 7:
								output("Du gehst zu Seth und verlangst ohne Umschweife von ihm, daß er dich heiratet.`n`nEr schaut dich ein paar Sekunden lang an.`n`n");
							  if ($c>=22){
									output("\"`^Natürlich, meine Liebe!`0\", sagt er. Die nächsten wochen bist du damit beschäftigt, eine gigantische Hochzeit vorzubereiten, die natürlich Seth zahlt, und danach geht es in den dunklen Wald in die Flitterwochen.");
									addnews("`&".$session[user][name]." und `^Seth`& haben heute feierlich den Bund der Ehe geschlossen!!!");
									$session['user']['marriedto']=4294967295; //int max.
					  				$session['bufflist']['lover']=$buff;
									$session['user']['donation']+=1;
								}else{
									output("`^Seth sagt: \"`^Es tut mir Leid, offensichtlich habe ich einen falschen Eindruck erweckt. Ich denke, wir sollten einfach nur Freunde sein.`0\"  Deprimiert hast du heute kein Verlangen mehr danach, nochmal im Wald kämpfen zu gehen.");
									$session[user][turns]=0;
								}
						}
						if ($c > $session[user][charm]) output("`n`n`^Du bekommst einen Charmepunkt!");
						if ($c < $session[user][charm]) output("`n`n`\$Du VERLIERST einen Charmepunkt!");
						$session[user][charm]=$c;
					}
				}else{
				  	output("Du denkst, es ist besser, dein Glück mit `^Seth`0 heute nicht mehr herauszufordern.");
				}
			}else{
			  //sorry, no lezbo action here.
			}
			break;
		case "converse":
		  output("Du schlenderst rüber zu einem Tisch, stellst den Fuß auf die Bank und lauschst der Unterhaltung:`n");
			viewcommentary("inn","Zur Unterhaltung beitragen:",20);
			break;
		case "bartender":
		if (getsetting("paidales",0)<=1 || $session[user][gotfreeale]>=2) {
			$alecost = $session[user][level]*10;
		} else {
			$alecost = 0;
		}

		  if ($HTTP_GET_VARS[act]==""){
				output("Cedrik schaut dich irgendwie schräg an. Er ist keiner von der Sorte, die einem Mann viel weiter trauen, ");
				output("als sie ihn werfen können, was Zwergen einen entscheidenden Vorteil verleiht. Mit Ausnahme von Regionen natürlich, ");
				output("in denen Zwergenweitwurf verboten wurde.  Cedrik poliert ein Glas und hält es ins Licht, das durch die Tür hereinscheint, als ein Gast ");
				output("die Schenke verläßt. Dann verzieht er das Gesicht, spuckt auf das Glas ");
				output("und fährt mit der Politur fort. \"`%Was willst'n?`0\", fragt er dich schroff.");
				addnav("Schwarzes Brett","inn.php?op=msgboard");
				addnav("Bestechen","inn.php?op=bartender&act=bribe");
				addnav("Edelsteine","inn.php?op=bartender&act=gems");
				if (getsetting("paidales",0)<=1) {
					addnav("Ale (`^$alecost`0 Gold)","inn.php?op=bartender&act=ale");
					addnav("Runde schmeißen","inn.php?op=bartender&act=schmeiss");
				} else {
					$amt=getsetting("paidales",0)-1;
					addnav("Ale (`^".($session[user][gotfreeale]>=2?"$alecost`0 Gold":"schon bezahlt`0").")","inn.php?op=bartender&act=ale");
					output("`nEs stehen noch $amt frisch gefüllte und schon bezahlte Krüge mit Ale vor Cedrik.");
					if ($session[user][gotfreeale]>=2) output(" Leider hattest du dein Frei-Ale für heute schon und du wirst selbst bezahlen müssen.");
				}
			  $drunkenness = array(-1=>"absolut nüchtern",
														 0=>"ziemlich nüchtern",
														 1=>"kaum berauscht",
									 					 2=>"leicht berauscht",
														 3=>"angetrunken",
														 4=>"leicht betrunken",
														 5=>"betrunken",
														 6=>"ordentlich betrunken",
														 7=>"besoffen",
														 8=>"richtig zugedröhnt",
														 9=>"fast bewusstlos"
									);
				$drunk = round($session[user][drunkenness]/10-.5,0);
				if ($drunkenness[$drunk]){
					output("`n`n`7Du fühlst dich ".$drunkenness[$drunk]."`n`n");
				}else{
					output("`n`n`7Du fühlst dich nicht mehr.`n`n");
				}
			}else if ($HTTP_GET_VARS[act]=="gems"){
			  if ($HTTP_POST_VARS[gemcount]==""){
					output("\"`%Du hast Edelsteine, oder?`0\", fragt dich Cedrik. \"`%Nun, für `^zwei Edelsteine`% werd ich dir nen magischen Trank machen!`0\"");
					output("`n`nWieviele Edelsteine gibst du ihm?");
					output("<form action='inn.php?op=bartender&act=gems' method='POST'><input name='gemcount' value='0'><input type='submit' class='button' value='Weggeben'>`n",true);
					output("Und was willst du dafür?`n<input type='radio' name='wish' value='1' checked> Charme`n<input type='radio' name='wish' value='2'> Lebenskraft`n",true);
					addnav("","inn.php?op=bartender&act=gems");
					output("<input type='radio' name='wish' value='3'> Gesundheit`n",true);
					output("<input type='radio' name='wish' value='4'> Vergessen`n",true);
					output("<input type='radio' name='wish' value='5'> Transmutation</form>",true);
				}else{
				  $gemcount = abs((int)$HTTP_POST_VARS[gemcount]);
					if ($gemcount>$session[user][gems]){
					  output("Cedrik starrt dich an und sagt: \"`%Du hast nich so viele Edelsteine, `bzieh los und besorg dir noch welche!`b`0\"");
					}else{
					  output("`#Du platzierst $gemcount Edelsteine auf der Theke.");
						if ($gemcount % 2 == 0){
							
						}else{
							output("  Cedrik, der über deine absolute mathematische Unfähigkeit bescheid weiss, ");
							output("gibt dir einen davon zurück.");
							$gemcount-=1;
						}
						if ($gemcount>0) output("  Du trinkst den Trank, den Cedrik dir im Austausch für deine Edelsteine gegeben hat und.....`n`n");
						$session[user][gems]-=$gemcount;
			  			//debuglog("used $gemcount gems on potions");
						if ($gemcount>0){
							switch($HTTP_POST_VARS[wish]){
								case 1:
									$session[user][charm]+=($gemcount/2);
									output("`&Du fühlst dich charmant! `^(Du erhältst Charmepunkte)");
									break;
								case 2:
									$session[user][maxhitpoints]+=($gemcount/2);
									$session[user][hitpoints]+=($gemcount/2);
									output("`&Du fühlst dich lebhaft! `^(Deine maximale Lebensenergie erhöht sich permanent)");
									break;
								case 3:
									if ($session[user][hitpoints]<$session[user][maxhitpoints]) $session[user][hitpoints]=$session[user][maxhitpoints];
									$session[user][hitpoints]+=($gemcount*10);
									output("`&Du fühlst dich gesund! `^(Du erhältst vorübergehend mehr Lebenspunkte)");
									break;
								case 4:
									$session[user][specialty]=0;
									output("`&Du fühlst dich völlig ziellos in deinem Leben. Du solltest eine Pause machen und einige wichtige Entscheidungen über dein Leben treffen! `^(Dein Spezialgebiet wurde zurückgesetzt)");
									break;
								case 5:
									if ($session['user']['race']==1) $session['user']['attack']--;
									if ($session['user']['race']==2) $session['user']['defence']--;
									if ($session['user']['race']==5) $session['user']['maxhitpoints']--;
									$session['user']['race']=0;
									output("`@Deine Knochen werden zu Gelatine und du musst vom Effekt des Tranks ordentlich würgen!`^(Deine Rasse wurde zurückgesetzt. Du kannst morgen eine neue wählen.)");
									if (isset($session['bufflist']['transmute'])) {
										$session['bufflist']['transmute']['rounds'] += 10;
									} else {
										$session['bufflist']['transmute']=array(
										    "name"=>"`6Transmutationskrankheit",
										    "rounds"=>10,
										    "wearoff"=>"Du hörst auf, deine Därme auszukotzen. Im wahrsten Sinne des Wortes.",
										    "atkmod"=>0.75,
										    "defmod"=>0.75,
										    "roundmsg"=>"Teile deiner Haut und deiner Knochen verformen sich wie Wachs.",
										    "survivenewday"=>1,
										    "newdaymessage"=>"`6Durch die Auswirkungen des Transmutationstranks fühlst du dich immer noch `2krank`6.",
										    "activate"=>"offense,defense"
										);
									}
									break;
							}
						}else{
						  output("`n`nDu hast das unbestimmte Gefühl, dass deine Edelsteine für etwas anderes als stinkende Tränke besser eingesetzt wären.");
						}
					}
				}
			}else if ($HTTP_GET_VARS[act]=="schmeiss"){
				output("Du bist guter Laune und überlegst dir, ob du für deine Kumpels hier in der Schenke ne Runde Ale spendieren solltest.`n");
				output("`n1 Ale kostet dich `^$alecost`0 Gold.`n");
				output("<form action='inn.php?op=bartender&act=schmeiss2' method='POST'>Die nächsten <input name='runden' id='runden' width='4'> Ale gehen auf deine Rechnung.`n",true);
				output("<input type='submit' class='button' value='Ausgeben'></form>",true);
				output("<script language='javascript'>document.getElementById('runden').focus();</script>",true);
				addnav("","inn.php?op=bartender&act=schmeiss2");
			}else if ($HTTP_GET_VARS[act]=="schmeiss2"){
				$amt = abs((int)$_POST['runden']);
				$jamjam=$amt*$alecost;
				$schussel=$session[user][name];
				if ($session[user][gold]<$jamjam){
					output("Du stellst gerade noch rechtzeitig vor einer Blamage fest, dass du nicht genug Gold dabei hast.");
				} else if (getsetting("paidales",0)>1 || $alecost==0){
					output("Tja, der gute Wille war da, doch ein anderer war schneller als du! Enttäuscht bewegst du dich Richtung Freiale und schwörst dir, in Zukunft schneller zu sein.");
				} else if (abs($session[user][gotfreeale]-2)==1){
					output("Cedrik schaut dir tief in die Augen und meint nur \"`%Du hast heute schonmal eine Runde spendiert. In meiner Schenke machst du niemanden zum Säufer. Alles klar?`0\"");
				} else if ($amt>getsetting("maxales",50)){
					output("\"`%Hast du sie noch alle, hier so mit deinem Gold anzugeben? Schau dich doch mal um, wieviele überhaupt da sind!`0\" Mit diesen Worten zeigt dir Cedrik einen Vogel und dreht sich gelangweilt weg. ");
				}else{
					output("Du sprichst mit Barkeeper Cedrik und schiebst ihm `^$jamjam`0 Gold rüber. Dieser nickt mit dem Kopf und grölt in die Runde \"`%Die nächsten $amt Ale gehen auf $schussel !!`0\".");
					output("Ein allgemeiner Freudenschrei ist die Antwort und du bist der Held der Stunde.`n`n");
					if ($amt>5){
						output("`^Du erhältst einen Charmepunkt!`0");
						$session[user][charm]+=1;
					}
					//if ($amt>10) $session['user']['donation']+=1;
					savesetting("paidales",$amt+1);
					$session[user][gold]-=$jamjam;
					$session[user][gotfreeale]++;
					$sql = "INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),'inn',".$session[user][acctid].",'/me spendiert die nächsten `^$amt`& Ale!')";
					db_query($sql) or die(db_error(LINK));
				}
			}else if ($HTTP_GET_VARS[act]=="bribe"){
				$g1 = $session[user][level]*10;
				$g2 = $session[user][level]*50;
				$g3 = $session[user][level]*100;
				$session[user][reputation]--;
				if ($HTTP_GET_VARS[type]==""){
					output("Wie viel willst du ihm anbieten?");
					addnav("1 Edelstein","inn.php?op=bartender&act=bribe&type=gem&amt=1");
					addnav("2 Edelsteine","inn.php?op=bartender&act=bribe&type=gem&amt=2");
					addnav("3 Edelsteine","inn.php?op=bartender&act=bribe&type=gem&amt=3");
					addnav("$g1 Gold","inn.php?op=bartender&act=bribe&type=gold&amt=$g1");
					addnav("$g2 Gold","inn.php?op=bartender&act=bribe&type=gold&amt=$g2");
					addnav("$g3 Gold","inn.php?op=bartender&act=bribe&type=gold&amt=$g3");
				}else{
				  if ($HTTP_GET_VARS[type]=="gem"){
						if ($session['user']['gems']<$_GET['amt']){
							$try=false;
							output("Du hast keine {$_GET['amt']} Edelsteine!");
						}else{
						 	$chance = $HTTP_GET_VARS[amt]/4;
							$session[user][gems]-=$HTTP_GET_VARS[amt];
			  				//debuglog("spent {$HTTP_GET_VARS['amt']} gems on bribing Cedrik");
							$try=true;
						}
					}else{
						if ($session['user']['gold']<$_GET['amt']){
							output("Du hast keine {$_GET['amt']} Gold!");
							$try=false;
						}else{
							$try=true;
							$chance = $HTTP_GET_VARS[amt]/($session[user][level]*110);
							$session[user][gold]-=$HTTP_GET_VARS[amt];
				  			//debuglog("spent {$HTTP_GET_VARS['amt']} gold bribing Cedrik");
						}
					}
					$chance*=100;
					if ($try){
						if (e_rand(0,100)<$chance){
							output("Cedrik lehnt sich zu dir über die Theke und fragt: \"`%Was kann ich für dich tun, Kleine".($session[user][sex]?"":"r")."?`0\"");
								if (getsetting("pvp",1)) {
								addnav("Wer schläft oben?","inn.php?op=bartender&act=listupstairs");
							}
							addnav("Farbenlehre","inn.php?op=bartender&act=colors");
							addnav("Spezialgebiet wechseln","inn.php?op=bartender&act=specialty");
						}else{
							output("Cedrik fängt an, die Oberfläche der Theke zu wischen, was eigentlich schon vor langer Zeit wieder einmal nötig gewesen wäre.  ");
							output("Als er damit fertig ist, ".($HTTP_GET_VARS[type]=="gem"?($HTTP_GET_VARS[amt]>0?"sind deine Edelsteine":"ist dein Edelstein"):"ist dein Gold"));
							output(" verschwunden. Du fragst wegen deinem Verlust nach, aber Cedrik starrt dich nur mit leerem Blick an.");
						}
					}else{
						output("`n`nCedrik steht nur da und schaut dich ausdruckslos an.");
					}
				}
			}else if ($HTTP_GET_VARS[act]=="ale"){
			  output("Du schlägst mit der Faust auf die Bar und verlangst ein Ale");
				if ($session[user][drunkenness]>66){
				  //************************************************************************************************************************************
					output(", aber Cedrik fährt unbekümmert damit fort, das Glas weiter zu polieren, an dem er gerade arbeitet. \"`%Du hast genug gehabt ".($session[user][sex]?"Mädl":"Bursche").".`0\" ");
				}else{
				  if ($session[user][gold]>=$alecost){
					  $session[user][drunkenness]+=33;
						$session[user][gold]-=$alecost;
						if (getsetting("paidales",0)>1 && $session[user][gotfreeale]<2) {
							savesetting("paidales",getsetting("paidales",0)-1);
							$session[user][gotfreeale]+=2;
						}
						//debuglog("spent $alecost gold on ale");
						output(".  Cedrik nimmt ein Glas und schenkt schäumendes Ale aus einem angezapften Fass hinter ihm ein.  ");
						output("Er gibt dem Glas Schwung und es rutscht über die Theke, wo du es mit deinen Kriegerreflexen fängst.  ");
						output("`n`nDu drehst dich um, trinkst dieses herzhafte Gesöff auf ex und gibst ".($session[user][sex]?"Seth":"Violet"));
						output(" ein Lächeln mit deinem Ale-Schaum-Oberlippenbart.`n`n");
						switch(e_rand(1,3)){
						  case 1:
							case 2:
							  output("`&Du fühlst dich gesund!");
								$session[user][hitpoints]+=round($session[user][maxhitpoints]*.1,0);
								break;
							case 3:
							  output("`&Du fühlst dich lebhaft!");
								$session[user][turns]++;
						}
						if ($session[user][drunkenness]>33) $session[user][reputation]--;
						$session[bufflist][101] = array("name"=>"`#Rausch","rounds"=>10,"wearoff"=>"Dein Rausch verschwindet.","atkmod"=>1.25,"roundmsg"=>"Du hast einen ordentlichen Rausch am laufen.","activate"=>"offense");
					}else{
					  output("Du hast aber nicht genug Geld bei dir. Wie kannst du ein Ale haben wollen, wenn du das Geld dafür nicht hast!?!");
					}
				}
			}else if ($HTTP_GET_VARS[act]=="listupstairs"){
				addnav("Liste aktualisieren","inn.php?op=bartender&act=listupstairs");
				output("Cedrik legt einen Satz Schlüssel vor dich auf die Theke und sagt dir, welcher Schlüssel wessen Zimmer öffnet. Du hast die Wahl. Du könntest bei jedem reinschlüpfen und angreifen.");
				$pvptime = getsetting("pvptimeout",600);
				$pvptimeout = date("Y-m-d H:i:s",strtotime(date("r")."-$pvptime seconds"));
				pvpwarning();
	if ($session['user']['pvpflag']=="5013-10-06 00:42:00"){
		output("`n`&(Du hast PvP-Immunität gekauft. Diese verfällt, wenn du jetzt angreifst!)`0`n`n");
	}
				$days = getsetting("pvpimmunity", 5);
				$exp = getsetting("pvpminexp", 1500);
				$sql = "SELECT name,alive,location,sex,level,laston,loggedin,login,pvpflag FROM accounts WHERE 
				(locked=0) AND 
				(level >= ".($session[user][level]-1)." AND level <= ".($session[user][level]+2).") AND 
				(alive=1 AND location=1) AND 
				(age > $days OR dragonkills > 0 OR pk > 0 OR experience > $exp) AND
				(laston < '".date("Y-m-d H:i:s",strtotime(date("r")."-".getsetting("LOGINTIMEOUT",900)." sec"))."' OR loggedin=0) AND
				(acctid <> ".$session[user][acctid].") AND
				(dragonkills > ".($session[user][dragonkills]-5).")
				ORDER BY level DESC";
				$result = db_query($sql) or die(db_error(LINK));
				output("<table border='0' cellpadding='3' cellspacing='0'><tr><td>Name</td><td>Level</td><td>Ops</td></tr>",true);
				for ($i=0;$i<db_num_rows($result);$i++){
					$row = db_fetch_assoc($result);
					$biolink = "bio.php?char=".rawurlencode($row[login])."&ret=".urlencode($_SERVER['REQUEST_URI']);
					addnav("", $biolink);
					if($row[pvpflag]>$pvptimeout){
						output("<tr class='".($i%2?"trlight":"trdark")."'><td>$row[name]</td><td>$row[level]</td><td>[ <a href='$biolink'>Bio</a> | `iimmun`i ]</td></tr>",true);
					}else{
						output("<tr class='".($i%2?"trlight":"trdark")."'><td>$row[name]</td><td>$row[level]</td><td>[ <a href='$biolink'>Bio</a> | <a href='pvp.php?act=attack&bg=1&name=".rawurlencode($row[login])."'>Angriff</a> ]</td></tr>",true);
						addnav("","pvp.php?act=attack&bg=1&name=".rawurlencode($row[login]));
					}
				}
				output("</table>",true);
				$session[user][reputation]--;
			}else if($HTTP_GET_VARS[act]=="colors"){
			  output("Cedrik lehnt sich weiter über die Bar. \"`%Du willst also was über Farben wissen, hmm?`0\" ");
				output("  Du willst gerade antworten, als du feststellst, dass das eine rhetorische Frage war.  ");
				output("Cedrik fährt fort: \`%Um Farbe in deine Texte zu bringen, musst du folgendes tun: Zuerst machst du ein &#0096; Zeichen ",true);
				output(" (Shift und die Taste links neben Backspace), gefolgt von 1, 2, 3, 4, 5, 6, 7, 8, 9, !, @, #, $, %, ^, Q oder &. Jedes dieser Zeichen entspricht ");
				output("einer Farbe, die folgendermaßen aussieht: `n`1&#0096;1 `2&#0096;2 `3&#0096;3 `4&#0096;4 `5&#0096;5 `6&#0096;6 `7&#0096;7 `8&#0096;8 `9&#0096;9  ",true);
				output("`n`!&#0096;! `@&#0096;@ `#&#0096;# `\$&#0096;\$ `%&#0096;% `^&#0096;^ `q&#0096;q `Q&#0096;Q `&&#0096;& `n",true);
				output("`T&#0096;T `t&#0096;t `R&#0096;R `r&#0096;r `V&#0096;V `v&#0096;v `g&#0096;g`n",true);
				output("`% kapiert?`0\"`n Hier kannst du testen:");
				output("<form action=\"$REQUEST_URI\" method='POST'>",true);
				output("Deine Eingabe: ".str_replace("`","&#0096;",HTMLEntities($HTTP_POST_VARS[testtext]))."`n",true);
				output("Sieht so aus: ".$HTTP_POST_VARS[testtext]." `n");
				output("<input name='testtext' id='input'><input type='submit' class='button' value='Testen'></form>",true);
				output("<script language='javascript'>document.getElementById('input').focus();</script>",true);

				output("`0`n`nDu kannst diese Farben in jedem Text verwenden, den du eingibst.");
				addnav("",$REQUEST_URI);
			}else if($HTTP_GET_VARS[act]=="specialty"){
				if ($HTTP_GET_VARS[specialty]==""){
					output("\"`2Ich will mein Spezialgebiet wechseln`0\", verkündest du Cedrik.`n`n");
					output("Ohne ein Wort packt Cedrik dich am Hemd, zieht dich über die Theke und zerrt dich hinter die Fässer hinter ihm. ");
					output("Dann dreht er am Hahn eines kleinen Fässchens mit der Aufschrift \"Feines Gesöff XXX\"");
					output("`n`nDu schaust dich um und erwartest, dass irgendwo eine Geheimtür aufgeht, aber nichts passiert. Stattdessen ");
					output("dreht Cedrik den Hahn wieder zurück und hebt einen frisch mit seinem vermutlich besten Gebräu gefüllten Krug. Das Zeug schäumt und ist von blau-grünlicher Farbe.");
					output("`n`n\"`3Was? Du hast einen geheimen Raum erwartet?`0\", fragt er dich. \"`3Also dann solltest du noch ");
					output("besser aufpassen, wie laut du sagst, dass du deine Fähigkeiten ändern willst. Nicht jeder sieht ");
					output("mit Wohlwollen auf diese Art von Dingen.`n`nWelches neue Spezialgebiet hast du dir denn gedacht?`0\"");
					addnav("Dunkle Künste",preg_replace("/[&?]c=[[:digit:]-]*/","",$REQUEST_URI)."&specialty=1");
					addnav("Mystische Kräfte",preg_replace("/[&?]c=[[:digit:]-]*/","",$REQUEST_URI)."&specialty=2");
					addnav("Diebeskünste",preg_replace("/[&?]c=[[:digit:]-]*/","",$REQUEST_URI)."&specialty=3");
				
				}else{
					output("\"`3Ok, du hast es.`0\"`n`n \"`2Das war schon alles?`0\", fragst du ihn.");
					output("`n`n\"`nCedrik fängt laut an zu lachen: \"`3Jup. Was hasten erwartet? Irgendne Art fantastisches und geheimnisvolles Ritual??? ");
					output("Du bist in Ordnung, Kleiner... spiel nur niemals Poker, ok?`0\"");
					output("`n`n\"`Ach, nochwas. Obwohl du dein Können in deiner alten Fertigkeit jetzt nicht mehr einsetzen kannst, hast du es immer noch. ");
					output("Deine neue Fertigkeit wirst du trainieren müssen, um wirklich gut darin zu sein.`0\"");
					//addnav("Return to the inn","inn.php");
					$session[user][specialty]=$HTTP_GET_VARS[specialty];
				}
			}
			break;
		case "room":
			$config = unserialize($session['user']['donationconfig']);
		  $expense = round(($session[user][level]*(10+log($session[user][level]))),0);
			if ($HTTP_GET_VARS[pay]){
				if ($_GET['coupon']==1){
			  	$config['innstays']--;
					debuglog("logged out in the inn");
					$session['user']['donationconfig']=serialize($config);
					$session['user']['loggedin']=0;
					$session['user']['location']=1;
					$session['user']['boughtroomtoday']=1;
					saveuser();
					$session=array();
					redirect("index.php");
				}else{
					if ($HTTP_GET_VARS[pay] == 2 || $session[user][gold]>=$expense || $session[user][boughtroomtoday]){
						if ($session[user][loggedin]){
							if ($session[user][boughtroomtoday]) {
							}else{
								if ($HTTP_GET_VARS[pay] == 2) {
									$fee = getsetting("innfee", "5%");
									if (strpos($fee, "%"))
										$expense += round($expense * $fee / 100,0);
									else
										$expense += $fee;
									$goldline = ",goldinbank=goldinbank-$expense";
								} else {
									$goldline = ",gold=gold-$expense";
								}
								$goldline .= ",boughtroomtoday=1";
							}
				  			debuglog("spent $expense gold on an inn room");
							$sql = "UPDATE accounts SET loggedin=0,location=1 $goldline WHERE acctid = ".$session[user][acctid];
							db_query($sql) or die(sql_error($sql));
						}
						$session=array();
						redirect("index.php");
					}else{
						output("\"Aah, so ist das also.\", sagt Cedrik und hängt den Schlüssel, den er gerade geholt hat, wieder an seinen Haken hinter der Theke. ");
						output("Vielleicht solltest du erstmal für das nötige Kleingeld sorgen, bevor du dich am ");
						output("örtlichen Handel beteiligst.");
					}
				}
			}else{
				if ($session[user][boughtroomtoday]){
					output("Du hast heute schon für ein Zimmer bezahlt.");
					addnav("Gehe ins Zimmer","inn.php?op=room&pay=1");
				}else{
					if ($config['innstays']>0){
						addnav("Zeige ihm den Gutschein für ".$config['innstays']." Übernachtungen","inn.php?op=room&pay=1&coupon=1");
					}
					output("Du trottest zum Barkeeper und fragst nach einem Zimmer. Er betrachtet dich und sagt: \"Das kostet `\$".$expense."`0 Gold für die Nacht.\"");
					$fee = getsetting("innfee", "5%");
					if (strpos($fee, "%"))
						$bankexpense = $expense + round($expense * $fee / 100,0);
					else
						$bankexpense = $expense + $fee;
					if ($session['user']['goldinbank'] >= $bankexpense && $bankexpense != $expense) {
						output("Weil du so eine nette Person bist, bietet er dir zum Preis von `\$".$bankexpense."`0 Gold auch an, direkt von der Bank zu bezahlen. Der Preis beinhaltet " . (strpos($fee, "%") ? $fee : "$fee Gold") . " Überweisungsgebühr.");
					}
								
					output("`n`nDu willst dich nicht von deinem Gold trennen und fängst an darüber zu debattieren, dass man in den Feldern auch kostenlos "
								."schlafen könnte. Schließlich siehst du aber ein, dass ein Zimmer in der Schenke vielleicht der sicherere Platz zum Schlafen ist, da es schwieriger für Herumstreicher sein dürfte, "
								."in einen verschlossenen Raum einzudringen.");
					addnav("Gib ihm $expense Gold","inn.php?op=room&pay=1");
					if ($session['user']['goldinbank'] >= $bankexpense) {
						addnav("Zahle $bankexpense Gold von der Bank","inn.php?op=room&pay=2");
					}
				}
			}
			break;
	}
  addnav("Zurück zur Schenke","inn.php");
}

output("</span>",true);

page_footer();
?>
