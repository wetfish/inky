<?php
	function title(){
		echo "inky :: pastebin and image hosting";
	}
	function head(){
?>
	
	<script type="text/javascript">
		$(document).ready(function(){
			var s = $("#yourgalleries");
			var t = s.wrapInner("<div>").children();
			var h = t.outerHeight();
			t.replaceWith(t.html());
			s.animate({scrollTop: h},"slow");
		});
		function newGallery(){
			window.location = "http://inky.ws/?p=new";
		}
	</script>
	<script type="text/javascript" src="http://static.inky.ws/overthrow.js"></script>
<?php
	}
	function body(){
		global $DB,$session_id_esc;
?>
	<div class="side">
		<div class="container block">
			<h3 class="cont_head">Your Galleries</h3>
			<div class="cont_body">
				<?php
					$q = $DB->query("SELECT `Name`,`Index`,`DatePosted` FROM `galleries` WHERE `SessionID`='$session_id_esc' ORDER BY `Index` ASC");
					if($DB->count($q)){
				?>
				<ul id='yourgalleries' class='overthrow'>
				<?php
						while(list($gallery_name,$gallery_index,$gallery_time) = $DB->fetch($q)){
							if($gallery_name == "") $gallery_name = "Untitled";
							$gallery_index = base_convert(intval($gallery_index),10,36);
				?>
					<li>
						<a href="/g/<?php echo $gallery_index; ?>"><?php echo abbr($gallery_name,23);?></a> 
						<span class='nothing'>(<?php echo time_ago($gallery_time);?>)</span>
					</li>
				<?php
						}
				?>
				</ul>
				<?php
					}
					else{
				?>
					<span class='nothing'>None yet.</span>
				<?php	
					}
				?>
				<a href="#" onclick="newGallery()">New Gallery</a>
			</div>
		</div>
		<?php ad_if_guest(); ?>
		<div class="container block">
			<h3 class="cont_head">Latest Public Galleries</h3>
			<div class="cont_body">
				<?php
					$q = $DB->query("SELECT `Name`,`Index`,`DatePosted` FROM `galleries` WHERE `Private`='0' ORDER BY `DatePosted` DESC LIMIT 0,5");
				?>
				<ul id="pubgalleries">
				<?php
					while(list($gallery_name,$gallery_index,$gallery_time) = $DB->fetch($q)){
						if($gallery_name == "") $gallery_name = "Untitled";
						$gallery_index = base_convert(intval($gallery_index),10,36);
				?>
					<li>
						<a href="/g/<?php echo $gallery_index; ?>"><?php echo abbr($gallery_name,22);?></a> 
						<span class='nothing'>(<?php echo time_ago($gallery_time);?>)</span>
					</li>
				<?php
					}
				?>
				</ul>
			</div>
		</div>
	</div>
	<div class="body">
		<div class="container block">
			<h3 class="cont_head">Welcome to Inky!</h3>
			<div class="cont_body">
				<p>Inky is a <a href="https://fsf.org">free/libre</a> image hosting and pastebin service, provided for your convenience.
				To get started, just <a href="#" onclick="newGallery()" >create a gallery</a> and start uploading!
				To best experience Inky, please make sure you have cookies and Javascript enabled.</p>
			</div>
		</div>
		<div class="container block">
			<h3 class="cont_head">Login</h3>
			<div class="cont_body loginForm">
				<table><tr><td>
				<?php loginForm(); ?>
				</td><td style="padding-left:52px;">
				<?php registerForm();?>
				</tr></tr></table>
			</div>
		</div>
		<div class="container block">
			<h3 class="cont_head">Current Statistics</h3>
			<div class="cont_body">
				<?php
				$q=$DB->query("SELECT COUNT(`SessionID`) FROM `sessions`");
				list($session_count)=$DB->fetch($q);
				$q=$DB->query("SELECT COUNT(`Username`) FROM `accounts`");
				list($user_count)=$DB->fetch($q);
				$q=$DB->query("SELECT COUNT(`Index`) FROM `galleries`");
				list($gallery_count)=$DB->fetch($q);
				$q=$DB->query("SELECT COUNT(`Index`) FROM `images`");
				list($image_count)=$DB->fetch($q);
				$q=$DB->query("SELECT COUNT(`Index`) FROM `pastes`");
				list($paste_count)=$DB->fetch($q);
				?>
				<p><b><?php echo $gallery_count;?></b> galleries, <b><?php echo $image_count;?></b> images, <b><?php echo $paste_count;?></b> pastes<br/>
				<b><?php echo $session_count;?></b> visitors this week<br/>
				<b><?php echo $user_count;?></b> registered users</p>
			</div>
		</div>
	</div>
<?php
	}
?>
