<?php

// 21072004

// includes specials for normal use
// idea by Durandil

require_once("common.php");

if ($_GET[ziel]=="") redirect("village.php");

checkday();
page_header("Etwas Besonderes");
addcommentary();
$pfad="special/".($_GET[ziel]).".php";
include("$pfad");

page_footer();
?>
