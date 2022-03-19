<?php

// 27062004

/* lake.php - an ol' temple's lake (Der Tempelsee)
* by weasel
* v2.0
* Extended by Warchild for lotgd.de
* in June 2004
* Idea by Burn
*
* Changelog
* 01.06.2004-11:32-warchild: Modifikation 1: Texte ausgedehnt, mehr M�glichkeiten
* 07.06.2004-12:16-warchild: kleinere Korrekturen
*/
output("`n`c`b`&Der Tempelsee`c`b`n");

if ($HTTP_GET_VARS[op]=="norp")
{ 
    $session[user][specialinc]="";
    output("`2Dir kommt das Ganze etwas komisch vor. Du machst lieber einen gro�en Bogen um diese Fl�ssigkeit.`n");
    $chance = e_rand(1,4);
    if ($chance > 3 && $session[user][turns] >0)
    {
        output("Deine Neugier treibt Dich allerdings dazu an, diesen Ort weiter zu erkunden. Doch auch nachdem Du in jedem Winkel gest�bert hast und selbst noch `7einen Stein `2aus der Strasse gebrochen hast, kannst Du nichts von Bedeutung finden. Frustiert stapfst Du zur�ck in den Wald.`n`n");
        output("`^Die verlorene Zeit h�ttest Du besser in einen Waldkampf gesteckt!");
        $session[user][turns]--;
    }
    addnav("Zur�ck in den Wald","forest.php"); 
}
else if ($HTTP_GET_VARS[op]=="trinken") 
{
    // Wasserfarbe festhalten
    $colour = $HTTP_GET_VARS[water];

    $session[user][specialinc]=""; 
	
    $rand = e_rand(1,3);
    if ($colour=="red") 
        $rand += 3;
    else if ($colour=="brown")
        $rand += 6;
    // result: blue 1-3, red 4-6, brown 7-9
    // wished results: blue 2/3 positive, red 1/3 positive, brown negative // not exactly with e_rand. red most
    
	output("`2Du nimmst einen kr�ftigen Schluck, und wartest ab was passiert..`n`n");
	
    switch ($rand)
    {
        // blue
        case 1:
        output("`2Au�er das die Br�he kalt war hast du nichts weiter gesp�rt.`n");
        output("`2Naja du f�hlst dich wenigstens wieder frisch.`n`n");
        addnav("Zur�ck in den Wald","forest.php");    
        break;

        case 2:
        output("`^Du sp�rst wie dein Blut pulsiert und sich das blaue Gl�hen auf deinen K�rper �bertr�gt.`n`n"); 
        output("`^Als du deine Hand ansiehst f�llt dir auf, das dort das Gl�hen geb�ndelt wird.`n");
        $session[user][gems]++;
        output("`^Ein Edelstein hat sich in deiner Hand gebildet. Du hast jetzt insgesamt ".$session[user][gems].", `n");
        output("wird es nicht Zeit langsam einen Juwelierladen zu er�ffnen?");
        addnav("Zur�ck in den Wald","forest.php");    
        break;
        
        case 3:
        output("`^Du f�hlst wie dein K�rper regeneriert.`n"); 
        output("`^Jetzt wo du wei�t, dass es eine Heilquelle ist und keine Gefahr davon ausgeht, entspannst du dich noch ein wenig `n");
        output("und tr�umst davon den Gr�nen Drachen zu besiegen.");
        if ($session[user][turns]>0) $session[user][turns]--;
        $session[user][hitpoints] = $session[user][maxhitpoints];
        addnav("Zur�ck in den Wald","forest.php"); 
	    break;

        // red
        case 4: // Brogads hot Chili by Burn
        output("`^Du probierst das Wasser und stellst fest, dass es genauso schmeckt wie `\$Brogads `&heisse `\$Chiliso�e`^! Verdammt, von dem Zeug h�ttest du schon damals die Finger lassen sollen!`n"); 
        output("`^Die extreme Sch�rfe l�sst deine Augen tr�nen und dich unkontrolliert husten. Als sich die So�e ihren Weg durch Deine Eingeweide w�hlt, wei�t du instinktiv, dass du die n�chsten Stunden heulend in den B�schen verbringen wirst.`n");
        if ($session[user][turns]<3) $session[user][turns] = 0;
        else 
            $session[user][turns] -= 2;
        addnav("Zur�ck in den Wald","forest.php"); 
	    break;

        case 5: // D�monenblut by Burn
        output("`^Als du das Zeug hinunterschluckst, sp�rst du, wie sich deine Wahrnehmung leicht ver�ndert. Die eingest�rzten S�ulenteile tragen zum Teil d�monische Fratzen, die dich h�hnisch anzugrinsen scheinen. Du sp�rst eine Ver�nderung in deinem K�rper...`n");
		if ($session[user][specialty] == 1) // Darkarts
		{
            output("da die `\$Essenz des B�sen`^ durch deine Adern rinnt! Die D�monen wispern dir dunkle Geheimnisse zu!`n`#");
            increment_specialty();
        }
        else
        {
            output("da etwas `\$B�sartiges, Fremdes`^ durch deine Adern rinnt. Du bekommst einen juckenden Ausschlag, der auch nach ein paar Tagen nicht verschwinden wird.`n");
            output("Du verlierst einen Charmpunkt!");
            $session[user][charm]--;
	$session[user][reputation]--;
        }
        addnav("Zur�ck in den Wald","forest.php"); 
	    break;

        case 6:
        output("`^Du f�hlst wie dein K�rper regeneriert.`n"); 
        output("`^Jetzt wo du wei�t, dass es eine Heilquelle ist und keine Gefahr davon ausgeht, entspannst du dich noch ein wenig. Dann kehrst Du mit neuem Mut in den Wald zur�ck.`nDu kannst heute ein paar Monster mehr erschlagen!`n");
        $session[user][turns]++;
        $session[user][hitpoints] = $session[user][maxhitpoints];
        addnav("Zur�ck in den Wald","forest.php"); 
	    break;

        // brown
        case 7:
        output("`^Die Br�he ist einfach ekelhaft.`n"); 
        output("`^Du �bergibst Dich spontan und kehrst dem Ort dann hastig den R�cken zu. Gl�cklicherweise hatte dein unvorsichtiger Trunk keine weiteren Konsequenzen.`n");
        addnav("Zur�ck in den Wald","forest.php"); 
	    break;

        case 8:
        output("`^Du versuchst zu schlucken, doch irgend ein kleiner Tierknochen bleibt in deinem Hals stecken.`n"); 
        output("`^W�hrend du auf die Knie sinkst und vergeblich nach Luft schnappst, �berlegst du noch, dass es eine dumme Idee war, von dem Zeug zu trinken...`n`n");
        output("`&Du bist gestorben! Du verlierst 10% deiner Erfahrung und kannst morgen wieder spielen!");
        $session[user][alive]=false;
        $session[user][hitpoints]=0;
        $session[user][experience]=$session[user][experience]*0.9;
        addnav("T�gliche News","news.php");
        addnews("`6".$session[user][name]." `6erstickte qualvoll an einem Vogelknochen!");
	    break;

        case 9:
        output("`^Der faulige Geschmack l��t dich w�rgen, und du stolperst in die B�sche, w�hrend sich dein Magen umdreht.`n"); 
        output("`^Erstaunlicherweise findest du auf dem R�ckweg `42 Edelsteine`^ als Du in ein verlassenes �ffchennest trittst.`n");
        $session[user][gems] += 2;
        addnav("Zur�ck in den Wald","forest.php"); 
	    break;        
	}
}else if ($HTTP_GET_VARS[op]=="spiegel") {
	output("`2Du beugst dich �ber den See, um dein Spiegelbild zu betrachten. Das bl�uliche Wasser zeigt tats�chlich ein Bild von dir, allerdings ist es ");
	output("ungew�hnlich verschwommen. Als du das Bild genauer betrachtest, stellst du fest, dass es dir weit mehr zeigt, als nur dein Aussehen:`n`n");
	output("`#Charme: ".grafbar(100,$session[user][charm],"20%",10)."`n`#Ansehen: ".grafbar(100,($session[user][reputation]+50),"20%",10),true);
	addnav("Zur�ck in den Wald","forest.php");
	$session[user][specialinc]==""; 
}
else
{ 
    output("`2Du stehst am Rande einer alten Tempelruine. Einige S�ulen sind zerfallen und liegen verteilt auf dem Boden. Ein seltsames graues Zwielicht herrscht �ber diesem Ort, als sei er zwischen Zeit und Raum eingefroren. `n");
    output("`2Die fein behauenen Rundsteine, mit denen die Umgebung gepflastert sind, sind `@grasbewachsen `2und an unregelm�ssigen Stellen herausgebrochen, als habe jemand willk�rlich Stolperstellen erzeugen wollen.");
    output("`2Vorsichtig schaust du dich etwas um und entdeckst eine kleine Quelle, die aus einer Wand des Gem�uers austritt.`n`n");
    // Farbe zuf�llig ermitteln, Codes:
    // bl�ulich ++ (60%) // percentage does not fit. values in the middle appear more often with e_rand() 
    // r�tlich + (30%)
    // braun -- (10%)
    $watertype = e_rand(1,10);
    switch ($watertype)
    {
        case 1:
        case 2:
        case 3:
        case 4:
        case 5:
        case 6:
            $colour = "blue";
            output("`2Dir f�llt auf, dass das Wasser `#leicht bl�ulich `2gl�ht. Nach n�herer Untersuchung kannst du nichts feststellen ");
            output("au�er das es eben `#bl�ulich gl�ht`2.`n");
            break;
        case 7:
        case 8:
        case 9:
            $colour = "red";
            output("`2Dir f�llt auf, dass das Wasser einen `4roten Schimmer`2 hat. Nach n�herer Untersuchung kannst du nichts feststellen ");
            output("au�er das es eben einen `4roten Schimmer `2hat.`n");
            break;
        case 10:
            $colour = "brown";
            output("`2Dir f�llt auf, dass das Wasser nur eine stinkende `6braune Suppe `2ist. Nach n�herer Untersuchung kannst du feststellen ");
            output("dass in der Suppe einige tote Tiere treiben. `n");
            break;
    }
    output("Das Wasser sammelt sich in einem halbrunden Becken am Fu� der alten Mauer, doch scheint das Becken nicht �berzulaufen. ");
    output("Wahrscheinlich gibt es irgendwo einen Abfluss.`n");
    output("In dem kleinen Becken hat sich jedoch genug angesammelt, um von ".($session[user][sex]?"einer zuf�llig vorbeiziehenden Kriegerin":"einem zuf�llig vorbeiziehenden Krieger")." getrunken werden zu k�nnen ");
    output("- wenn ".($session[user][sex]?"sie":"er")." wollte...");
	output("Das Wasser ist glatt und spiegelt die Landschaft wider.`n");
    addnav("Ich hab Durst!","forest.php?op=trinken&water=".$colour);
    addnav("Spiegelbild","forest.php?op=spiegel"); 
    addnav("Ich lasse es lieber bleiben!","forest.php?op=norp");
    $session[user][specialinc]="lake.php"; 
}
?>
