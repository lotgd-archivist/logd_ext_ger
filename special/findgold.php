<?php
$gold = e_rand($session[user][level]*10,$session[user][level]*50);
output("`^Das Gl�ck l�chelt dich an. Du findest $gold Gold!`0");
$session[user][gold]+=$gold;
//debuglog("found $gold gold in the forest");
?>
