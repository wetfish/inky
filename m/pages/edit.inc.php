<?php
	global $DB,$session_id,$session_id_esc;
	if(!isset($_POST['index'])){
		header("Location: /?p=404");
		exit();
	}
	
	include "bans.inc.php";
	
	$gallery_id=$_POST['index'];
	
	$q = $DB->query("SELECT * FROM `galleries` WHERE `Index`='$gallery_id'");
	if(!$DB->count($q)){
		header("Location: /?p=404");
		exit();
	}
	
	list($gallery_id,$gallery_name,$gallery_date,$gallery_exp,$gallery_owner,$gallery_privacy,$gallery_mode) = $DB->fetch($q);
	if($gallery_name == "") $gallery_name = "Untitled";
	
	if($gallery_owner != $session_id){
		header("Location: /?p=401");
		exit();
	}
	
	$sql = "UPDATE `galleries` SET";
	
	if($_POST['name'] != $gallery_name){
		$sql.= " `Name` = '".$DB->esc(htmlspecialchars($_POST['name'],ENT_QUOTES))."',";
	}
	
	if($_POST['expiry'] != $gallery_exp){
		$sql.= " `DateExpires` = '".$DB->esc(intval($_POST['expiry']))."',";
	}
	
	if($_POST['privacy'] != $gallery_privacy){
		$sql.= " `Private` = '".$DB->esc(htmlspecialchars($_POST['privacy'],ENT_QUOTES))."',";
	}

	if($_POST['displaymode'] != $gallery_mode){
		$sql.= " `DisplayMode` = '".$DB->esc($_POST['displaymode'])."',";
	}
	
	$sql=preg_replace("/,$/","",$sql);
	
	$sql.= " WHERE `Index` = '$gallery_id' LIMIT 1";
	
	if($sql!="UPDATE `galleries` SET WHERE `Index` = '$gallery_id' LIMIT 1"){
		$DB->query($sql);
	}
	
	push_message($session_id,"Changes saved.");
	header("Location: /g/".base_convert(intval($gallery_id),10,36));
	exit();
?>

