<?php
	date_default_timezone_set("America/New_York");
	function title(){
		echo "inky :: you've been bad!";
	}
	function body(){
	global $DB,$session_id_esc;
	$q=$DB->query("SELECT * FROM `bans` WHERE `SessionID` = '$session_id_esc' LIMIT 1");
?>
<div class="container block">
	<h3 class="cont_head">Hey, you!</h3>
	<div class="cont_body">
	<p>You are no longer allowed to generate content on inky!</p>
	<?php
		if($DB->count($q)){
			$r=$DB->fetch($q);
	?>
	<p>You were banned on <b><?php echo date("M d, Y",intval($r['Date']))." at ".date("h:i A T",intval($r['Date']));?></b> for 
	<b><?php echo stripslashes($r['Reason']);?></b>.
	<?php if(intval($r['DateExpires'])>0){?>
	This ban expires on 
	<b><?php echo date("M d, Y",intval($r['DateExpires']))." at ".date("h:i A T",intval($r['DateExpires']));?></b>.
	Until then, you will be able to browse inky, but not upload new content.</p>
	<?php } else { ?>
	This ban does not expire.
	<?php
			}
		}
	?>
	</div>
</div>
<?php
	}
?>
