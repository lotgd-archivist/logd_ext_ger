<?php

// 24072004

require_once "common.php";
$balance = getsetting("creaturebalance", 0.33);

// Handle updating any commentary that might be around.
addcommentary();

//savesetting("creaturebalance","0.33");
if ($_GET[op]=="darkhorse"){
	$_GET[op]="";
	$session[user][specialinc]="darkhorse.php";
}
if ($_GET[op]=="castle"){
	$_GET[op]="";
	$session[user][specialinc]="castle.php";
}
$fight = false;
page_header("Der Wald");
if ($session[user][superuser]>1 && $HTTP_GET_VARS[specialinc]!=""){
  $session[user][specialinc] = $HTTP_GET_VARS[specialinc];
}
if ($session[user][specialinc]!=""){
  //echo "$x including special/".$session[user][specialinc];
	
	output("`^`c`bEtwas Besonderes!`c`b`0");
	$specialinc = $session[user][specialinc];
	$session[user][specialinc] = "";
	include("special/".$specialinc);
	if (!is_array($session['allowednavs']) || count($session['allowednavs'])==0) {
		forest(true);
		//output(serialize($session['allowednavs']));
	}
	page_footer();
	exit();
}
if ($HTTP_GET_VARS[op]=="run"){
	if (e_rand()%3 == 0){
		output ("`c`b`&Du bist erfolgreich vor deinem Gegner geflohen!`0`b`c`n");
		$session[user][reputation]--;
		$HTTP_GET_VARS[op]="";
	}else{
		output("`c`b`\$Dir ist es nicht gelungen deinem Gegner zu entkommen!`0`b`c");
	}
}
if ($HTTP_GET_VARS[op]=="dragon"){
	addnav("Betrete die Höhle","dragon.php");
	addnav("Renne weg wie ein Baby","inn.php");
	output("`\$Du betrittst den dunklen Eingang einer Höhle in den Tiefen des Waldes, ");
	output(" im Umkreis von mehreren hundert Metern sind die Bäume bis zu den Stümpfen niedergebrannt.  ");
	output("Rauchschwaden steigen an der Decke des Höhleneinganges empor und werden plötzlich ");
	output("von einer kalten Windböe verweht.  Der Eingang der Höhle liegt an der Seite eines Felsens ");
	output("ein Dutzent Meter über dem Boden des Waldes, wobei Geröll eine kegelförmige ");
	output("Rampe zum Eingang bildet.  Stalaktiten und Stalagmiten nahe des Einganges ");
	output("erwecken in dir dein Eindruck, dass der Höhleneingang in Wirklichkeit ");
	output("das Maul einer riesigen Bestie ist.  ");
	output("`n`nAls du vorsichtig den Eingang der Höhle betrittst, hörst - oder besser fühlst du, ");
	output("ein lautes Rumpeln, das etwa dreißig Sekunden andauert, bevor es wieder verstummt ");
	output("Du bemerkst, dass dir ein Schwefelgeruch entgegenkommt.  Das Poltern ertönt erneut, und hört wieder auf, ");
	output("in einem regelmäßigen Rhythmus.  ");
	output("`n`nDu kletterst den Geröllhaufen rauf, der zum Eingang der Höhle führt. Deine Schritte zerbrechen ");
	output("die scheinbaren Überreste ehemaliger Helden.");
	output("`n`nJeder Instinkt in deinem Körper will fliehen und so schnell wie möglich zurück ins warme Wirtshaus und ");
	output(" ".($session[user][sex]?"zum noch wärmeren Seth":"zur noch wärmeren Violet").".  Was tust du?");
	$session[user][seendragon]=1;
}
if ($HTTP_GET_VARS[op]=="search"){
	checkday();
  if ($session[user][turns]<=0){
    output("`\$`bDu bist zu müde um heute den Wald weiter zu durchsuchen. Vielleicht hast du morgen mehr Energie dazu.`b`0");
    $HTTP_GET_VARS[op]="";
  }else{
	  $session[user][drunkenness]=round($session[user][drunkenness]*.9,0);
  	$specialtychance = e_rand()%7;
  	if ($specialtychance==0){
  		output("`^`c`bEtwas Besonderes!`c`b`0");
			if ($handle = opendir("special")){
			  $events = array();
			  while (false !== ($file = readdir($handle))){
				  if (strpos($file,".php")>0){
				  	  // Skip the darkhorse if the horse knows the way
					  if ($session['user']['hashorse'] > 0 && 
					  	  $playermount['tavern'] > 0 &&
						  strpos($file, "darkhorse") !== false) {
					  	continue;
					  }
					  array_push($events,$file);
					}
				}
				$x = e_rand(0,count($events)-1);
				if (count($events)==0){
				  output("`b`@Arrr, dein Administrator hat entschieden, dass es dir nicht erlaubt ist, besondere Ereignisse zu haben.  Beschwer dich bei ihm, nicht beim Programmierer.");
				}else{
				  $y = $HTTP_GET_VARS[op];
					$HTTP_GET_VARS[op]="";
				  //echo "$x including special/".$events[$x];
				  include("special/".$events[$x]);
					$HTTP_GET_VARS[op]=$y;
				}
			}else{
			  output("`c`b`\$FEHLER!!!`b`c`&Es ist nicht möglich die besonderen Ereignisse zu öffnen! Bitte benachrichtige den Administrator!!");
			}
  		if ($nav=="") forest(true);
  	}else{
      $session[user][turns]--;
  		$battle=true;
			if (e_rand(0,2)==1){
				$plev = (e_rand(1,5)==1?1:0);
				$nlev = (e_rand(1,3)==1?1:0);
			}else{
			  $plev=0;
				$nlev=0;
			}
			if ($HTTP_GET_VARS['type']=="slum"){
			  $nlev++;
				output("`\$Du steuerst den Abschnitt des Waldes an, von dem du weißt, dass sich dort Feinde aufhalten, die dir ein bisschen angenehmer sind.`0`n");
				$session[user][reputation]--;
			}
			if ($HTTP_GET_VARS['type']=="thrill"){
			  $plev++;
				output("`\$Du steuerst den Abschnitt des Waldes an, in dem sich Kreaturen deiner schlimmsten Albträume aufhalten, in der Hoffnung dass Du eine findest die verletzt ist.`0`n");
				$session[user][reputation]++;
			}
			$targetlevel = ($session['user']['level'] + $plev - $nlev );
			if ($targetlevel<1) $targetlevel=1;
			$sql = "SELECT * FROM creatures WHERE creaturelevel = $targetlevel ORDER BY rand(".e_rand().") LIMIT 1";
			$result = db_query($sql) or die(db_error(LINK));
			$badguy = db_fetch_assoc($result);
			$expflux = round($badguy['creatureexp']/10,0);
			// more XP per DK
//			$badguy['creatureexp']+=round($session['user']['dragonkills']/300 * $badguy['creatureexp']);

			$expflux = e_rand(-$expflux,$expflux);
			$badguy['creatureexp']+=$expflux;

			//make badguys get harder as you advance in dragon kills.
			//output("`#Debug: badguy gets `%$dk`# dk points, `%+$atkflux`# attack, `%+$defflux`# defense, +`%$hpflux`# hitpoints.`n");
			$badguy['playerstarthp']=$session['user']['hitpoints'];
			$dk = 0;
			while(list($key, $val)=each($session[user][dragonpoints])) {
				if ($val=="at" || $val=="de") $dk++;
			}
			$dk += (int)(($session['user']['maxhitpoints']-
				($session['user']['level']*10))/5);
			if (!$beta) $dk = round($dk * 0.25, 0);
			else $dk = round($dk,0);

			$atkflux = e_rand(0, $dk);
			if ($beta) $atkflux = min($atkflux, round($dk/4));
			$defflux = e_rand(0, ($dk-$atkflux));
			if ($beta) $defflux = min($defflux, round($dk/4));
			$hpflux = ($dk - ($atkflux+$defflux)) * 5;
			$badguy['creatureattack']+=$atkflux;
			$badguy['creaturedefense']+=$defflux;
			$badguy['creaturehealth']+=$hpflux;
			if ($beta) {
				$badguy['creaturedefense']*=0.66;
				$badguy['creaturegold']*=(1+(.05*$dk));
				if ($session['user']['race']==4) $badguy['creaturegold']*=1.1;
			} else {
				if ($session['user']['race']==4) $badguy['creaturegold']*=1.2;
			}
			$badguy['diddamage']=0;
			$session['user']['badguy']=createstring($badguy);
			if ($beta) {
				if ($session['user']['superuser']>=3){
					output("Debug: $dk dragon points.`n");
					output("Debug: +$atkflux attack.`n");
					output("Debug: +$defflux defense.`n");
					output("Debug: +$hpflux health.`n");
				} 
			}
		}
	}
}
if ($HTTP_GET_VARS[op]=="fight" || $HTTP_GET_VARS[op]=="run"){
	$battle=true;
}
if ($battle){
  include("battle.php");
//	output(serialize($badguy));
	if ($victory){
		if (getsetting("dropmingold",0)){
			$badguy[creaturegold]=e_rand($badguy[creaturegold]/4,3*$badguy[creaturegold]/4);
		}else{
			$badguy[creaturegold]=e_rand(0,$badguy[creaturegold]);
		}
		$expbonus = round(
			($badguy[creatureexp] *
				(1 + .25 *
					($badguy[creaturelevel]-$session[user][level])
				)
			) - $badguy[creatureexp],0
		);
		output("`b`&$badguy[creaturelose]`0`b`n"); 
		output("`b`\$Du hast $badguy[creaturename] erledigt!`0`b`n");
		output("`#Du erbeutest `^$badguy[creaturegold]`# Goldstücke!`n");
		if ($badguy['creaturegold']) {
			//debuglog("received {$badguy['creaturegold']} gold for slaying a monster.");
		}

		//find something
		$findit=e_rand(1,27);
		if ($findit == 2) { //gem
			output("`&Du findest EINEN EDELSTEIN!`n`#");
		  	$session['user']['gems']++;
		  	//debuglog("found a gem when slaying a monster.");
		}
		if ($findit == 5) $session['user']['donation']+=1;

		if ($findit == 20 && e_rand(1,4)==3){ // item
			$sql="SELECT * FROM items WHERE owner=0 AND (class='Beute.Prot' OR class='Zaub.Prot') ORDER BY rand(".e_rand().") LIMIT 1";
			$result = db_query($sql) or die(db_error(LINK));
			$row2 = db_fetch_assoc($result);
			if ($row2[name]){
				if ($row2['class']=="Beute.Prot"){
					$sql="INSERT INTO items(name,class,owner,gold,gems,description) VALUES ('".addslashes($row2[name])."','Beute',".$session[user][acctid].",$row2[gold],$row2[gems],'".addslashes($row2[description])."')";
				}else if ($row2['class']=="Zaub.Prot"){
					$row2[description].=" (gebraucht)";
					$row2[value1]=e_rand(1,$row2[value2]);
					$row2[gold]=$row2[gold]*(($row2[value1]+1)/($row2[value2]+1));
					$sql="INSERT INTO items(name,class,owner,gold,gems,value1,value2,hvalue,description,buff) VALUES ('".addslashes($row2[name])."','Zauber',".$session[user][acctid].",$row2[gold],0,$row2[value1],$row2[value2],$row2[hvalue],'".addslashes($row2[description])."','".addslashes($row2[buff])."')";
				}else{
					$sql = "UPDATE items SET owner=".$session[user][acctid]." WHERE id=$row2[id]";
				}
				db_query($sql) or die(sql_error($sql));
				output("`n`qBeim Durchsuchen von $badguy[creaturename] `qfindest du `&$row2[name]`q! ($row2[description])`n`n`#");
			}
		}
		if ($findit == 25 && e_rand(1,6)==2){ // armor
			$sql = "SELECT * FROM armor WHERE defense<=".$session[user][level]." ORDER BY rand(".e_rand().") LIMIT 1";
			$result2 = db_query($sql) or die(db_error(LINK));
			if (db_num_rows($result2)>0){
				$row2 = db_fetch_assoc($result2);
				$row2['value']=round($row2['value']/10);
				$sql="INSERT INTO items(name,class,owner,gold,value1,description) VALUES ('".addslashes($row2[armorname])."','Rüstung',".$session[user][acctid].",$row2[value],$row2[defense],'Gebrauchte Level $row2[level] Rüstung mit $row2[defense] Verteidigung.')";
				db_query($sql) or die(sql_error($sql));
				output("`n`QBeim Durchsuchen von $badguy[creaturename] `Qfindest du die Rüstung `%$row2[armorname]`Q!`n`n`#");
			}
		}
		if ($findit == 26 && e_rand(1,6)==2){ // weapon
			$sql = "SELECT * FROM weapons WHERE damage<=".$session[user][level]." ORDER BY rand(".e_rand().") LIMIT 1";
			$result2 = db_query($sql) or die(db_error(LINK));
			if (db_num_rows($result2)>0){
				$row2 = db_fetch_assoc($result2);
				$row2['value']=round($row2['value']/10);
				$sql="INSERT INTO items(name,class,owner,gold,value1,description) VALUES ('".addslashes($row2[weaponname])."','Waffe',".$session[user][acctid].",$row2[value],$row2[damage],'Gebrauchte Level $row2[level] Waffe mit $row2[damage] Angriffswert.')";
				db_query($sql) or die(sql_error($sql));
				output("`n`QBeim Durchsuchen von $badguy[creaturename] `Qfindest du die Waffe `%$row2[weaponname]`Q!`n`n`#");
			}
		}

		if ($expbonus>0){
		  output("`#*** Durch die hohe Schwierigkeit des Kampfes erhältst du zusätzlich `^$expbonus`# Erfahrungspunkte! `n($badguy[creatureexp] + ".abs($expbonus)." = ".($badguy[creatureexp]+$expbonus).") ");
		}else if ($expbonus<0){
		  output("`#*** Weil dieser Kampf so leicht war, verlierst du `^".abs($expbonus)."`# Erfahrungspunkte! `n($badguy[creatureexp] - ".abs($expbonus)." = ".($badguy[creatureexp]+$expbonus).") ");
		}
		output("Du bekommst insgesamt `^".($badguy[creatureexp]+$expbonus)."`# Erfahrungspunkte!`n`0");
		$session[user][gold]+=$badguy[creaturegold];
		$session[user][experience]+=($badguy[creatureexp]+$expbonus);
		$creaturelevel = $badguy[creaturelevel];
		$HTTP_GET_VARS[op]="";
		//if ($session[user][hitpoints] == $session[user][maxhitpoints]){
		if ($badguy['diddamage']!=1){
			if ($session[user][level]>=getsetting("lowslumlevel",4) || $session[user][level]<=$creaturelevel){
				output("`b`c`&~~ Perfekter Kampf! ~~`\$`n`bDu erhältst eine Extrarunde!`c`0`n");
				$session[user][turns]++;
				if ($expbonus>0){
					$session['user']['donation']+=1;
				}
			}else{
				output("`b`c`&~~ Perfekter Kampf! ~~`b`\$`nEin schwierigerer Kampf hätte dir eine extra Runde gebracht.`c`n`0");
			}
		}
		$dontdisplayforestmessage=true;
		addhistory(($badguy['playerstarthp']-$session['user']['hitpoints'])/max($session['user']['maxhitpoints'],$badguy['playerstarthp']));
		$badguy=array();
	}else{
		if($defeat){
			addnav("Tägliche News","news.php");
			$sql = "SELECT taunt FROM taunts ORDER BY rand(".e_rand().") LIMIT 1";
			$result = db_query($sql) or die(db_error(LINK));
			$taunt = db_fetch_assoc($result);
			$taunt = str_replace("%s",($session[user][sex]?"sie":"ihn"),$taunt[taunt]);
			$taunt = str_replace("%o",($session[user][sex]?"sie":"er"),$taunt);
			$taunt = str_replace("%p",($session[user][sex]?"ihr":"sein"),$taunt);
			$taunt = str_replace("%x",($session[user][weapon]),$taunt);
			$taunt = str_replace("%X",$badguy[creatureweapon],$taunt);
			$taunt = str_replace("%W",$badguy[creaturename],$taunt);
			$taunt = str_replace("%w",$session[user][name],$taunt);
			addhistory(1);
			addnews("`%".$session[user][name]."`5 wurde im Wald von $badguy[creaturename] niedergemetzelt.`n$taunt");
			$session[user][alive]=false;
			debuglog("lost {$session['user']['gold']} gold when they were slain in the forest");
			$session[user][gold]=0;
			$session[user][hitpoints]=0;
			$session[user][experience]=round($session[user][experience]*.9,0);
			$session[user][badguy]="";
			output("`b`&Du wurdest von `%$badguy[creaturename]`& niedergemetzelt!!!`n");
			output("`4Dein ganzes Gold wurde dir abgenommen!`n");
			output("`410% deiner Erfahrung hast du verloren!`n");
			output("Du kannst morgen weiter kämpfen.");
			
			page_footer();
		}else{
		  fightnav();
		}
	}
}

if ($HTTP_GET_VARS[op]==""){
	// Need to pass the variable here so that we show the forest message
	// sometimes, but not others.
	forest($dontdisplayforestmessage);
}

page_footer();

function addhistory($value){
/*
	global $session,$balance;
	$history = unserialize($session['user']['history']);
	$historycount=50;
	for ($x=0;$x<$historycount;$x++){
		if (!isset($history[$x])) $history[$x]=$balance;
	}
	array_shift($history);
	array_push($history,$value);
	$history = array_values($history);
	for ($x=0;$x<$historycount;$x++){
		$history[$x] = round($history[$x],4);
		if ($session['user']['superuser']>=3) output("History: {$history[$x]}`n");
	}
	$session['user']['history']=serialize($history);
 */
}
?>
