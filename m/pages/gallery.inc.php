<?php
	date_default_timezone_set("America/New_York");

	function title(){
		global $DB,$session_id,$session_id_esc;

		$gallery_id = base_convert($_GET['i'],36,10);
                $q = $DB->query("SELECT * FROM `galleries` WHERE `Index`='$gallery_id'");
                if(!$DB->count($q)){
                        header("Location: /?p=404");
                        exit();
                }
                list($gallery_id,$gallery_name,$gallery_date,$gallery_exp,$gallery_owner,$gallery_privacy,$gallery_authkey,$gallery_mode) = $DB->fetch($q);
                if($gallery_privacy && $gallery_authkey != "" && $gallery_owner != $session_id && $gallery_authkey != $authkey){
                        header("Location: /?p=401");
                        exit();
                }

                if($gallery_name == "") $gallery_name = "Untitled gallery";
		echo "inky :: $gallery_name";
	}

	function head_inject(){
	 global $DB,$session_id,$session_id_esc;

         $gallery_id = base_convert($_GET['i'],36,10);
         $q = $DB->query("SELECT * FROM `galleries` WHERE `Index`='$gallery_id'");
         if(!$DB->count($q)){
                 header("Location: /?p=404");
                 exit();
         }
         list($gallery_id,$gallery_name,$gallery_date,$gallery_exp,$gallery_owner,$gallery_privacy,$gallery_authkey,$gallery_mode) = $DB->fetch($q);
	 if($gallery_name == "") $gallery_name = "Untitled gallery";
	 $g_url = "http://inky.ws/g/".base_convert($gallery_id,10,36);
 	 if($gallery_privacy)
	  $g_url = "http://inky.ws/p/".base_convert($gallery_id,10,36)."/$gallery_authkey";
	?>
	<div class='share'>
	<span>Share this gallery</span>
	<!-- AddThis Button BEGIN -->
	<div class="addthis_toolbox addthis_default_style addthis_32x32_style" addthis:url="<?php echo $g_url;?>" addthis:title="<?php echo $gallery_name;?> on inky.ws" addthis:description="<?php echo $gallery_name;?> on inky.ws">
	<a class="addthis_button_preferred_1"></a>
	<a class="addthis_button_preferred_2"></a>
	<a class="addthis_button_preferred_3"></a>
	<a class="addthis_button_preferred_4"></a>
	<a class="addthis_button_compact"></a>
	<a class="addthis_counter addthis_bubble_style"></a>
	</div>
	<script type="text/javascript" src="http://s7.addthis.com/js/300/addthis_widget.js#pubid=ra-4df402ea172ba2fb"></script>
	<!-- AddThis Button END -->
	</div>
	<?php
	}

	function body(){
		global $DB,$session_id,$session_id_esc;
		$authkey = "";
		if(!isset($_GET['i'])){
			header("Location: /?p=404");
			exit();
		}
		if(isset($_GET['a'])){
			$authkey = $_GET['a'];
		}

		$gallery_id = base_convert($_GET['i'],36,10);

		$q = $DB->query("SELECT * FROM `galleries` WHERE `Index`='$gallery_id'");
		if(!$DB->count($q)){
			header("Location: /?p=404");
			exit();
		}
		list($gallery_id,$gallery_name,$gallery_date,$gallery_exp,$gallery_owner,$gallery_privacy,$gallery_authkey,$gallery_mode) = $DB->fetch($q);
		if($gallery_privacy && $gallery_authkey != "" && $gallery_owner != $session_id && $gallery_authkey != $authkey){
			header("Location: /?p=401");
			exit();
		}
		if($gallery_name == "") $gallery_name = "Untitled";
?>
	<?php
		ad_if_guest_gallery($gallery_id);
	?>
	<div class="container block">
		<h3 class="cont_head">
			Gallery: <a href="/g/<?php echo base_convert(intval($gallery_id),10,36);?>"><?php echo $gallery_name;?></a>
			<?php if($gallery_owner == $session_id){ ?>
			<span class='pipe'>|</span> <a class='settingsLink' href="javascript:void(null)" onclick="slidefade('editForm',this)"><span>settings</span></a>
			<?php } ?>
		</h3>
		<div class="cont_body">
			<?php
				get_messages($session_id);

				if($gallery_owner == $session_id){
			?>
			<form action="/?p=edit" method="post" class="hidden editForm" id="editForm">
				<table>
					<tr>
						<td class='label'>Name</td>
						<td class='input'><input name="name" value="<?php echo $gallery_name;?>"/></td>
					</tr>
					<tr>
						<td class='label'>Expires</td>
						<td class='input'>
							<select name="expiry">
								<?php
									$expiry=array(	"Never" 		=> 0,
													"10 Minutes"	=> $gallery_date+60*10,
													"1 Hour"  		=> $gallery_date+60*60,
													"1 Day"			=> $gallery_date+60*60*24,
													"1 Week"		=> $gallery_date+60*60*24*7,
													"30 Days"		=> $gallery_date+60*60*24*30
									);
									foreach($expiry as $i=>$v){
								?>
								<option value="<?php echo $v;?>" <?php if($v==$gallery_exp) echo "selected='selected'";?>><?php echo $i;?></option>
								<?php
									}
								?>
							</select>
						</td>
					</tr>
					<tr>
						<td class='label'>Visibility</td>
						<td class='input'>
							<select name="privacy">
								<?php
									$privacy=array(	"Private" 		=> 1,
													"Public"		=> 0
									);
									foreach($privacy as $i=>$v){
								?>
								<option value="<?php echo $v;?>" <?php if($v==$gallery_privacy) echo "selected='selected'";?>><?php echo $i;?></option>
								<?php
									}
								?>
							</select>
						</td>
					</tr>
					<tr>
						<td class='label'>Image Display Mode</td>
						<td class='input'>
							<select name="displaymode">
								<option value="thumb" <?php if($gallery_mode=="thumb") echo "selected='selected'";?>>Thumbnails</option>
								<option value="large" <?php if($gallery_mode=="large") echo "selected='selected'";?>>Large Images</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>
							<input type="hidden" name="index" value="<?php echo $gallery_id;?>"/>
							<input type="submit" name="submit" value="Save Changes"/> 
							<button onclick="return deleteGallery(<?php echo $gallery_id;?>)">Delete Gallery</button>
						</td>
					</tr>
					<?php if($gallery_privacy && $gallery_authkey != ""){ ?>
					<tr><td colspan="2">&nbsp;</td></tr>
					<tr><td>Private URL</td><td>
					<input readonly="readonly" value="<?php echo "http://inky.ws/p/".base_convert($gallery_id,10,36)."/$gallery_authkey";?>" size="62" onclick="this.select()"/>
					<br/>(Required for people other than yourself to view this gallery.)</td></tr>
					<?php } ?>
				</table>
			</form>
		<?php
				}
			?>
		</div>
	</div>
	<div class="container block">
		<h3 class="cont_head ghead"><div>Images</div><?php
		if($gallery_owner == $session_id){
		?>
		<span class='addLink'><a href="/?p=addImage&amp;i=<?php echo $gallery_id;?>">Add image</a></span>
		<?php
		}
		?></h3>
		<div class="cont_body">
			<?php
				$urlAdditional = "";
				if($gallery_privacy && $gallery_authkey != ""){
					$urlAdditional="/$gallery_authkey";
				}

				$q = $DB->query("SELECT `Index`,`Date` FROM `images` WHERE `GalleryID`='$gallery_id' ORDER BY `Index` ASC");
				if($DB->count($q)){
			?>
			<div class="image_cont">
			<?php
					while(list($image_i,$image_date) = $DB->fetch($q)){
						if($gallery_mode == "thumb"){
			?>
				<div class="image">
					<div>
					<a href="http://static.inky.ws/image/<?php echo $image_i.$urlAdditional;?>/image.jpg" id="image_<?php echo $image_i;?>"><img src="http://static.inky.ws/thumb/<?php echo $image_i.$urlAdditional;?>/thumb.jpg" alt="uploaded image"/></a>
					</div>
					<span><?php echo date("M d, H:i T",$image_date);?></span>
					<span class='delete'>
					<?php if($gallery_owner == $session_id){ ?>
					<a href="/?p=deleteImage&amp;i=<?php echo $image_i;?>">Delete</a> &middot; 
					<?php } ?>
					<a href="http://static.inky.ws/image/<?php echo $image_i.$urlAdditional;?>/image.jpg" target="_blank">Hotlink</a>
					</span>
					<script type="text/javascript">
					addFancybox("image_<?php echo $image_i;?>");
					</script>
				</div>
			<?php
						}
						else{
			?>
                                <div class="image-large">
                                        <div>
     					<a href="http://static.inky.ws/image/<?php echo $image_i.$urlAdditional;?>/image.jpg" id="image_<?php echo $image_i;?>" target="_blank"><img src="http://static.inky.ws/image/<?php echo $image_i.$urlAdditional;?>/image.jpg" alt="uploaded image"/></a>
                                        </div>
                                        <span><?php echo date("M d, H:i T",$image_date);?></span>
                                        <span class='delete'>
                                        <?php if($gallery_owner == $session_id){ ?>
                                        <a href="/?p=deleteImage&amp;i=<?php echo $image_i;?>">Delete</a> &middot;
                                        <?php } ?>
                                        <a href="http://static.inky.ws/image/<?php echo $image_i.$urlAdditional;?>/image.jpg" target="_blank">Hotlink</a>
                                        </span>
                                </div>

			<?php
						}
					}
			?>
			</div>
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
		<h3 class="cont_head ghead"><div>Pastes</div> <?php
		if($gallery_owner == $session_id){
		?>
		<span class='addLink'><a href="/?p=addPaste&amp;i=<?php echo $gallery_id;?>">Add paste</a></span>
		<?php
		}
		?></h3>
		<div class="cont_body">
			<?php
				$q = $DB->query("SELECT `Index`,`Date`,`Filename`,`Syntax`,`Content` FROM `pastes` WHERE `GalleryID`='$gallery_id' ORDER BY `Index` ASC");
				if($DB->count($q)){
					while(list($paste_id,$paste_date,$paste_filename,$paste_syntax,$paste_content) = $DB->fetch($q)){
					  if($paste_filename != ""){
					    $paste_filename = " - $paste_filename";
					  }
			?>
			<div class="paste">
				<div class="pastehead"><a href='#p<?php echo $paste_id;?>' id='p<?php echo $paste_id;?>'>Paste #<?php echo $paste_id;?></a><?php echo $paste_filename; ?> - <?php echo $paste_syntax;?> - <a href="http://static.inky.ws/text/<?php echo $paste_id.$urlAdditional;?>">View Plaintext</a> - <a href="http://static.inky.ws/syn/<?php echo $paste_id.$urlAdditional;?>">Highlighted</a> - <a href="http://static.inky.ws/paste/<?php echo $paste_id.$urlAdditional;?>">Download</a></div>
				<?php
				$geshi=new GeSHi($paste_content,$paste_syntax);
				$geshi->enable_classes();
				$geshi->set_header_type(GESHI_HEADER_DIV);
				$geshi->enable_line_numbers(GESHI_NORMAL_LINE_NUMBERS);
				echo $geshi->parse_code();
				
				if($gallery_owner == $session_id){
			?>
				<div class='ctrl'>
					<a href="/?p=deletePaste&amp;i=<?php echo $paste_id;?>" class='delete'>Delete paste</a>
				</div>
			<?php
				}
				?>
			</div>
			<?php
					}
				}
				else{
			?>
				<span class='nothing'>None yet.</span>
			<?php	
				}

			?>
		</div>
	</div>

<?php
	}
?>
