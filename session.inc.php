<?php
	
	global $DB;
	
	$session_set    = false;
	$session_ip     = $_SERVER['REMOTE_ADDR'];
	$session_id     = null;
	$session_id_esc = null;
	$session_exp    = time()+60*60*24*7;
	
	if(isset($_COOKIE['inkysession'])){
		$session_id     = $_COOKIE['inkysession'];
		$session_id_esc = $DB->esc($session_id);
		$q              = $DB->query("SELECT * FROM `sessions` WHERE `SessionID` = '$session_id_esc' LIMIT 1");
		if($DB->count($q)){
			$session_set = true;
			$r = $DB->fetch($q);
			if($session_ip != $r['LastAddr']){
				$DB->query("UPDATE `sessions` SET `LastAddr` = '$session_ip' WHERE `SessionID` = '$session_id_esc' LIMIT 1");
			}
			$DB->query("UPDATE `sessions` SET `Expires` = '$session_exp' WHERE `SessionID` = '$session_id_esc' LIMIT 1");
		}
	}
	
	if(!$session_set){
		$session_id = md5(uniqid("inky",true));
		$session_id_esc = $DB->esc($session_id);
		$q = $DB->query("INSERT INTO `sessions` (`SessionID`,`LastAddr`,`Created`,`Expires`) VALUES ('$session_id_esc','$session_ip','".time()."','$session_exp')");
	}

	if($session_id_esc == null){
		$session_id_esc = $DB->esc($session_id);
	}
	
	setcookie("inkysession", $session_id, $session_exp, "/");
	
?>
