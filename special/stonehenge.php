<?php
if ($HTTP_GET_VARS[op]=="" || $HTTP_GET_VARS[op]=="search"){
  output("`#Du wanderst auf der Suche nach etwas zum Bek�mpfen ziellos durch den Wald. Pl�tzlich stehst du mitten auf einem Feld.");
	output("In der Mitte kannst du einen Steinkreis sehen. Du hast das legend�re ");
	output("Stonehenge gefunden! Du hast die Leute im Dorf �ber diesen mystischen Ort reden h�ren, aber");
	output(" du hast eigentlich nie geglaubt, dass es wirklich existiert. Sie sagen, der Kreis hat gro�e magische ");
	output("Kr�fte und dass diese Kr�fte unberechenbar sind. Was wirst du tun?");
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
		output("`#Obwohl du wei�t, da� die Kr�fte der Steine unvorhersagbar wirken, nimmst du diese Chance wahr. Du ");
		output("l�ufst in die Mitte der unzerst�rbaren Steine und bist bereit, die fantastischen Kr�fte von Stonehenge zu erfahren. ");
		output("Als du die Mitte erreichst, wird der Himmel zu einer schwarzen, sternenklaren Nacht. Du bemerkst, dass der Boden unter ");
		output("deinen F�ssen in einem schwachen Licht lila zu gl�hen scheint, fast so, als ob sich der Boden selbst in Nebel ");
		output("verwandeln will. Du f�hlst ein Kitzeln, das sich durch deinen gesamten K�rper ausbreitet. Pl�tzlich umgibt ein ");
		output("helles, intensives Licht den Kreis und dich. Als das Licht verschwindet, ");
		switch ($rand){
		  case 1:
		  case 2:
                output("bist du nicht mehr l�nger in Stonehenge.`n`n�berall um dich herum sind die Seelen derer, die ");
				output("in alten Schlachten und bei bedauerlichen Unf�llen umgekommen sind. ");
				output("Jede tr�gt Anzeichen der Niedertracht, durch welche sie ihr Ende gefunden haben. Du bemerkst mit steigender Verzweiflung, da� ");
				output("der Steinkreis dich direkt ins Land der Toten transportiert hat!");
				output("`n`n`^Du wurdest aufgrund deiner d�mmlichen Entscheidung in die Unterwelt geschickt.`n");
				output("Da du physisch dorthin transportiert worden bist, hast du noch dein ganzes Gold.`n");
				output("Du verlierst aber 5% deiner Erfahrung.`n");
				output("Du kannst morgen wieder spielen.");
				$session[user][alive]=false;
				$session[user][hitpoints]=0;
				$session[user][experience]*=0.95;
				addnav("T�gliche News","news.php");
				addnews($session[user][name]." ist f�r eine Weile verschwunden und jene, welche gesucht haben, kommen nicht zur�ck.");
				break;
			case 3:
     				output(" liegt dort nur noch der K�rper eines Kriegers, der die Kr�fte von Stonehenge herausgefordert hat.");
				output("`n`n`^Dein Geist wurde aus deinem K�rper gerissen!`n");
				output("Da dein K�rper in Stonehenge liegt, verlierst du all dein Gold.`n");
				output("Du verlierst 10% deiner Erfahrung.`n");
				output("Du kannst morgen wieder spielen.");
				$session[user][alive]=false;
				$session[user][hitpoints]=0;
				$session[user][experience]*=0.9;
				$session['user']['donation']+=1;
				$session[user][gold] = 0;
				addnav("T�gliche News","news.php");
				addnews($session[user][name]."'s lebloser K�rper wurde auf einer leeren Lichtung gefunden.");
				break;
			case 4:
			case 5:
			case 6:
			    output("f�hlst du eine zerrende Energie durch deinen K�rper zucken, als ob deine Muskeln verbrennen w�rden. Als der schreckliche Schmerz nachl�sst, bemerkst du, dass deine Muskeln VIEL gr�sser geworden sind.");
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
			    output("...`n`n`^bemerkst du `%$rewardn vor deinen F�ssen!`n`n");
				$session[user][gems]+=$reward;
				//debuglog("found gems from Stonehenge");  // said 4 gems ... can be less!!
				break;
			
			case 11:
			case 12:
			case 13:			
				output("hast du viel mehr Vertrauen in deine eigenen F�higkeiten.`n`n");
//                output("`^You gain four charm!");    // whoooohaa ... slow down a bit ;)
	output("`^Dein Charme steigt!");
                $session[user][charm] += 2;
            	break;
			case 14:
			case 15:
			case 16:
			case 17:
			case 18:
			    output("f�hlst du dich pl�tzlich extrem gesund.");
				output("`n`n`^Deine Lebenspunkte wurden vollst�ndig aufgef�llt.");
				if ($session[user][hitpoints]<$session[user][maxhitpoints]) $session[user][hitpoints]=$session[user][maxhitpoints];
				break;
			case 19:
			case 20:
			  output("f�hlst du deine Ausdauer in die H�he schiessen!");
			 // 	$reward = $session[user][maxhitpoints] * 0.1;     // uhm ... seems to be too much for permanent HP
			  	$reward = 2;
				output("`n`n`^Deine Lebenspunkte wurden `bpermanent`b um `7$reward `^erh�ht!");
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
				
				output("ist der Tag vergangen. Es scheint, als h�tte Stonehenge dich f�r die meiste Zeit des Tages in der Zeit eingefroren.`n");
				output("Das Ergebnis ist, da� du $lostTurns Waldk�mpfe verlierst!");				
				break;
		}
	}else{
	  output("`#Du f�rchtest die unglaublichen Kr�fte von Stonehenge und beschliesst, die Steine lieber in Ruhe zu lassen. Du gehst zur�ck in den Wald.");
	}
}
?>
