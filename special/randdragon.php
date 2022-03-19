<?php

// 22062004

/* Random Green Dragon Encounter v1 by Timothy Drescher (Voratus)
Current version can be found at Domarr's Keep (lgd.tod-online.com)
This is a simple "forest special" which helps to keep the main idea in mind, by giving any player an
encounter with the Green Dragon, and the results could be deadly.
The following names/locations are server-specific and should be changed:
	Plains of Al'Khadar (and reference to "plains")
	Domarr's Keep (the main city)

Version History
1.0 original version

german translation by anpera
some changes for my game - may not work with other versions!
*/

if (!isset($session)) exit();
$session[user][specialinc]="randdragon.php";
if ($HTTP_GET_VARS['count']==3) {
	output("`tDer `@Gr�ne Drachen`t hat genug von deinem Geschwafel. Er bl�st dich mit einem Feuersto� weg!`n`n");
	output("Du fragst dich noch, was schlimmer ist, der Schmerz, oder der Gestank deines verbrennenden Fleischs. Aber das spielt keine Rolle. ");
	output("Das Reich der Schatten empf�ngt dich.`n`n`4Du wurdest vom `@Gr�nen Drachen`4 gegrillt!`n");
	output("Du verlierst 5% deiner Erfahrung und alles Gold.");
	addnews("`%".$session[user][name]."`t wurde bei einer zuf�lligen Begegnung im Wald vom `@Gr�nen Drachen`t get�tet!");
	$session['user']['gold']=0;
	$session[user][experience]=round($session[user][experience]*.95,0);
	$session['user']['alive']=false;
	$session['user']['hitpoints']=0;
	$session['user']['specialinc']="";
	addnav("T�gliche News","news.php");
} else {
	switch($HTTP_GET_VARS[op]){
		case "":
			output("`tBei deinem Streifzug durch die W�lder h�rst du pl�tzlich ein lautes Br�llen. Das Ger�usch l�sst das Blut in deinen Adern gefrieren.`n");
			output("Ein tiefes Stampfen ist hinter dir zu h�ren. Starr vor Schreck f�hlst du einen Sto� hei�en Atem in deinem Nacken. ");
			output("Langsam drehst du dich um - und siehst einen riesigen `@Gr�nen Drachen`t vor dir stehen. ");
			output("`n`nDas k�nnte �rger geben...");
			addnav("Angreifen!","forest.php?op=slay");
			addnav("Um Gnade winseln","forest.php?op=cower");
			addnav("Rede dich raus","forest.php?op=talk");
			addnav("Lauf weg!","forest.php?op=flee");
			$session[user][specialinc]="randdragon.php";
			break;	
		case "slay":
			output("`tDu h�ltst deine Waffe fest im Griff und bereitest dich auf den Angriff auf diese gewaltige Kreatur vor.");
			output("`n`nDu br�llst einen Kampfschrei und springst auf den Drachen zu!`nDoch bevor deine Waffe den Drachen ber�hrt, schl�gt er sie dir mit seinem ");
			output("Schwanz aus der Hand und spuckt dir seinen Feueratem entgegen. ");
			if ($session['user']['level'] < 15) {
				output("`n`nDer Strahl wirft dich zu Boden. Du kannst f�hlen, wie sich durch die gro�e Hitze schwere Blasen auf deiner Haut bilden. ");
				output("`nGeschw�chst schaust du zum `@Gr�nen Drachen`t auf, der auf dich zu stolziert. ");
				if (rand(1,4)==1) {
					output("Er beugt sich gerade zu dir herunter, um dich zu verschlingen, als pl�tzlich ein Pfeil ");
					output(" scheinbar aus dem Nichts im Kopf des Drachen einschl�gt.`n");
					output("Mit einem entsetzlichen Br�llen fliegt der Drachen davon.`nDu kannst gerade noch einen Elfen auf dich zurennen sehen, ");
					output("dann wird dir schwarz vor Augen.`n`nEinige Zeit sp�ter erwachst du auf einer Lichtung. Deine ");
					output(" Wunden wurden geheilt, aber nichts kann die Verletzungen, die Drachenatem verursacht, wirklich vollst�ndig beseitigen.");
					output("`nDu verlierst zwei Charmepunkte durch die Verbrennungen!");
					addnews("`%".$session[user][name]."`t hat irgendwie eine zuf�llige Begegnung mit dem `@Gr�nen Drachen`t �berlebt.");
					$session['user']['charm']-=2;
					$session['user']['turns']-=2;
					if ($session['user']['turns'] < 0) $session['user']['turns']=0;
					$session['user']['reputation']++;
					$session['user']['hitpoints']=$session['user']['maxhitpoints'];
					$session[user][specialinc]="";
				} else {
					output("`nDas ist das Letzte, was du siehst, bevor du in die ewige Dunkelheit gleitest.`n`n");
					output("`4Du wurdest vom `@Gr�nen Drachen`4 gefressen!");
					output("Du verlierst 10% deiner Erfahrung und alles Gold.");
					addnews("`%".$session[user][name]."`t wurde bei einer zuf�lligen Begegnung im Wald vom `@Gr�nen Drachen`t get�tet!");
					$session['user']['gold']=0;
					$session[user][experience]=round($session[user][experience]*.9,0);
					$session['user']['alive']=false;
					$session['user']['hitpoints']=0;
					$session['user']['specialinc']="";
					addnav("T�gliche News","news.php");
				}
			} else {
				output("`nDu schaffst es im letzten Moment, dem Feuersto� aus dem Weg zu stolpern, um dich kurz darauf ");
				output("Auge in Auge mit diesem gewaltigen Biest zu finden. Sp�ttisch sagt er zu dir: \"`5Nich hier.");
				output(" Nicht jetzt.`t\"`nMit diesen Worten hebt der Drachen ab und steigt in die L�fte davon. Du bist wieder alleine ");
				output(" mit deinen Gedanken.");
				$session['user']['specialinc']="";
			}
			break;
		case "cower":
			output("`tDu kauerst dich vor dem `@Gr�nen Drachen`t zusammen und flehst um dein Leben. Der Drachen schnaubt dir erneut seinen hei�en Atem ");
			output("entgegen. \"`5An jemandem, der so erb�rmlich jammert, w�rde ich mir sicher nur den Magen verderben.");
			output("`n`5Hau schon ab.`@\"`nDu beschlie�t, dass es das Beste ist, den Anweisungen der Kreatur zu folgen, und so ");
			output("hoppelst du ver�ngstigt davon.");
			//addnews("`%".$session[user][name]."`5 grovelled ".($session[user][sex]?"her":"his")." way out of being dinner for the Green Dragon.");
			//$session['user']['charm']--;
			$session['user']['specialinc']="";
			$session['user']['reputation']-=2;
			break;
		case "talk":
			output("`tDu bist der Meinung, dass du diese Begegnung �berleben k�nntest, wenn es dir gelingt, den `@Gr�nen Drachen`t in ein Gespr�ch zu verwickeln. ");
			output("Jetzt brauchst du nur noch etwas, wor�ber ihr reden k�nntet.`n");
			addnav("Themen");
			addnav("W?Das Wetter","forest.php?op=weather&count=0");
			addnav("G?Der Gr�ne Drachen","forest.php?op=dragon&count=0");
			addnav("Violet","forest.php?op=violet&count=0");
			addnav("Seth","forest.php?op=seth&count=0");
			addnav("Cedrik","forest.php?op=cedrik&count=0");
			addnav("Degolburg","forest.php?op=city&count=0");
			addnav("Stottere unkontrolliert","forest.php?op=stutter&count=0");
			break;
		case "weather":
			$count=$HTTP_GET_VARS['count'];
			$count++;
			output("`t\"`qAlso ".getsetting("weather","Wetter").", was h�ltst du von diesem Wetter?`t\"`nDer Drachen legt den Kopf schief und schaut dich an. Ein kurzes ");
			output("Schnauben schl�gt dir hei�e, dampfende Luft entgegen.`n`nVielleicht interessiert den Drachen etwas anderes mehr?");
			addnav("Themen");
			addnav("G?Der Gr�ne Drachen","forest.php?op=dragon&count={$count}");
			addnav("Violet","forest.php?op=violet&count={$count}");
			addnav("Seth","forest.php?op=seth&count={$count}");
			addnav("Cedrik","forest.php?op=cedrik&count={$count}");
			addnav("Degolburg","forest.php?op=city&count={$count}");
			addnav("Stottere unkontrolliert","forest.php?op=stutter&count={$count}");
			break;
		case "dragon":
			$count=$HTTP_GET_VARS['count'];
			$count++;
			output("`t\"`qDu bist also der Gr�ne Drachen, h�?`t\"`nDer Drachen gibt ein ohrenbet�ubendes Br�llen von sich und ");
			output("leckt sich dann die Lippen. Vielleicht w�re ein anderes Thema besser zur Unterhaltung geeignet.");
			addnav("Themen");
			addnav("W?Das Wetter","forest.php?op=weather&count={$count}");
			addnav("Violet","forest.php?op=violet&count={$count}");
			addnav("Seth","forest.php?op=seth&count={$count}");
			addnav("Cedrik","forest.php?op=cedrik&count={$count}");
			addnav("Degolburg","forest.php?op=city&count={$count}");
			addnav("Stottere unkontrolliert","forest.php?op=stutter&count={$count}");
			break;
		case "violet":
			$count=$HTTP_GET_VARS['count'];
			$count++;
			output("`t\"`qDiese Violet ist ganz sch�n s�ss, was?`t\"`nDer Drachen nickt. \"`5Ein schmackhafter, s�sser Happen w�re das ");
			output(" Eine Schande, dass sie niemals die Schenke verl�sst. Aber vielleicht wirst du meinen Hunger solang stillen?.`t\"`n");
			output("Du solltest dir etwas anderes ausdenken. Schnell!");
			addnav("Themen");
			addnav("W?Das Wetter","forest.php?op=weather&count={$count}");
			addnav("G?Der Gr�ne Drachen","forest.php?op=dragon&count={$count}");
			addnav("Seth","forest.php?op=seth&count={$count}");
			addnav("Cedrik","forest.php?op=cedrik&count={$count}");
			addnav("Degolburg","forest.php?op=city&count={$count}");
			addnav("Stottere unkontrolliert","forest.php?op=stutter&count={$count}");
			break;
		case "seth":
			$count=$HTTP_GET_VARS['count'];
			$count++;
			output("`t\"`qSeth ist ein netter Kerl, stimmts?`t\"`nDer Drachen dreht den Kopf in Gedanken.`n");
			output("\"`5Ein bisschen schwer zu schlucken, w�rde ich wetten, aber er verl�sst ja nie die Schenke. Du dagegen hast es getan. ");
			output("`t\"`nDer Drachen schaut dich hungrig an. Zeit f�r eien Themenwechsel!");
			addnav("Themen");
			addnav("W?Das Wetter","forest.php?op=weather&count={$count}");
			addnav("G?Der Gr�ne Drachen","forest.php?op=dragon&count={$count}");
			addnav("Violet","forest.php?op=violet&count={$count}");
			addnav("Cedrik","forest.php?op=cedrik&count={$count}");
			addnav("Degolburg","forest.php?op=city&count={$count}");
			addnav("Stottere unkontrolliert","forest.php?op=stutter&count={$count}");
			break;
		case "cedrik":
			$count=$HTTP_GET_VARS['count'];
			$count++;
			output("`t\"`qCedrik ist ein m�rrischer alter Kerl, meinst du nicht auch?`t\"`nDer Drachen blinzelt langsam.");
			output("\"`5Dieser Sterbliche interessiet mich nicht. Aber du bietest dich mir doch geradezu an.`t\"`nMan braucht keinen");
			output("Gedankenleser, um zu erfahren, was dieses Ding denkt. Und du solltest seine Gedanken schnell ");
			output("in eine andere Richtung lenken.");
			addnav("Themen");
			addnav("W?Das Wetter","forest.php?op=weather&count={$count}");
			addnav("G?Der Gr�ne Drachen","forest.php?op=dragon&count={$count}");
			addnav("Violet","forest.php?op=violet&count={$count}");
			addnav("Seth","forest.php?op=seth&count={$count}");
			addnav("Degolburg","forest.php?op=city&count={$count}");
			addnav("Stottere unkontrolliert","forest.php?op=stutter&count={$count}");
			break;
		case "city":
			$count=$HTTP_GET_VARS['count'];
			$count++;
			output("`t\"`qWusstest du, dass das Dorf Degolburg hei�t? Ist ein ziemlich beeindruckendes �rtchen!`t\"`nDer Drachen gr�hlt laut.`n\"`5Diese stinkende Stadt ist mir ");
			output("ein Dorn im Auge, weiter nichts! Ich sollte ihre schwachen Mauern niederrei�en und die Stadt nieerbrennen! ");
			output(" Alle sollten `bmeinen`b Namen kennen und mich f�rchten!`t\"`nNun, es scheint so, als ob du die Kreatur ");
			output("ver�rgert hast. Vielleicht hilft ein Themenwechsel.");
			addnav("Themen");
			addnav("W?Das Wetter","forest.php?op=weather&count={$count}");
			addnav("G?Der Gr�ne Drachen","forest.php?op=dragon&count={$count}");
			addnav("Violet","forest.php?op=violet&count={$count}");
			addnav("Seth","forest.php?op=seth&count={$count}");
			addnav("Cedrik","forest.php?op=cedrik&count={$count}");
			addnav("Name?","forest.php?op=name&count={$count}");
			addnav("Stottere unkontrolliert","forest.php?op=stutter&count={$count}");
			break;
		case "stutter":
			$count=$HTTP_GET_VARS['count'];
			$count++;
			output("`tDu versuchst, ein intelligentes Thema zu finden, aber stattdessen stotterst du nur unkontrolliert vor dich hin.");
			output(" Der Drachen rollt dramatisch mit den Augen. Er schl�gt dich mit seinem Schwanz auf den Hinterkopf und du wirst ");
			output("ohnm�chtig.`n`nEinige Zeit sp�ter wachst du mit einer gewaltigen Beule am Kopf wieder auf.`n");
			if ($session['user']['hitpoints'] > $session['user']['maxhitpoints']*.1) {
				$session['user']['hitpoints']=round($session['user']['maxhitpoints']*.1);
			} else {
				$session['user']['hitpoints']=1;
			}		
			$session['user']['turns']--;
			if ($session['user']['turns'] < 0) $session['user']['turns']=0;
			addnews("`%".$session[user][name]."`t hat irgendwie eine zuf�llige Begegnung mit dem `@Gr�nen Drachen`t �berlebt.");
			$session['user']['reputation']++;
			$session['user']['specialinc']="";
			break;
		case "name":
			output("`t\"`qWie ist dein Name, oh m�chtiger Drachen?`t\"`nDer Drachen betrachtet dich ernst. \"`5Du w�rst nicht");
			output(" in der Lage, ihn richtig auszusprechen. Es sind nur wenige in dieser Welt �brig, die das k�nnen, denn es verlangt ");
			output(" die Sprachfertigkeit eines ausgewachsenen Drachen, von denen es nur noch wenige gibt. Unsere einst gro�e und stolze Rasse");
			output(" wurde von den niederen Rassen zu Aasfressern reduziert, aus Angst, wir k�nnten sie alle vernichten.`t\"`n");
			output("Der Drachen schaut einen Moment weg, dann wendet er sich dir erneut zu. \"`5Drachen haben nur get�tet, was wir als Nahrung brauchten.");
			output(" Jetzt t�ten wir, um zu �berleben.`t\"`n`n\"`5Weiche von mir, bevor ich mich entschlie�e, dich ohne Grund zu t�ten.`t\"");
			output("`nDu h�ltst es f�r eine gute Idee, dich zu beeilen, bevor es sich der Drache anders �berlegt und einen Snack aus dir macht.");
			addnews("`%".$session[user][name]."`t hat irgendwie eine zuf�llige Begegnung mit dem `@Gr�nen Drachen`t �berlebt.");
			$session['user']['specialinc']="";
			break;
		case "flee":
			$results=rand(1,4);
			if ($results==1) {
				output("`tDu drehst dich um und fl�chtest so schnell du kannst vor der Macht des Drachens. Du glaubst, du schaffst es, denn ");
				output("du h�rst keinen Verfolger hinter dir.`nDu h�ltst an, um dich umzudrehen. Keine Spur vom Drachen!`n");
				output("Du hast wirklich Gl�ck gehabt.");
				addnews("`%".$session[user][name]."`t hat irgendwie eine zuf�llige Begegnung mit dem `@Gr�nen Drachen`t �berlebt.");
				$session['user']['specialinc']="";
				$session['user']['reputation']--;
			} elseif ($results==4) {
				output("`tDu drehst dich um und fl�chtest so schnell du kannst vor der Macht des Drachens. Du glaubst, du schaffst es, denn ");
				output("du h�rst keinen Verfolger hinter dir.`nDu h�ltst an, um dich umzudrehen. Keine Spur vom Drachen!`n");
				output("Du glaubst schon, du bist dem Biest entkommen und drehst dich wieder um. Und da steht der Drachen direkt vor dir und ");
				output("sein weit aufgerissene Maul rast auf dich zu.`nBevor du Zeit hast, zu reagieren, ");
				output("hat dich der Drachen verspeist..");
				output("`4Du bist gestorben!`nDu verlierst 10% deiner Erfahrung und all dein Gold.");
				addnews("`%".$session[user][name]."`t wurde bei einer zuf�lligen Begegnung im Wald vom `@Gr�nen Drachen`t get�tet!");
				$session['user']['gold']=0;
				$session[user][experience]=round($session[user][experience]*.9,0);
				$session['user']['alive']=false;
				$session['user']['hitpoints']=0;
				$session['user']['specialinc']="";
				addnav("T�gliche News","news.php");
			} else {
				output("`tDu drehst dich um um vor der Macht des Drachen zu fliehen. W�hrend du rennst, f�hlst du pl�tzlich, wie du von einer ");
				output(" Welle der Hitze umschlossen wirst. Der Drachen hat dich mit einem Feuersto� erwischt!");
				$damage=e_rand(round($session['user']['maxhitpoints']*.5,0),$session['user']['maxhitpoints']);
				output("Du verlierst $damage Lebenspunkte durch diesen Treffer!`n");
				$session['user']['hitpoints']-=$damage;
				if ($session['user']['hitpoints'] < 1) {
					$session['user']['hitpoints']=0;
					output("`4Du bist gestorben!`nDu verlierst 10% deiner Erfahrungspunkte und alles Gold!");
					addnews("`%".$session[user][name]."`t wurde bei einer zuf�lligen Begegnung im Wald vom `@Gr�nen Drachen`t get�tet!");
					$session['user']['gold']=0;
					$session[user][experience]=round($session[user][experience]*.9,0);
					$session['user']['alive']=false;
					$session['user']['specialinc']="";
					addnav("T�gliche News","news.php");
				} else {
					output("Du rollst dich auf dem Boden, um das Feuer zu l�schen. Nachdem du festgestellt hast, dass du nicht tot bist, ");
					output("blickst du dich nach dem Drachen um. Doch der ist verschwunden. Hat er dich absichtlich nicht get�tet? ");
					output("Oder war er blo� der Meinung, dass du jetzt verkocht bist?`nDie Antwort wirst du nie erfahren, so machst du dich wieder auf den Weg. ");
					output("Ein Besuch beim Heiler d�rfte jetzt erstmal an der Reihe sein.");
					addnews("`%".$session[user][name]."`t hat irgendwie eine zuf�llige Begegnung mit dem `@Gr�nen Drachen`t �berlebt.");
					$session['user']['specialinc']="";
				}
			}
			break;
	}
}
//output("</span>",true); // what's that good for?
?>
