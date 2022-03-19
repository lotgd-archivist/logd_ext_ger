<?php
/* 
 * slump
 * you fall over your own feet or a branche
 * your moneybag gets broken and all your money lays on the ground
 * most of the gold you can collect but some pieces are grabed by the newest player and the last dragonfighter
 * if they are set (like it is by anpera)
 *
 * region: Forest
 *
 * by bibir
 *
 * v.1.0 040422(yymmdd)basis erstellt
 */

checkday();

$newplayer=stripslashes(getsetting("newplayer",""));
$newdk=stripslashes(getsetting("newdragonkill",""));

$sql="SELECT acctid,name,goldinbank FROM accounts WHERE name like '".$newplayer."' LIMIT 1";
$result = db_query($sql) or die(db_error(LINK));
$rownew = db_fetch_assoc($result);

$sql="SELECT acctid,name,goldinbank FROM accounts WHERE name like '".$newdk."' LIMIT 1";
$result = db_query($sql) or die(db_error(LINK));
$rowdk = db_fetch_assoc($result);


output("`6Plötzlich stürzt du - waren es deine etwas ungeschickten Füße, oder doch ein Ast?`n");
output("Das spielt jetzt auch keine Rolle mehr, denn nun liegst du am Boden und bemerkst, dass dein Goldbeutel zerrissen ");
output("und das ganze Gold auf dem Boden verteilt ist.`n`n`0");

