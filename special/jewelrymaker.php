<?php

// 27062004

/*
* jewelrymaker.php - die seltsame Elfenkunst
* 
* coded by Warchild ( warchild@gmx.org )
* based on the items-table introduced by anpera
* 6/2004
* Version 0.91a dt
* Letzte �nderungen: 
* 
*/

if ($HTTP_GET_VARS[op]=="")
{
    output("`n`@Du schlenderst auf Deinem Weg an einem riesigen Baumstamm vorbei. Sprossen f�hren am Stamm wie eine Leiter direkt nach oben und eine `&weisse Kordel `@baumelt daneben. Du ziehst daran - irgendwo in dem Wipfel �ber Dir l�utet eine Glocke und eine Stimme ruft: `#\"Oh, Kundschaft! Klettere nur herauf!\"`@`nDu weisst mittlererweile, dass allerhand seltsame Gestalten im Wald hausen - willst Du hinaufklettern?");
    addnav("Zum Baumhaus klettern","forest.php?op=climbtree");
    addnav("Den Ort verlassen","forest.php?op=notree");
	$session[user][specialinc]="jewelrymaker.php";
}
else if ($HTTP_GET_VARS[op]=="climbtree")
{
    $session[user][specialinc]="jewelrymaker.php";
    output("`@Sprosse f�r Sprosse erklimmst Du den Baum und stehst bald auf einer Art Plattform, wo Dich ein hagerer `2Elf`@ - der ein braunes Gewand tr�gt und seine `6goldblonden Haare`@ zu einem Pferdeschwanz nach hinten gebunden hat - begr��t.`n");
    output("`#\"Willkommen in `!Feinfingers`# - meinem - Hause! Meine Profession ist die Sch�nheit, mein Leben die �sthetik! Ich kann Dir aus Deinem `6Gold ein Kunstwerk`# schaffen, was seinesgleichen sucht. Du musst mir nur `^all Dein Gold `#geben und ich schaffe Dir etwas Unvergleichliches, etwas, das noch kein Auge je erblickt hat! M�chtest Du das?\"`n`@Du z�gerst. Dein ganzes Gold?");
    addnav("Alles Gold hergeben!", "forest.php?op=givegold");
    addnav("Nix is! Ich geh!", "forest.php?op=noway");
}
else if ($HTTP_GET_VARS[op]=="givegold")
{
    // User hat schon ein "Kunstwerk" ?
    $sql = "SELECT * FROM items WHERE owner=".$session[user][acctid]." AND class='Schmuck' AND name='Elfenkunst'";
    $result = db_query($sql);
    if (db_num_rows($result) >0) // User hat schon Schmuck
    {
        $session[user][specialinc]="jewelrymaker.php";
        output("`@Der Elf mustert Dich mit moosgr�nen Augen durchdringend.`n`#\"Hm... ich hab doch f�r Dich schon ein unsterbliches Kunstwerk geschaffen! So etwas kann ich nicht zweimal tun! Ich muss Dich bitten zu gehen!\"");
        addnav("Schade!","forest.php?op=noway");
    }
    else
    {        
        if ($session[user][gold] > 0)
        {
            $session[user][specialinc]="jewelrymaker.php";
            output("`@Der Elf nimmt all Dein Gold und spricht einen Zauber dar�ber. Es verwandelt sich...`n`n");
            output("`6in ein wundersch�nes `&Etwas `6was Du leider nicht identifizieren kannst. Aber sch�n ist es. Irgendwie.");
            output("`n`n`@Du nimmst das Gebilde und staunst eine Weile dar�ber. Dann steckst Du es ein. Vielleicht gibt Dir ja ein H�ndler was daf�r...");
            // Goldwert randomisieren und Edelsteinwert randomisieren
            $goldvalue = e_rand(1, $session[user][gold] * 2);
            $gemvalue = e_rand(0,2);
            $sql = "INSERT INTO items (name,owner,class,gold,gems,description) VALUES ('Elfenkunst',".$session[user][acctid].",'Schmuck',$goldvalue,$gemvalue,'Ein wundersch�nes nutzloses Dings')";
            db_query($sql);
            if (db_affected_rows(LINK)<=0) 
            {
                output("`\$Fehler`^: Dein Inventar konnte nicht aktualisiert werden! Bitte benachrichtige den Admin.");
            }
            else // Alles ok, Gold auf 0 setzen
                $session[user][gold] = 0;
            addnav("Danke! Auf Wiedersehen!","forest.php?op=noway");
        }
        else // User pleite
        {
            $session[user][specialinc]="";
            output("`@Du willst dem Elfen gerade Deine Taschen ausleeren, da f�llt Dir auf, dass Du gar kein Gold mit hast! Da Dir das peinlich ist wartest Du, bis er sich umdreht, dann fl�chtest Du in den Wald zur�ck...");
        }
    }

}
else if ($HTTP_GET_VARS[op]=="noway")
{
    $session[user][specialinc]="";
    output("`@Du machst Dich wieder auf den Weg nach unten und verschwindest im Gr�n des Waldes, diesen seltsamen Elfen hast Du bald vergessen...");
    
}
else
{
    $session[user][specialinc]="";
    output("`n`@Du hast keine Lust, m�hsam nach oben zu kraxeln. Was eine Zeitverschwendung! Du gehst lieber zum Monsterkillen zur�ck in den Wald...");
}
?>