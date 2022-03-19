<?	
require_once "common.php";
isnewday(2);

//select distinct creaturelevel,max(creaturehealth) as creaturehealth,max(creatureattack) as creatureattack,max(creaturedefense) as creaturedefense,max(creatureexp) as creatureexp,max(creaturegold) as creaturegold from creatures where creaturelevel<17 group by creaturelevel;
$creaturestattable="
+---------------+----------------+----------------+-----------------+-------------+--------------+
| creaturelevel | creaturehealth | creatureattack | creaturedefense | creatureexp | creaturegold |
+---------------+----------------+----------------+-----------------+-------------+--------------+
|             1 |             10 |              1 |               1 |          14 |           36 |
|             2 |             21 |              3 |               3 |          24 |           97 |
|             3 |             32 |              5 |               4 |          34 |          148 |
|             4 |             43 |              7 |               6 |          45 |          162 |
|             5 |             53 |              9 |               7 |          55 |          198 |
|             6 |             64 |             11 |               8 |          66 |          234 |
|             7 |             74 |             13 |              10 |          77 |          268 |
|             8 |             84 |             15 |              11 |          89 |          302 |
|             9 |             94 |             17 |              13 |         101 |          336 |
|            10 |            105 |             19 |              14 |         114 |          369 |
|            11 |            115 |             21 |              15 |         127 |          402 |
|            12 |            125 |             23 |              17 |         141 |          435 |
|            13 |            135 |             25 |              18 |         156 |          467 |
|            14 |            145 |             27 |              20 |         172 |          499 |
|            15 |            155 |             29 |              21 |         189 |          531 |
|            16 |            166 |             31 |              22 |         207 |          563 |
+---------------+----------------+----------------+-----------------+-------------+--------------+
";
$creaturestats=Array();
$creaturestattable=split("\n",$creaturestattable);
$x=0;
while (list($key,$val)=each($creaturestattable)){
	if (strpos($val,"|")!==false){
		//echo("$val`n");
		$x++;
		$a = split("\\|",$val);
		if ($x==1){
			$stats=array();
			while (list($key1,$val1)=each($a)){
				if (trim($val1)>"") {
					$stats[$key1]=trim($val1);
					//output($val1." is col $key1`n");
				}
			}
		}else{
			reset($stats);
			while (list($key1,$val1)=each($stats)){
				$creaturestats[(int)$a[1]][$val1]=trim($a[$key1]);
				//output ("[".(int)$a[1]."][$val1]=".trim($a[$key1])."`n");
			}
		}
	}
}

page_header("Creature Editor");

