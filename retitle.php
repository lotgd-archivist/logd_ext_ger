<?
require_once "common.php";
isnewday(3);

page_header("Retitler");
addnav("G?Zurück zur Grotte","superuser.php");
addnav("W?Zurück zum Weltlichen","village.php");
addnav("Titel neu zuweisen","retitle.php?op=rebuild");
if ($_GET['op']=="rebuild"){

    //output("<pre>".htmlentities($titles)."</pre>",true);
    //output("<pre>".htmlentities(output_array($titles))."</pre>",true);

   // $sql = "SELECT   name,title,dragonkills,acctid,sex,ctitle  FROM  accounts WHERE dragonkills>0";
    $sql = "SELECT   name,title,dragonkills,acctid,sex,ctitle  FROM  accounts WHERE 1";
    $result = db_query($sql);
    for ($i=0;$i<db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        //if ($i==0) echo "x".nl2br(output_array($titles));
        $newtitle = $titles[(int)$row['dragonkills']][(int)$row['sex']];
		if ($row['ctitle'] == "") {
			$oname = $row['name'];
			if ($row['title']!=""){
				$n = $row['name'];
				$x = strpos($n,$row['title']);
				if ($x!==false){
					$regname=substr($n,$x+strlen($row['title']));
					$row['name'] = substr($n,0,$x).$newtitle.$regname;
				}else{
					$row['name'] = $newtitle." ".$row['name'];
				}
			}else{
				$row[name] = $newtitle." ".$row['name'];
			}
		}
        output("`@Ändere `^$oname`@ auf `^{$row['name']} `@($newtitle-{$row['dragonkills']}[{$row['sex']}]({$row['ctitle']}`@))`n");
        if ($session['user']['acctid']==$row['acctid']){
            $session['user']['title']=$newtitle;
            $session['user']['name']=$row['name'];
        }else{
            $sql = "UPDATE accounts SET name='".addslashes($row['name'])."', title='".addslashes($newtitle)."' WHERE acctid='{$row['acctid']}'";
            //output("`0$sql`n");
            (db_query($sql));
        }
    }
}else{
    output("Diese Seite lässt dich alle Usertitel anpassen, wenn die im Drachenscript verändert wurden.");
}
page_footer();
?>
