<?php
	include "../sql.inc.php";
	include "../initdb.inc.php";
	global $DB;
	$dest=135;
	if(!isset($_GET['i'])){
		exit();
	}
	$id = intval($_GET['i']);
	$q=$DB->query("SELECT `Content`,`Date`,`GalleryID` FROM `images` WHERE `Index` = '$id'");
	$r= $DB->fetch($q);
        $q2=$DB->query("SELECT `Private`,`Authkey` FROM `galleries` WHERE `Index`='{$r[2]}'");
        $r2=$DB->fetch($q2);
        if($r2[0] == "1" && $r2[1] != ""){
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
	header("Content-type: image/jpeg");
	$img=imagecreatefromstring($r[0]);
	if($img){
		$aspect=imagesy($img)/imagesx($img);
		if(imagesx($img)>$dest||imagesy($img)>$dest){
			if($aspect>=1){
				$newy=$dest;
				$newx=$newy/$aspect;
			}
			else{
				$newx=$dest;
				$newy=$aspect*$newx;
			}
			$newimg=imagecreatetruecolor($newx,$newy);
			imagecopyresampled($newimg,$img,0,0,0,0,$newx,$newy,imagesx($img),imagesy($img));
			unset($img);
			$img=$newimg;
			unset($newimg);
		}
		imagejpeg($img,null,80);
	}
	else{
		$img=imagecreatetruecolor(80,80);
		$c = imagecolorallocate($img,255,255,255);
		imagestring($img,1,5,5,"No thumbnail",$c);
		imagestring($img,1,5,13,"available.",$c);
		imagejpeg($img,null,80);
	}
?>
