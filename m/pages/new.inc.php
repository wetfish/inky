<?php
	global $DB,$session_id_esc;
	include "bans.inc.php";
	$DB->query("INSERT INTO `galleries` (`Name`,`DatePosted`,`DateExpires`,`SessionID`,`Authkey`) ".
		   "VALUES ('','".time()."','".(time()+60*60*24*7)."','$session_id_esc','".uniqid()."')");
	if($DB->affected()){
		$id = base_convert(intval($DB->get_id()),10,36);
		header("Location: /g/$id");
		exit();
	}
?>
