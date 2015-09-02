<?php
	function title(){
		echo "inky :: pastebin and image hosting";
	}
	function head(){
?>
	
	<script type="text/javascript">
		$(document).ready(function(){
			$(".unfold .cont_head").click(function(e){
				$(e.target).siblings(".cont_body").slideToggle();
			});
		});
	</script>
<?php
	}
	function body(){
		global $DB,$session_id_esc;
?>
		<div class="container block unfold">
			<h3 class="cont_head">Your Galleries</h3>
			<div class="cont_body">
				<?php
					$q = $DB->query("SELECT `Name`,`Index`,`DatePosted` FROM `galleries` WHERE `SessionID`='$session_id_esc' ORDER BY `Index` ASC");
					if($DB->count($q)){
				?>
				<ul id='yourgalleries'>
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
			</div>
		</div>
		<div class="container block">
			<h3 class="cont_head"><a rel="nofollow" href="./?p=new">New Gallery &rarr;</a></h3>
		</div>
		<div class="container block">
			<h3 class="cont_head">Login</h3>
			<div class="cont_body loginForm">
				<ul class="tabbed">
					<ul>
						<li>Login</li>
						<li>Register<li>
					</ul>
					<li><?php loginForm(); ?></li>
					<li><?php registerForm(); ?></li>
				</ul>
			</div>
		</div>
<?php
	}
?>
