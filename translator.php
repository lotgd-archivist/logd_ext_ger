<?
/* Format for translator.php
 Each translatable page has its own entry below, locate the page where the text you want
 to translate is, and populate the $replace array with "From"=>"To" translation combinations.
 Only one translation per output() or addnav() call will occur, so if you have multiple
 translations that have to occur on the same call, place them in to their own array
 as an element in the $replace array.  This entire sub array will be replaced, and if any
 matches are found, further replacements will not be made.
 
 If you are replacing a single output() or addnav() call that uses variables in the middle,
 you will have to follow the above stated process for each piece of text between the variables.
 Example, 
 output("MightyE rules`nOh yes he does`n");
 output("MightyE is Awesome $i times a day, and Superawesome $j times a day.");
 you will need a replace array like this:
 $replace = array(
   "MightyE rules`nOh yes he does`n"=>"MightyE rulezors`nOh my yes`n"
   ,array(
     "MightyE is Awesome"=>"MightyE is Awesomezor"
     ,"times a day, and Superawesome"=>"timez a dayzor, and Superawesomezor"
     ,"times a day."=>"timez a dayzor."
     )
 );
 
*/
//output(output_array($session['user']['prefs']));

$language = $session['user']['prefs']['language'];
if ($language=="") $language=$_COOKIE['language'];
if ($language=="") $language=getsetting("defaultlanguage","en");

if (file_exists("translator_".$language.".php")){
	require_once "translator_".$language.".php";
}else{
	require_once "translator_en.php";
}

function replacer($input,$replace){
	$originput = $input;
	if (!is_array($replace)) return $input;
	while (list($s,$r)=each($replace)){
		if (is_array($r)){
			$input = str_replace(array_keys($r),array_values($r),$input);
		}else{
			$input = str_replace($s,$r,$input);
		}
		if ($originput!=$input) return $input;
	}
	return $input;
}
?>