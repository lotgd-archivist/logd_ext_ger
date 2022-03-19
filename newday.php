<?php

// 24072004

require_once "common.php";

/***************
 **  SETTINGS **
 ***************/
$turnsperday = getsetting("turns",10);
$maxinterest = ((float)getsetting("maxinterest",10)/100) + 1; //1.1;
$mininterest = ((float)getsetting("mininterest",1)/100) + 1; //1.1;
//$mininterest = 1.01;
$dailypvpfights = getsetting("pvpday",3);

if ($_GET['resurrection']=="true") {
	$resline = "&resurrection=true";
} else if ($_GET['resurrection']=="egg") {
	$resline = "&resurrection=egg";
} else {
	$resline = "";
}

// $resline = $_GET['resurrection']=="true" ? "&resurrection=true" : "" ;
/******************
 ** End Settings **
 ******************/
if (count($session['user']['dragonpoints']) <$session['user']['dragonkills']&&$_GET['dk']!=""){
	array_push($session['user']['dragonpoints'],$_GET[dk]);
	switch($_GET['dk']){
	case "hp":
		$session['user']['maxhitpoints']+=5;
		break;
	case "at":
		$session['user']['attack']++;
		break;
	case "de":
		$session['user']['defence']++;
		break;	
	}
}
if (count($session['user']['dragonpoints'])<$session['user']['dragonkills'] && $_GET['dk']!="ignore"){
	page_header("Drachenpunkte");
	addnav("Max Lebenspunkte +5","newday.php?dk=hp$resline");
	addnav("Waldkämpfe +1","newday.php?dk=ff$resline");
	addnav("Angriff + 1","newday.php?dk=at$resline");
	addnav("Verteidigung + 1","newday.php?dk=de$resline");
	//addnav("Ignore (Dragon Points are bugged atm)","newday.php?dk=ignore$resline");
	output("`@Du hast noch `^".($session['user']['dragonkills']-count($session['user']['dragonpoints']))."`@  Drachenpunkte übrig. Wie willst du sie einsetzen?`n`n");
	output("Du bekommst 1 Drachenpunkt pro getötetem Drachen. Die Änderungen der Eigenschaften durch Drachenpunkte sind permanent.");
}else if ((int)$session['user']['race']==0){
	page_header("Ein wenig über deine Vorgeschichte");
	if ($_GET['setrace']!=""){
		$session['user']['race']=(int)($_GET['setrace']);
		switch($_GET['setrace']){
		case "1":
			$session['user']['attack']++;
			output("`2Als Troll warst du immer auf dich alleine gestellt. Die Möglichkeiten des Kampfs sind dir nicht fremd.`n`^Du erhältst einen zusätzlichen Punkt auf deinen Angriffswert!");
			break;
		case "2":
			$session['user']['defence']++;
			output("`^Als Elf bist du dir immer allem bewusst, was um dich herum passiert. Nur sehr wenig kann dich überraschen.`nDu bekommst einen zusätzlichen Punkt auf deinen Verteidigungswert!");
			break;
		case "3":
			output("`&Deine Größe und Stärke als Mensch erlaubt es dir, Waffen ohne große Anstrengungen zu führen und dadurch länger durchzuhalten, als andere Rassen.`n`^Du hast jeden Tag einen zusätzlichen Waldkampf!");
			break;
		case "4":
			output("`#Als Zwerg fällt es dir leicht, den Wert bestimmter Güter besser einzuschätzen.`n`^Du bekommst mehr Gold durch Waldkämpfe!");
			break;
		case "5":
			output("`5Als Echsenwesen hast du durch deine Häutungen einen klaren gesundheitlichen Vorteil gegenüber anderen Rassen.`n`^Du startest mit einem permanenten Lebenspunkt mehr!");
			$session['user']['maxhitpoints']++;
			break;
		}
		addnav("Weiter","newday.php?continue=1$resline");
		if ($session['user']['dragonkills']==0 && $session['user']['level']==1){
			addnews("`#{$session[user][name]} `#hat unsere Welt betreten. Willkommen!");
		}
	}else{
		output("Wo bist du aufgewachsen?`n`n");
		output("<a href='newday.php?setrace=1$resline'>In den Sümpfen von Glukmoore</a> als `2Troll`0, auf dich alleine gestellt seit dem Moment, als du aus der lederartigen Hülle deines Eis geschlüpft bist und aus den Knochen deiner ungeschlüpften Geschwister ein erstes Festmahl gemacht hast.`n`n",true);
		output("<a href='newday.php?setrace=2$resline'>Hoch über den Bäumen</a> des Waldes Glorfindal, in zerbrechlich wirkenden, kunstvoll verzierten Bauten der `^Elfen`0, die so aussehen, als ob sie beim leisesten Windhauch zusammenstürzen würden und doch schon Jahrhunderte überdauern.`n`n",true);
		output("<a href='newday.php?setrace=3$resline'>Im Flachland in der Stadt Romar</a>, der Stadt der `&Menschen`0. Du hast immer nur zu deinem Vater aufgesehen und bist jedem seiner Schritte gefolgt, bis er auszog den `@Grünen Drachen`0 zu vernichten und nie wieder gesehen wurde.`n`n",true);
		output("<a href='newday.php?setrace=4$resline'>Tief in der Unterirdischen Festung Qexelcrag</a>, der Heimat der edlen und starken `#Zwerge`0, deren Verlangen nach Besitz und Reichtum in keinem Verhältnis zu ihrer Körpergrösse steht.`n`n",true);
		output("<a href='newday.php?setrace=5$resline'>In einem Erdloch in der öden Landschaft</a> weit außerhalb jeder Siedlung bist du als `5Echsenwesen`0 aus deinem Ei geschlüpft. Artverwandt mit den Drachen hast du es nicht leicht in dieser Welt.`n`n",true);
		addnav("Wähle deine Rasse");
		addnav("`2Troll`0","newday.php?setrace=1$resline");
		addnav("`^Elf`0","newday.php?setrace=2$resline");
		addnav("`&Mensch`0","newday.php?setrace=3$resline");
		addnav("`#Zwerg`0","newday.php?setrace=4$resline");
		addnav("`5Echse`0","newday.php?setrace=5$resline");
		addnav("","newday.php?setrace=1$resline");
		addnav("","newday.php?setrace=2$resline");
		addnav("","newday.php?setrace=3$resline");
		addnav("","newday.php?setrace=4$resline");
		addnav("","newday.php?setrace=5$resline");
	}
}else if ((int)$session['user']['specialty']==0){
  if ($HTTP_GET_VARS['setspecialty']===NULL){
		addnav("","newday.php?setspecialty=1$resline");
		addnav("","newday.php?setspecialty=2$resline");
		addnav("","newday.php?setspecialty=3$resline");
		page_header("Ein wenig über deine Vorgeschichte");
		
		output("Du erinnerst dich, dass du als Kind:`n`n");
		output("<a href='newday.php?setspecialty=1$resline'>viele Kreaturen des Waldes getötet hast (`\$Dunkle Künste`0)</a>`n",true);
		output("<a href='newday.php?setspecialty=2$resline'>mit mystischen Kräften experimentiert hast (`%Mystische Kräfte`0)</a>`n",true);
		output("<a href='newday.php?setspecialty=3$resline'>von den Reichen gestohlen und es dir selbst gegeben hast (`^Diebeskunst`0)</a>`n",true);
		addnav("`\$Dunkle Künste","newday.php?setspecialty=1$resline");
		addnav("`%Mystische Kräfte","newday.php?setspecialty=2$resline");
		addnav("`^Diebeskünste","newday.php?setspecialty=3$resline");
  }else{
	  addnav("Weiter","newday.php?continue=1$resline");
		switch($HTTP_GET_VARS['setspecialty']){
		  case 1:
			  page_header("Dunkle Künste");
				output("`5Du erinnerst dich, dass du damit aufgewachsen bist, viele kleine Waldkreaturen zu töten, weil du davon überzeugt warst, sie haben sich gegen dich verschworen. ");
				output("Deine Eltern haben dir einen idiotischen Zweig gekauft, weil sie besorgt darüber waren, dass du die Kreaturen des Waldes mit bloßen Händen töten musst. ");
				output("Noch vor deinem Teenageralter hast du damit begonnen, finstere Rituale mit und an den Kreaturen durchzuführen, wobei du am Ende oft tagelang im Wald verschwunden bist. ");
				output("Niemand außer dir wusste damals wirklich, was die Ursache für die seltsamen Geräusche aus dem Wald war...");
				break;
			case 2:
			  page_header("Mystische Kräfte");
				output("`3Du hast schon als Kind gewusst, dass diese Welt mehr als das Physische bietet, woran du herumspielen konntest. ");
				output("Du hast erkannt, dass du mit etwas Training deinen Geist selbst in eine Waffe verwandeln kannst. ");
				output("Mit der Zeit hast du gelernt, die Gedanken kleiner Kreaturen zu kontrollieren und ihnen deinen Willen aufzuzwingen. ");
				output("Du bist auch auf die mystische Kraft namens Mana gestossen, die du in die Form von Feuer, Wasser, Eis, Erde, Wind bringen und sogar als Waffe gegen deine Feinde einsetzen kannst.");
				break;
			case 3:
			  page_header("Diebeskünste");
				output("`6Du hast schon sehr früh bemerkt, dass ein gewöhnlicher Rempler im Gedränge dir das Gold eines vom Glück bevorzugteren Menschen einbringen kann. ");
				output("Außerdem hast du entdeckt, dass der Rücken deiner Feinde anfälliger gegen kleine Klingen ist, als deren Vorderseite gegen mächtige Waffen.");
				break;
		}
		$session['user']['specialty']=$HTTP_GET_VARS['setspecialty'];
	}
}else{
  if ($session['user']['slainby']!=""){
		page_header("Du wurdest umgebracht!");
		output("`\$Im ".$session['user']['killedin']." hat dich `%".$session['user']['slainby']."`\$ getötet und dein Gold genommen. Ausserdem hast du 5% deiner Erfahrungspunkte verloren. Meinst du nicht auch, es ist Zeit für Rache?");
		addnav("Weiter","newday.php?continue=1$resline");
	  $session['user']['slainby']="";
	}else{
		page_header("Es ist ein neuer Tag!");
		$interestrate = e_rand($mininterest*100,$maxinterest*100)/(float)100;
		output("`c<font size='+1'>`b`#Es ist ein neuer Tag!`0`b</font>`c",true);
if (!$session['user']['prefs']['nosounds']) output("<embed src=\"media/newday.wav\" width=10 height=10 autostart=true loop=false hidden=true volume=100>",true);

		if ($session['user']['alive']!=true){
			$session['user']['resurrections']++;
			output("`@Du bist wiedererweckt worden! Dies ist der Tag deiner ".ordinal($session['user']['resurrections'])." Wiederauferstehung.`0`n");
			$session['user']['alive']=true;
		}
		$session[user][age]++;
		$session[user][seenmaster]=0;
		output("Du öffnest deine Augen und stellst fest, dass dir ein neuer Tag geschenkt wurde. Dies ist dein `^".ordinal($session['user']['age'])."`0 Tag in diesem Land. ");
		output("Du fühlst dich frisch und bereit für die Welt!`n");
		output("`2Runden für den heutigen Tag: `^$turnsperday`n");


		if ($session[user][goldinbank]<0 && abs($session[user][goldinbank])<(int)getsetting("maxinbank",10000)){
			output("`2Heutiger Zinssatz: `^".(($interestrate-1)*100)."% `n");
			output("`2Zinsen für Schulden: `^".-(int)($session['user']['goldinbank']*($interestrate-1))."`2 Gold.`n");
		}else if ($session[user][goldinbank]<0 && abs($session[user][goldinbank])>=(int)getsetting("maxinbank",10000)){
			output("`4Die Bank erlässt dir deine Zinsen, da du schon hoch genug verschuldet bist.`n");
			$interestrate=1;
		}else if ($session[user][goldinbank]>=0 && $session[user][goldinbank]>=(int)getsetting("maxinbank",10000) && $session['user']['turns']<=getsetting("fightsforinterest",4)){
			$interestrate=1;
			output("`4Die Bank kann dir heute keinen Zinsen zahlen. Sie würde früher oder später an dir pleite gehen.`n");
		}else if ($session[user][goldinbank]>=0 && $session[user][goldinbank]<(int)getsetting("maxinbank",10000) && $session['user']['turns']<=getsetting("fightsforinterest",4)){
			output("`2Heutiger Zinssatz: `^".(($interestrate-1)*100)."% `n");
			output("`2Durch Zinsen verdientes Gold: `^".(int)($session['user']['goldinbank']*($interestrate-1))."`n");
		}else{
			$interestrate=1;
			output("`2Dein heutiger Zinssatz beträgt `^0% (Die Bank gibt nur den Leuten Zinsen, die dafür arbeiten)`n");
		}


/*
		if ($session['user']['turns']>getsetting("fightsforinterest",4) && $session['user']['goldinbank']>=0) {
			$interestrate=1;
			output("`2Today's interest rate: `^0% (Bankers in this village only give interest to those who work for it)`n");
		}else{
			output("`2Today's interest rate: `^".(($interestrate-1)*100)."% `n");
			if (abs($session['user']['goldinbank'])>(int)getsetting("maxinbank",10000)){
				 if ($session['user']['goldinbank']>=0 ){
					output("`4Die Bank kann dir heute keinen Zinsen zahlen. Sie würde früher oder später an dir pleite gehen.`n");
				}else{
					output("`4Die Bank erlässt dir deine Zinsen, da du schon hoch genug verschuldet bist.`n");
				}
				$interestrate=1;
			}else if ($session['user']['goldinbank']>=0 ){
				output("`2Gold earned from interest: `^".(int)($session['user']['goldinbank']*($interestrate-1))."`n");
			}else{
				output("`2Zinsen für Schulden: `^".-(int)($session['user']['goldinbank']*($interestrate-1))."`2 Gold.`n");
			}
		}
*/
		output("`2Deine Gesundheit wurde wiederhergestellt auf `^".$session['user']['maxhitpoints']."`n");
		$skills = array(1=>"Dunkle Künste","Mystische Kräfte","Diebeskünste");
		$sb = getsetting("specialtybonus",1);
		output("`2Für dein Spezialgebiet `&".$skills[$session['user']['specialty']]."`2, erhältst du zusätzlich $sb Anwendung(en) in `&".$skills[$session['user']['specialty']]."`2 für heute.`n");
		$session['user']['darkartuses'] = (int)($session['user']['darkarts']/3) + ($session['user']['specialty']==1?$sb:0);
		$session['user']['magicuses'] = (int)($session['user']['magic']/3) + ($session['user']['specialty']==2?$sb:0);
		$session['user']['thieveryuses'] = (int)($session['user']['thievery']/3) + ($session['user']['specialty']==3?$sb:0);
		//$session['user']['bufflist']=array(); // with this here, buffs are always wiped, so the preserve stuff fails!
		if ($session['user']['marriedto']==4294967295 || $session['user']['charisma']==4294967295){
			output("`n`%Du bist verheiratet, es gibt also keinen Grund mehr, das perfekte Image aufrecht zu halten. Du lässt dich heute ein bisschen gehen.`n Du verlierst einen Charmepunkt.`n");
			$session['user']['charm']--;
			if ($session['user']['charm']<=0){
				output("`n`bAls du heute aufwachst, findest du folgende Notiz neben dir im Bett:`n`5".($session[user][sex]?"Liebste":"Liebster")."");
				output("".$session['user']['name']."`5.");
				output("`nTrotz vieler großartiger Küsse, fühle ich mich einfach nicht mehr so zu dir hingezogen wie es früher war.`n`n");
				output("Nenne mich wankelmütig, aber ich muss weiterziehen. Es gibt andere Krieger".($session[user][sex]?"innen":"")." in diesem Dorf und ich glaube, ");
				output("einige davon sind wirklich heiss. Es liegt also nicht an dir, sondern an mir, usw. usw.");
  				$sql = "SELECT acctid,name FROM accounts WHERE locked=0 AND acctid=".$session[user][marriedto]."";
  				$result = db_query($sql) or die(db_error(LINK));
				$row = db_fetch_assoc($result);
				$partner=$row[name];
				if ($partner=="") $partner = $session[user][sex]?"Seth":"Violet";
				output("`n`nSei nicht traurig!`nIn Liebe, $partner`b`n");
				addnews("`\$$partner `\$hat {$session['user']['name']}`\$ für \"andere Interessen\" verlassen!");
				if ($session['user']['marriedto']==4294967295) $session['user']['marriedto']=0;
				if ($session['user']['charisma']==4294967295){
					 $session['user']['charisma']=0;
					$session['user']['marriedto']=0;
					$sql = "UPDATE accounts SET charisma=0,marriedto=0 WHERE acctid='$row[acctid]'";
					db_query($sql);
					systemmail($row['acctid'],"`\$Wieder solo!`0","`6Du hast `&{$session['user']['name']}`6 verlassen. ".($session[user][sex]?"Sie":"Er")." war einfach widerlich in letzter Zeit.");
				}
			}
		}

		//clear all standard buffs
		$tempbuf = unserialize($session['user']['bufflist']);
		$session['user']['bufflist']="";
		$session['bufflist']=array();
		while(list($key,$val)=@each($tempbuff)){
			if ($val['survivenewday']==1){
				$session['bufflist'][$key]=$val;
				output("{$val['newdaymessage']}`n");
			}
		}

		reset($session['user']['dragonpoints']);
		$dkff=0;
		while(list($key,$val)=each($session['user']['dragonpoints'])){
			if ($val=="ff"){
				$dkff++;
			}
		}
		if ($session[user][hashorse]){
			$session['bufflist']['mount']=unserialize($playermount['mountbuff']);
		}
		if ($dkff>0) output("`n`2Du erhöhst deine Waldkämpfe um `^$dkff`2 durch verteilte Drachenpunkte!"); 
		$r1 = e_rand(-1,1);
		$r2 = e_rand(-1,1);
		$spirits = $r1+$r2;
		if ($_GET['resurrection']=="true"){
			addnews("`&{$session['user']['name']}`& wurde von `\$Ramius`& wiedererweckt.");
			$spirits=-6;
			$session['user']['deathpower']-=100;
			$session['user']['restorepage']="village.php?c=1";
		}
		if ($_GET['resurrection']=="egg"){
			addnews("`&{$session['user']['name']}`& hat das `^goldene Ei`& benutzt und entkam so dem Schattenreich.");
			$spirits=-6;
			//$session['user']['deathpower']-=100;
			$session['user']['restorepage']="village.php?c=1";
			savesetting("hasegg",stripslashes(0));
		}
		$sp = array((-6)=>"Auferstanden",(-2)=>"Sehr schlecht",(-1)=>"Schlecht","0"=>"Normal",1=>"Gut",2=>"Sehr gut");
		output("`n`2Dein Geist und deine Stimmung ist heute `^".$sp[$spirits]."`2!`n");
		if (abs($spirits)>0){
			output("`2Deswegen `^");
			if($spirits>0){
				output("bekommst du zusätzlich ");
			}else{
				output("verlierst du ");
			}
			output(abs($spirits)." Runden`2 für heute.`n");
		}
		$rp = $session['user']['restorepage'];
		$x = max(strrpos("&",$rp),strrpos("?",$rp));
		if ($x>0) $rp = substr($rp,0,$x);
		if (substr($rp,0,10)=="badnav.php"){
			addnav("Weiter","news.php");
		}else{
			addnav("Weiter",preg_replace("'[?&][c][=].+'","",$rp));
		}
		
		$session['user']['laston'] = date("Y-m-d H:i:s");
		$bgold = $session['user']['goldinbank'];
		$session['user']['goldinbank']*=$interestrate;
		$nbgold = $session['user']['goldinbank'] - $bgold;

		if ($nbgold != 0) {
			//debuglog(($nbgold >= 0 ? "earned " : "paid ") . abs($nbgold) . " gold in interest");
		}
		$session['user']['turns']=$turnsperday+$spirits+$dkff;
		if ($session[user][maxhitpoints]<6) $session[user][maxhitpoints]=6;
		$session['user']['hitpoints'] = $session[user][maxhitpoints];
		$session['user']['spirits'] = $spirits;
		$session['user']['playerfights'] = $dailypvpfights;
		$session['user']['transferredtoday'] = 0;
		$session['user']['amountouttoday'] = 0;
		$session['user']['seendragon'] = 0;
		$session['user']['seenmaster'] = 0;
		$session['user']['seenlover'] = 0;
		$session['user']['witch'] = 0;
		$session['user']['usedouthouse'] = 0;
		$session['user']['seenAcademy'] = 0;
		$session['user']['gotfreeale'] = 0;
		$session['user']['fedmount'] = 0;
		if ($_GET['resurrection']!="true" && $_GET['resurrection']!="egg" ){
			$session['user']['soulpoints']=50 + 5 * $session['user']['level'];
			$session['user']['gravefights']=getsetting("gravefightsperday",10);
			$session['user']['reputation']+=5;
		}
		$session['user']['seenbard'] = 0;
		$session['user']['boughtroomtoday'] = 0;
		$session['user']['lottery'] = 0;
		$session['user']['recentcomments']=$session['user']['lasthit'];
		$session['user']['lasthit'] = date("Y-m-d H:i:s");
		if ($session['user']['drunkenness']>66){
		  output("`&Wegen deines schrecklichen Katers wird dir 1 Runde für heute abgezogen.");
			$session['user']['turns']--;
		}
		
// following by talisman & JT
//Set global newdaysemaphore

       $lastnewdaysemaphore = convertgametime(strtotime(getsetting("newdaysemaphore","0000-00-00 00:00:00")));
       $gametoday = gametime();
        
        if (date("Ymd",$gametoday)!=date("Ymd",$lastnewdaysemaphore)){
            $sql = "LOCK TABLES settings WRITE";
            db_query($sql);

           $lastnewdaysemaphore = convertgametime(strtotime(getsetting("newdaysemaphore","0000-00-00 00:00:00")));
                                                                                
            $gametoday = gametime();
            if (date("Ymd",$gametoday)!=date("Ymd",$lastnewdaysemaphore)){
                //we need to run the hook, update the setting, and unlock.
                savesetting("newdaysemaphore",date("Y-m-d H:i:s"));
                $sql = "UNLOCK TABLES";
                db_query($sql);
                                                                                
			require_once "setnewday.php";	

            }else{
                //someone else beat us to it, unlock.
                $sql = "UNLOCK TABLES";
                db_query($sql);
				output("Somebody beat us to it");
            }
        }

	output("`nDer Schmerz in deinen wetterfühligen Knochen sagt dir das heutige Wetter: `6".$settings['weather']."`@.`n");
	if ($_GET['resurrection']==""){
		if ($session['user']['specialty']==1 && $settings['weather']=="Regnerisch"){
			output("`^`nDer Regen schlägt dir aufs Gemüt, aber erweitert deine Dunklen Künste. Du bekommst eine zusätzliche Anwendung.`n");
			$session[user][darkartuses]++;
			}	
		if ($session['user']['specialty']==2 and $settings['weather']=="Gewittersturm"){
			output("`^`nDie Blitze fördern deine Mystischen Kräfte. Du bekommst eine zusätzliche Anwendung.`n");
			$session[user][magicuses]++;
			}	
		if ($session['user']['specialty']==3 and $settings['weather']=="Neblig"){
			output("`^`nDer Nebel bietet Dieben einen zusätzlichen Vorteil. Du bekommst eine zusätzliche Anwendung.`n");
			$session[user][thieveryuses]++;
			}		
	}
//End global newdaysemaphore code and weather mod.

		if ($session['user']['hashorse']){
			//$horses=array(1=>"pony","gelding","stallion");
			//output("`n`&You strap your `%".$session['user']['weapon']."`& to your ".$horses[$session['user']['hashorse']]."'s saddlebags and head out for some adventure.`0");
			//output("`n`&Because you have a ".$horses[$session['user']['hashorse']].", you gain ".((int)$session['user']['hashorse'])." forest fights for today!`n`0");
			//$session['user']['turns']+=((int)$session['user']['hashorse']);
			output(str_replace("{weapon}",$session['user']['weapon'],"`n`&{$playermount['newday']}`n`0"));
			if ($playermount['mountforestfights']>0){
				output("`n`&Weil du ein(e/n) {$playermount['mountname']} besitzt, bekommst du `^".((int)$playermount['mountforestfights'])."`& Runden zusätzlich.`n`0");
				$session['user']['turns']+=(int)$playermount['mountforestfights'];
			}
		}else{
			output("`n`&Du schnallst dein(e/n) `%".$session['user']['weapon']."`& auf den Rücken und ziehst los ins Abenteuer.`0");
		}
		if ($session['user']['race']==3) {
			$session['user']['turns']++;
			output("`n`&Weil du ein Mensch bist, bekommst du `^1`& Waldkampf zusätzlich!`n`0");
		}
		$config = unserialize($session['user']['donationconfig']);
		if (!is_array($config['forestfights'])) $config['forestfights']=array();
		reset($config['forestfights']);
		while (list($key,$val)=each($config['forestfights'])){
			$config['forestfights'][$key]['left']--;
			output("`@Du bekommst eine Extrarunde für die Punkte auf `^{$val['bought']}`@.");
			$session['user']['turns']++;
			if ($val['left']>1){
				output(" Du hast `^".($val['left']-1)."`@ Tage von diesem Kauf übrig.`n");
			}else{
				unset($config['forestfights'][$key]);
				output(" Dieser Kauf ist damit abgelaufen.`n");
			}
		}
		if ($config['healer'] > 0) {
			$config['healer']--;
			if ($config['healer'] > 0) {
				output("`n`@Golinda ist bereit, dich noch {$config['healer']} weitere Tage zu behandeln.");
			} else {
				output("`n`@Golinda wird dich nicht länger behandeln.");
				unset($config['healer']);
			}
		}
		if ($config['goldmineday']>0) $config['goldmineday']=0;
		$session['user']['donationconfig']=serialize($config);
		if ($session['user']['hauntedby']>""){
			output("`n`n`)Du wurdest von {$session['user']['hauntedby']}`) heimgesucht und verlierst eine Runde!");
			$session['user']['turns']--;
			$session['user']['hauntedby']="";
		}
		// Ehre & Ansehen
		if ($session['user']['reputation']<=-50){
			$session['user']['reputation']=-50;
			output("`n`8Da du aufgrund deiner Ehrenlosigkeit häufig Steine in den Weg gelegt bekommst, kannst du heute 1 Runden weniger kämpfen. Außerdem sind deine Feinde vor dir gewarnt.`nDu solltest dringend etwas für deine Ehre tun!");
			$session['user']['turns']--;
			$session['user']['playerfights']--;
		}else if ($session['user']['reputation']<=-30){
			output("`n`8Deine Ehrenlosigkeit hat sich herumgesprochen! Deine Feinde sind vor dir gewarnt, weshalb dir heute 1 Spielerkampf weniger gelingen wird.`nDu solltest dringend etwas für deine Ehre tun!");
			$session['user']['playerfights']--;
		}else if ($session['user']['reputation']<-10){
			output("`n`8Da du aufgrund deiner Ehrenlosigkeit häufig Steine in den Weg gelegt bekommst, kannst du heute 1 Runde weniger kämpfen.");
			$session['user']['turns']--;
		}else if ($session['user']['reputation']>=30){
			if ($session['user']['reputation']>50) $session['user']['reputation']=50;
			output("`n`9Da du aufgrund deiner großen Ehrenhaftigkeit das Volk auf deiner Seite hast, kannst du heute 1 Runde und 1 Spielerkampf mehr kämpfen.");
			$session['user']['turns']++;
			$session['user']['playerfights']++;
		}else if ($session['user']['reputation']>10){
			output("`n`9Da du aufgrund deiner großen Ehrenhaftigkeit das Volk auf deiner Seite hast, kannst du heute 1 Runde mehr kämpfen.");
			$session['user']['turns']++;
		}

		$session['user']['drunkenness']=0;
		$session['user']['bounties']=0;
		// Buffs from items
		$sql="SELECT * FROM items WHERE (class='Fluch' OR class='Geschenk' OR class='Zauber') AND owner=".$session[user][acctid]." ORDER BY id";
		$result=db_query($sql);
		for ($i=0;$i<db_num_rows($result);$i++){
		  	$row = db_fetch_assoc($result);
			if (strlen($row[buff])>8){
				$row[buff]=unserialize($row[buff]);
				if ($row['class']!='Zauber') $session[bufflist][$row[buff][name]]=$row[buff];
				if ($row['class']=='Fluch') output("`n`G$row[name]`G nagt an dir.");
				if ($row['class']=='Geschenk') output("`n`1$row[name]`1: $row[description]");
			}
			if ($row[hvalue]>0){
				$row[hvalue]--;
				if ($row[hvalue]<=0){
					db_query("DELETE FROM items WHERE id=$row[id]");
					if ($row['class']=='Fluch') output(" Aber nur noch heute.");
					if ($row['class']=='Zauber') output("`n`Q$row[name]`Q hat seine Kraft verloren.");
				}else{
					$what="hvalue=$row[hvalue]";
					if ($row['class']=='Zauber') $what.=", value1=$row[value2]";
					db_query("UPDATE items SET $what WHERE id=$row[id]");
				}
			}
		}		
	}
}
page_footer();
?>
