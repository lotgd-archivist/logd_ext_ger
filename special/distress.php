<?php

// 22062004

/* *******************
The damsel in distress was written by Joe Naylor
Much of the event text was written by Matt Clift, I understand it is
heavily inspired by an event in Legend of the Red Dragon.
Feel free to use this any way you want to, but please give credit where due.
Version 1.1ger
******************* */

if (!isset($session)) exit();

if ($HTTP_GET_VARS[op]==""){
    $session[user][specialinc]="distress.php";
    output("`n`3Bei der Suche im Wald findest du ein Mann mit dem Gesicht nach unten im Schmutz liegen. ");
    output("Der Pfeil in seinem R�cken, der See an Blut und das Fehlen jeder Bewegung sind gute Anzeichen daf�r: ");
    output("Dieser Mann ist tot.`n`n");

    output("`3Du durchsucht die Leiche und findest ein zerkn�lltes St�ck Papier in seiner geschlossenen Faust. ");
    output("Vorsichtig befreist du das Papier aus seiner Hand und erkennst, dass es eine hastig niedergeschriebene Notiz ist.  ");
    output("Auf dem Papier steht geschrieben:`n`n");
    
    output("`3\"`7Hilfe! Ich wurde von meinem gemeinem alten Onkel eingesperrt. Er will mich zur Hochzeit zwingen.  ");
    output("Bitte rette mich! Ich werde gefangen gehalten in ... `3\"`n`n");
    
    output("`3Der Rest der Notiz ist entweder zu stark mit Blut beschmiert, oder zu stark besch�digt, um mehr zu erkennen.`n`n");
    
    output("`3Emp�rt schreist du: \"`7Ich muss ".($session[user][sex]?"ihn":"sie")." retten!`3\" Aber wo willst du suchen?`n`n");
    
    output("<a href=forest.php?op=1>Gehe zur Lindwurmfestung</a>`n", true);
    output("<a href=forest.php?op=2>Gehe zur Burg Slaag</a>`n", true);
    output("<a href=forest.php?op=3>Gehe zu Draco's Kerker</a>`n", true);
    output("`n<a href=forest.php?op=no>Das ist reine Zeitverschwendung</a>", true);
    addnav("Gehe zu:");
    addnav("Lindwurmfestung","forest.php?op=1");
    addnav("S?Burg Slaag","forest.php?op=2");
    addnav("Draco's Kerker","forest.php?op=3");
    addnav("Ignoriere es","forest.php?op=no"); // Changed because too many players kept hitting 'f' by mistake due to most things in forest being a 'F'ight.
    addnav("","forest.php?op=1");
    addnav("","forest.php?op=2");
    addnav("","forest.php?op=3");
    addnav("","forest.php?op=no");

}else if ($HTTP_GET_VARS[op]=="no"){
    output("`3Du kn�llst die Notiz zusammen und wirfst sie in den Wald.  Du hast keine Angst, es ist dir nur die Zeit zu Schade daf�r. ");
    output("Nop, �berhaupt keine Angst, kein bisschen. Du wendest dem erb�rmlichen Hilferuf ".($session[user][sex]?"des armen Mannes":"der armen Jungfrau")." ");
    output("den R�cken zu und machst dich auf, im Wald etwas weniger gef�hrl... �h, etwas gr��ere Herausforderungen zu finden.");
    // addnav("Zur�ck in den Wald","forest.php");
    $session[user][specialinc]="";
}else{
    switch($HTTP_GET_VARS[op]) {
        case 1: $loc = "`#der Lindwurmfestung";
            break;
        case 2: $loc = "`#Burg Slaag";
            break;
        case 3: $loc = "`#Draco's Kerker";
            break;
    }

    output("`n`3Du st�rmst durch die Tore von $loc `3und metzelst die Wachen und alles nieder, was dir in die Quere kommt. ");

    switch (e_rand(1, 10)) {
        case 1:
        case 2:
        case 3:
        case 4:
            output("`3Schlie�lich �ffnest du etwas, das wie eine T�r aussieht und entdeckst eine gut eingerichtete Kammer. `n`n");
            output("Die Kammer enth�lt ".($session[user][sex]?"einen jungen, attraktiven und dankbaren Bewohner":"eine junge, h�bsche und dankbare Bewohnerin").". `n`n");
            output("\"`#Du bist gekommen!`3\" ".($session[user][sex]?"sagt er, \"`#Meine Heldin,":"strahlt sie,  \"`#Mein Held,")." wie kann ich dir jemals danken?`3\"`n`n");
            output("Nach einigen Stunden der Umarmung verl�sst du ".($session[user][sex]?"den Prinzen":"die Prinzessin")." ");
            output("und gehst wieder deinen eigenen Weg. Allerdings nicht ohne ein kleines Zeichen ".($session[user][sex]?"seiner":"ihrer")." Wertsch�tzung.`n`n");
	$session[user][reputation]+=3;
	addnews("`%".$session[user][name]."`3 rettete ".($session[user][sex]?"einen Prinzen":"eine Prinzessin")." aus $loc `3!");
            switch (e_rand(1, 5)) {
                case 1:
                    output("".($session[user][sex]?"Er":"Sie")." gibt dir einen kleinen Lederbeutel.`n`n");
                    $reward = e_rand(1, 2);
                    output("`^Du bekommst $reward ".($reward==1?"Edelstein":"Edelsteine")."!");
                    $session[user][gems] += $reward;
					//debuglog("gained $reward gems rescuing a damsel in distress");
                    break;
                case 2:
                    output("".($session[user][sex]?"Er":"Sie")." gibt dir einen kleinen Lederbeutel.`n`n");
                    $reward = e_rand(1, $session[user][level]*30);
                    output("`^Du bekommst $reward Goldst�cke!");
                    $session[user][gold] += $reward;
					//debuglog("gained $reward gold rescuing a damsel in distress");
                    break;
                case 3:
                    output("".($session[user][sex]?"Er":"Sie")." hat dir Dinge gezeigt, von denen du nichtmal zu tr�umen gewagt hast.`n`n");
                    output("`^Deine Erfahrung steigt!");
                    $session[user][experience] *= 1.1;
                    break;
                case 4:
                    output("".($session[user][sex]?"Er":"Sie")." hat dir beigebracht, wie ".($session[user][sex]?"eine richtige Frau":"ein richtiger Mann")." zu sein.`n`n");
                    output("`^Du erh�ltst zwei Charmepunkte!");
                    $session[user][charm] += 2;
                    break;
                case 5:
                    output("".($session[user][sex]?"Er":"Sie")." gibt dir eine Fahrt mit der Kutsche zur�ck ins Dorf, alleine...`n`n");
                    output("`^Du bekommst einen Waldkampf und bist vollst�ndig geheilt!");
                    $session[user][turns] ++;
                    if ($session['user']['hitpoints']<$session['user']['maxhitpoints']) $session[user][hitpoints] = $session[user][maxhitpoints];
                    break;
                }
            break;
        case 5:
            output("`3Schlie�lich �ffnest du etwas, das wie eine T�r aussieht und entdeckst eine gut eingerichtete Kammer. `n`n");
            output("Die Kammer enth�lt eine grosse Truhe, aus der ged�mpfte Hilferufe kommen. Du reisst die Truhe auf  ");
            output("und wirfst dich in Heldenpose. Dann siehst du den Bewohner der Truhe. ");
            output(" ".($session[user][sex]?"Ein monstr�ser und einsamer Troll":"Eine monstr�se und einsame Trolldame")."steigt daraus empor!!  Nach einigen Stunden des Vergn�gens ");
            output("l�sst ".($session[user][sex]?"er":"sie")." dich ziehen.  Du f�hlst dich mehr als ein bisschen schmutzig.`n`n");
						if ($session[user][race] == 1) {
							output("Du hast schon fast vergessen, wie potent deine Rasse ist!`n");
							output("`%Du bekommst 1 Waldkampf!`n");
							output("`%Du bekommst einen Charmepunkt!`n");
							$session[user][turns]+=1;
							$session[user][charm]++;
						} else {
							output("`%Du verlierst einen Waldkampf!`n");
							output("`%Du verlierst einen Charmepunkt!`n");
							if ($session[user][turns] > 0) $session[user][turns]--;
							if ($session[user][charm] > 0) $session[user][charm]--;
						}
            break;
        case 6:
            output("`3Schlie�lich �ffnest du etwas, das wie eine T�r aussieht und entdeckst eine gut eingerichtete Kammer. `n`n");
            output("In der Kammer steht ein ".($session[user][sex]?"runzeliger alter Mann":"runzeliges altes Weib")."!  Du schnappst vor Abscheu vor diesem f�rchterlichen Ding nach Luft ");
            output("vor dir, bevor du laut schreiend davon rennst. Irgendwie hast du das Gef�hl, ");
            output("irgendetwas hat dich ausgenutzt.`n`n"); 
            output("`%Du verlierst einen Charmpunkt!`n");
            if ($session[user][charm] > 0) $session[user][charm]--;
            break;
        case 7:
            output("`3Schlie�lich �ffnest du etwas, das wie eine T�r aussieht und entdeckst eine gut eingerichtete Kammer. `n`n");
            output("Du st�rzt in den Raum. Am Fenster sitzt ein l�cherlich aussehender unm�nnlicher Trottel.  ");
            output("\"`5Du bist gekommen!`3\", heult er. Er springt auf die Beine und rennt dir entgegen, stolpert dabei aber �ber seinen ");
            output("Nachttopf und verf�ngt sich in seinen Kleidern. Du nutzt diese Gelegenheit und machst dich so schnell und leise wie ");
            output("m�glich aus dem Staub. Gl�cklicherweise hat ausser deinem Stolz sonst nichts Schaden genommen.`n`n");
            break;
        case 8:
            output("`3Eine heisse Schlacht entbrennt und du bringst einiges an M�he auf! Ungl�cklicherweise bist du hoffnungslos ");
            output("in Unterzahl. Du versuchst wegzurennen, f�llst aber sehr bald zwischen den Klingen deiner Feinde. `n`n");
            output("`%Du bist gestorben!`n`n");
            output("`3Die Lektion, die du heute gelernt hast, gleicht jeden Erfahrungsverlust aus.`n");
            output("Du kannst morgen wieder spielen.");
            $session[user][alive]=false;
            $session[user][hitpoints]=0;
	$session['user']['donation']+=1;
            addnav("T�gliche News","news.php");
            addnews("`%".$session[user][name]."`3 starb beim Versuch, ".($session[user][sex]?"einen Prinzen":"eine Prinzessin")." aus $loc `3zu befreien.");
	$session[user][reputation]+=5;
            break;
        case 9:
            output("`3Eine heisse Schlacht entbrennt und du bringst einiges an M�he auf! Ungl�cklicherweise bist du hoffnungslos ");
            output("in Unterzahl. Schlie�lich siehst du doch eine Chance zu Flucht und brichst aus. Das letzte, was die Einwohner von $loc `3");
            output("von dir sehen, ist dein R�cken. Du fliehst in den Wald.`n`n");
            output("`%Du verlierst einen Waldkampf!`n");
            output("`%Du verlierst die meisten deiner Lebenspunkte!`n");
            if ($session[user][turns]>0) $session[user][turns]--;
            if ($session[user][hitpoints]>($session[user][hitpoints]*.1)) $session[user][hitpoints]=round($session[user][hitpoints]*.1,0);
            break;
        case 10:
            output("`3Schlie�lich �ffnest du etwas, das wie eine T�r aussieht und entdeckst eine gut eingerichtete Kammer. `n`n");
            output("Du st�rmst hinein und findest einen �berraschten Edelmann und seine Frau friedlich beim Essen vor.`n`n");
            output("\"`3Er fordert eine Erkl�rung: \"`^Was bedeutet das?`3\" Du versuchst ihm zu erkl�ren, wie es dazu kam und ");
            output("dass du den falschen Ort erwischt hast. Aber er scheint an Erkl�rungen kein Interesse zu haben! Die Beh�rden werden gerufen und ");
            output("du mu�t f�r alle entstandenen Sch�den aufkommen.`n`n");
            output("`%Du verlierst alles Gold, das du dabei hattest!`n");
			//debuglog("lost {$session['user']['gold']} gold dying while rescuing a damsel in distress");
            $session[user][gold]=0;
            break;
    }

    $session[user][specialinc]="";
//addnav("Zur�ck in den Wald","forest.php");
}

?>
