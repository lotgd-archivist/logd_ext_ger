<?

// found at logd.dragoncat.net
// translation by anpera

$session['user']['specialinc']="smith.php";

if ($_GET['op']=="none"){
    output("Smiythe w�nscht dir noch einen guten Tag und schlendert zur�ck in den Wald.");
    $session['user']['specialinc']="";
}elseif ($session['user']['gems']>0 && ($_GET['op']=="weapon" || $_GET['op']=="armor")){
    $session['user']['specialinc']="";
    $previously_upgraded   = strpos($session['user'][$_GET['op']]," +1")!==false ? true : false;
    $previously_downgraded = strpos($session['user'][$_GET['op']]," -1")!==false ? true : false;
    output("`7Du gibst Smiythe dein(e/n) `#");
    output($session['user'][$_GET['op']]);
    if ($previously_upgraded){
        output("`7 und er begutachtet das Teil sorgf�ltig.  \"`6Aha, ich sehe, dass ich daran schon gearbeitet habe. Ich frage dich: Wie soll ich Perfektion verbessern?`7");
        output("`n`n\"`6Nein, ich f�rchte, daran kann ich nichts mehr verbessern. Gehabt euch wohl, Freund!`7\", sagt er und macht sich auf den Weg in den Wald.");
    }elseif ($previously_downgraded){
        output("`7 und er begutachtet das Teil sorgf�ltig.  \"`6Aha, ich sehe, dass schon irgendein Metzger an dieser ".($_GET['op']=="weapon"?"Waffe":"R�stung")." herumgefummelt hat!  Ich h�tte niemals so schlechte Qualit�t geliefert.  Egal,");
        output(" ich kann den Schaden leicht reparieren!`7\"");
        output("`n`n`^Deine ".($_GET['op']=="weapon"?"Waffe":"R�stung")." wurde repariert!");
        $session['user']['gems']--;
        $session['user'][$_GET['op']."value"]*=1.33;
        $session['user'][$_GET['op']] = str_replace(" -1","",$session['user'][$_GET['op']]);
    }else{
        $session['user']['gems']--;
        $r = e_rand(1,100);
        if ($r<30){
            output("`7 und er begutachtet das Teil sorgf�ltig.  \"`6Daran kann ich nicht viel machen, mein Freund, tut mir Leid.`7\" sagt er und gibt es dir zur�ck.");
        }elseif ($r<90){
            output("`7 und er begutachtet das Teil einen kurzen Moment. Dann zieht er einen Amboss und einen kleinen Schmiedeofen hinter seinem R�cken hervor und macht sich an die Arbeit.  Nach einigen Stunden gibt er dir dein(e/n) {$session['user'][$_GET['op']]} zur�ck - besser als vorher!");
            $session['user'][$_GET['op']] = $session['user'][$_GET['op']]." +1";
            $session['user'][$_GET['op'].($_GET['op']=="weapon"?"dmg":"def")]+=1;
            $session['user'][($_GET['op']=="weapon"?"attack":"defence")]++;
            $session['user'][$_GET['op']."value"]*=1.33;
        }else{
            output("`7 und er f�ngt sofort an, wie ein kleines Kind darauf herum zu h�mmern.`nTja, deiner ".($_GET['op']=="weapon"?"Waffe":"R�stung")." ist diese Behandlung nicht besonders gut bekommen. Sie wurde schlechter!");
            $session['user'][$_GET['op']] = $session['user'][$_GET['op']]." -1";
            $session['user'][$_GET['op'].($_GET['op']=="weapon"?"dmg":"def")]-=1;
            $session['user'][($_GET['op']=="weapon"?"attack":"defence")]--;
            $session['user'][$_GET['op']."value"]*=0.75;
        }
    }
}elseif ($session['user']['gems']<=0 && ($_GET['op']=="weapon" || $_GET['op']=="armor")){
    output("Du hast nicht genug Edelsteine, um deine Ausr�stung verbessern zu lassen, so kehrst du besch�mt �ber deine Armut in den Wald zur�ck.");
    $session['user']['specialinc']="";    
}else{
    output("`7Du stapfst vorsichtig durchs Unterholz, als du einen st�mmigen Mann mit einem schweren Hammer in dern Hand bemerkst.  ");
    output("Sicher, da� er keine Bedrohung f�r dich darstellt, n�herst du dich ihm und sagst: \"`&Hey du!`7\".`n`n");
    output("\"`6Mein Name ist Smiythy.`7\", antwortet er.");
    output("`n`n\"`&Was?`7\" fragst du und l�sst dir deine Verwunderung anmerken.");
    output("`n`n\"`6Smiythe, das ist mein Name.  Ich bin ein Schmied.  Smiythe der Schmied werde ich von einigen genannt.  Und ich w�rde mich freuen, dir meine Schmiedek�nste f�r eine geringe Geb�hr anbieten zu d�rfen.`7");
    output("`n`n\"`6F�r nur 1 Edelstein kann ich versuchen, deine R�stung oder deine Waffe zu verbessern.  Aber ich muss dich warnen: Obwohl ich der beste Schmied hier in der Gegend bin, ");
    output("mache ich trotzdem hin und wieder Fehler und kann nicht immer f�r beste Qualit�t garantieren.`7\"");
    addnav("R�stung verbessern (1 Edelstein)","forest.php?op=armor");
    addnav("Waffe verbessern (1 Edelstein)","forest.php?op=weapon");
    addnav("Lieber keine Experimente","forest.php?op=none");
}
?>