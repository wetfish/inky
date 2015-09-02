<?php
function title(){
	echo "inky :: images";
}
function body(){
if(!isset($_GET['authorized'])) exit("not authorized");
?>
<div class="container block">
	<h3 class="cont_head">images</h3>
</div>
<?php
	global $DB;
	$q = $DB->query("SELECT `Index`,`GalleryId` FROM `images` ORDER BY `Index`");
	while(list($i,$g) = $DB->fetch($q)){
		$q1 = $DB->query("SELECT `Authkey` FROM `galleries` WHERE `Index`='$g'");
		list($a) = $DB->fetch($q1);
		echo "<a href='http://static.inky.ws/image/$i/$a'><img src='http://static.inky.ws/thumb/$i/$a'/></a> ";
	}
}
?>
