<?php
if (!isset($session)) exit();
$session[user][specialinc]="skillmaster.php";
switch((int)$session[user][specialty]){
case 1:
	$c="`$";
	break;
case 2:
	$c="`%";
	break;
case 3:
	$c="`^";
	break;
default:
	output("Du wanderst plan- und ziellos durchs Leben. Du solltest eine Rast machen und einige wichtige Entscheidungen f�r dein weiteres Leben treffen.");
	$session[user][specialinc]="";
	//addnav("Return to the forest", "forest.php");
}
$skills = array(1=>"Dunkle K�nste","Mystische Kr�fte","Diebeskunst");

if ($_GET[op]=="give"){
	if ($session[user][gems]>0){
		output("$c Du gibst `@Foil`&wench$c einen Edelstein und sie �berreicht dir einen Zettel aus Pergament mit Anweisungen, wie du deine Fertigkeiten steigern kannst.`n`n");
		output("Du studierst den Zettel, zerreisst ihn und futterst ihn auf, damit Ungl�ubige nicht an die Information gelangen k�nnen. `n`n`@Foil`&wench$c seufzt... \"`&Du h�ttest ihn nicht ");
		output("zu essen brauchen... Naja, jetzt verschwinde von hier!$c\"`#");
		increment_specialty();
		$session[user][gems]--;
		//debuglog("gave 1 gem to Foilwench");
	}else{
		output("$c Du �berreichst deinen imagin�ren Edelstein. `@Foil`&wench$c starrt dich verdutzt an. \"`&Komm wieder, wenn du einen `bechten`b Edelstein hast, du Dummkopf.$c\"`n`n");
		output("\"`#Dummkopf?$c\"`n`n");
		output("Damit schmeisst `@Foil`&wench$c dich endg�ltig raus.");
	}	
	$session[user][specialinc]="";
	//addnav("Zur�ck zum Wald", "forest.php");
}else if($_GET[op]=="dont"){
	output("$c Du informierst `@Foil`&wench$c dar�ber, dass sie sich ihren Reichtum selbst verdienen sollte. Dann stampfst du davon.");
	$session[user][specialinc]="";
	//addnav("Return to the forest", "forest.php");
}else if($session[user][specialty]>0){
	output("$c Auf deinen Streifz�gen durch den Wald auf Jagd nach Beute st�sst du auf eine kleine H�tte. Du gehst hinein und wirst vom grauen Gesicht einer kampferprobten alten Frau empfangen: ");
	output("\"`&Sei gegr�sst, ".$session[user][name].", ich bin `@Foil`&wench$c, Meister in allem.$c\"`n`n\"`#Meister in allem?$c\" fragst du nach.`n`n");
	output("\"`&Ja, Meister in Allem. Es liegt in meiner Macht, alle F�higkeiten zu kontrollieren und zu lehren.$c\"`n`n\"`#Und zu lehren?$c\" fragst du sie.`n`n");
	output("Die alte Frau seufzt: \"`&Ja, und zu lehren.  Ich werde dir zeigen, wie du deine ".$skills[$session[user][specialty]]." steigern kannst - unter zwei Bedingungen.$c\"`n`n");
	output("\"`#Zwei Bedingungen?$c\" wiederholst du fragend.`n`n");
	output("\"`&Ja. Zuerst musst du mir einen Edelstein geben und dann musst du aufh�ren, alles was ich sage als Frage zu wiederholen!$c\"`n`n");
	output("\"`#Ein Edelstein!$c\" sagst du bestimmt.`n`n");
	output("\"`&Nun ... ich glaube das war keine Frage. Und wie sieht es mit dem Edelstein aus?$c\"");
	addnav("Gib ihr einen Edelstein","forest.php?op=give");
	addnav("Gib ihr keinen Edelstein","forest.php?op=dont");
}
?>
