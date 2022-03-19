<?php
/* *******************
The riddling gnome was written by Joe Naylor
Feel free to use this any way you want to, but please give credit where due.
Version 1.1ger
******************* */

if (!isset($session)) exit();

//** Used to remove extra words from the beginning and end of a string
// Note that string will be converted to lowercase
function filterwords($string)
{
    $string = strtolower($string);

    //Words to remove
    $filterpre = array (
        "a",
        "an",
        "and",
        "the",
        "my",
        "your",
        "someones",
        "someone's",
        "someone",
        "his",
        "her",
        	"ein",
	"eine",
	"der",
	"die",
	"das",
	"s");
    //Letters to take off the end
    $filterpost = array (
        "s",
        "ing",
        "ed");

		//split in to array of words
    $filtstr = explode(" ", trim($string));
    foreach ($filtstr as $key => $filtstr1)
        $filtstr[$key] = trim($filtstr1);

		//pop off word if found in $filterpre
    foreach ($filtstr as $key => $filtstr1)
        foreach ($filterpre as $filterpre1) 
            if (!strcasecmp($filtstr1, $filterpre1))
                $filtstr[$key] = "";
    
		//trim off common word endings
    foreach ($filtstr as $key => $filtstr1)
        foreach ($filterpost as $filterpost1) 
            if (strlen($filtstr) > strlen($filterpost1)) 
                if (!strcasecmp(substr($filtstr1, -1*strlen($filterpost1)), $filterpost1)) 
                    $filtstr[$key] = substr($filtstr1, 0, strlen($filterstr)-strlen($filterpost1));

		//rebuild filtered input
    $string = implode("", $filtstr);

    return $string;
}

