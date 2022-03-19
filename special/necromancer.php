<?php

//by: thedragonreborn


if (!isset($session)) exit();

if ($_GET['op']=="return") {
	$session[user][gems]--;
	$session['user']['specialinc']="";
	redirect("forest.php");
}

reset($session['user']['dragonpoints']);
$dkhp=0;
while(list($key,$val)=each($session['user']['dragonpoints'])){
	if ($val=="hp") $dkhp++;
}
$maxhp=getsetting("limithp",0)*$session[user][dragonkills]+10*$session[user][level]+5*$dkhp;
$minhp=10*$session[user][level]+(5*$dkhp)-1;

if ($HTTP_GET_VARS[op]=="necromancer"){
  	$session[user][turns]--;
	if($session[user][turns]<0) $session[user][turns]=0;
	output("`@Ohne R�cksicht auf seine zweifellos b�se Natur l�ufst du zu dem alten Mann. Als du n�her kommst, zieht sich ein fieses Grinsen ");
	output("�ber sein Gesicht. ");
	switch(e_rand(1,15)){
		case 1:
      		output("\"`#Hat dir deine Mama nicht beigebracht, niemals mit Fremden zu sprechen?`@\" Der alte Totenbeschw�rer verf�llt in ein gemeines Lachen, zieht einen schwarzen ");
		output("Stab hervor und schwenkt ihn �ber deinem Kopf. Du sp�rst einen brennenden Schmerz w�hrend deine Seele aus deinem ");
		output("K�rper gesogen wird.");
		output("`n`n`^Dein Geist wurde vom K�rper getrennt!`n");
		output("Dieser heimt�ckische alte Mann durchsucht deine Leiche und nimmt all dein Gold!`n");
		output("Du verlierst 10% deiner Erfahrung.`n");
		output("Du kannst morgen wieder spielen.");
		$session[user][alive]=false;
		$session[user][hitpoints]=0;
		$session[user][experience]*=0.9;
		$session[user][gold] = 0;
		$session['user']['donation']+=1;
		addnav("T�gliche News","news.php");
		addnews($session[user][name]."'s K�rper wurde allen Goldes beraubt im Wald gefunden.");
		break;
		case 2:
		case 3:
	    	output("Du kommst n�her und er beginnt zu murmeln: \"`#Ja, mein ".($session[user][sex]?"M�dl":"Jung").", komm noch ein St�ckchen n�her. So ist gut. Noch ein bisschen `bN�HER`b!`@\" ");
		output("Der Totenbeschw�rer schreit dieses letzte Wort, zieht einen schwarzen Zauberstab und schockt deinen K�rper mit Schmerz. ");
		output("Es ist, als ob geschmolzenes Feuer durch deine Knochen, Venen und deine Haut flie�t. Gerade als der Tod nach dir greifen will, ");
		output(" h�rt der Schmerz auf. Du stehst noch vom Schmerz zitternd auf. Der alte Totenbeschw�rer ist nirgends in Sicht. Du f�hlst, dass du ");
		output("nie wieder so sein wirst, wie du einmal warst...");
		if ($session[user][maxhitpoints]>$minhp){
			$session[user][maxhitpoints]-=1;
			output("`n`n`^Du verlierst `bpermenant`b einen Lebenspunkt!`n");
		}
		output("Du hast nur noch einen Lebenspunkt!`n");
		$session[user][hitpoints]=1;
		addnews($session[user][name]." kam aus dem Wald heim - etwas schw�cher als ".($session[user][sex]?sie:er)." vorher war.");
		break;
		case 4:
		case 5:
		case 6:
		case 7:
		case 8:
		case 9:
		case 10:
		case 11:
	  	output(" Du kommst n�her und er murmelt: \"`#Einen Edelstein f�r mich, ja?`@\"`n`n");
	  	output("Gibst du ihm einen Edelstein?");
		output("`n`n<a href='forest.php?op=GiveGem'>Gib ihm einen Edelstein</a>`n<a href='forest.php?op=KeepGem'>Behalte deine Edelsteine</a>",true);
		addnav("Edelstein geben","forest.php?op=GiveGem");
		addnav("Edelsteine behalten","forest.php?op=KeepGem");
		addnav("","forest.php?op=GiveGem");
		addnav("","forest.php?op=KeepGem");
		$session[user][specialinc] = "necromancer.php";
		break;
		case 12:
		case 13:
		case 14:
		case 15:
		output("Du stehst schon fast vor ihm, als du ein knirschendes Ger�usch von rechts h�rst. Du drehst dich hin und siehst ");
		output("eine der Dorfwachen Richtung Dorf zur�ck durch den Wald laufen. Du wendest dich wieder dem alten Mann zu, ");
		output("aber das Ger�uch hat ihn wohl verscheucht. Nun wirst du wohl nie erfahren, was er von dir wollte. ");
		break;
	}
}else if ($HTTP_GET_VARS[op]=="leave"){
  	output("`@Aus Furcht vor der b�sen Ausstrahlung dieses Mannes kehrst du um und rennst so schnell du kannst! ");
	// addnav("Zur�ck in den Wald","forest.php");
	$session[user][specialinc]="";
}else if($HTTP_GET_VARS[op]=="GiveGem"){
  	if ($session[user][gems]>0){
    		output("`@Du hast irgendwie Mitleid mit dem alten Mann und reichst ihm einen deiner schwer verdienten Edelsteine. Er schnappt ihn sich und ");
		switch (e_rand(1,8)){
	    		case 1:
			case 2:
			case 3:
			case 4:
			output("gackert vor Freude. Er dreht sich zu dir um: \"`#Weil du mir so ein sch�nes Geschenk gemacht hast, mein");
	      		output(($session[user][sex]?"e S�sse":" Junge").", werde ich ein gutes Wort f�r dich bei meinem alten Freund ");
			output("`\$Ramius`# ein.`@\"`n Der Totenbeschw�rer zieht einen schwarzen Zauberstab hervor und tippt dir damit auf den Kopf. ");
			output(" Dann verschwindet er im Wald.`n`n");
			$favor = e_rand(5, 35);
			output("`^Du erh�ltst `&$favor`^ Gefallen bei `\$Ramius`^, dem Gott der Toten.");
			$session[user][deathpower] += $favor;
			$session[user][gems]--;
			//debuglog("gained $favor favor from a necromancer");
	  		break;
			case 5:
	      		output("rennt mit einer Geschwindigkeit, die du ihm nicht zugetraut h�ttest, in den Wald. Dieser gierige kleine Dieb hat dir deinen Edelstein gestohlen!");
			$session[user][gems]--;
			//debuglog("lost one gem to an old man");
			break;
			case 6:
			case 7:
			case 8:
			output("gew�hrt dir ein Gespr�ch mit den Toten:`n`n");
			viewcommentary("shade","Sprich",25,"spricht");
			$session[user][specialinc] = "necromancer.php";
			addnav("Zur�ck in den Wald","forest.php?op=return");
			break;
	  	}
	}else{
		output("`@Du greifst in deinen Beutel und stellst fest, dass du keine Edelsteine hast. Der alte Mann schaut dich erwartungsvoll an. Als er bemerkt, ");
		output(" dass du keine Edelsteine hast, runzelt er die Stirn. Du sp�rst �rger in der Luft und fl�chtest so schnell du kannst.");
	}
}else if ($HTTP_GET_VARS[op]=="KeepGem"){
	output("`@Du willst dich nicht von deinen teuren Edelsteinen trennen, drehst um und marschierst zur�ck in den Wald.");
	// addnav("Zur�ck in den Wald","forest.php");
	$session[user][specialinc]="";
}else{
	output("`@Du begegnest einem seltsamen alten Mann. Seine Augen sind tief in die H�hlen gesunken und es scheint, als ob sein finsterer Gesichtsausdruck fester Bestandteil seines Gesichts ist. Du f�hlst eine finstere Austrahlung von ihm ausgehen.`n`n");
    	output("Er sieht dich und spricht in einer tiefen, rauchigen Stimme: \"`#Ahh... Komm mal her mein".($session[user][sex]?"e Kleine":" Kleiner").". Weisst du, 's is ne Menge Schreckliches und Gef�hrliches in diesen W�ldern unterwegs`@\"`n`n");
	output("Wirst du wie er verlangt zu ihm gehen? Du weisst, da� dich das mindestens einen Waldkampf kosten wird.");
	output("`n`n<a href='forest.php?op=necromancer'>Gehe zu ihm</a>`n<a href='forest.php?op=leave'>Kehre um</a>",true);
	addnav("Gehe zu ihm","forest.php?op=necromancer");
	addnav("Umkehren","forest.php?op=leave");
	addnav("","forest.php?op=necromancer");
	addnav("","forest.php?op=leave");
	$session[user][specialinc] = "necromancer.php";
}
?>