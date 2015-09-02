<?php
	include "../sql.inc.php";	
	include "../initdb.inc.php";
	global $DB;
	if(!isset($_GET['i'])){
		exit();
	}
	$id = intval($_GET['i']);
	$q=$DB->query("SELECT `Content`,`Date`,`GalleryID` FROM `pastes` WHERE `Index` = '$id'");
	$r= $DB->fetch($q);
        $q2=$DB->query("SELECT `Private`,`Authkey` FROM `galleries` WHERE `Index`={$r[2]}");
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
	header("Content-type: text/plain; charset=utf-8");
	echo $r[0];
?>