if ($session[user][gold]== 0) {
	output("`^Zum Glück hast du kein Gold, welches dir verloren gehen könnte.`n`n`0");

// falls selbst juengster spieler oder drachenkämpfer 
// oder kein juengster spieler und drachenkaempfer in den settings
}else if (($newplayer.$newdk == "")||($session[user][name] == $rownew[name])||($session[user][name] == $rowdk[name])){
	output("`^Schnell sammelst du dein Gold wieder ein und gehst deinen Weg weiter.`n`n`0");

}else if($rownew == ""){ //kein juengster spieler - nur juengster drachenkaempfer
//	output("`n`^es gibt keinen juengsten spieler`0`n");
	output("`6Schnell willst du das Gold wieder einsammeln, doch bei ein paar Goldstücken ist jemand schneller.`n`n");
	output("`3Du denkst: \"`#Der hat doch gerade den `@grünen Drachen `#erfolgreich bekämpft");
	output(" - wie war der Name noch gleich?`n");
	output("Ah,`0 $rowdk[name] `#muss es gewesen sein.`3\"`n`n`0");
	output("`6Jetzt ist es zu spät, so flink er beim Aufheben war, so flink ist er auch verschwunden.`0`n`n");
	//goldverteilung
	$save = round($session[user][gold]*0.8,0);
	$lost = round($session[user][gold]*0.2,0);
	$mailmessage = "`^".$session['user']['name']."`2 stürzte im Wald über einen Ast und verlor dabei ".($session['user']['sex']?"ihr":"sein")." Gold.`n`n"
		."Ein paar Goldstücke rollten dir dabei vor die Füße, die du aufgehoben und behalten hast. "
		."Die gefundenen `^ $lost `2Goldstücke hast du direkt zur Bank gebracht.`0";
 	systemmail($rowdk[acctid],"`2Du hast Gold im Wald gefunden",$mailmessage);
	$session[user][gold]=$save;
	$dkgain = $rowdk[goldinbank]+ $lost;
	output("`^Du hast wenigstens noch $save Goldstücke retten können.`0`n`n");
	$sql = "UPDATE accounts SET goldinbank=$dkgain WHERE acctid=$rowdk[acctid]";	
	db_query($sql); 

}else if($rowdk == ""){ //kein juengster drachenkaempfer nur juengster spieler
	output("`6Schnell willst du das Gold wieder einsammeln, doch bei ein paar Goldstücken ist jemand schneller.`n`n");
	output("`3Du denkst: \"`#der ist doch gerade neu in dieser Welt");
	output(" - wie war der Name noch gleich?`n");
	output("Ah,`0 $rownew[name] `#muss es gewesen sein.`3\"`n`n`0");
	//goldverteilung
	$save = round($session[user][gold]*0.8, 0);
	$lost = round($session[user][gold]*0.2, 0);
	$mailmessage = "`^".$session['user']['name']."`2 stürzte im Wald über einen Ast und verlor dabei ".($session['user']['sex']?"ihr":"sein")." Gold.`n`n"
		."Ein paar Goldstücke rollten dir dabei vor die Füße, die du aufgehoben und behalten hast. "
		."Die gefundenen `^ $lost `2Goldstücke hast du direkt zur Bank gebracht.`0";
 	systemmail($rownew[acctid],"`2Du hast Gold im Wald gefunden",$mailmessage); 
	$session[user][gold]=$save;
	$newgain = $rownew[goldinbank]+ $lost;
	output("`^Du hast wenigstens noch $save Goldstücke retten können.`0`n`n");
	$sql = "UPDATE accounts SET goldinbank=$newgain WHERE acctid=$rownew[acctid]";	
	db_query($sql);

}else {
	output("`6Schnell willst du das Gold wieder einsammeln, doch bei ein paar Goldstücken ist jemand schneller.`n`n");
	output("Den einen Dieb hast du doch gerade erst hier neu in der Welt gesehen, ");
	output("und der andere hatte eben den `@grünen Drachen `6erfolgreich bekämpft");
	output("- wie waren ihre Namen noch gleich?`n`n`0");
	output($rownew[name]."`# und `0".$rowdk[name]."`# müssen es gewesen sein.`3\"`6, denkst du. `n`n"); //name des juengsten spielers und drachentoeters
	output("Jetzt ist es zu spät, so flink sie beim Aufheben waren, so flink sind sie auch verschwunden.`n`n`0");
	// goldverteilung
	$save = round($session[user][gold]*0.7, 0);
	$newgain = round($session[user][gold]*0.2, 0);
	$dkgain =round($session[user][gold]*0.1, 0);
	$mailmessage1 = "`^".$session['user']['name']."`2 stürzte im Wald über einen Ast und verlor dabei ".($session['user']['sex']?"ihr":"sein")." Gold.`n`n"
		."Ein paar Goldstücke rollten dir dabei vor die Füße, die du aufgehoben und behalten hast. "
		."Die gefundenen `^ $dkgain `2Goldstücke hast du direkt zur Bank gebracht.`0";
 	systemmail($rowdk[acctid],"`2Du hast Gold im Wald gefunden",$mailmessage1); 
	$mailmessage2 = "`^".$session['user']['name']."`2 stürzte im Wald über einen Ast und verlor dabei ".($session['user']['sex']?"ihr":"sein")." Gold.`n`n"
		."Ein paar Goldstücke rollten dir dabei vor die Füße, die du aufgehoben und behalten hast. "
		."Die gefundenen `^ $newgain `2Goldstücke hast du direkt zur Bank gebracht.`0";
 	systemmail($rownew[acctid],"`2Du hast Gold im Wald gefunden",$mailmessage2); 
	$newgain += $rownew[goldinbank];
	$dkgain = $rowdk[goldinbank]+round($session[user][gold]*0.1, 0);
	$session[user][gold]=$save;
	output("`^Du hast wenigstens noch $save Goldstücke retten können.`0`n`n");
	$sql = "UPDATE accounts SET goldinbank=$newgain WHERE acctid=$rownew[acctid]";	
	db_query($sql);
	$sql = "UPDATE accounts SET goldinbank=$dkgain WHERE acctid=$rowdk[acctid]";	
	db_query($sql);
}

output("`@Du hast gelernt, dass man vorsichtig sein muss, wohin man seine Schritte setzt`n");
$reward=e_rand($session[user][experience]*0.05, $session[user][experience]*0.1);
//$reward+=10;
output("und erhältst `& $reward `@Erfahrungspunkte.`0");
$session[user][experience]+=$reward;

?>