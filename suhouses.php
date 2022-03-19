<?php
/*
* Version:	25.04.2004
* Author:	anpera
* Email:		logd@anpera.de
* 
* Purpose:	Admin tool for houses
*		
* BETA !!
*
* Ok, lets do the code...
*/

	
require_once("common.php");

page_header("Hausmeister");

function disp_status(){
	output("<ul>",true);
	output("`n`@Häuserstatus:`n`^`b0:`b `6im Bau`^`n`b1:`b `!bewohnt`^`n`b2:`b `^zum Verkauf`^`n");
	output("`b3:`b `4Verlassen`^`n`b4:`b `\$Bauruine`0");
	output("</ul>",true);
}

if ($_GET[op]=="drin"){
	addnav("Schlüssel hinzufügen","suhouses.php?op=keys&hid=$_GET[id]");
	addnav("Daten ändern","suhouses.php?op=data&id=$_GET[id]");
	addnav("Haus zerstören","suhouses.php?op=destroy&id=$_GET[id]"); // bad idea
	addnav("Kommentare","suhouses.php?op=comment&id=$_GET[id]");
	addnav("Hausmeister","suhouses.php");
	$sql="SELECT * FROM houses WHERE houseid=$_GET[id]";
	$result = db_query($sql) or die(db_error(LINK));
	$row = db_fetch_assoc($result);
	output("`n`@Hausnummer: `^`b$row[houseid]`b");
	output("`n`@Name: `^`b$row[housename]`b");
	output("`n`@Beschreibung: `^`b$row[description]`b");
	output("`n`@Gold: `^`b$row[gold]`b");
	output("`n`@Edelsteine: `^`b$row[gems]`b");
	output("`n`@Status: `^`b$row[status]`b (");
	if ($row[status]==0) output("`6im Bau`0");
	if ($row[status]==1) output("`!bewohnt`0");
	if ($row[status]==2) output("`^zum Verkauf`0");
	if ($row[status]==3) output("`4Verlassen`0");
	if ($row[status]==4) output("`\$Bauruine`0");
	$sql = "SELECT name FROM accounts WHERE acctid=$row[owner]";
	$result2 = db_query($sql);
	$row2  = db_fetch_assoc($result2);
	output("`^)`n`@Besitzer: `^`b$row[owner]`b ($row2[name]`^)");
	output("`n`n`@Schlüssel: `^`n");
	output("<table border='0' cellpadding='3' cellspacing='0'><tr><td>Nr.</td><td>Owner ID (Name)</td><td>Hausnr</td><td>Nr. (DB)</td><td>gebraucht?</td><td>Ops</td></tr>",true);
$sql = "SELECT items.*,accounts.acctid, accounts.name FROM items LEFT JOIN accounts ON accounts.acctid=items.owner WHERE items.value1=$row[houseid] AND items.class='Schlüssel' ORDER BY items.value2 ASC,items.id ASC";
	$result = db_query($sql) or die(db_error(LINK));
	for ($i=1;$i<=db_num_rows($result);$i++){
		$item = db_fetch_assoc($result);
		output("<tr><td>`b$i`b</td><td>".($item['acctid']?"$item[acctid] ($item[name])":"0 (`4Verloren`0)")."</td><td>$item[value1]</td><td>$item[value2]</td><td>$item[hvalue]</td><td>",true);
		if ($row2[name]==""){
			output("<a href='suhouses.php?op=keys&subop=change&hid=$_GET[id]&id2=$i&owner=$row[owner]'>Reset</a> | ",true);
			addnav("","suhouses.php?op=keys&subop=change&hid=$_GET[id]&id2=$i&owner=$row[owner]");
		}
		output("<a href='suhouses.php?op=keys&subop=edit&id=$item[id]&hid=$_GET[id]'>Edit</a> | <a href='suhouses.php?op=keys&subop=delete&id=$item[id]&hid=$_GET[id]' onClick=\"return confirm('Diesen Schlüssel wirklich löschen?');\">Löschen</a>",true);
		addnav("","suhouses.php?op=keys&subop=edit&id=$item[id]&hid=$_GET[id]");
		addnav("","suhouses.php?op=keys&subop=delete&id=$item[id]&hid=$_GET[id]");
		output("</td></tr>",true);
	}
	output("</table>`n",true);
}else if ($_GET[op]=="comment"){
	if ($_GET[subop]=="delete"){
		$sql = "DELETE FROM commentary WHERE commentid='$_GET[commentid]'";
		db_query($sql);
	}
	viewcommentary("house-$_GET[id]","X",100);
	addnav("Zurück zu Haus $_GET[id]","suhouses.php?op=drin&id=$_GET[id]");
}else if ($_GET[op]=="info"){
	$sql="SELECT acctid,name,house,housekey FROM accounts WHERE house ORDER BY house ASC";
	output("<table cellpadding=2 align='center'><tr><td>`bacctid`b</td><td>`bName`b</td><td>`bhouse`b</td><td>`bhousekey`b</td></tr>",true);
	$result = db_query($sql) or die(db_error(LINK));
	if (db_num_rows($result)==0){
		output("<tr><td colspan=4 align='center'>`&`iEs gibt keine Häuser`i`0</td></tr>",true);
	}else{
		for ($i=0;$i<db_num_rows($result);$i++){
			$row = db_fetch_assoc($result);
			output("<tr><td align='center'>$row[acctid]</td><td>$row[name]</td><td>$row[house]</td><td>$row[housekey]</td></tr>",true);
		}
	}
	output("</table>",true);
	addnav("Hausmeister","suhouses.php");
}else if ($_GET[op]=="destroy"){ // bad idea! write this code on your own risk! .. ok, i wrote it
	if ($_GET[subop]=="confirmed"){
		$sql="DELETE FROM houses WHERE houseid=$_GET[id]";
		db_query($sql);
		$sql="DELETE FROM items WHERE class='Schlüssel' AND value1=$_GET[id]";
		db_query($sql);
		$sql="UPDATE accounts SET house=0,housekey=0 WHERE house=$_GET[id]";
		db_query($sql);
		output("`@Haus gelöscht");
	}else{
		output("`b`\$Haus Nummer $_GET[id] und alle Schlüssel wirklich löschen?`b");
		addnav("LÖSCHEN","suhouses.php?op=destroy&subop=confirmed&id=$_GET[id]");
	}
	addnav("Hausmeister","suhouses.php");
}else if ($_GET[op]=="newhouse"){
	addnav("Hausmeister","suhouses.php");
	if ($_GET[subop]=="save"){ // save new house
		if ($_POST[auto]=="true"){ // check given data
			$sql = "SELECT house,housekey FROM accounts WHERE acctid=$_POST[owner]";
			$result = db_query($sql) or die(db_error(LINK));
			$row = db_fetch_assoc($result);
			if ($row[house]>0 && $_POST[owner]){
				output("`\$Fehler: Zielperson besitzt bereits ein anderes Haus oder existiert nicht.");
			}else if (!$_POST[housename]){
				output("`\$Fehler: Du musst einen Namen für das Haus eingeben.");
			}else if ((int)$_POST[owner]<1 && (int)$_POST[status]<=1){
				output("`\$Fehler: Für diesen Status ist ein Besitzer zwingend erforderlich.");
			}else{
				if ((int)$_POST[status]>1 && (int)$_POST[owner]>0){
					output("`^Warnung: Diesem Status darf kein Besitzer zugeordnet werden. Besitzer auf 0 gesetzt.`n");
					$_POST[owner]="0";
				}
				output("`@Neues Haus erstellt.`n");
				$sql = "INSERT INTO houses (owner,status,gold,gems,housename,description) VALUES ($_POST[owner],$_POST[status],$_POST[gold],$_POST[gems],'$_POST[housename]','$_POST[description]')";
				db_query($sql);
				$sql = "SELECT houseid FROM houses WHERE owner=$_POST[owner] ORDER BY houseid DESC LIMIT 1";
				$result2 = db_query($sql) or die(db_error(LINK));
				$row2 = db_fetch_assoc($result2);
				if ($_POST[status]=="1" || $_POST[status]=="2" || $_POST[status]=="3"){
					for ($i=1;$i<10;$i++){
						$sql = "INSERT INTO items (name,owner,class,value1,value2,description) VALUES ('Hausschlüssel',".($_POST[status]=="1"?"$_POST[owner]":"0").",'Schlüssel',$row2[houseid],$i,'Schlüssel für Haus Nummer $row2[houseid]')";
						db_query($sql);
					}
					output("`@Schlüssel in Datenbank eingetragen`n");
				}
				if ($_POST[status]=="0" || $_POST[status]=="1"){
					$sql="UPDATE accounts SET house=$row2[houseid],housekey=".($_POST[status]=="1"?"$row2[houseid]":"0")." WHERE acctid=$_POST[owner]";
					output("`@Userdatenbank angepasst`n");
					db_query($sql);
				}
			}
		}else{
			output("`@Neues Haus erstellt.");
			$sql = "INSERT INTO houses (owner,status,gold,gems,housename,description) VALUES ($_POST[owner],$_POST[status],$_POST[gold],$_POST[gems],'$_POST[housename]','$_POST[description]')";
			db_query($sql);
		}
	}else{
		output("`@Neues Haus anlegen:`n`n");
		output("`0<form action=\"suhouses.php?op=newhouse&subop=save\" method='POST'>",true);
		output("<table><tr><td>Name </td><td><input name='housename' maxlength='25'></td></tr>",true);
		output("<tr><td>Gold </td><td><input type='text' name='gold' value='0'> </td></tr>",true);
		output("<tr><td>Edelsteine </td><td><input type='text' name='gems' value='0'></td></tr>",true);
		output("<tr><td>Beschreibung </td><td><input type='text' name='description' maxlength='250'></td></tr>",true);
		output("<tr><td>Status </td><td><input type='text' name='status' value='2'></td></tr>",true);
		output("<tr><td>`4Besitzer (ID)`0 </td><td><input type='text' name='owner' value='0'> `4(VORSICHT!)`0</td></tr>",true);
		output("<tr><td>`4Sicherer Modus`0 </td><td><input type='checkbox' name='auto' checked='true' value='true'> `4(VORSICHT!)`0</td></tr></table>`n",true);
		output("<input type='submit' class='button' value='Speichern'></form>",true);
		output("`0`n`nIm unsicheren Modus Haus auch im User-Editor beim Besitzer eintragen! Status berücksichtigen! Schlüsselverwaltung!");
		disp_status();
		addnav("","suhouses.php?op=newhouse&subop=save");
	}
}else if ($_GET[op]=="keys"){
	addnav("Hausmeister","suhouses.php");
	addnav("Zurück zu Haus $_GET[hid]","suhouses.php?op=drin&id=$_GET[hid]");
	if ($_GET[subop]=="change"){ // reset key owner
		$sql="UPDATE items SET owner=$_GET[owner] WHERE value1=$_GET[hid] AND class='Schlüssel' AND value2=$_GET[id2]";
		db_query($sql);
		output("`@Schlüssel `^$_GET[id2]`@ für Haus Nummer `^$_GET[hid]`@ zurückgesetzt.");
	}else if ($_GET[subop]=="edit"){ // enter new values for key
		$sql = "SELECT * FROM items WHERE id=$_GET[id]";
		$result = db_query($sql) or die(db_error(LINK));
		$item = db_fetch_assoc($result);
		output("`@Schlüssel Nr. $item[value2] (item-ID $_GET[id]) für Haus $_GET[hid] bearbeiten:`n`n");
		output("`0<form action=\"suhouses.php?op=keys&subop=edit2&id=$_GET[id]&hid=$_GET[hid]\" method='POST'>",true);
		output("<table>",true);
		output("<tr><td>Besitzer (owner: acctid) </td><td><input type='text' name='owner' value='$item[owner]'></td></tr>",true);
		// output("<tr><td>Für Haus Nr. (value1) </td><td><input type='text' name='value1' value='$item[value1]'></td></tr>",true); // to change house delete the key and add a new key in other house
		output("<tr><td>In Gebrauch? (hvalue: 0 oder Hausnr.) </td><td><input type='text' name='hvalue' value='$item[hvalue]'></td></tr>",true);
		output("<tr><td>`4Schlüssel-ID (value2: Laufende Nr.)`0 </td><td><input type='text' name='value2' value='$item[value2]'> `4(VORSICHT!)`0</td></tr>",true);
		output("</table>`n",true);
		output("<input type='submit' class='button' value='Speichern'></form>",true);
		output("`0`n`nSchlüssel-ID darf nicht doppelt vergeben werden.`nSchlüssel ohne Besitzer werden als verloren behandelt.");
		addnav("","suhouses.php?op=keys&subop=edit2&id=$_GET[id]&hid=$_GET[hid]");
	}else if ($_GET[subop]=="edit2"){ // save new values into DB
		$sql = "SELECT * FROM items WHERE id=$_GET[id]";
		$result = db_query($sql) or die(db_error(LINK));
		$item = db_fetch_assoc($result);
		$action=false;
		if ((int)$_POST[value2]!=(int)$item[value2]){
			$sql = "SELECT id FROM items WHERE class='Schlüssel' AND value1=$_GET[hid] AND value2=$_POST[value2]";
			$result = db_query($sql) or die(db_error(LINK));
			$row = db_fetch_assoc($result);
			if ($row[id]){
				output("`\$Fehler: Diese ID ist bereits vergeben.");
			}else{
				$action=true;
			}
		}
		if ((int)$item[owner]!=(int)$_POST[owner]){
			$action=false;
			$sql = "SELECT acctid FROM accounts WHERE acctid=$_POST[owner]";
			$result = db_query($sql) or die(db_error(LINK));
			$row = db_fetch_assoc($result);
			if (!$row[acctid]){
				output("`\$Fehler: Der User existiert nicht.");
			}else{
				$action=true;
			}
		}
		if ($action){
			$sql = "UPDATE items SET owner=$_POST[owner],value2=$_POST[value2],hvalue=$_POST[hvalue] WHERE id=$_GET[id]";
			db_query($sql);
			output("`@Änderungen übernommen.");
		}
	}else if ($_GET[subop]=="savenew"){ // save new key
		if ($_POST[value2]){
			$sql = "SELECT value1,value2 FROM items WHERE class='Schlüssel' AND value2=$_POST[value2] AND value1=$_GET[hid]";
			$result = db_query($sql) or die(db_error(LINK));
			$item = db_fetch_assoc($result);
			$sql="SELECT acctid FROM accounts WHERE acctid=$_POST[owner]";
			$result = db_query($sql) or die(db_error(LINK));
			$row = db_fetch_assoc($result);
		}
		if (!$_POST[value2]){
			output("`\$Fehler: Du musst eine Schlüssel-ID angeben");
		}else if ((int)$item[value2]==(int)$_POST[value2]){
			output("`\$Fehler: Diese ID ist bereits vergeben.");
		}else if (!$row[acctid]){
			output("`\$Fehler: Der User existiert nicht.");
		}else{
			$sql = "INSERT INTO items (name,owner,class,value1,value2,hvalue,description) VALUES ('Hausschlüssel',$_POST[owner],'Schlüssel',$_GET[hid],$_POST[value2],$_POST[hvalue],'Schlüssel für Haus Nummer $_GET[hid]')";
			db_query($sql);
			output("`@Schlüssel eingetragen.");
		}
	}else if ($_GET[subop]=="delete"){ // delete key
		output("`@Schlüssel gelöscht.");
		$sql = "DELETE FROM items WHERE id=$_GET[id]";
		db_query($sql);
	}else{ // enter new key
		output("`@Neuen Schlüssel für Haus $_GET[hid] anlegen:`n`n");
		output("`0<form action=\"suhouses.php?op=keys&subop=savenew&hid=$_GET[hid]\" method='POST'>",true);
		output("<table>",true);
		output("<tr><td>Besitzer (owner: acctid) </td><td><input type='text' name='owner' value='0'></td></tr>",true);
		output("<tr><td>In Gebrauch? (hvalue: 0 oder Hausnr.) </td><td><input type='text' name='hvalue' value='0'></td></tr>",true);
		output("<tr><td>`4Schlüssel-ID (value2: Laufende Nr.)`0 </td><td><input type='text' name='value2'> `4(VORSICHT!)`0</td></tr>",true);
		output("</table>`n",true);
		output("<input type='submit' class='button' value='Speichern'></form>",true);
		output("`0`n`nSchlüssel-ID darf nicht doppelt vergeben werden.`nSchlüssel ohne Besitzer werden als verloren behandelt.");
		addnav("","suhouses.php?op=keys&subop=savenew&hid=$_GET[hid]");
	}
}else if ($_GET[op]=="data"){
	addnav("Hausmeister","suhouses.php");
	addnav("Zurück zu Haus $_GET[id]","suhouses.php?op=drin&id=$_GET[id]");
	if ($_GET[subop]=="save"){ // save values
		$action=false;
		if ($_POST[auto]=="true"){ // check given data
			$sql = "SELECT * FROM houses WHERE houseid=$_GET[id]";
			$result = db_query($sql) or die(db_error(LINK));
			$row = db_fetch_assoc($result);
			$sql = "SELECT house,housekey FROM accounts WHERE acctid=$_POST[owner]";
			$result2 = db_query($sql) or die(db_error(LINK));
			$row2 = db_fetch_assoc($result2);
			if ($row2[house]!=$_GET[id] && $row2[house]>0){
				output("`\$Fehler: Zielperson besitzt bereits ein anderes Haus oder existiert nicht. Datenbank nicht aktualisiert.");
			}else if ($row[status]!=$_POST[status] && $row[owner]!=$_POST[owner]){
				output("`\$Fehler: Status und Besitzer können im sicheren Modus nicht gleichzeitig geändert werden. Datenbank nicht aktualisiert.");
			}else{
				if ($row[owner]!=$_POST[owner] && ($_POST[status]=="3" || $_POST[status]=="4")){
					$_POST[status]="0";
					output("`^Warnung: Status dieses Hauses lässt keinen Besitzer zu. Status auf 0 (im Bau) gesetzt.`n");
				}
				if ($row[status]!=$_POST[status] && (int)$_POST[status]>2 && (int)$_POST[owner]>0){
					$_POST[owner]="0";
					output("`^Warnung: Dieser Statuswechsel lässt keinen Besitzer zu. Besitzer auf 0 gesetzt.`n");
				}
				if ($row[status]!=$_POST[status] && $row[owner]==0 && (int)$_POST[status]<3){
					output("`^Warnung: Dieser Status erfordert einen Besitzer! Bitte unbedingt einen Besitzer zuordnen!`n");
				}
				$action=true;
				if ((int)$_POST[status]!=(int)$row[status]){
					if ($_POST[status]=="0" || $_POST[status]=="4"){
						$sql="DELETE FROM items WHERE class='Schlüssel' AND value1=$_GET[id]";
						db_query($sql);
						$house=0;
						if ($_POST[status]=="0") $house=$_GET[id];
						$housekey=0;
						output("`@Schlüssel aus Datenbank gelöscht`n");
					}
					if ($_POST[status]=="3" && $row[status]!=4 && $row[status]!=0){
						$house=0;
						$housekey=0;
						$sql="UPDATE items SET owner=0 WHERE class='Schlüssel' AND owner=$row[owner] AND value1=$_GET[id]";
						db_query($sql);
						output("`@Nicht vergebene Schlüssel zurückgesetzt`n");
					}else if ($_POST[status]=="3"){
						$house=0;
						$housekey=0;
						for ($i=1;$i<10;$i++){
							$sql = "INSERT INTO items (name,owner,class,value1,value2,description) VALUES ('Hausschlüssel',0,'Schlüssel',$_GET[id],$i,'Schlüssel für Haus Nummer $_GET[id]')";
							db_query($sql);
						}
						output("`@Schlüssel in Datenbank eingetragen`n");
					}
					if ($_POST[status]=="1" && ($row[status]==0 || $row[status]==4)){
						for ($i=1;$i<10;$i++){
							$sql = "INSERT INTO items (name,owner,class,value1,value2,description) VALUES ('Hausschlüssel',$_POST[owner],'Schlüssel',$_GET[id],$i,'Schlüssel für Haus Nummer $_GET[id]')";
							db_query($sql);
						}
						$house=$_GET[id];
						$housekey=$_GET[id];
						output("`@Schlüssel in Datenbank eingetragen`n");
					}elseif ($_POST[status]=="1"){
						$sql="UPDATE items SET owner=$_POST[owner] WHERE class='Schlüssel' AND owner=0 AND value1=$_GET[id]";
						db_query($sql);
						$house=$_GET[id];
						$housekey=$_GET[id];
					}
					if ($_POST[status]=="2" && ($row[status]==0 || $row[status]==4)){
						for ($i=1;$i<10;$i++){
							$sql = "INSERT INTO items (name,owner,class,value1,value2,description) VALUES ('Hausschlüssel',0,'Schlüssel',$_GET[id],$i,'Schlüssel für Haus Nummer $_GET[id]')";
							db_query($sql);
						}
						$house=$_GET[id];
						$housekey=$_GET[id];
						output("`@Schlüssel in Datenbank eingetragen`n");
					}elseif ($_POST[status]=="2"){
						$sql="UPDATE items SET owner=0 WHERE class='Schlüssel' AND value1=$_GET[id]";
						db_query($sql);
						$house=$_GET[id];
						$housekey=0;
					}
					$sql="UPDATE accounts SET house=$house,housekey=$housekey WHERE acctid=$row[owner]";
					db_query($sql);
				}else{
					$sql="UPDATE accounts SET house=0,housekey=0 WHERE acctid=$row[owner]";
					db_query($sql);
					if ($_POST[status]=="1"){
						$housekey=$_GET[id];
					}else{
						$housekey=0;
					}
					$sql="UPDATE accounts SET house=$_GET[id],housekey=$housekey WHERE acctid=$_POST[owner]";
					db_query($sql);
					$sql="UPDATE items SET owner=$_POST[owner] WHERE class='Schlüssel' AND owner=$row[owner] AND value1=$_GET[id]";
					db_query($sql);

				}
			}
		}else{
			$action=true;
		}
		if ($action){
			output("`@Daten gespeichert.");
			$sql="UPDATE houses SET owner=$_POST[owner],housename='".addslashes(rawurldecode($_POST[housename]))."',gold=$_POST[gold],gems=$_POST[gems],status=$_POST[status],description='".addslashes(rawurldecode($_POST[description]))."' WHERE houseid=$_GET[id]";
			db_query($sql);
		}
	}else{
		$sql = "SELECT * FROM houses WHERE houseid=$_GET[id]";
		$result = db_query($sql) or die(db_error(LINK));
		$row = db_fetch_assoc($result);
		output("`@Daten für Haus `b$_GET[id]`b ändern:`n`n");
		output("`0<form action=\"suhouses.php?op=data&subop=save&id=$_GET[id]\" method='POST'>",true);
		output("<table><tr><td>Name </td><td><input name='housename' maxlength='25' value='".(rawurlencode($row[housename]))."'></td></tr>",true);
		output("<tr><td>Gold </td><td><input type='text' name='gold' value='$row[gold]'> </td></tr>",true);
		output("<tr><td>Edelsteine </td><td><input type='text' name='gems' value='$row[gems]'></td></tr>",true);
		output("<tr><td>Beschreibung </td><td><input type='text' name='description' maxlength='250' value='".(rawurlencode($row[description]))."'></td></tr>",true);
		output("<tr><td>`4Status`0 </td><td><input type='text' name='status' value='$row[status]'> `4(VORSICHT!)`0</td></tr>",true);
		output("<tr><td>`4Besitzer (ID)`0 </td><td><input type='text' name='owner' value='$row[owner]'> `4(VORSICHT!)`0</td></tr>",true);
		output("<tr><td>`4Sicherer Modus`0 </td><td><input type='checkbox' name='auto' checked='true' value='true'> `4(VORSICHT!)`0</td></tr></table>`n",true);
		output("<input type='submit' class='button' value='Speichern'></form>",true);
		output("`0`n`nDaten, die nicht geändert werden sollen, `bnicht`b verändern!`nStatusänderung kann Auswirkungen auf die Schlüsselverwaltung haben!`nBesitzer- und Statusänderungen müssen im unsicheren Modus manuell übertragen werden!`n");
		addnav("","suhouses.php?op=data&subop=save&id=$_GET[id]");
		disp_status();
	}
}else{
	output("`@`b`cDas Wohnviertel`c`b`n`n");
	output("Wähle das Haus:`n`n");
	output("<table cellpadding=2 align='center'><tr><td>`bHausNr.`b</td><td>`bName`b</td><td>`bStatus`b</td></tr>",true);
	$ppp=25; // Player Per Page +1 to display
	if (!$_GET[limit]){
		$page=0;
	}else{
		$page=(int)$_GET[limit];
		addnav("Vorherige Seite","suhouses.php?limit=".($page-1)."");
	}
	$limit="".($page*$ppp).",".($ppp+1);
	$sql = "SELECT houseid,housename,status FROM houses WHERE 1 ORDER BY houseid ASC LIMIT $limit";
	$result = db_query($sql) or die(db_error(LINK));
	if (db_num_rows($result)>$ppp) addnav("Nächste Seite","suhouses.php?limit=".($page+1)."");
	if (db_num_rows($result)==0){
		output("<tr><td colspan=3 align='center'>`&`iEs gibt keine Häuser`i`0</td></tr>",true);
	}else{
		for ($i=0;$i<db_num_rows($result);$i++){
			$row2 = db_fetch_assoc($result);
			output("<tr><td align='center'>$row2[houseid]</td><td><a href='suhouses.php?op=drin&id=$row2[houseid]'>$row2[housename]</a></td><td>$row2[status]</td></tr>",true);
			addnav("","suhouses.php?op=drin&id=$row2[houseid]");
		}
	}
	output("</table>",true);
	addnav("User mit Haus","suhouses.php?op=info");
	addnav("Neues Haus","suhouses.php?op=newhouse");
}
addnav("Zurück zur Grotte","superuser.php");
addnav("Zurück zum Weltlichen","village.php");
output("`n<div align='right'>`)2004 by anpera</div>",true);
page_footer();
?>