if ($HTTP_GET_VARS[op]==""){
    output("`6`nEin kurzer kleiner Gnom mit Blättern in den Haaren hockt neben einem kleinen Baum. Er grinst und kichert hinter einer seiner fettigen Hände.");
    output("Für einen Moment sieht es so aus, als ob er in den Wald wegkrabbeln will, aber dann grinst er dich an.`n`n");
    output("`6\"`@Ich werde dir einen Gefallen tun,`6\" sagt er, \"`@wenn du mein Rätsel lösen kannst!`6\"`n`n");
    output("`6Vorübergehend verfällt er in unkontrolliertes Kichern, fasst sich wieder für einen Moment und fährt fort.`n`n");

    output("`6\"`@Aber wenn du falsch liegen solltest, mein Gefallen es wird sein!`6\"`n");
    output("`6`nNimmst du die Herausforderung an?`n`n");
    output("<a href=forest.php?op=yes>Ja</a>`n", true);
    output("<a href=forest.php?op=no>Nein</a>`n", true);

    addnav("Ja","forest.php?op=yes");
    addnav("Nein","forest.php?op=no");
    addnav("","forest.php?op=yes");
    addnav("","forest.php?op=no");
    if ($session[user][specialinc]!="riddles.php"){
        $session[user][specialmisc]=NULL;
    }
    $session[user][specialinc]="riddles.php";

}else if($HTTP_GET_VARS[op]=="yes"){
    //if ($HTTP_POST_VARS[guess]==NULL){
		if ($_GET['subop']!="answer"){
        $session[user][specialinc]="riddles.php";
        $rid = $session[user][specialmisc];
        if (!strpos($rid, "Riddle")) {
            $sq1 = "SELECT * FROM riddles ORDER BY rand(".e_rand().")";
        }else{
            $rid = substr($rid, -1*(strlen($rid)-6));    // 6 letters in "Riddle"
            $sq1 = "SELECT * FROM riddles WHERE id=$rid";
        }        
        $result = db_query($sq1) or die(db_error(LINK));
        $riddle = db_fetch_assoc($result);
        $session[user][specialmisc]="Riddle" . $riddle[id];
        output("`6Vor Freude kichernd stellt er sein Rätsel:`n`n");
        output("`6\"`@$riddle[riddle]`6\"`n`n");
        output("`6Was meinst du?");
        output("<form action='forest.php?op=yes&subop=answer' method='POST'><input name='guess'><input type='submit' class='button' value='Rate'></form>",true);
        addnav("","forest.php?op=yes&subop=answer");
    }else{
        $rid = substr($session[user][specialmisc], 6);
        $sq1 = "SELECT * FROM riddles WHERE id=$rid";
        $result = db_query($sq1) or die(db_error(LINK));
        $riddle = db_fetch_assoc($result);


        //*** Get and filter correct answer
        $answer = explode(";", $riddle[answer]); //there can be more than one answer in the database, seperated by semicolons (;)
        foreach($answer as $key => $answer1) {
					//changed "" to " " below, I believe this is the correct implementation.
            $answer[$key] = preg_replace("/[^[:alpha:]]/"," ",$answer1); 
            $answer[$key] = filterwords($answer1);
            }
        
        //*** Get and filter players guess
        $guess = $HTTP_POST_VARS[guess];
        // $guessdebug = $guess; // This is only for debugging, see below when the answer is wrong.
        $guess = preg_replace("/[^[:alpha:]]/"," ",$guess); 
        $guess = filterwords($guess);

        $correct = 0;
				//changed to 2 on the levenshtein just for compassion's sake :-)  --MightyE
        foreach($answer as $answer1)
            if (levenshtein($guess,$answer1) <= 2) //Allow one letter to be off to compensate for silly spelling mistakes
                $correct = 1;

        if ($correct) {
            output("`n`6\"`@Eidechsen und Kaulquappen!! Du hast es!`6\", tobt er, `n");
            output("`6\"`@Oh na gut. Hier hast du deinen dämlichen Preis.`6\"`n`n");

            // It would be nice to have some more consequences
            $rand = e_rand(1, 10);
            switch ($rand){
                case 1:
                case 2:
                case 3:
                case 4:
                    output("`^Er gibt dir einen Edelstein!");
                    $session[user][gems]++;
					//debuglog("gained 1 gem from the riddle master");
                    break;
                case 5:
                case 6:
                case 7:
                    output("`^Er gibt dir zwei Edelsteine!");
                    $session[user][gems]+=2;
					//debuglog("gained 2 gems from the riddle master");
                    break;
                case 8:
                case 9:
                    output("Er macht ein Hokus Pokus und dreht sich um. Nach diesem Schauspiel fühlst du dich bereit für den Kampf!");
                    output("`n`n`^Du erhältst einen zusätzlichen Waldkampf!");
                    $session[user][turns]++;
                    break;
                case 10:
                    output("Er schaut dir tief in die Augen, dann zieht er dir kräftig eins über die Rübe.  ");
                    if ($session[user][specialty]) {
                        output("Als du wieder zu dir kommst, fühlst du dich ein klein wenig geschickter.`n`#");
                        increment_specialty();
                    }else{
                        output("Das war eine Lektion in Spaß.");
                        output("`n`n`^Du erhältst ein paar Erfahrungspunkte!");
                        $session[user][experience] += $session[user][level] * 10;
                    }
                    break;
            }

        }else{
            /* ************
            This saves the wrong answers in a database table, so I can review them
                from time to time and debug my answer interpretation code.  You
                don't need to run this unless you're doing something like that. */
            // $answer1 = implode (" ", $answer);
            // $sq1 = "INSERT INTO riddledebug (id,answer,guess,date,player) VALUES ($rid,'$riddle[answer]','$guessdebug',NOW(),{$session[user][acctid]})";
            // $result = db_query($sq1);
            /***************/ 

            output("`n`6Der merkwürdige Gnom gackert vor Freude und tanzt um dich herum. Du fühlst dich ziemlich albern dabei, als dieser verrückte Gnom um dich herum tänzelt wie eine Fee,");
            output("`deswegen machst du dich leise aus dem Staub, solange der Gnom abgelenkt ist. Irgendwie fühlst du dich jetzt weniger als ein Held - mit diesem spöttischen Gelächter in den Ohren. ");

            // It would be nice to have some more consequences
            $rand = e_rand(1, 6);
            switch ($rand){
                case 1:
                case 2:
                case 3:
                    output("Nicht viel später stellst du fest, dass dir auch etwas Gold fehlt...");
                    output("`n`n`^Du hast Gold verloren!");
					$gold = e_rand(1, $session[user][level]*10);
					if ($gold > $session['user']['gold'])
						$gold = $session['user']['gold'];
                    $session[user][gold] -= $gold;
					//debuglog("lost $gold gold to the riddlemaster");
                case 4:
                case 5:
                    output("Du glaubst nicht, dass du sofort wieder einem Gegner gegenüber treten kannst.");
                    output("`n`n`^Du verlierst einen Waldkampf!");
                    if ($session[user][turns]>0) $session[user][turns]--;
                    break;
                case 6:
                    output("Was wird wohl ".($session[user][sex]?"Seth":"Violet")." davon halten?");
                    output("`n`n`^Du verlierst einen Charmepunkt!");
                    if ($session[user][charm]>0) $session[user][charm]--;
                    break;
                }    
        }

        $session[user][specialinc]="";
        $session[user][specialmisc]="";
    }
}else if($HTTP_GET_VARS[op]=="no"){
    output("`n`6Du fürchtest dich lächerlich zu machen und lehnst seine Herausforderung ab. Er war sowieso etwas gruselig.");
    output("`n`6Der merkwürdige Gnom kichert hysterisch und verschwindet im Wald.");
    $session[user][specialinc]="";
    $session[user][specialmisc]="";
}
?>
