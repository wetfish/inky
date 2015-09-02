<?php
	global $DB,$session_id_esc,$session_id,$session_ip,$session_exp;
	include "bans.inc.php";
	if(!isset($_POST['user'],$_POST['pass'])){
		exit("You got here in a way I don't approve of.");
	}
	$user=$DB->esc($_POST['user']);
	$pass=md5($_POST['pass']);
	$q = $DB->query("SELECT * FROM `accounts` WHERE `Username`='$user'");
	if($DB->count($q)){
		exit("That username is taken! Go back and try a different one, please.");
	}
	$DB->query("INSERT INTO `accounts` (`SessionId`,`Username`,`Password`) VALUES ('$session_id_esc','$user','$pass')");

	$DB->query("DELETE FROM `sessions` WHERE `SessionID` = '$session_id_esc'");
	$DB->query("INSERT INTO `sessions` (`SessionID`,`LastAddr`,`Created`,`Expires`) VALUES ('$session_id_esc','$session_ip','".time()."','$session_exp')");
	setcookie("inkysession",$session_id,$session_exp,"/");

	header("Location: /");
	exit();
?>
