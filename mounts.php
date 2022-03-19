<?
require_once "common.php";
isnewday(3);

page_header("Mount Editor");
addnav("G?Return to the Grotto","superuser.php");
addnav("M?Return to the Mundane","village.php");
addnav("Add a mount","mounts.php?op=add");

if ($_GET['op']=="del"){
	$sql = "UPDATE mounts SET mountactive=0 WHERE mountid='{$_GET['id']}'";
	db_query($sql);
	$_GET['op']="";
}
if ($_GET['op']=="undel"){
	$sql = "UPDATE mounts SET mountactive=1 WHERE mountid='{$_GET['id']}'";
	db_query($sql);
	$_GET['op']="";
}

if ($_GET['op']==""){
	$sql = "SELECT * FROM mounts ORDER BY mountcategory, mountcostgems, mountcostgold";
	output("<table>",true);
	output("<tr><td>Ops</td><td>Name</td><td>Cost</td><td>&nbsp;</td></tr>",true);
	$result = db_query($sql);
	$cat = "";
	for ($i=0;$i<db_num_rows($result);$i++){
		$row = db_fetch_assoc($result);
		if ($cat!=$row['mountcategory']){
			output("<tr><td colspan='4'>Category: {$row['mountcategory']}</td></tr>",true);
			$cat = $row['mountcategory'];
		}
		output("<tr>",true);
		output("<td>[ <a href='mounts.php?op=edit&id={$row['mountid']}'>Edit</a> |",true);
		addnav("","mounts.php?op=edit&id={$row['mountid']}");
		if ($row['mountactive']) {
			output(" <a href='mounts.php?op=del&id={$row['mountid']}'>Deactivate</a> ]</td>",true);
			addnav("","mounts.php?op=del&id={$row['mountid']}");
		}else{
			output(" <a href='mounts.php?op=undel&id={$row['mountid']}'>Activate</a> ]</td>",true);
			addnav("","mounts.php?op=undel&id={$row['mountid']}");
		}
		output("<td>{$row['mountname']}</td>",true);
		output("<td>{$row['mountcostgems']} gems, {$row['mountcostgold']} gold</td>",true);
		//output("<td>{$row['mountbuff']}</td>",true);
		output("<td>FF: {$row['mountforestfights']}, DarkHorse: {$row['tavern']}</td>",true);
		output("</tr>",true);
	}
	output("</table>",true);
}elseif ($_GET['op']=="add"){
	output("Add a mount:`n");
	addnav("Mount Editor Home","mounts.php");
	mountform(array());
}elseif ($_GET['op']=="edit"){
	addnav("Mount Editor Home","mounts.php");
	$sql = "SELECT * FROM mounts WHERE mountid='{$_GET['id']}'";
	$result = db_query($sql);
	if (db_num_rows($result)<=0){
		output("`iThis mount was not found.`i");
	}else{
		output("Mount Editor:`n");
		$row = db_fetch_assoc($result);
		$row['mountbuff']=unserialize($row['mountbuff']);
		mountform($row);
	}
}elseif ($_GET['op']=="save"){
	$buff = array();
	reset($_POST['mount']['mountbuff']);
	$_POST['mount']['mountbuff']['activate']=join(",",$_POST['mount']['mountbuff']['activate']);
	while (list($key,$val)=each($_POST['mount']['mountbuff'])){
		if ($val>""){
			$buff[$key]=stripslashes($val);
		}
	}
	//$buff['activate']=join(",",$buff['activate']);
	$_POST['mount']['mountbuff']=$buff;
	reset($_POST['mount']);
	$keys='';
	$vals='';
	$sql='';
	$i=0;
	while (list($key,$val)=each($_POST['mount'])){
		if (is_array($val)) $val = addslashes(serialize($val));
		if ($_GET['id']>""){
			$sql.=($i>0?",":"")."$key='$val'";
		}else{
			$keys.=($i>0?",":"")."$key";
			$vals.=($i>0?",":"")."'$val'";
		}
		$i++;
	}
	if ($_GET['id']>""){
		$sql="UPDATE mounts SET $sql WHERE mountid='{$_GET['id']}'";
	}else{
		$sql="INSERT INTO mounts ($keys) VALUES ($vals)";
	}
	db_query($sql);
	if (db_affected_rows()>0){
		output("Mount saved!");
	}else{
		output("Mount not saved: $sql");
	}
	addnav("Mount Editor Home","mounts.php");
}

