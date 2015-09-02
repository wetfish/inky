<?php
	global $DB,$session_id,$session_id_esc;
	if(!isset($_GET['i'])){
		header("Location: /?p=404");
		exit();
	}
	$gallery_id = $_GET['i'];
	
	$q = $DB->query("SELECT * FROM `galleries` WHERE `Index`='$gallery_id'");
	if(!$DB->count($q)){
		header("Location: /?p=404");
		exit();
	}
	
	list($gallery_id,$gallery_name,$gallery_date,$gallery_exp,$gallery_owner) = $DB->fetch($q);
	if($gallery_name == "") $gallery_name = "Untitled";
	
	if($gallery_owner != $session_id){
		header("Location: /?p=401");
		exit();
	}
	
	$DB->query("DELETE FROM `galleries` WHERE `Index` = '$gallery_id' LIMIT 1");
	$DB->query("DELETE FROM `images` WHERE `GalleryID` = '$gallery_id'");
	$DB->query("DELETE FROM `pastes` WHERE `GalleryID` = '$gallery_id'");	
	
	header("Location: /");
	exit();
?>
