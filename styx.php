<?php

// 22062004

// Another work from that stupid german guy who lives for LoGD by Eric Stevens
//
// v. 21042004
//
// Escape from death, or haunt the world of the living from beyond your grave
// or do other things you wouldn't have thought to be possible at all.
//
// You can download the complete 0.9.7+jt extended (GER) which contains this piece of code
// from somewhere on Hatetepe://w�.anpera.net

require_once "common.php";
checkday();

if ($_GET[op]=="enterdead"){
	page_header("Der Seelenfluss");
	$was=e_rand(1,4);
	output("`9Deine Seele folgt dem Fluss der Toten aufw�rts. Du siehst viele Seelen, die im Fluss mitgerissen werden, einige werden auf Booten von toten F�hrm�nnern gefahren und andere versuchen wie du die Flucht.");
	if($was==2){
		output("`9 Schlie�lich siehst du ein Licht am Horizont, aus dem der Fluss zu entspringen scheint. Eilig bewegst du dich darauf zu.");
		output("`n`n`#Dir gelingt die Flucht aus dem Totenreich!`n`n`9Ersch�pft �ffnest du die Augen. Dein K�rper ben�tigt dringend Heilung, wenn du ihn nicht sofort wieder verlieren willst. Wie lange du tot warst, kannst du nicht sagen, aber sehr lange kann es nicht gewesen sein.");
		$session[user][alive]=1;
		$session[user][hitpoints]=1;
		$session[user][spirits]=-6;
		if ($session[user][turns]>2) $session[user][turns]-=2;
		addnav("Zum Dorf","village.php");
		addnews("`&".$session[user][name]."`& gelang die Flucht aus dem Totenreich.");
	}else{
		output("`9 Die vielen H�nde, die aus dem Fluss nach dir greifen, lassen dich nur langsam vorankommen. Schlie�lich zerren sie dich ganz in den Fluss. Du wirst zur�ck ins Totenreich geschwemmt - direkt vor `\$Ramius`9' F�sse.");
		output("`n`nDein Fluchtversuch ist gescheitert.");
		if (!$session['user']['prefs']['nosounds']) output("<embed src=\"media/lachen.wav\" width=10 height=10 autostart=true loop=false hidden=true volume=100>",true);
		addnav("Zum Friedhof","graveyard.php");
	}

}elseif ($_GET[op]=="explore"){
	page_header("Der Seelenfluss");
	if ($_GET[subop]=="favor"){
		output("`9F�r ein paar mehr Gefallen bei `\$Ramius`9 w�rdest du sogar deine Seele verkaufen. Ups! Lieber doch nicht. So versprichst du dieser trotteligen Seele");
		output(" ein paar deiner Edelsteine, die dir hier unten ja sonst wirklich nichts n�tzen. Wie du `4Hatetepe`9 die Steinchen �bergeben sollst, ist dir in dem Moment egal - dir ");
		output("w�re es sogar recht, wenn er die Steine nie einfordern w�rde.`n");
		output("`4Hatetepe`9 verspricht, ein gutes Wort f�r dich bei `\$Ramius`9 einzulegen. Gerade als du ihn fragen willst, was er hier unten �berhaupt mit Edelsteinen anfangen will, ");
		output(" findest du dich auf dem Friedhof wieder...");
		$session[user][deathpower]+=10;
		$session[user][gems]-=2;
		addnav("Weiter...","graveyard.php");
	}elseif ($_GET[subop]=="gem"){
		output("`4Hatetepe`9 verspricht dir, einen Edelstein f�r dich im Dorf bereit zu legen. Gerade als du ihn fragen willst, wie er das schaffen will, ");
		output(" findest du dich auf dem Friedhof wieder...");
		addnav("Weiter...","graveyard.php");
		$session[user][gems]++;
		$session[user][deathpower]-=5;
	}elseif ($_GET[subop]=="gf"){
		$session[user][gravefights]++;
		$session[user][gems]--;
		addnav("Weiter...","graveyard.php");
	}elseif ($_GET[subop]=="gf3"){
		$session[user][gravefights]+=3;
		$session[user][gems]-=5;
		addnav("Weiter...","graveyard.php");
	}elseif ($_GET[subop]=="spuken"){
		if ($session[user][deathpower]<=0){
			output("`9Du hast keine Gefallen mehr �brig, die du auf diese Weise verspielen k�nntest. Traurig dar�ber, dass du wohl gerade deine Chance, ");
			output(" heute noch aus dem Totenreich zu kommen, verspielt hast, machst du dich auf den Weg zur�ck zum Friedhof");
		}else{
			output("`9Du verlierst einen Gefallen und nimmst mit der Welt der Lebenden Kontakt auf.`n Du hast noch `b`4".$session[user][deathpower]."`b`9 Gefallen.`n`n");
			addcommentary();
			switch($_GET[where]){
				case "1":
				viewcommentary("pvparena","Spuke",10,"seufzt von irgendwo her");
				break;
				case "2":
				viewcommentary("village","Spuke",25,"seufzt von irgendwo her");
				break;
				case "3":
				viewcommentary("academy","Spuke",25,"spukt durch die Hallen");
				break;
				case "4":
				viewcommentary("gardens","Spuke",30,"seufzt von irgendwo her");
				break;
				case "5":
				viewcommentary("inn","Spuke",20,"seufzt von irgendwo her");
				break;
				case "6":
				viewcommentary("hunterlodge","Spuke",20,"seufzt von irgendwo her");
				break;
				case "7":
				viewcommentary("well","Spuke",25,"klagt aus der Tiefe");
				default:
				viewcommentary("grassyfield","Spuke",10,"seufzt von irgendwo her");
			}
			$session[user][deathpower]--;
		}
		addnav("Zum Friedhof","graveyard.php");
	}else{
		$what=e_rand(1,3);
		$where=e_rand(1,8);
		if ($what==1){
			output("`9\"`!Sei gegr�sst!`9\", spricht dich eine alte Seele an, die schon seit Ewigkeiten hier zu sein scheint, \"`!Ich bin `4Hatetepe`!, ");
			output("der tote H�ndler, der nie gestorben ist, immer auf dem Sprung und schon ewig hier. Ich tausche hier meine Waren, die mir nie geh�rten. Sie bringen dir sowohl im Totenreich, wie auch");
			output(" im Reich der Lebenden einen Vorteil, der keiner ist. Also, kann ich dir materiellen oder spirituellen Besitz anbieten oder abkn�pfen?`9\"");
			addnav("Kaufen");
			if ($session[user][gems]>0) addnav("1 Grabkampf  f�r 1 Edelstein","styx.php?op=explore&subop=gf");
			if ($session[user][gems]>4) addnav("3 Grabk�mpfe f�r 5 Edelsteine","styx.php?op=explore&subop=gf3");
			if ($session[user][deathpower]>4) addnav("1 Edelstein f�r 5 Gefallen","styx.php?op=explore&subop=gem");
			if ($session[user][gems]>1) addnav("10 Gefallen f�r 2 Edelsteine","styx.php?op=explore&subop=favor");
			if ($session[user][deathpower]>0) addnav("Spuken f�r Gefallen","styx.php?op=explore&subop=spuken&where=$where");
			addnav("Sonstiges");
			addnav("Zum Friedhof","graveyard.php");
		}elseif ($what==2){
			output("`9Du entdeckst eine M�glichkeit, mit der Welt der Lebenden in Verbindung zu treten! Allerdings wird dich dieses Unternehmen einiges ");
			output("an Gefallen kosten und wer und ob dich jemand h�ren wird, wissen nicht einmal die G�tter.");
			addnav("Spuken f�r Gefallen","styx.php?op=explore&subop=spuken&where=$where");
			addnav("Zum Friedhof","graveyard.php");
		}else{
			output("`9Du entdeckst hier absolut nichts besonderes.");
			addnav("Zum Friedhof","graveyard.php");
		}

	}
}else{
	page_header("Der Seelenfluss");
	if (!$session[user][alive]){
		output("`9Du bemerkst einen seltsamen Schimmer und wandelst darauf zu.`nDu hast den `bFluss der Seelen`b gefunden! Jenen merkw�rdigen Ort, ");
		output("der angeblich das Reich der Toten und die Welt der Lebenden verbindet und wo all die toten Kreaturen herkommen, die einst den Wald und jetzt den Friedhof bev�lkern. Du witterst eine Chance, dem Totenreich zu entfliehen, aber du wei�t auch um die ");
		output("Gefahren einer solchen Unternehmung bescheid.`n`nWirst du den Fluchtversuch wagen? Oder willst du diesen sagenhaft Ort n�her untersuchen?"); 
		addnav("Fluchtversuch","styx.php?op=enterdead");
		addnav("Ort untersuchen","styx.php?op=explore");
		addnav("Zur�ck zum Friedhof","graveyard.php");
	}else{
		redirect("village.php");
	}
}

//output("`n`n`\$`bIN ARBEIT`b");
page_footer();
?>