function mountform($mount){
	global $output;
	output("<form action='mounts.php?op=save&id={$mount['mountid']}' method='POST'>",true);
	addnav("","mounts.php?op=save&id={$mount['mountid']}");
	$output.="<table>";
	$output.="<tr><td>Mount Name:</td><td><input name='mount[mountname]' value=\"".htmlentities($mount['mountname'])."\"></td></tr>";
	$output.="<tr><td>Mount Description:</td><td><input name='mount[mountdesc]' value=\"".htmlentities($mount['mountdesc'])."\"></td></tr>";
	$output.="<tr><td>Mount Category:</td><td><input name='mount[mountcategory]' value=\"".htmlentities($mount['mountcategory'])."\"></td></tr>";
	$output.="<tr><td>Mount Cost (Gems):</td><td><input name='mount[mountcostgems]' value=\"".htmlentities((int)$mount['mountcostgems'])."\"></td></tr>";
	$output.="<tr><td>Mount Cost (Gold):</td><td><input name='mount[mountcostgold]' value=\"".htmlentities((int)$mount['mountcostgold'])."\"></td></tr>";
	$output.="<tr><td>Delta Forest Fights:</td><td><input name='mount[mountforestfights]' value=\"".htmlentities((int)$mount['mountforestfights'])."\" size='5'></td></tr>";
	$output.="<tr><td>Tavern Enabled:</td><td><input name='mount[tavern]' value=\"".htmlentities((int)$mount['tavern'])."\" size='1'></td></tr>";
	$output.="<tr><td>New Day Message:</td><td><input name='mount[newday]' value=\"".htmlentities($mount['newday'])."\" size='40'></td></tr>";
	$output.="<tr><td>Full Recharge Message:</td><td><input name='mount[recharge]' value=\"".htmlentities($mount['recharge'])."\" size='40'></td></tr>";
	$output.="<tr><td>Partial Recharge Message:</td><td><input name='mount[partrecharge]' value=\"".htmlentities($mount['partrecharge'])."\" size='40'></td></tr>";
	$output.="<tr><td>Chance of entering mine (percent):</td><td><input name='mount[mine_canenter]' value=\"".htmlentities((int)$mount['mine_canenter'])."\"></td></tr>";
	$output.="<tr><td>Chance of dying in mine (percent):</td><td><input name='mount[mine_candie]' value=\"".htmlentities((int)$mount['mine_candie'])."\"></td></tr>";
	$output.="<tr><td>Chance of saving player in mine (percent):</td><td><input name='mount[mine_cansave]' value=\"".htmlentities((int)$mount['mine_cansave'])."\"></td></tr>";
	$output.="<tr><td>Mine tether message:</td><td><input name='mount[mine_tethermsg]' value=\"".htmlentities($mount['mine_tethermsg'])."\" size='40'></td></tr>";
	$output.="<tr><td>Mine death message:</td><td><input name='mount[mine_deathmsg]' value=\"".htmlentities($mount['mine_deathmsg'])."\" size='40'></td></tr>";
	$output.="<tr><td>Mine tether message:</td><td><input name='mount[mine_savemsg]' value=\"".htmlentities($mount['mine_savemsg'])."\" size='40'></td></tr>";
	$output.="<tr><td valign='top'>Mount Buff:</td><td>";
	$output.="<b>Messages:</b><Br/>";
	$output.="Buff name: <input name='mount[mountbuff][name]' value=\"".htmlentities($mount['mountbuff']['name'])."\"><Br/>";
	//output("Initial Message: <input name='mount[mountbuff][startmsg]' value=\"".htmlentities($mount['mountbuff']['startmsg'])."\">`n",true);
	$output.="Message each round: <input name='mount[mountbuff][roundmsg]' value=\"".htmlentities($mount['mountbuff']['roundmsg'])."\"><Br/>";
	$output.="Wear off message: <input name='mount[mountbuff][wearoff]' value=\"".htmlentities($mount['mountbuff']['wearoff'])."\"><Br/>";
	$output.="Effect Message: <input name='mount[mountbuff][effectmsg]' value=\"".htmlentities($mount['mountbuff']['effectmsg'])."\"><Br/>";
	$output.="Effect No Damage Message: <input name='mount[mountbuff][effectnodmgmsg]' value=\"".htmlentities($mount['mountbuff']['effectnodmgmsg'])."\"><Br/>";
	$output.="Effect Fail Message: <input name='mount[mountbuff][effectfailmsg]' value=\"".htmlentities($mount['mountbuff']['effectfailmsg'])."\"><Br/>";
	$output.="<Br/><b>Effects:</b><Br/>";
	$output.="Rounds to last (from new day): <input name='mount[mountbuff][rounds]' value=\"".htmlentities((int)$mount['mountbuff']['rounds'])."\" size='5'><Br/>";
	$output.="Player Atk mod: <input name='mount[mountbuff][atkmod]' value=\"".htmlentities($mount['mountbuff']['atkmod'])."\" size='5'> (multiplier)<Br/>";
	$output.="Player Def mod: <input name='mount[mountbuff][defmod]' value=\"".htmlentities($mount['mountbuff']['defmod'])."\" size='5'> (multiplier)<Br/>";
	$output.="Regen: <input name='mount[mountbuff][regen]' value=\"".htmlentities($mount['mountbuff']['regen'])."\"><Br/>";
	$output.="Minion Count: <input name='mount[mountbuff][minioncount]' value=\"".htmlentities($mount['mountbuff']['minioncount'])."\"><Br/>";
	$output.="Min Badguy Damage: <input name='mount[mountbuff][minbadguydamage]' value=\"".htmlentities($mount['mountbuff']['minbadguydamage'])."\" size='5'><Br/>";
	$output.="Max Badguy Damage: <input name='mount[mountbuff][maxbadguydamage]' value=\"".htmlentities($mount['mountbuff']['maxbadguydamage'])."\" size='5'><Br/>";
	$output.="Lifetap: <input name='mount[mountbuff][lifetap]' value=\"".htmlentities($mount['mountbuff']['lifetap'])."\" size='5'> (multiplier)<Br/>";
	$output.="Damage shield: <input name='mount[mountbuff][damageshield]' value=\"".htmlentities($mount['mountbuff']['damageshield'])."\" size='5'> (multiplier)<Br/>";
	$output.="Badguy Damage mod: <input name='mount[mountbuff][badguydmgmod]' value=\"".htmlentities($mount['mountbuff']['badguydmgmod'])."\" size='5'> (multiplier)<Br/>";
	$output.="Badguy Atk mod: <input name='mount[mountbuff][badguyatkmod]' value=\"".htmlentities($mount['mountbuff']['badguyatkmod'])."\" size='5'> (multiplier)<Br/>";
	$output.="Badguy Def mod: <input name='mount[mountbuff][badguydefmod]' value=\"".htmlentities($mount['mountbuff']['badguydefmod'])."\" size='5'> (multiplier)<Br/>";
	//$output.=": <input name='mount[mountbuff][]' value=\"".htmlentities($mount['mountbuff'][''])."\">`n",true);
	
	$output.="<Br/><b>Activate:</b><Br/>";
	$output.="<input type='checkbox' name='mount[mountbuff][activate][]' value=\"roundstart\"".(strpos($mount['mountbuff']['activate'],"roundstart")!==false?" checked":"")."> Round Start<Br/>";
	$output.="<input type='checkbox' name='mount[mountbuff][activate][]' value=\"offense\"".(strpos($mount['mountbuff']['activate'],"offense")!==false?" checked":"")."> On Attack<Br/>";
	$output.="<input type='checkbox' name='mount[mountbuff][activate][]' value=\"defense\"".(strpos($mount['mountbuff']['activate'],"defense")!==false?" checked":"")."> On Defend<Br/>";
	$output.="<Br/>";
	$output.="</td></tr>";
	$output.="</table>";
	$output.="<input type='submit' class='button' value='Save'></form>";
}

page_footer();
?>
