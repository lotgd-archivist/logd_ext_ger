<?php 

// 22062004

// translation found at http://logd.ist-hier.de
// small ... BIG ... modifications by anpera

if (!isset($session)) exit();

if ($HTTP_GET_VARS[op]=="do"){
	$session[user][reputation]--;
	if ($session['user']['sex']>0){
		output("`%Du folgst dem Gott in die B�sche. Wenige Minuten sp�ter, sind "); 
		output("nur noch leise Ger�usche zu h�ren. "); 
		output("Als du erwachst, stellst du fest dass ...`n`n`^"); 
		switch(e_rand(1,10)){ 
			case 1: 
			case 2: 
			output("er verschwunden ist ohne sich zu verabschieden. Aufgrund deiner Ersch�pfung, verlierst du einen Waldkampf."); 
			$session[user][turns]-=1; 
			$session[user][experience]+=150; 
			break; 
			case 3: 
			case 4: 
			output("er verschwunden ist ohne sich zu verabschieden. Du f�hlst dich gut und k�nntest jetzt einen Kampf vertragen."); 
			$session[user][turns]+=1; 
			$session[user][experience]+=150; 
			break; 
			case 5: 
			output("er dir einen Beutel mit Edelsteinen da gelassen hat. Du f�hlst dich benutzt, akzeptierst aber die Bezahlung!"); 
			$session[user][gems]+=3; 
			$session[user][experience]+=150; 
			break; 
			case 6: 
			output("er einen goldenen Apfel da gelassen hat. Als du in den Apfel bei�t, f�hlst du zus�tzliche Lebenskraft in dir!"); 
			$session[user][maxhitpoints]+=1; 
			$session[user][experience]+=150; 
			break; 
			case 7: 
			case 8: 
			case 9: 
			case 10: 
			increment_specialty(); 
			break; 
		} 
		addnews($session[user][name]." hatte einen Quicky mit einem Gott.");
		$session[user][specialinc]="";
		//addnav("Zur�ck in den Wald","forest.php");
	}else{ 
		output("`%Du folgst der G�ttin in die B�sche. Wenige Minuten sp�ter, sind "); 
		output("nur noch leise Ger�usche zu h�ren. "); 
		output("Als du erwachst, stellst du fest dass ...`n`n`^"); 
		switch(e_rand(1,10)){ 
			case 1: 
			case 2: 
			output("sie verschwunden ist ohne sich zu verabschieden. Aufgrund deiner Ersch�pfung, verlierst du einen Waldkampf."); 
			$session[user][turns]-=1; 
			$session[user][experience]+=150; 
			break; 
			case 3: 
			case 4: 
			output("sie verschwunden ist ohne sich zu verabschieden. Du f�hlst dich gut und k�nntest jetzt einen Kampf vertragen."); 
			$session[user][turns]+=1; 
			$session[user][experience]+=150; 
			break; 
			case 5: 
			output("sie dir einen Beutel mit Edelsteinen da gelassen hat. Du f�hlst dich benutzt, akzeptierst aber die Bezahlung!"); 
			$session[user][gems]+=3; 
			$session[user][experience]+=150; 
			break; 
			case 6: 
			output("sie einen goldenen Apfel da gelassen hat. Als du in den Apfel beisst, f�hlst du zus�tzliche Lebenskraft in dir!"); 
			$session[user][maxhitpoints]+=1; 
			$session[user][experience]+=150; 
			break; 
			case 7: 
			case 8: 
			case 9: 
			case 10: 
			increment_specialty(); 
			break; 
		} 
		addnews($session[user][name]." hatte einen Quicky mit einer G�ttin.");
		$session[user][specialinc]="";
		//addnav("Zur�ck in den Wald","forest.php");
	}
}else if ($HTTP_GET_VARS[op]=="dont"){
	output("In Gedanken an ".($session[user][sex]?"deinen Geliebten":"deine Geliebte")." rennst du weg. Die Gottheit lacht auf und verschwindet in den Schatten des Waldes.");
	$session[user][specialinc]="";
	$session[user][reputation]+=2;
	//addnav("Zur�ck in den Wald","forest.php");
}else{
	if ($session['user']['sex']>0){
		output("`%Als du durch den Wald wanderst, spricht dich ein stattlicher Mann an. \"`^Ich bin der Gott Fexez. Ich habe von dir geh�rt. Komm mit mir.`%\" Was tust du ?"); 
	}else{
		output("`%Als du durch den Wald wanderst, erscheint Dir eine wundersch�ne Frau. \"`^Ich bin die G�ttin Aphrodite. Ich habe sogar in Athen von deiner Manneskraft geh�rt. Ich will es ausprobieren. Komm mit mir.`%\" Was tust du?"); 
	}
		addnav("Gehe mit","forest.php?op=do"); 
		addnav("Laufe weg","forest.php?op=dont"); 
		$session[user][specialinc]="aphrodite.php"; 
}
?>