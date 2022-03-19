<?php

// anti cheat module for custom methods to detect players who are cheating
// 
// function ac_check returns true, if the user seems to be trying to cheat
// and false if everything seems fine

function ac_check($row) {
	global $session;
	if (isset($row['acctid'])) {
		if (!isset($row['uniqueid'])) {
			$sql = "SELECT uniqueid FROM accounts WHERE acctid = '".$row['acctid']."'";
			$result = db_query($sql);
			if (db_num_rows($result)>0) {
				$row = db_fetch_assoc($result);
			} else {
				return false;
			}
		}
		if ($session['user']['uniqueid'] == $row['uniqueid']) {
			return true;
		}else{
			return false;
		}
	}else {
		return false;
	}
}

?>