<?php
if ($HTTP_GET_VARS[op]==""){
  output("`#Du entdeckst einen schmalen Strom schwach gl�henden Wassers, das �ber runde, glatte, wei�e Steine blubbert. Du kannst eine magische ");
	output("Kraft in diesem Wasser f�hlen. Es zu trinken, k�nnte ungeahnte Kr�fte in dir freisetzen - oder es k�nnte ");
	output("dich zum v�lligen Kr�ppel machen. Wagst du es, von dem Wasser zu trinken?");
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
		output("`#Im Wissen, da� dieses Wasser dich auch umbringen k�nnte, willst du trotzdem die Chance wahrnehmen. Du kniest dich am Rand des Stroms nieder ");
		output("und nimmst einen langen, kr�ftigen Schluck von diesem kalten Wasser. Du f�hlst W�rme ");
		output("von deiner Brust heraufziehen, ");
		switch ($rand){
		  case 1:
        output("`igefolgt von einer bedrohlichen, beklemmenden K�lte`i. Du taumelst und greifst dir an die Brust. Du f�hlst das, was ");
				output("du dir als die Hand des Sensenmanns vorstellst, der seinen gnadenlosen Griff um dein Herz legt.");
				output("`n`nDu brichst am Rande des Stroms zusammen. Dabei erkennst du erst jetzt gerade noch, da� die Steine, die dir aufgefallen sind ");
				output("die blanken Sch�del anderer Abenteurer sind, die genauso viel Pech hatten wie du.");
				output("`n`nDunkelheit umf�ngt dich, w�hrend du da liegst und in die B�ume starrst ");
				output("Dein Atem wird d�nner und immer unregelm��iger. ");
				output("Warmer Sonnenschein strahlt dir ins Gesicht, als scharfer Kontrast zu der Leere, die von deinem ");
				output("Herzen Besitz ergreift.");
				output("`n`n`^Du bist an den dunklen Kr�ften des Stroms gestorben.`n");
				output("Da die Waldkreaturen die Gefahr dieses Platzes kennen, meiden sie ihn und deinen K�rper als Nahrungsquelle. Du beh�ltst dein Gold.`n");
				output("Die Lektion, die du heute gelernt hast, gleicht jeden Erfahrungsverlust aus.`n");
				output("Du kannst morgen wieder k�mpfen.");
				$session[user][alive]=false;
				$session[user][hitpoints]=0;
				addnav("T�gliche News","news.php");
				addnews($session[user][name]." hat seltsame Kr�fte im Wald entdeckt und wurde nie wieder gesehen.");
			break;
			case 2:
        output("`igefolgt von einer bedrohlichen, beklemmenden K�lte`i. Du taumelst und greifst dir an die Brust. Du f�hlst das, was ");
				output("du dir als die Hand des Sensenmanns vorstellst, der seinen gnadenlosen Griff um dein Herz legt.");
				output("`n`nDu brichst am Rande des Stroms zusammen. Dabei erkennst du erst jetzt gerade noch, da� die Steine, die dir aufgefallen sind ");
				output("die blanken Sch�del anderer Abenteurer sind, die genauso viel Pech hatten wie du.");
				output("`n`nDunkelheit umf�ngt dich, w�hrend du da liegst und in die B�ume starrst. ");
				output("Dein Atem wird d�nner und immer unregelm��iger. ");
				output("Warmer Sonnenschein strahlt dir ins Gesicht, als scharfer Kontrast zu der Leere, die von deinem ");
				output("Herzen Besitz ergreift.`n`n");
				output("Als du deinen letzten Atem aushauchst, h�rst du ein entferntes leises Kichern. Du findest die Kraft, ");
				output("die Augen zu �ffnen und siehst eine kleine Fee �ber deinem Gesicht schweben, die ");
				output("unachtsam ihren Feenstaub �berall �ber dich verstreut. Dieser gibt dir genug Kraft, dich wieder ");
				output("aufzurappeln. Dein abruptes Aufstehen erschreckt die Fee, und noch bevor du die M�glichkeit hast, ");
				output("ihr zu danken, fliegt sie davon.");
				output("`n`n`^Du bist dem Tod knapp entkommen! Du hast einen Waldkampf und die meisten deiner Lebenspunkte verloren.");
				if ($session[user][turns]>0) $session[user][turns]--;
				if ($session[user][hitpoints]>($session[user][hitpoints]*.1)) $session[user][hitpoints]=round($session[user][hitpoints]*.1,0);
			break;
			case 3:
			  output("du f�hlst dich GEST�RKT!");
				output("`n`n`^Deine Lebenspunkte wurden aufgef�llt und du sp�rst die Kraft f�r einen weiteren Waldkampf.");
				if ($session[user][hitpoints]<$session[user][maxhitpoints]) $session[user][hitpoints]=$session[user][maxhitpoints];
				$session[user][turns]++;
				break;
			case 4:
			  output("du f�hlst deine SINNE GESCH�RFT! Du bemerkst unter den Kieselsteinen am Bach etwas glitzern.");
				output("`n`n`^Du findest einen EDELSTEIN!");
				$session[user][gems]++;
				//debuglog("found 1 gem by the stream");
				break;
			case 5:
			case 6:
			case 7:
			  output("du f�hlst dich VOLLER ENERGIE!");
				output("`n`n`^Du bekommst einen zus�tzlichen Waldkampf!");
				$session[user][turns]++;
				break;
			default:
			  output("du f�hlst dich GESUND!");
				output("`n`n`^Deine Lebenspunkte wurden vollst�ndig aufgef�llt.");
				if ($session[user][hitpoints]<$session[user][maxhitpoints]) $session[user][hitpoints]=$session[user][maxhitpoints];
		}
	}else{
	  output("`#Weil du die verh�ngnisvollen Kr�fte in diesem Wasser f�rchtest, entschlie�t du dich, es nicht zu trinken und gehst zur�ck in den Wald.");
	}
}
?>
