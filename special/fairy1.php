<?php
if (!isset($session)) exit();
if ($HTTP_GET_VARS[op]==""){
	output("`%Du begegnest einer Fee. \"`^Gib mir einen Edelstein!`%\", verlangt sie. Was tust du?");
	addnav("Gib ihr einen Edelstein","forest.php?op=give");
	addnav("Gib ihr keinen Edelstein","forest.php?op=dont");
	$session[user][specialinc]="fairy1.php";
}else if ($HTTP_GET_VARS[op]=="give"){
  if ($session[user][gems]>0){
	  output("`%Du gibst der Fee einen deiner schwer verdienten Edelsteine. Sie schaut ihn an, quickt vor Entz�ckung und, ");
		output("verspricht dir als Gegenleistung ein Geschenk. Sie schwebt dir �ber den Kopf und streut goldenen Feenstaub auf  ");
		output("dich herab, bevor sie davon huscht. Du stellst fest.... `n`n`^");
		$session[user][gems]--;
		//debuglog("gave 1 gem to a fairy");
		switch(e_rand(1,7)){
		  case 1:
			  output("Du bekommst einen zus�tzlichen Waldkampf!");
				$session[user][turns]++;
				break;
			case 2:
			case 3:
				output("Du f�hlst deine Sinne gesch�rft und bemerkst `%ZWEI`^ Edelsteine in der N�he!");
				$session[user][gems]+=2;
				//debuglog("found 2 gem from a fairy");
				break;
			case 4:
			case 5:
				output("Deine maximalen Lebenspunkte sind `bpermanent`b um 1 erh�ht!");
				$session[user][maxhitpoints]++;
				$session[user][hitpoints]++;
				break;
			case 6:
			case 7:
				increment_specialty();
				break;
		}
	}else{
	  output("`%Du versprichst der Fee einen Edelstein, aber als du dein Golds�ckchen �ffnest, entdeckst du, ");
		output("da� du gar keinen Edelstein hast. Die kleine Fee schwebt vor dir, die Arme in die H�fte gestemmt und mit dem Fu� in der Luft klopfend, ");
		output("w�hrend du ihr zu erkl�ren versuchst, warum du sie angelogen hast.");
		output("`n`nAls sie genug von deinem Gestammel hat, streut sie �rgerlich etwas roten Feenstaub auf dich.  ");
		output("Du wirst ohnm�chtig und als du wieder zu dir kommst, hast du keine Ahnung, wo du bist. Du brauchst ");
		output("so viel Zeit, um den Weg zur�ck ins Dorf zu finden, da� du einen ganzen Waldkampf verlierst.");
		$session[user][turns]--;
	}
}else{
  output("`%Du willst dich nicht von einem deiner kostbaren Edelsteine trennen und schmetterst das kleine Gesch�pf im ");
	output("Vorbeigehen auf den Boden.");
}


?>
