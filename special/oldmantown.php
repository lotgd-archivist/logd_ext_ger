<?php

// 22062004

if (!isset($session)) exit();
if ($HTTP_GET_VARS[op]==""){
  output("`@Du begegnest einem merkw�rdigen alten Mann!`n`n\"`#Ich hab mich verlaufen.`@\", sagt er, \"`#Kannst du mich ins Dorf zur�ckbringen?`@\"`n`n");
	output("Du wei�t, da� du einen Waldkampf f�r heute verlieren wirst, wenn du diesen alten Mann ins Dorf bringst. Wirst du ihm helfen?");
	addnav("F�hre ihn ins Dorf","forest.php?op=walk");
	addnav("Lass ihn stehen","forest.php?op=return");
	$session[user][specialinc] = "oldmantown.php";
}else if ($HTTP_GET_VARS[op]=="walk"){
  $session[user][turns]--;
	if (e_rand(0,1)==0){
	  output("`@Du nimmst dir die Zeit, ihn zur�ck ins Dorf zu geleiten.`n`nAls Gegenleistung schl�gt er dich mit seinem h�bschen Stock und du erh�ltst `%einen Charmepunkt`@!");
		$session[user][charm]++;
	}else{
    output("`@Du nimmst dir die Zeit, ihn zur�ck ins Dorf zu geleiten.`n`nAls Dankesch�n gibt er dir `%einen Edelstein`@!");
		$session[user][gems]++;
		//debuglog("got 1 gem for walking old man to village");
	}
	//addnav("Return to the forest","forest.php");
	$session[user][reputation]++;
	$session[user][specialinc]="";
}else if ($HTTP_GET_VARS[op]=="return"){
  output("`@Du erkl�rst dem Opa, da� du viel zu besch�ftigt bist, um ihm zu helfen.`n`nKeine gro�e Sache, er sollte in der Lage sein, den Weg zur�ck ");
	output("ins Dorf selbst zu finden. Immerhin hat er es ja auch vom Dorf hierher geschafft, oder? Ein Wolf heult links von dir in der Ferne und wenige Sekunden sp�ter ");
	output("antwortet ein anderer Wolf viel n�her von rechts. Jup, der Mann sollte in Sicherheit sein.");
	//addnav("Zur�ck in den Wald","forest.php");
	$session[user][specialinc]="";
}
?>
