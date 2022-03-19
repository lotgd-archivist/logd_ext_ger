<?php

// 1508004

require_once "common.php";
addcommentary();
$cost = $session[user][level]*20;
$gems=array(1=>1,2,3);
$costs=array(1=>4000-3*getsetting("selledgems",0),7800-6*getsetting("selledgems",0),11400-9*getsetting("selledgems",0));
$scost=1200-getsetting("selledgems",0);
if ($_GET[op]=="pay"){
	if ($session[user][gold]>=$cost){ // Gunnar Kreitz
//	if ($session[user][gold]>$cost){ // Eric Stevens
		$session[user][gold]-=$cost;
		//debuglog("spent $cost gold to speak to the dead");
		if ($_GET[was]=="flirt"){
			 redirect("gypsy.php?op=flirt2");
		} else {
			redirect("gypsy.php?op=talk");
		}
	}else{
		page_header("Zigeunerzelt");
		addnav("Zurück zum Dorf","village.php");
		output("`5Du bietest der alten Zigeunerin deine `^{$session[user][gold]}`5 Gold für die Beschwörungssitzung. Sie informiert dich, dass die Toten zwar tot, aber deswegen trotzdem nicht billig sind.");
	}
}elseif ($_GET[op]=="talk"){
	page_header("In tiefer Trance sprichst du mit den Schatten");
	// by nTE- with modifications from anpera
	$sql="SELECT name FROM accounts WHERE locked=0 AND loggedin=1 AND alive=0 AND laston>'".date("Y-m-d H:i:s",strtotime(date("r")."-".getsetting("LOGINTIMEOUT",900)." seconds"))."' ORDER BY login ASC"; 
	$result=db_query($sql) or die(sql_error($sql));
	$count=db_num_rows($result);
	$names=$count?"":"niemandem";
	for ($i=0;$i<$count;$i++){ 
		$row=db_fetch_assoc($result); 
		$names.="`^$row[name]"; 
		if ($i<$count) $names.=", "; 
	} 
	db_free_result($result); 
	output("`5Du fühlst die Anwesenheit von $names`5.`n`n"); 
	output("`5Solange du in tiefer Trance bist, kannst du mit den Toten sprechen:`n");
	viewcommentary("shade","Sprich zu den Toten",25,"spricht");
	addnav("Erwachen","village.php");
} else if ($_GET[op]=="flirt2"){ 
	page_header("In tiefer Trance sprichst du mit den Schatten");
	output("`5Die Zigeunerin versetzt dich in tiefe Trance.`n`% Du findest ".($session[user][sex]?"deinen Mann":"deine Frau")." im Land der Schatten und flirtest eine Weile mit ".($session[user][sex]?"ihm, um sein":"ihr, um ihr")." Leid zu lindern. ");
	output("`n`^Du bekommst einen Charmepunkt.");
	$session['bufflist']['lover']=array("name"=>"`!Schutz der Liebe","rounds"=>60,"wearoff"=>"`!Du vermisst deine große Liebe!`0","defmod"=>1.2,"roundmsg"=>"Deine große Liebe lässt dich an deine Sicherheit denken!","activate"=>"defense");
	$session['user']['charm']++;
	$session['user']['seenlover']=1;
	addnav("Erwachen","village.php");
}elseif($_GET[op]=="buy"){
	page_header("Zigeunerzelt");
	if ($session[user][transferredtoday]>getsetting("transferreceive",3)){
		output("`5Du hast heute schon genug Geschäfte gemacht. `6Vessa`5 hat keine Lust mit dir zu handeln. Warte bis morgen.");
	}else if ($session[user][gems]>getsetting("selledgems",0)) {
		output("`6Vessa`5wirft einen neidischen Blick auf dein Säckchen Edelsteine und beschließt, dir nichts mehr zu geben.");
	} else {
  	      	if ($session[user][gold]>=$costs[$_GET[level]]){
           			if (getsetting("selledgems",0) >= $_GET[level]) {
              				output( "`6Vessa`5 grapscht sich deine `^".($costs[$_GET[level]])."`5 Goldstücke und gibt dir im Gegenzug `#".($gems[$_GET[level]])."`5 Edelstein".($gems[$_GET[level]]>=2?"e":"").".`n`n");
              				$session[user][gold]-=$costs[$_GET[level]];
              				$session[user][gems]+=$gems[$_GET[level]];
				$session[user][transferredtoday]+=1;
              				if (getsetting("selledgems",0) - $_GET[level] < 1) {
                				savesetting("selledgems","0");
              				} else {
                				savesetting("selledgems",getsetting("selledgems",0)-$_GET[level]);
              				}
           			} else {
              				output("`6Vessa`5 teilt dir mit, dass sie nicht mehr so viele Edelsteine hat und bittet dich später noch einmal wiederzukommen.`n`n");
           			}
        		}else{
            			output( "`6Vessa`5 zeigt dir den Stinkefinger, als du versuchst, ihr weniger zu zahlen als ihre Edelsteine momentan Wert sind.`n`n");    
        		}
	}
	addnav("Zurück zum Dorf","village.php");
}elseif($_GET[op]=="sell"){
	page_header("Zigeunerzelt");
	$maxout = $session[user][level]*getsetting("maxtransferout",25);
    	if ($session[user][gems]<1){
        		output("`6Vessa`5 haut mit der Faust auf den Tisch und fragt dich, ob du sie veralbern willst. Du hast keinen Edelstein.`n`n");
	}else if ($session[user][transferredtoday]>getsetting("transferreceive",3)){
		output("`5Du hast heute schon genug Geschäfte gemacht. `6Vessa`5 hat keine Lust mit dir zu handeln. Warte bis morgen.");
    	}else{
        		output("`6Vessa`5 nimmt deinen Edelstein und gibt dir dafür $scost Goldstücke.`n`n");
        		$session[user][gold]+=$scost;
        		$session[user][gems]-=1;
        		savesetting("selledgems",getsetting("selledgems",0)+1);
		$session[user][transferredtoday]+=1;
    	}
	addnav("Zigeunerzelt","gypsy.php");
	addnav("Zurück zum Dorf","village.php");
}else{
	checkday();
	page_header("Zigeunerzelt");
	output("`5Du betrittst das Zigeunerzelt hinter `#Pegasus`5' Rüstungsladen, welches eine Unterhaltung mit den Verstorbenen verspricht. Im typischen Zigeunerstil sitzt eine alte Frau hinter 
	einer irgendwie schmierigen Kristallkugel. Sie sagt dir, dass die Verstorbenen nur mit den Bezahlenden reden. Der Preis ist `^$cost`5 Gold.");
	output("`nDie Zigeunerin `6Vessa`5 gibt dir auch zu verstehen, dass sie mit Edelsteinen handelt.`nMomentan hat sie `#".getsetting("selledgems",0)."`5 Edelsteine auf Lager.");
	if (getsetting("selledgems",0)>=1000) output(" Sie scheint aber kein Interesse an weiteren Edelsteinen zu haben. Oder sie hat einfach kein Gold mehr, um weitere Edelsteine zu kaufen.");
	addnav("Bezahle und rede mit den Toten","gypsy.php?op=pay");
	if ($session[user][charisma]==4294967295 && $session[user][seenlover]<1) {
  		$sql = "SELECT name,alive FROM accounts WHERE ".$session[user][marriedto]." = acctid ORDER BY charm DESC";
  		$result = db_query($sql) or die(db_error(LINK));
		$row = db_fetch_assoc($result);
		if ($row[alive]==0) addnav("Bezahle und flirte mit $row[name]","gypsy.php?op=pay&was=flirt");
	}
	//addnav("Tarotkarten legen (1 Edelstein)","tarot.php");
	if ($session[user][superuser]>1) addnav("Superusereintrag","gypsy.php?op=talk");
	addnav("Edelsteine");
	if ($session['user']['level']<15){
		addnav("Kaufe 1 Edelstein ($costs[1] Gold)","gypsy.php?op=buy&level=1");
		addnav("Kaufe 2 Edelsteine ($costs[2] Gold)","gypsy.php?op=buy&level=2");
		addnav("Kaufe 3 Edelsteine ($costs[3] Gold)","gypsy.php?op=buy&level=3");
	}
	if (getsetting("selledgems",0)<25 && $session[user][level]>1) addnav("Verkaufe 1 Edelstein für $scost Gold","gypsy.php?op=sell");
	addnav("Zurück");
	// addnav("Forget it","village.php");
	addnav("Zurück zum Dorf","village.php");
}
page_footer();
?>
