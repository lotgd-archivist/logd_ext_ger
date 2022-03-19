<?php

//13082004

if (!isset($session)) exit();
if ($HTTP_GET_VARS[op]==""){
  output("`3Ein alter Mann hält dich im Wald an und fragt dich: \"`!Wie würde es dir gefallen, ein kleines Ratespielchen ");
    output("mit mir zu spielen?`3\" Da du Leute wie ihn kennst, weißt du, daß er auf einem kleinen Wetteinsatz bestehen wird, wenn du dich darauf einlässt. ");
    output("`n`nWillst du sein Spiel spielen?`n`n<a href='forest.php?op=yes'>Ja</a>`n<a href='forest.php?op=no'>Nein</a>",true);
    addnav("Ja","forest.php?op=yes");
    addnav("Nein","forest.php?op=no");
    addnav("","forest.php?op=yes");
    addnav("","forest.php?op=no");
    $session[user][specialinc]="oldmanbet.php";
}else if($HTTP_GET_VARS[op]=="yes"){
  if ($session[user][gold]>0){
        $session[user][specialinc]="oldmanbet.php";
        $bet = abs((int)$HTTP_GET_VARS[bet] + (int)$HTTP_POST_VARS[bet]);
        if ($bet<=0){
            output("`3\"`!Ich denke mir eine Zahl aus und du hast 6 Versuche, diese Zahl zwischen 1 und 100 zu erraten. Ich werde dir immer sagen, ob dein Versuch zu hoch oder zu niedrig war.`3\"`n`n");
            output("`3\"`!Wie hoch ist ein Einsatz, ".($session[user][sex]?"junge Dame":"junger Mann")."?`3\"");
            output("<form action='forest.php?op=yes' method='POST'><input name='bet' id='bet'><input type='submit' class='button' value='Setze'></form>",true);
            output("<script language='JavaScript'>document.getElementById('bet').focus();</script>",true); // Bravebrain
            addnav("","forest.php?op=yes");
            $session[user][specialmisc]=e_rand(1,100);
        }else if($bet>$session[user][gold]){
          output("`3Der alte Mann streckt seinen Stock aus und klopft damit deinen Golsbeutel ab. \"`!Ich glaub nicht, daß da `^$bet`! Gold drin ist!`3\", erklärt er.`n`n");
            output("Verzweifelt versuchst du ihm deinen guten Willen zu zeigen und kippst den Beutelinhalt vor ihm aus: `^".$session[user][gold]."`3 Gold.");
            output("`n`nVerlegen kehrst du in den Wald zurück.");
            $session[user][specialinc]="";
            //addnav("Zurück in den Wald","forest.php");
        }else{
            if ($HTTP_POST_VARS[guess]!==NULL){
              $try = (int)$HTTP_GET_VARS['try'];
              if ($HTTP_POST_VARS[guess]==$session[user][specialmisc]){
                  if ($try == 1) {
                        output("`3\"`!UNGLAUBLICH`3\", schreit der alte Mann, \"`!Du hast meine Zahl mit nur `^einem Versuch`! erraten! Nun, ich gratuliere dir. Ich bin stark beeindruckt. Es ist gerade so, als ob du meine Gedanken lesen könntest.`3\" Er schaut dich misstrauisch eine Weile an und überlegt, ob er sich mit deinem Gewinn einfach aus dem Staub machen soll, erinnert sich dann aber an deine scheinbaren geistigen Kräfte und händigt dir deine `^$bet`3 Gold aus.");
                    } else {
                        output("`3\"`!AAAH!!!!`3\", schreit der alte Mann, \"`!Du hast die Zahl mit nur $try Versuchen erraten!  Es war `^".$session[user][specialmisc]."`!!!  Nun, ich gratuliere dir , ");
                        output("und denke ich werde jetzt besser gehen...`3\" Er will ins Unterholz verschwinden, doch mit einem flinken Schlag mit ".$session[user][weapon]);
                        output(" schlägst du ihn KO. Du hilfst ihm dabei, dir die `^$bet`3 Goldmünzen zu geben, die er dir schuldet.");
                    }
                    $session[user][gold]+=$bet;
                    //debuglog("won $bet gold from the old man in the forest");
                    $session[user][specialinc]="";
	$session[user][specialmisc]=""; 
                }else{
                  if ($HTTP_GET_VARS['try']>=6&&((int)$HTTP_POST_VARS[guess]>=0&&(int)$HTTP_POST_VARS[guess]<=100)){
                      output("`3Der Mann gluckst vor Freude: \"`!Die Zahl war `^".$session[user][specialmisc]."`!.`3\" Als der ehrenwerte Bürger, der du bist ");
                        output("gibst du dem Mann die `^$bet`3 Goldmünzen, die du ihm schuldest, bereit, von hier zu verschwinden.");
                        $session[user][specialinc]="";
	$session[user][specialmisc]=""; 
                        $session[user][gold]-=$bet;
                        //debuglog("lost $bet gold to the old man in the forest");
                    }else{
                          if((int)$HTTP_POST_VARS[guess]>100||(int)$HTTP_POST_VARS[guess]<0||!(int)$HTTP_POST_VARS[guess]){
                          $try--;
                          output("`3Der Alte lacht: \"`!Das ist wie einem Baby ein Schwert abzunehmen, wenn du wirklich glaubst, $HTTP_POST_VARS[guess] ist zwischen 1 und 100!`3\"`n\"`!Du hast noch `^".(6-$try)."`! Versuche übrig.`3\"`n");
                        } else if ((int)$HTTP_POST_VARS[guess]>$session[user][specialmisc]){
                          output("`3\"`!Nop, nicht `^".(int)$HTTP_POST_VARS[guess]."`!, meine Zahl ist kleiner als das!  Das war Versuch `^$try`!.`3\"`n`n");
                        }else{
                          output("`3\"`!Nop, nicht `^".(int)$HTTP_POST_VARS[guess]."`!, meine Zahl ist größer als das!  Das war Versuch `^$try`!.`3\"`n`n");
                        }
                        output("`3Du hast `^$bet`3 Gold gesetzt.  Was schätzt du?");
                        output("<form action='forest.php?op=yes&bet=$bet&try=".(++$try)."' method='POST'><input name='guess' id='guess'><input type='submit' class='button' value='Rate'></form>",true);
                        output("<script language='JavaScript'>document.getElementById('guess').focus();</script>",true); // Bravebrain
                        addnav("","forest.php?op=yes&bet=$bet&try=$try");
                    }
                }
            }else{
                output("`3Du hast `^$bet`3 Gold gesetzt.  Was schätzt du?");
                output("<form action='forest.php?op=yes&bet=$bet&try=1' method='POST'><input name='guess'><input type='submit' class='button' value='Rate'></form>",true);
                addnav("","forest.php?op=yes&bet=$bet&try=1");
            }
        }
    }else{
      output("`3Der alte Mann streckt seinen Stock aus und klopft deinen Goldbeutel ab. \"`!Leer?!?! Wie kannst du etwas setzen ohne Gold??`3\", brüllt er.");
        output("   Damit dreht er sich mit einem HARUMF um und verschwindet im Unterholz.");
        //addnav("Zurück in den Wald","forest.php");
        $session[user][specialinc]="";
    }
}else if($HTTP_GET_VARS[op]=="no"){
  output("`3Aus Furcht, dich von deinem teuren teuren Gold trennen zu müssen, lehnst du das Spiel des Alten ab. Hätte ja eh nicht viel Sinn gehabt ");
    output("weil du so oder so gewonnen hättest. Jep, hätte definitiv keine Chance dieser alte Kerl, nop.");
    //addnav("Zurück zum Wald","forest.php");
    $session[user][specialinc]="";
   $session[user][specialmisc]=""; 
}
?>
