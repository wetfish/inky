<?php
	global $DB,$session_id,$session_id_esc;
	if(!isset($_POST['index'])){
		header("Location: /?p=404");
		exit();
	}
	$gallery_id = $_POST['index'];
	
	include "bans.inc.php";
	
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
	
	$pasteData   = $DB->esc($_POST['content']);
	$pasteSyntax = $DB->esc($_POST['language']);
	$pasteName   = $DB->esc($_POST['filename']);
	
	$DB->query("INSERT INTO `pastes` (`GalleryID`,`Date`,`Syntax`,`Filename`,`Content`) VALUES(".
				"'$gallery_id','".time()."','$pasteSyntax','$pasteName','$pasteData')");
	
	push_message($session_id,'Paste successful.');
	header("Location: /g/".base_convert(intval($gallery_id),10,36));
	exit();
?>

