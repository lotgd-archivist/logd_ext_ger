<?php
if ($HTTP_GET_VARS[op]==""){
  output("`#Du entdeckst einen schmalen Strom schwach glühenden Wassers, das über runde, glatte, weiße Steine blubbert. Du kannst eine magische ");
	output("Kraft in diesem Wasser fühlen. Es zu trinken, könnte ungeahnte Kräfte in dir freisetzen - oder es könnte ");
	output("dich zum völligen Krüppel machen. Wagst du es, von dem Wasser zu trinken?");
	output("`n`n<a href='forest.php?op=drink'>Trinken</a>`n<a href='forest.php?op=nodrink'>Nicht trinken</a>",true);
	addnav("Trinken","forest.php?op=drink");
	addnav("Nicht Trinken","forest.php?op=nodrink");
	addnav("","forest.php?op=drink");
	addnav("","forest.php?op=nodrink");
	$session[user][specialinc]="glowingstream.php";
}else{
  $session[user][specialinc]="";
	if ($HTTP_GET_VARS[op]=="drink"){
	  $rand = e_rand(1,10);
		output("`#Im Wissen, daß dieses Wasser dich auch umbringen könnte, willst du trotzdem die Chance wahrnehmen. Du kniest dich am Rand des Stroms nieder ");
		output("und nimmst einen langen, kräftigen Schluck von diesem kalten Wasser. Du fühlst Wärme ");
		output("von deiner Brust heraufziehen, ");
		switch ($rand){
		  case 1:
        output("`igefolgt von einer bedrohlichen, beklemmenden Kälte`i. Du taumelst und greifst dir an die Brust. Du fühlst das, was ");
				output("du dir als die Hand des Sensenmanns vorstellst, der seinen gnadenlosen Griff um dein Herz legt.");
				output("`n`nDu brichst am Rande des Stroms zusammen. Dabei erkennst du erst jetzt gerade noch, daß die Steine, die dir aufgefallen sind ");
				output("die blanken Schädel anderer Abenteurer sind, die genauso viel Pech hatten wie du.");
				output("`n`nDunkelheit umfängt dich, während du da liegst und in die Bäume starrst ");
				output("Dein Atem wird dünner und immer unregelmäßiger. ");
				output("Warmer Sonnenschein strahlt dir ins Gesicht, als scharfer Kontrast zu der Leere, die von deinem ");
				output("Herzen Besitz ergreift.");
				output("`n`n`^Du bist an den dunklen Kräften des Stroms gestorben.`n");
				output("Da die Waldkreaturen die Gefahr dieses Platzes kennen, meiden sie ihn und deinen Körper als Nahrungsquelle. Du behältst dein Gold.`n");
				output("Die Lektion, die du heute gelernt hast, gleicht jeden Erfahrungsverlust aus.`n");
				output("Du kannst morgen wieder kämpfen.");
				$session[user][alive]=false;
				$session[user][hitpoints]=0;
				addnav("Tägliche News","news.php");
				addnews($session[user][name]." hat seltsame Kräfte im Wald entdeckt und wurde nie wieder gesehen.");
			break;
			case 2:
        output("`igefolgt von einer bedrohlichen, beklemmenden Kälte`i. Du taumelst und greifst dir an die Brust. Du fühlst das, was ");
				output("du dir als die Hand des Sensenmanns vorstellst, der seinen gnadenlosen Griff um dein Herz legt.");
				output("`n`nDu brichst am Rande des Stroms zusammen. Dabei erkennst du erst jetzt gerade noch, daß die Steine, die dir aufgefallen sind ");
				output("die blanken Schädel anderer Abenteurer sind, die genauso viel Pech hatten wie du.");
				output("`n`nDunkelheit umfängt dich, während du da liegst und in die Bäume starrst. ");
				output("Dein Atem wird dünner und immer unregelmäßiger. ");
				output("Warmer Sonnenschein strahlt dir ins Gesicht, als scharfer Kontrast zu der Leere, die von deinem ");
				output("Herzen Besitz ergreift.`n`n");
				output("Als du deinen letzten Atem aushauchst, hörst du ein entferntes leises Kichern. Du findest die Kraft, ");
				output("die Augen zu öffnen und siehst eine kleine Fee über deinem Gesicht schweben, die ");
				output("unachtsam ihren Feenstaub überall über dich verstreut. Dieser gibt dir genug Kraft, dich wieder ");
				output("aufzurappeln. Dein abruptes Aufstehen erschreckt die Fee, und noch bevor du die Möglichkeit hast, ");
				output("ihr zu danken, fliegt sie davon.");
				output("`n`n`^Du bist dem Tod knapp entkommen! Du hast einen Waldkampf und die meisten deiner Lebenspunkte verloren.");
				if ($session[user][turns]>0) $session[user][turns]--;
				if ($session[user][hitpoints]>($session[user][hitpoints]*.1)) $session[user][hitpoints]=round($session[user][hitpoints]*.1,0);
			break;
			case 3:
			  output("du fühlst dich GESTÄRKT!");
				output("`n`n`^Deine Lebenspunkte wurden aufgefüllt und du spürst die Kraft für einen weiteren Waldkampf.");
				if ($session[user][hitpoints]<$session[user][maxhitpoints]) $session[user][hitpoints]=$session[user][maxhitpoints];
				$session[user][turns]++;
				break;
			case 4:
			  output("du fühlst deine SINNE GESCHÄRFT! Du bemerkst unter den Kieselsteinen am Bach etwas glitzern.");
				output("`n`n`^Du findest einen EDELSTEIN!");
				$session[user][gems]++;
				//debuglog("found 1 gem by the stream");
				break;
			case 5:
			case 6:
			case 7:
			  output("du fühlst dich VOLLER ENERGIE!");
				output("`n`n`^Du bekommst einen zusätzlichen Waldkampf!");
				$session[user][turns]++;
				break;
			default:
			  output("du fühlst dich GESUND!");
				output("`n`n`^Deine Lebenspunkte wurden vollständig aufgefüllt.");
				if ($session[user][hitpoints]<$session[user][maxhitpoints]) $session[user][hitpoints]=$session[user][maxhitpoints];
		}
	}else{
	  output("`#Weil du die verhängnisvollen Kräfte in diesem Wasser fürchtest, entschließt du dich, es nicht zu trinken und gehst zurück in den Wald.");
	}
}
?>
