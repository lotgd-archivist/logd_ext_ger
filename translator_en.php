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
function translate($input){
	return $input;
}

?>