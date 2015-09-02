<?php
	global $DB,$session_id,$session_id_esc;
	if(!isset($_GET['i'])){
		header("Location: /?p=404");
		exit();
	}
	$id = $_GET['i'];
	
	$q = $DB->query("SELECT `GalleryID` FROM `images` WHERE `Index`='$id' LIMIT 1");
	if(!$DB->count($q)){
		header("Location: /?p=404");
		exit();
	}
	list($gallery_id) = $DB->fetch($q);
	
	$q = $DB->query("SELECT `SessionID` FROM `galleries` WHERE `Index` = '$gallery_id' LIMIT 1");
	list($gallery_owner) = $DB->fetch($q);
	
	if($gallery_owner != $session_id){
		header("Location: /?p=401");
		exit();
	}
	
	$DB->query("DELETE FROM `images` WHERE `Index` = '$id' LIMIT 1");	
	
	push_message($session_id,'Image deleted.');
	header("Location: /?p=gallery&i=".base_convert(intval($gallery_id),10,36));
	exit();
?>

