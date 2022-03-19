<?php
if ($HTTP_GET_VARS[op]=="" || $HTTP_GET_VARS[op]=="search"){
  output("`#Du wanderst auf der Suche nach etwas zum Bekämpfen ziellos durch den Wald. Plötzlich stehst du mitten auf einem Feld.");
	output("In der Mitte kannst du einen Steinkreis sehen. Du hast das legendäre ");
	output("Stonehenge gefunden! Du hast die Leute im Dorf über diesen mystischen Ort reden hören, aber");
	output(" du hast eigentlich nie geglaubt, dass es wirklich existiert. Sie sagen, der Kreis hat große magische ");
	output("Kräfte und dass diese Kräfte unberechenbar sind. Was wirst du tun?");
	output("`n`n<a href='forest.php?op=stonehenge'>Betrete Stonehenge</a>`n<a href='forest.php?op=leavestonehenge'>Lasse es in Ruhe</a>",true);
	addnav("S?Betrete Stonehenge","forest.php?op=stonehenge");
	addnav("Lasse es in Ruhe","forest.php?op=leavestonehenge");
	addnav("","forest.php?op=stonehenge");
	addnav("","forest.php?op=leavestonehenge");
	$session[user][specialinc]="stonehenge.php";
}else{
  $session[user][specialinc]="";
	if ($HTTP_GET_VARS[op]=="stonehenge"){
	  $rand = e_rand(1,22);
		output("`#Obwohl du weißt, daß die Kräfte der Steine unvorhersagbar wirken, nimmst du diese Chance wahr. Du ");
		output("läufst in die Mitte der unzerstörbaren Steine und bist bereit, die fantastischen Kräfte von Stonehenge zu erfahren. ");
		output("Als du die Mitte erreichst, wird der Himmel zu einer schwarzen, sternenklaren Nacht. Du bemerkst, dass der Boden unter ");
		output("deinen Füssen in einem schwachen Licht lila zu glühen scheint, fast so, als ob sich der Boden selbst in Nebel ");
		output("verwandeln will. Du fühlst ein Kitzeln, das sich durch deinen gesamten Körper ausbreitet. Plötzlich umgibt ein ");
		output("helles, intensives Licht den Kreis und dich. Als das Licht verschwindet, ");
		switch ($rand){
		  case 1:
		  case 2:
                output("bist du nicht mehr länger in Stonehenge.`n`nÜberall um dich herum sind die Seelen derer, die ");
				output("in alten Schlachten und bei bedauerlichen Unfällen umgekommen sind. ");
				output("Jede trägt Anzeichen der Niedertracht, durch welche sie ihr Ende gefunden haben. Du bemerkst mit steigender Verzweiflung, daß ");
				output("der Steinkreis dich direkt ins Land der Toten transportiert hat!");
				output("`n`n`^Du wurdest aufgrund deiner dümmlichen Entscheidung in die Unterwelt geschickt.`n");
				output("Da du physisch dorthin transportiert worden bist, hast du noch dein ganzes Gold.`n");
				output("Du verlierst aber 5% deiner Erfahrung.`n");
				output("Du kannst morgen wieder spielen.");
				$session[user][alive]=false;
				$session[user][hitpoints]=0;
				$session[user][experience]*=0.95;
				addnav("Tägliche News","news.php");
				addnews($session[user][name]." ist für eine Weile verschwunden und jene, welche gesucht haben, kommen nicht zurück.");
				break;
			case 3:
     				output(" liegt dort nur noch der Körper eines Kriegers, der die Kräfte von Stonehenge herausgefordert hat.");
				output("`n`n`^Dein Geist wurde aus deinem Körper gerissen!`n");
				output("Da dein Körper in Stonehenge liegt, verlierst du all dein Gold.`n");
				output("Du verlierst 10% deiner Erfahrung.`n");
				output("Du kannst morgen wieder spielen.");
				$session[user][alive]=false;
				$session[user][hitpoints]=0;
				$session[user][experience]*=0.9;
				$session['user']['donation']+=1;
				$session[user][gold] = 0;
				addnav("Tägliche News","news.php");
				addnews($session[user][name]."'s lebloser Körper wurde auf einer leeren Lichtung gefunden.");
				break;
			case 4:
			case 5:
			case 6:
			    output("fühlst du eine zerrende Energie durch deinen Körper zucken, als ob deine Muskeln verbrennen würden. Als der schreckliche Schmerz nachlässt, bemerkst du, dass deine Muskeln VIEL grösser geworden sind.");
			  	$reward = round($session[user][experience] * 0.1);
				output("`n`n`^Du bekommst `7$reward`^ Erfahrungspunkte!");
				$session[user][experience] += $reward;
				break;
			case 7:
			case 8:
			case 9:
			case 10:
			    $reward = e_rand(1, 3); 		// original value: 1,4
			 	if ($reward == 4) $rewardn = "VIER`^ Edelsteine";
				else if ($reward == 3) $rewardn = "DREI`^ Edelsteine";
				else if ($reward == 2) $rewardn = "ZWEI`^ Edelsteine";
				else if ($reward == 1) $rewardn = "EINEN`^ Edelstein";
			    output("...`n`n`^bemerkst du `%$rewardn vor deinen Füssen!`n`n");
				$session[user][gems]+=$reward;
				//debuglog("found gems from Stonehenge");  // said 4 gems ... can be less!!
				break;
			
			case 11:
			case 12:
			case 13:			
				output("hast du viel mehr Vertrauen in deine eigenen Fähigkeiten.`n`n");
//                output("`^You gain four charm!");    // whoooohaa ... slow down a bit ;)
	output("`^Dein Charme steigt!");
                $session[user][charm] += 2;
            	break;
			case 14:
			case 15:
			case 16:
			case 17:
			case 18:
			    output("fühlst du dich plötzlich extrem gesund.");
				output("`n`n`^Deine Lebenspunkte wurden vollständig aufgefüllt.");
				if ($session[user][hitpoints]<$session[user][maxhitpoints]) $session[user][hitpoints]=$session[user][maxhitpoints];
				break;
			case 19:
			case 20:
			  output("fühlst du deine Ausdauer in die Höhe schiessen!");
			 // 	$reward = $session[user][maxhitpoints] * 0.1;     // uhm ... seems to be too much for permanent HP
			  	$reward = 2;
				output("`n`n`^Deine Lebenspunkte wurden `bpermanent`b um `7$reward `^erhöht!");
				$session[user][maxhitpoints] += $reward;
				$session[user][hitpoints] = $session[user][maxhitpoints];
				break;
			case 21:
			case 22:
				$prevTurns = $session[user][turns];
				if ($prevTurns >= 3) $session[user][turns]-=3;	// original value 5 - but i only offer 20 ff a day
				else if ($prevTurns < 3) $session[user][turns]=0;
				$currentTurns = $session[user][turns];
				$lostTurns = $prevTurns - $currentTurns;
				
				output("ist der Tag vergangen. Es scheint, als hätte Stonehenge dich für die meiste Zeit des Tages in der Zeit eingefroren.`n");
				output("Das Ergebnis ist, daß du $lostTurns Waldkämpfe verlierst!");				
				break;
		}
	}else{
	  output("`#Du fürchtest die unglaublichen Kräfte von Stonehenge und beschliesst, die Steine lieber in Ruhe zu lassen. Du gehst zurück in den Wald.");
	}
}
?>
