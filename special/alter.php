<?php
if (!isset($session)) exit();
if ($HTTP_GET_VARS[op]==""){
  output("`@Du stolperst �ber eine Lichtung und bemerkst einen Altar mit 5 Seiten vor dir. Auf jeder Seite liegt ein anderer Gegenstand. Du siehst `#einen Dolch, `\$einen Sch�del,`% einen juwelenbesetzten Stab, `^ein Rechenbrett `7und ein schlicht aussehendes Buch. `@In der Mitte �ber dem Altar befindet sich ein `&Kristallblitz.`n`n");
	output("  `@Du wei�t, da� es dich Zeit f�r einen ganzen Waldkampf kosten wird, einen der Gegenst�nde n�her zu untersuchen.`n`n`n");
	addnav("Nimm den Dolch","forest.php?op=dagger");
	addnav("Nimm den Sch�del","forest.php?op=skull");
	addnav("Nimm den Stab","forest.php?op=wand");
	addnav("Nimm das Rechenbrett","forest.php?op=abacus");
	addnav("Nimm das Buch","forest.php?op=book");
	addnav("Nimm den Kristallblitz","forest.php?op=bolt");
	addnav("Verlasse den Altar unber�hrt","forest.php?op=forgetit");
	$session[user][specialinc] = "alter.php";

}else if ($HTTP_GET_VARS[op]=="dagger"){
  $session[user][turns]--; 
	if (e_rand(0,1)==0){
	  output("`#Du nimmst den Dolch von seinem Platz. Doch er l�st sich in deinen H�nden in Luft auf und du f�hlst eine Welle von Energie in deinen K�rper str�men!`n`n  `&Du erh�ltst 10 zus�tzliche Anwendungen in Diebesk�nsten.`n`n`#Aber du bist auch etwas traurig, denn diese Kraft wird morgen wieder verschwunden sein.");
		$session[user][thieveryuses] = $session[user][thieveryuses] + 10;
	}else{
    output("`#Du nimmst den Dolch von seinem Platz. Doch er l�st sich in deinen H�nden in Luft auf und du f�hlst eine Welle von Energie in deinen K�rper str�men!`n`n  `&Du erh�ltst 3 Level in Diebesk�nsten!");
		$session[user][thievery] = $session[user][thievery] + 3;
		$session[user][thieveryuses]++;
	}
	addnav("Zur�ck in den Wald","forest.php");
	$session[user][specialinc]="";

}else if ($HTTP_GET_VARS[op]=="skull"){
  $session[user][turns]--; 
	if (e_rand(0,1)==0){
	  output("`#Du greifst nach dem Sch�del. Vor deinen Augen l�st sich der Sch�del auf und du f�hlst eine Energiewelle in deinen K�rper fahren!`n`n  `&Du erh�ltst 10 zus�tzliche Anwendungen der Dunklen K�nste.`n`n`#Aber du bist auch etwas traurig, denn diese Kraft wird morgen wieder verschwunden sein.");
		$session[user][darkartuses] = $session[user][darkartuses] + 10;
	}else{
    output("`#Du greifst nach dem Sch�del. Vor deinen Augen l�st sich der Sch�del auf und du f�hlst eine Energiewelle in deinen K�rper fahren!`n`n  `&Du erh�ltst 3 Levels in Dunklen K�nsten!");
		$session[user][darkarts] = $session[user][darkarts] + 3;
		$session[user][darkartuses]++;
	}
	addnav("Zur�ck in den Wald","forest.php");
	$session[user][specialinc]="";

}else if ($HTTP_GET_VARS[op]=="wand"){
  $session[user][turns]--; 
	if (e_rand(0,1)==0){
	  output("`#Du hebst den Stab von seinem Platz auf. In einem Lichtblitz verschwindet er und eine seltsame Kraft durchstr�mt deinen K�rper!`n`n  `&Du erh�ltst 10 zus�tzliche Anwendungen in Mystischen Kr�ften.`n`n`#Aber du bist auch etwas traurig, denn diese Kraft wird morgen wieder verschwunden sein.");
		$session[user][magicuses] = $session[user][magicuses] + 10;
	}else{
    output("`#Du hebst den Stab von seinem Platz auf. In einem Lichtblitz verschwindet er und eine seltsame Kraft durchstr�mt deinen K�rper!`n`n  `&Du erh�ltst 3 Levels in Mystischen Kr�ften!");
		$session[user][magic] = $session[user][magic] + 3;
		$session[user][magicuses]++;
	}
	addnav("Zur�ck in den Wald","forest.php");
	$session[user][specialinc]="";

}else if ($HTTP_GET_VARS[op]=="abacus"){
  $session[user][turns]--; 
	if (e_rand(0,1)==0){
	  $gold = e_rand($session[user][level]*30,$session[user][level]*90);
	  $gems = e_rand(1,4);
	  output("`#Du nimmst das Rechenbrett von seinem Platz.  Das Rechenbrett verwandelt sich in einen Beutel voller Gold und Edelsteine!`n`n Du bekommst $gold Goldst�cke und $gems Edelsteine!");
		$session[user][gold]+=$gold;
		$session[user][gems]+=$gems;
	}else{
		$gold = $session[user][gold]+($session[user][level]*20);
    output("`@`#Du nimmst das Rechenbrett von seinem Platz.  Das Rechenbrett verwandelt sich in einen Beutel voller Gold!`n`n Du bekommst $gold Goldst�cke!");
		$session[user][gold]+=$gold;
		}
	addnav("Zur�ck in den Wald","forest.php");
	$session[user][specialinc]="";

}else if ($HTTP_GET_VARS[op]=="book"){
  $session[user][turns]--; 
	if (e_rand(0,1)==0){
	  $exp=$session[user][experience]*0.15;
	  output("`#Du nimmst das Buch und beginnst darin zu lesen. Das Wissen in diesem Buch hilft dir viel weiter und du legst es an seinen Platz zur�ck, damit ein anderer auch noch davon profitieren kann.`n`nDu bekommst $exp Erfahrungspunkte!");
		$session[user][experience]+=$exp;
	}else{
		$ffights = e_rand(1,5);
    output("`@`#Du nimmst das Buch und beginnst darin zu lesen.  Das Buch enth�lt ein Geheimnis, wie du deine heutigen Streifz�ge durch den Wald profitabler gestalten kannst.  Du legst das Buch an seinen Platz zur�ck, damit ein anderer auch noch davon profitieren kann.`n`nDu bekommst $ffights zus�tzliche Waldk�mpfe!");
		$session[user][turns]+=$ffights;
		}
	addnav("Zur�ck in den Wald","forest.php");
	$session[user][specialinc]="";

}else if ($HTTP_GET_VARS[op]=="bolt"){
  $session[user][turns]--; 
	$bchance=e_rand(0,7);
	if ($bchance==0){
		    output("`#Du greifst nach dem Kristallblitz.  Der Blitz verschwindet aus deinen H�nden und erscheint wieder auf dem Altar. Nach einigen Versuchen, den Blitz zu bekommen, hast du keine Lust mehr, noch mehr Zeit damit zu vergeuden. Du f�rchtest auch, die G�tter dadurch herauszufordern.");
			addnav("Zur�ck in den Wald","forest.php");
		}elseif ($bchance==1){
			output("`#Du greifst nach dem Kristallblitz. Als du den Blitz gerade ber�hrst, wirst du r�ckw�rts auf den Boden geschleudert. Du kommst schnell wieder auf die Beine und f�hlst dich sehr m�chtig!`n`nDu bekommst 10 Anwendungen in allen Fertigkeiten! Leider sp�rst du, da� diese Macht nicht einmal bis zum n�chsten Morgen halten wird.");
			$session[user][thieveryuses]+=10;
			$session[user][darkartuses]+=10;
			$session[user][magicuses]+=10;
			addnav("Zur�ck in den Wald","forest.php");
		}elseif($bchance==2){
			output("`#Du greifst nach dem Kristallblitz. Als du den Blitz gerade ber�hrst, wirst du r�ckw�rts auf den Boden geschleudert. Du kommst schnell wieder auf die Beine und f�hlst dich sehr m�chtig!`n`nDu steigst in jeder Fertigkeit 3 Level auf!");
			$session[user][thievery]+=3;
			$session[user][darkarts]+=3;
			$session[user][magic]+=3;
			$session[user][thieveryuses]++;
			$session[user][darkartuses]++;
			$session[user][magicuses]++;
			addnav("Zur�ck in den Wald","forest.php");
		}elseif($bchance==3){
			output("`#Du greifst nach dem Kristallblitz. Als du den Blitz gerade ber�hrst, wirst du r�ckw�rts auf den Boden geschleudert. Du kommst schnell wieder auf die Beine und f�hlst dich sehr m�chtig!`n`nDu bekommst 5 zus�tzliche Lebenspunkte!");
			$session[user][maxhitpoints]+=5;
			$session[user][hitpoints]+=5;
			addnav("Zur�ck in den Wald","forest.php");
		}elseif($bchance==4){
			output("`#Du greifst nach dem Kristallblitz. Als du den Blitz gerade ber�hrst, wirst du r�ckw�rts auf den Boden geschleudert. Du kommst schnell wieder auf die Beine und f�hlst dich sehr m�chtig!`n`nDu bekommst 2 Angriffspunkte und 2 Verteidigungspunkte dazu!");
			$session[user][attack]+=2;
			$session[user][defence]+=2;
			addnav("Zur�ck in den Wald","forest.php");
		}elseif($bchance==5){
			$exp=$session[user][experience]*0.2;
			output("`#Du greifst nach dem Kristallblitz. Als du den Blitz gerade ber�hrst, wirst du r�ckw�rts auf den Boden geschleudert. Du kommst schnell wieder auf die Beine und f�hlst dich sehr m�chtig!`n`nDu bekommst $exp Erfahrungspunkte!");
			$session[user][experience]+=$exp;
			addnav("Zur�ck in den Wald","forest.php");
		}elseif($bchance==6){
			$exp=$session[user][experience]*.2;
			output("`#Deine Hand n�hert sich dem Kristallblitz, als der Himmel pl�tzlich vor Wolken �berkocht. Du f�rchtest, die G�tter ver�rgert zu haben und beginnst zu rennen. Doch noch bevor du die Lichtung verlassen kannst, wirst du von einem Blitz getroffen.`n`nDu f�hlst dich d�mmer!  Du verlierst $exp Erfahrungspunkte!");
			$session[user][experience]-=$exp;
			addnav("Zur�ck in den Wald","forest.php");
		}else{
			output("`#Deine Hand n�hert sich dem Kristallblitz, als der Himmel pl�tzlich vor Wolken �berkocht. Du f�rchtest, die G�tter ver�rgert zu haben und beginnst zu rennen. Doch noch bevor du die Lichtung verlassen kannst, wirst du von einem Blitz getroffen.`n`nDu bist tot!");
			output("Du verlierst 5% deiner Erfahrungspunkte und all dein Gold!`n`n");
			output("Du kannst morgen wieder spielen.");
			$session[user][alive]=false;
            $session[user][hitpoints]=0;
			$session[user][gold]=0;
            $session[user][experience]=$session[user][experience]*0.95;
			addnav("T�gliche News","news.php");
            addnews($session[user][name]." wurde von den G�ttern niedergeschmettert, da ".($session[user][sex]?"sie":"er")." von Gier zerfressen war!");
		}
	$session[user][specialinc]="";

}else if ($HTTP_GET_VARS[op]=="forgetit"){
  output("`@Du beschlie�t, das Schicksal lieber nicht herauszufordern und dadurch wom�glich die G�tter zu ver�rgern. Du l��t den Altar in Ruhe.");
	output("Als du die Lichtung gerade verlassen willst, stolperst du �ber ein Beutelchen mit einem Edelstein! Die G�tter m�ssen dir wohlgesonnen sein!");
	$session[user][gems]+=1;
	//addnav("Zur�ck in den Wald","forest.php");
	$session[user][specialinc]="";
}
?>