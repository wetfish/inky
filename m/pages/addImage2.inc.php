<?php
	global $DB,$session_id,$session_id_esc;
	$maxsize=2*1024*1024;

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

	$results = array();
	foreach($_FILES['image']['name'] as $i => $name){
	    $result = "An unknown error occured.";
	      try{
		if(is_uploaded_file($_FILES['image']['tmp_name'][$i])){
			if($_FILES['image']['size'][$i] <= $maxsize){
				$result = '1';
				$img_data = addslashes(file_get_contents($_FILES['image']['tmp_name'][$i]));
				$img_info = getimagesize($_FILES['image']['tmp_name'][$i]);
				if($img_info[0]<=1 || $img_info[1]<=1){
					$result = "Invalid image.";
				}
				else{
					$DB->query("INSERT INTO `images` (`GalleryID`,`Date`,`Content`) VALUES('$gallery_id','".time()."','$img_data')");
					if($DB->error() != ""){
						$result = "Unable to upload image.";
					}
					else{
						$result = "Uploaded successfully.";
					}
				}
			}
			else{
				$result = "Image file too large. Maximum 2 MB.";
			}
		}
	      }
	      catch(Exception $e){
			$result = "Exception occurred: ".$e->message;
	      }
	      push_message($session_id,$result);
	}
	header("Location: /g/".base_convert(intval($gallery_id),10,36));
	exit();
?>

