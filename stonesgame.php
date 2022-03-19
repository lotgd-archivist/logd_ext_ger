<?php

// 12072004

/*
Stones (slots)
simple little slots game for your casino
Author: Lonnyl of http://www.pqcomp.com/logd
Difficulty: Easy
no sql to add
simply upload, link it to your casino.php or whatever you may be using.
upload images to your images folder.
if not using a casino.php change the return nav at the bottom of this file.
(casino.php is not an avialble file, you need to make one of your own)

translation by anpera
linkable in darkhorse tavern for old man and in castle
*/

require_once "common.php";
checkday();
page_header("Steinchenglücksspiel");

output("`c`b`&Steinchenglücksspiel`0`b`c`n`n");
if ($HTTP_GET_VARS[op] == ""){
	output("`tDu fragst nach dem Glückspiel. Der alte Mann schaut dich ernst an und macht dir klar, dass er diese Spiel nur um Edelsteine spielen wird!`n");
	output("Dann erklärt er dir das Spiel:`n");
	output("Er wird nacheinander 3 Steine aus seiner Tasche ziehen. Folgende Gewinne sind möglich:`n`#");
	output("`n<IMG SRC=\"images/stone1.gif\" align='middle'><IMG SRC=\"images/stone1.gif\" align='middle'> = 1 Edelstein`n",true);
	output("<IMG SRC=\"images/stone2.gif\" align='middle'><IMG SRC=\"images/stone2.gif\" align='middle'> = 2 Edelsteine`n",true);
	output("<IMG SRC=\"images/stone1.gif\" align='middle'><IMG SRC=\"images/stone1.gif\" align='middle'><IMG SRC=\"images/stone1.gif\" align='middle'> = 4 Edelsteine.`n",true);
	output("<IMG SRC=\"images/stone2.gif\" align='middle'><IMG SRC=\"images/stone2.gif\" align='middle'><IMG SRC=\"images/stone2.gif\" align='middle'> = 8 Edelsteine.`n",true);
	addnav("Edelstein setzen","stonesgame.php?op=play");
}else if ($HTTP_GET_VARS[op] == "play" && $session['user']['gems']>0){
	output("`tDu wirfst einen Edelstein auf den Tisch und der Alte fängt an, Steine aus seinem Beute fallen zu lassen.`n`n");
	$session['user']['gems']-=1;
	$stoneone=e_rand(1,3000);
	$stonetwo=e_rand(1,4000);
	$stonethr=e_rand(1,5000);
	$stoneone=round($stoneone/1000);
	$stonetwo=round($stonetwo/1000);
	if ($stonetwo == 4) $stonetwo = 3;
	$stonethr=round($stonethr/1000);
	if ($stonethr > 3) $stonethr = 3;
	if ($stoneone == 0) $stoneone = 3;
	if ($stonetwo == 0) $stonetwo = 3;
	if ($stonethr == 0) $stonethr = 3;
	output("<IMG SRC=\"images/stone".$stoneone.".gif\"> <IMG SRC=\"images/stone".$stonetwo.".gif\"> <IMG SRC=\"images/stone".$stonethr.".gif\">`n`n",true);
	if ($stoneone == 3) $stoneone = 0;
	if ($stonetwo == 3) $stonetwo = 0;
	if ($stonethr == 3) $stonethr = 0;
	if ($stoneone == 2) $stoneone = 5;
	if ($stonetwo == 2) $stonetwo = 5;
	if ($stonethr == 2) $stonethr = 5;
	$stonetotal=($stoneone+$stonetwo+$stonethr);
	if ($stonetotal == 2 or $stonetotal == 7){
		//push
		$session['user']['gems']+=1;
		output("`tOhne ein weiteres Wort schiebt dir der alte Mann deinen Edelstein zurück, welchen du schnell schnappst und wieder sicher verstaust.`n");
	}elseif ($stonetotal == 10 or $stonetotal == 11){
		//double
		$session['user']['gems']+=2;
		output("`tOhne ein weiteres Wort legt der alte Mann einen Edelstein zu deinem dazu und schiebt dir die beiden Edelsteine zu. Schnell verstaust du sie sicher in deinem Edelsteinbeutelchen.`n");
	}elseif ($stonetotal == 3 or $stonetotal == 8){
		//triple
		$session['user']['gems']+=4;
		output("`tOhne ein weiteres Wort legt der alte Mann 3 Edelsteine zu deinem dazu und schiebt dir die 4 Edelsteine zu. Schnell verstaust du sie sicher in deinem Edelsteinbeutelchen.`n");
	}elseif ($stonetotal == 15 or $stonetotal == 16){
		//quad
		$session['user']['gems']+=8;
		output("`tMit betrübtem Gesicht, aber immer noch ohne Worte, legt der alte Mann 7 Edelsteine zu deinem dazu und schibt sie dir entgegen. Schnell verstaust du sie sicher in deinem Edelsteinbeutelchen.`n");
	}else{
		output("`tDer Alte schnappt sich deinen Edelstein und lässt ihn verschwinden. Dann schaut er dich erwartungsvoll an.`n");
	}
	addnav("Nochmal","stonesgame.php?op=play");
}else{
	output("`tDu hast keine Edelsteine dabei. Wie peinlich.`n");
}
if ($session[user][specialinc]=="darkhorse.php") addnav("Zurück zur Taverne","forest.php?specialinc=darkhorse.php&op=oldman");
else if ($session[user][specialinc]=="castle.php") addnav("Zurück zur Burg","forest.php?specialinc=castle.php&op=return");
else addnav("Zurück zum Dorf","village.php");

//I cannot make you keep this line here but would appreciate it left in.
rawoutput("<br><br><br><div style=\"text-align: right;\"><font size='1'><a href=\"http://www.pqcomp.com\" target=\"_blank\">Stones by Lonny @ http://www.pqcomp.com</a></font><br>");
page_footer();
?>