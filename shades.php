<?php
require_once "common.php";

page_header("Land der Schatten");
addcommentary();
checkday();

if ($session['user']['alive']) redirect("village.php");
if ($session[user][acctid]==getsetting("hasegg",0)) addnav("Benutze das goldene Ei","newday.php?resurrection=egg");
output("`\$Du wandelst jetzt unter den Toten, du bist nur noch ein Schatten. �berall um dich herum sind die Seelen der in alten Schlachten und bei  
gelegentlichen Unf�llen gefallenen K�mpfer. Jede tr�gt Anzeichen der Niedertracht, durch welche sie ihr Ende gefunden haben.`n`n
Im Dorf d�rfte es jetzt etwa `^".getgametime()."`\$ sein, aber hier herrscht die Ewigkeit und Zeit gibt es mehr als genug.`n`n
Die verlorenen Seelen fl�stern ihre Qualen und plagen deinen Geist mit ihrer Verzweiflung:`n");
viewcommentary("shade","Verzweifeln",25,"jammert");
addnav("Der Friedhof","graveyard.php");
addnav("Kriegerliste","list.php");
addnav("In Ruhmeshalle spuken","hof.php");
addnav("Zur�ck zu den News","news.php");
if ($session[user][superuser]>=2){
  addnav("Admin Grotte","superuser.php");
}
page_footer();
?>
