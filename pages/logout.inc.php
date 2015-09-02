<?php
	setcookie("inkysession","",time()-86400);
	header("Location: /");
	exit();
?>
