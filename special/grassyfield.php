<?php
//Original idea by Sean McKillion
//modifications by Eric Stevens
//further modifications by JT Traub
if ($_GET['op']=="return") {
    $session['user']['specialmisc']="";
    $session['user']['specialinc']="";
    redirect("forest.php");
}

checkday();

output("`n`c`#Du stolperst auf eine grasbewachsene Lichtung`c");
addnav("Zur�ck in den Wald","forest.php?op=return");
if ($session['user']['specialmisc'] != "Hier gibt's nichts zu sehen.") {
    if ($session[user][hashorse]>0){
        $buff = unserialize($playermount['mountbuff']);
        if ($session['bufflist']['mount']['rounds'] > $buff['rounds']*.5) {
            if ($playermount['partrecharge']) {
                output("`n`n{$playermount['partrecharge']}");
            } else {
                output("`n`n`&Du erlaubst deinem {$playermount['mountname']} sich hier zu st�rken und herumzutollen.");
            }
        } else {
            if ($playermount['recharge']) {
                output("`n`n{$playermount['recharge']}");
            } else {
                output("`n`n`&Du erlaubst deinem {$playermount['mountname']} zu jagen und sich auszuruhen.");
            }
        }
    
        $session['bufflist']['mount']=$buff;

        if ($session[user][hitpoints]<$session[user][maxhitpoints]){
            output("`n`^Dein Nickerchen hat dich vollst�ndig geheilt!");
            $session[user][hitpoints] = $session[user][maxhitpoints];
        }
        $session['user']['turns']--;
        output("`n`n`^Du verlierst einen Waldkampf f�r heute.");
    } else {
        output("`n`n`&Du beschlie�t, dir einen Moment Zeit zu nehmen und deinen armen F��en eine kurze Pause von deinen riskanten Abenteuern zu g�nnen. Du genie�t die wundersch�ne Umgebung.");
        output("`n`n`^Du regenerierst vollst�ndig!");
        if ($session[user][hitpoints]<$session[user][maxhitpoints])
            $session[user][hitpoints] = $session[user][maxhitpoints];
    }
    $session['user']['specialmisc'] = "Hier gibt's nichts zu sehen.";
} else {
    output("`n`n`&Du ruhst dich eine Weile hier aus und genie�t die Sonne und den Schatten.");
}
$session['user']['specialinc'] = "grassyfield.php";

output("`n`n`@Rede mit den anderen, die hier herumlungern.`n");
viewcommentary("grassyfield","Hinzuf�gen",10,"sagt");
?>