if ($session[user][superuser] >= 2){
	addnav("G?Zurück zur Grotte","superuser.php");
	addnav("W?Zurück zum Weltlichen","village.php");
	if ($HTTP_POST_VARS[save]<>""){
		if (!isset($HTTP_POST_VARS['location'])) $HTTP_POST_VARS['location']=0;
		if ($HTTP_POST_VARS[id]!=""){
			$sql="UPDATE creatures SET ";
			//unset($HTTP_POST_VARS[save]);
			while (list($key,$val)=each($HTTP_POST_VARS)){
				if (substr($key,0,8)=="creature") $sql.="$key = \"$val\", ";
			}
			reset($creaturestats[(int)$HTTP_POST_VARS[creaturelevel]]);
			while (list($key,$val)=each($creaturestats[$HTTP_POST_VARS[creaturelevel]])){
				if ( $key!="creaturelevel" && substr($key,0,8)=="creature"){
					$sql.="$key = \"".addslashes($val)."\", ";
				}
			}
			$sql.=" location=\"".(int)($_POST['location'])."\", ";
			//$sql = substr($sql,0,strlen($sql)-2);
			$sql.= " createdby=\"".addslashes($session[user][login])."\" ";
			$sql.= " WHERE creatureid='$HTTP_POST_VARS[id]'";
			//output($sql);
			db_query($sql) or output("`\$".db_error(LINK)."`0`n`#$sql`0`n");
			output(db_affected_rows()." ".(db_affected_rows()==1?"Eintrag":"Einträge")." geändert.");
		}else{
			$cols = array();
			$vals = array();
			
			while (list($key,$val)=each($HTTP_POST_VARS)){
				if (substr($key,0,8)=="creature" || $key=="location") {
					array_push($cols,$key);
					array_push($vals,$val);
					//$sql.="$key = \"$val\", ";
				}
			}
			reset($creaturestats[(int)$HTTP_POST_VARS[creaturelevel]]);
			while (list($key,$val)=each($creaturestats[$HTTP_POST_VARS[creaturelevel]])){
				if ($key!="creaturelevel"){
					//$sql.="$key = \"".addslashes($val)."\", ";
					array_push($cols,$key);
					array_push($vals,$val);
				}
			}
			$sql="INSERT INTO creatures (".join(",",$cols).",createdby) VALUES (\"".join("\",\"",$vals)."\",\"".addslashes($session['user']['login'])."\")";
			//echo $sql;
			db_query($sql);
		}
	}
	if ($HTTP_GET_VARS[op]=="del"){
		$sql = "DELETE FROM creatures WHERE creatureid = \"$HTTP_GET_VARS[id]\"";
		db_query($sql);
		if (db_affected_rows()>0){
			output("Kreatur gelöscht`n`n");
		}else{
			output("Kreatur nicht gelöscht: ".db_error(LINK));
		}
		$HTTP_GET_VARS[op]="";
	}
	if ($HTTP_GET_VARS[op]==""){
		$sql = "SELECT * FROM creatures ORDER BY creaturelevel,creaturename";
		$result = db_query($sql) or die(db_error(LINK));
		addnav("Eine Kreatur hinzufügen","creatures.php?op=add");
		output("<table><tr><td>Ops</td><td>Kreaturname</td><td>Level</td><td>Waffe</td><td>Nachricht beim Tod</td><td>Autor</td></tr>",true);
		addnav("","creatures.php");
		for ($i=0;$i<db_num_rows($result);$i++){
			$row = db_fetch_assoc($result);
			if ($row[creaturelevel]==17 || $row[creaturelevel]==18){
				output("<tr><td> [Edit|Del] </td><td>",true);
			}else{
				output("<tr><td> [<a href='creatures.php?op=edit&id=$row[creatureid]'>Edit</a>|".
				"<a href='creatures.php?op=del&id=$row[creatureid]' onClick='return confirm(\"Bist du dir sicher, dass du diese Kreatur löschen willst?\");'>Del</a>] </td><td>",true);
				addnav("","creatures.php?op=edit&id=$row[creatureid]");
				addnav("","creatures.php?op=del&id=$row[creatureid]");
			}
			output($row[creaturename]);
			output("</td><td>",true);
			output($row[creaturelevel]);
			output("</td><td>",true);
			output($row[creatureweapon]);
			output("</td><td>",true);
			output($row[creaturelose]);
			output("</td><td>",true);
			output($row[createdby]);
			output("</td></tr>",true);
		}
		output("</table>",true);
	}else{
		if ($HTTP_GET_VARS[op]=="edit" || $HTTP_GET_VARS[op]=="add"){
			if ($HTTP_GET_VARS[op]=="edit"){
				$sql = "SELECT * FROM creatures WHERE creatureid=$HTTP_GET_VARS[id]";
				$result = db_query($sql) or die(db_error(LINK));
				if (db_num_rows($result)<>1){
					output("`4Fehler`0, diese Kreatur wurde nicht gefunden!");
				}else{
					$row = db_fetch_assoc($result);
				}
			}
			output("<form action='creatures.php' method='POST'>",true);
			output("<input name='id' value=\"".HTMLEntities($HTTP_GET_VARS[id])."\" type='hidden'>",true);
			output("<table border='0' cellpadding='2' cellspacing='0'>",true);
			output("<tr><td>Kreaturname:</td><td><input name='creaturename' maxlength='50' value=\"".HTMLEntities($row[creaturename])."\"></td></tr>",true);
			output("<tr><td>Waffe: </td><td><input name='creatureweapon' maxlength='50' value=\"".HTMLEntities($row[creatureweapon])."\"></td></tr>",true);
			output("<tr><td colspan='2'>Nachricht beim Tod: <br><input name='creaturelose' size='65' maxlength='120' value=\"".HTMLEntities($row[creaturelose])."\"></td></tr>",true);
			output("<tr><td>Level: </td><td><select name='creaturelevel'>",true);
			for ($i=1;$i<=16;$i++){
				output("<option value='$i'".($row[creaturelevel]==$i?" selected":"").">$i</option>\n",true);
			}
			output("</select></td></tr>",true);
			output("<tr><td>Kreatur ist auch auf dem Friedhof</td><td><input type='radio' name='location' value='1'".($row['location']==1?" checked":"").">Ja <input type='radio' name='location' value='0'".($row['location']==0?" checked":"").">Nein </td></tr>",true);
			output("<tr><td colspan='2'><input type='hidden' name='save' value='Save'><input type='submit' class='button' name='submit' value='Speichern'></td></tr>",true);
			output("</table>",true);
			output("</form>",true);
			addnav("","creatures.php");
		}else{
		
		}
		addnav("Zurück zum Monster-Editor","creatures.php");
	}
}else{
	output("Weil du versucht hast die Götter zu betrügen, wurdest du niedergeschmettert!");
	addnews("`&".$session[user][name]." wurde für den Versuch, die Götter zu betrügen, niedergeschmettert (hat versucht die Superuser-Seiten zu hacken).");
	$session[user][hitpoints]=0;
}
page_footer();
?>