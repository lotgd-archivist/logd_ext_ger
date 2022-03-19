<?php
require_once "common.php";
isnewday(2);

page_header("Rüstungseditor");
$armorlevel = (int)$_GET[level];
addnav("G?Zurück zur Grotte","superuser.php");
addnav("W?Zurück zum Weltlichen","village.php");
addnav("Rüstungseditor Home","armoreditor.php?level=$armorlevel");

addnav("Rüstung hinzufügen","armoreditor.php?op=add&level=$armorlevel");
$values = array(1=>48,225,585,990,1575,2250,2790,3420,4230,5040,5850,6840,8010,9000,10350);
	output("`&<h3>Rüstungen für $armorlevel Drachenkills</h3>`0",true);

$armorarray=array(
	"Rüstungen,title",
	"armorid"=>"Rüstungs ID,hidden",
	"armorname"=>"Rüstungsname Name",
	"defense"=>"Verteidigung,enum,1,1,2,2,3,3,4,4,5,5,6,6,7,7,8,8,9,9,10,10,11,11,12,12,13,13,14,14,15,15",
	"Rüstungen,title");
if($_GET[op]=="edit" || $_GET[op]=="add"){
	if ($_GET[op]=="edit"){
		$sql = "SELECT * FROM armor WHERE armorid='$_GET[id]'";
		$result = db_query($sql);
		$row = db_fetch_assoc($result);
	}else{
		$sql = "SELECT max(defense+1) AS defense FROM armor WHERE level=$armorlevel";
		$result = db_query($sql);
		$row = db_fetch_assoc($result);
	}
	output("<form action='armoreditor.php?op=save&level=$armorlevel' method='POST'>",true);
	addnav("","armoreditor.php?op=save&level=$armorlevel");
	showform($armorarray,$row);
	output("</form>",true);
}else if($_GET[op]=="del"){
	$sql = "DELETE FROM armor WHERE armorid='$_GET[id]'";
	db_query($sql);
	//output($sql);
	redirect("armoreditor.php?level=$armorlevel");
}else if($_GET[op]=="save"){
	if ((int)$_POST[armorid]>0){
		$sql = "UPDATE armor SET armorname=\"$_POST[armorname]\",defense=\"$_POST[defense]\",value=".$values[$_POST[defense]]." WHERE armorid='$_POST[armorid]'";
	}else{
		$sql = "INSERT INTO armor (level,defense,armorname,value) VALUES ($armorlevel,\"$_POST[defense]\",\"$_POST[armorname]\",".$values[$_POST[defense]].")";
	}
	db_query($sql);
	//output($sql);
	redirect("armoreditor.php?level=$armorlevel");
}else if ($_GET[op]==""){
	$sql = "SELECT max(level+1) AS level FROM armor";
	$res = db_query($sql);
	$row = db_fetch_assoc($res);
	$max = $row['level'];
	for ($i=0;$i<=$max;$i++){
		addnav("Rüstungen für $i DKs","armoreditor.php?level=$i");
	}
	output("<table>",true);
	$sql = "SELECT * FROM armor WHERE level=".(int)$_GET[level]." ORDER BY defense";
	$result= db_query($sql) or die(db_error(LINK));
	for ($i=0;$i<db_num_rows($result);$i++){
		$row = db_fetch_assoc($result);
		if ($i==0){
			output("<tr>",true);
			output("<td>Ops</td>",true);
			while (list($key,$val)=each($row)){
				output("<td>$key</td>",true);
			}
			output("</tr>",true);
			reset($row);
		}
		output("<tr>",true);
		output("<td>[<a href='armoreditor.php?op=edit&id=$row[armorid]&level=$armorlevel'>Edit</a>|<a href='armoreditor.php?op=del&id=$row[armorid]&level=$armorlevel' onClick='return confirm(\"Diese Rüstung wirklich löschen?\");'>Löschen</a>]</td>",true);
		addnav("","armoreditor.php?op=edit&id=$row[armorid]&level=$armorlevel");
		addnav("","armoreditor.php?op=del&id=$row[armorid]&level=$armorlevel");
		while (list($key,$val)=each($row)){
			output("<td>$val</td>",true);
		}
		output("</tr>",true);
	}
	output("</table>",true);
}
page_footer();
?>
