<?php
	$request = "default";
	if(isset($_GET['p'])){
		$request = str_replace(array(".","/"),"",$_GET['p']);
	}
	$request.=".inc.php";
	if(file_exists("./pages/$request")){
		include "./pages/$request";
	}
	else{
		include "./pages/404.inc.php";
	}
?>
