<?php
	global $DB,$session_id_esc;
	$q=$DB->query("SELECT * FROM `bans` WHERE `SessionID` = '$session_id_esc'");
	if($DB->count($q)){
		header("Location: /?p=ban");
		exit();
	}
?>
