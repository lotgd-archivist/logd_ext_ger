<?php
/*

The Inn Lottery by unknown
Found at sourceforge project page
Modifications and translation by anpera

*/

require_once "common.php";
page_header("Lotterie");

$myname = $session[user][name];
$jackpot = getsetting("jackpot",100);
$winnumber = getsetting("lottonumber",123);
$cost = $session[user][level]*5;

switch($HTTP_GET_VARS[op]){
	case "":
	if($session[user][lottery]<1){
		addnav("Los kaufen","lottery.php?op=buy");
		addnav("Zurück zur Bar","inn.php");
		output("`^Du kannst jeden Tag ein Los kaufen und dein Glück damit versuchen, den Jackpot zu knacken. ");
		output(" Um zu gewinnen, muss die Zahl auf deinem Los mit der Gewinn-Nummer übereinstimmen. Ein Los kostet dich $cost Gold. ");
		output(" Je mehr Leute Lose kaufen, umso höher steigt der Jackpot. Sobald der Jackpot geknackt ist, wird eine neue Gewinn-Nummer festgelegt. ");
		output("`n`n");
		output("`7     `iJackpot: `^$jackpot`7 Gold!`i`n");
		output("`7     `iGewinn-Nummer: `@$winnumber`i`n`n");
	}else{
		addnav("Zurück zur Kneipe","inn.php");
		output("`7Du hast heute schon dein Glück versucht. Bitte warte bis morgen.`n");
	}
	break;
	case "buy":
	if($session[user][gold]<$cost){
		output("`^Ein Los kostet $cost Gold! Soviel hast du nicht dabei.`n");
		addnav("Zurück zur Kneipe","inn.php");
	}else{
		addnav("Zurück zur Kneipe","inn.php");
		$session[user][lottery] = e_rand(100,500);
		$session[user][gold]-=$cost;
		savesetting("jackpot",(string)(getsetting("jackpot",0)+ ($cost)));
		output("`^Die Nummer auf deinem Los ist `b`@".$session[user][lottery]."`b`^...`n");
		if ($session[user][lottery] == $winnumber){
			output("`^`cDU HAST GEWONNEN!!!!! DIE NUMMERN STIMMEN ÜBEREIN! DU GEWINNST `b$jackpot`b GOLD!`n");
			$session[user][gold]+=getsetting("jackpot",0);
			savesetting("jackpot",100);
			savesetting("lottonumber",e_rand(100,500));
			addnews($session[user][name]." `^hat den Jackpot geknackt und $jackpot Gold gewonnen.");
			
			output("<SCRIPT Language='JavaScript'>
count = 40;
speed = 3;
snowfall = true;

function start() {
if (document.all) {
  yMax = document.body.clientHeight;
  xMax = document.body.clientWidth;
  }
else if (document.layers) {
  yMax = window.innerHeight;
  xMax = window.innerWidth;
  }

xachse = new Array();
for(i = 1; i <=40; i++)  {
	x = 10000;
	do
	{	x = Math.round(Math.random() * 10000);  } 
	while(x > xMax-50);
	xachse[i] = x;
};

yachse = new Array();
for(i = 1; i <=40; i++)  {	yachse[i] = - Math.round(Math.random() * 1000); };
show();
movesnow(); }

function movesnow() {
if (snowfall) {
for(i = 1; i <=count; i++)
  { 
  	wind = Math.round(Math.random() * 10);
	if (wind == 2) {
		if (document.all) {  document.all('snow'+i).style.left = xachse[i] + wind; }
		else if (document.layers) { document.layers['snow' + i].left = xachse[i] + wind;       }
	}
	if (wind == 1) {
		if (document.all) {  document.all('snow'+i).style.left = xachse[i] - wind; }
		else if (document.layers) { document.layers['snow' + i].left = xachse[i] - wind;       }
	 }

	if (document.all) {      	if (yachse[i] >= yMax-50+document.body.scrollTop) { yachse[i] = - Math.round(Math.random() * 1000); }; }
	else if (document.layers) {     	if (yachse[i] >= yMax-50+pageYOffset) { yachse[i] = - Math.round(Math.random() * 1000); };     }

	if ( count >= 30 ) { yachse[i] = yachse[i] + speed + 1;	 }
	else  { yachse[i] = yachse[i] + speed;	 };
	if (document.all) {      document.all('snow'+i).style.top = yachse[i]; }
	else if (document.layers) {     document.layers['snow' + i].top = yachse[i];      }
	}

    setTimeout('movesnow()',10);
}
}

function hide(){
for(i = 1; i <=count; i++)
  { 
		if (document.all) {  document.all('snow'+i).style.visibility = 'hidden'; }
		else if (document.layers) { document.layers['snow' + i].visibility = 'hide';       }
		}
		}

function show(){
for(i = 1; i <=count; i++)
  { 
		if (document.all) {  document.all('snow'+i).style.visibility = 'visible'; }
		else if (document.layers) { document.layers['snow' + i].visibility = 'show';       }
		}
		}
</SCRIPT>
<body onload='start()'>
<div id='snow1' style='position:absolute; z-index:3; font-size:15pt; color:#009900; font-family:Times New Roman, Helvetica; visibility:hidden; width:20'>\$</div>
<div id='snow2' style='position:absolute; z-index:3; font-size:15pt; color:#009900; font-family:Times New Roman, Helvetica; visibility:hidden; width:20'>\$</div>
<div id='snow3' style='position:absolute; z-index:3; font-size:15pt; color:#009900; font-family:Times New Roman, Helvetica; visibility:hidden; width:20'>\$</div>
<div id='snow4' style='position:absolute; z-index:3; font-size:15pt; color:#009900; font-family:Times New Roman, Helvetica; visibility:hidden; width:20'>\$</div>
<div id='snow5' style='position:absolute; z-index:3; font-size:15pt; color:#009900; font-family:Times New Roman, Helvetica; visibility:hidden; width:20'>\$</div>
<div id='snow6' style='position:absolute; z-index:3; font-size:15pt; color:#009900; font-family:Times New Roman, Helvetica; visibility:hidden; width:20'>\$</div>
<div id='snow7' style='position:absolute; z-index:3; font-size:15pt; color:#009900; font-family:Times New Roman, Helvetica; visibility:hidden; width:20'>\$</div>
<div id='snow8' style='position:absolute; z-index:3; font-size:15pt; color:#009900; font-family:Times New Roman, Helvetica; visibility:hidden; width:20'>\$</div>
<div id='snow9' style='position:absolute; z-index:3; font-size:15pt; color:#009900; font-family:Times New Roman, Helvetica; visibility:hidden; width:20'>\$</div>
<div id='snow10' style='position:absolute; z-index:3; font-size:15pt; color:#00CC00; font-family:Times New Roman, Helvetica; visibility:hidden; width:20'>\$</div>
<div id='snow11' style='position:absolute; z-index:3; font-size:15pt; color:#00CC00; font-family:Times New Roman, Helvetica; visibility:hidden; width:20'>\$</div>
<div id='snow12' style='position:absolute; z-index:3; font-size:15pt; color:#00CC00; font-family:Times New Roman, Helvetica; visibility:hidden; width:20'>\$</div>
<div id='snow13' style='position:absolute; z-index:3; font-size:15pt; color:#00CC00; font-family:Times New Roman, Helvetica; visibility:hidden; width:20'>\$</div>
<div id='snow14' style='position:absolute; z-index:3; font-size:15pt; color:#00CC00; font-family:Times New Roman, Helvetica; visibility:hidden; width:20'>;)</div>
<div id='snow15' style='position:absolute; z-index:3; font-size:15pt; color:#00CC00; font-family:Times New Roman, Helvetica; visibility:hidden; width:20'>\$</div>
<div id='snow16' style='position:absolute; z-index:3; font-size:15pt; color:#00CC00; font-family:Times New Roman, Helvetica; visibility:hidden; width:20'>\$</div>
<div id='snow17' style='position:absolute; z-index:3; font-size:15pt; color:#00CC00; font-family:Times New Roman, Helvetica; visibility:hidden; width:20'>\$</div>
<div id='snow18' style='position:absolute; z-index:3; font-size:15pt; color:#00CC00; font-family:Times New Roman, Helvetica; visibility:hidden; width:20'>\$</div>
<div id='snow19' style='position:absolute; z-index:3; font-size:15pt; color:#00FF00; font-family:Times New Roman, Helvetica; visibility:hidden; width:20'>\$</div>
<div id='snow20' style='position:absolute; z-index:3; font-size:15pt; color:#00FF00; font-family:Times New Roman, Helvetica; visibility:hidden; width:20'>\$</div>
<div id='snow21' style='position:absolute; z-index:3; font-size:15pt; color:#00FF00; font-family:Times New Roman, Helvetica; visibility:hidden; width:20'>\$</div>
<div id='snow22' style='position:absolute; z-index:3; font-size:15pt; color:#00FF00; font-family:Times New Roman, Helvetica; visibility:hidden; width:20'>\$</div>
<div id='snow23' style='position:absolute; z-index:3; font-size:15pt; color:#00FF00; font-family:Times New Roman, Helvetica; visibility:hidden; width:20'>\$</div>
<div id='snow24' style='position:absolute; z-index:3; font-size:15pt; color:#00FF00; font-family:Times New Roman, Helvetica; visibility:hidden; width:20'>\$</div>
<div id='snow25' style='position:absolute; z-index:3; font-size:15pt; color:#00FF00; font-family:Times New Roman, Helvetica; visibility:hidden; width:20'>\$</div>
<div id='snow26' style='position:absolute; z-index:3; font-size:15pt; color:#00FF00; font-family:Times New Roman, Helvetica; visibility:hidden; width:20'>\$</div>
<div id='snow27' style='position:absolute; z-index:3; font-size:15pt; color:#00FF00; font-family:Times New Roman, Helvetica; visibility:hidden; width:20'>\$</div>
<div id='snow28' style='position:absolute; z-index:3; font-size:15pt; color:#00FF00; font-family:Times New Roman, Helvetica; visibility:hidden; width:20'>\$</div>
<div id='snow29' style='position:absolute; z-index:3; font-size:15pt; color:#006600; font-family:Times New Roman, Helvetica; visibility:hidden; width:20'>\$</div>
<div id='snow30' style='position:absolute; z-index:3; font-size:15pt; color:#006600; font-family:Times New Roman, Helvetica; visibility:hidden; width:20'>\$</div>
<div id='snow31' style='position:absolute; z-index:3; font-size:15pt; color:#006600; font-family:Times New Roman, Helvetica; visibility:hidden; width:20'>\$</div>
<div id='snow32' style='position:absolute; z-index:3; font-size:15pt; color:#006600; font-family:Times New Roman, Helvetica; visibility:hidden; width:20'>\$</div>
<div id='snow33' style='position:absolute; z-index:3; font-size:15pt; color:#009900; font-family:Times New Roman, Helvetica; visibility:hidden; width:20'>\$</div>
<div id='snow34' style='position:absolute; z-index:3; font-size:15pt; color:#009900; font-family:Times New Roman, Helvetica; visibility:hidden; width:20'>\$</div>
<div id='snow35' style='position:absolute; z-index:3; font-size:15pt; color:#00CC00; font-family:Times New Roman, Helvetica; visibility:hidden; width:20'>\$</div>
<div id='snow36' style='position:absolute; z-index:3; font-size:15pt; color:#00CC00; font-family:Times New Roman, Helvetica; visibility:hidden; width:20'>\$</div>
<div id='snow37' style='position:absolute; z-index:3; font-size:15pt; color:#00AA00; font-family:Times New Roman, Helvetica; visibility:hidden; width:20'>\$</div>
<div id='snow38' style='position:absolute; z-index:3; font-size:15pt; color:#00AA00; font-family:Times New Roman, Helvetica; visibility:hidden; width:20'>\$</div>
<div id='snow39' style='position:absolute; z-index:3; font-size:15pt; color:#00BB00; font-family:Times New Roman, Helvetica; visibility:hidden; width:20'>\$</div>
<div id='snow40' style='position:absolute; z-index:3; font-size:15pt; color:#00BB00; font-family:Times New Roman, Helvetica; visibility:hidden; width:20'>\$</div>
",true);

		}else{
			output("`^Sorry, diesmal hast du kein Glück gehabt...`n");
		}
	}
	break;
}
page_footer();
?>     
