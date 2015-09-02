<?php
	include "../sql.inc.php";
	include "../initdb.inc.php";
	global $DB;
	if(!isset($_GET['i'])){
		exit();
	}
	$id = intval($_GET['i']);
	$q=$DB->query("SELECT `Content`,`Date`,`GalleryID`,`Type` FROM `images` WHERE `Index` = '$id'");
	$r=$DB->fetch($q);
	$q2=$DB->query("SELECT `Private`,`Authkey` FROM `galleries` WHERE `Index`='{$r['GalleryID']}'");
	$r2=$DB->fetch($q2);
	if($r2['Private'] == 1 && $r2['Authkey'] != ""){
		if(!isset($_GET['a'])){
			header("content-type: text/plain");
			exit("Not authorized.");
		}
		else{
			if(str_replace('/','',$_GET['a']) != $r2[1]){
				header("content-type: text/plain");
				exit("Not authorized.");
			}
		}
	}
	header("Last-Modified: ".gmdate("D, d M Y H:i:s",intval($r[1]))." GMT");
	if($r['Type'] == ""){
		$tmp=tempnam("/tmp","inky");
		file_put_contents($tmp,$r[0]);
		$size = getimagesize($tmp);
		$r['Type'] = $size['mime'];
		$_type = $DB->esc($r['Type']);
		$DB->query("UPDATE `images` SET `Type`='$_type' WHERE `Index`='$id'");
		unlink($tmp);
	}
	header("Content-type: {$r['Type']}");
	echo $r[0];
?>
