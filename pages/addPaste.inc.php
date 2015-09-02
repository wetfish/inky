<?php
	function title(){
		echo "inky :: add paste";
	}
	function body(){	
		global $DB,$session_id,$session_id_esc;
		if(!isset($_GET['i'])){
			header("Location: /?p=404");
			exit();
		}
	
		$gallery_id = $_GET['i'];
	
		$q = $DB->query("SELECT * FROM `galleries` WHERE `Index`='$gallery_id'");
		if(!$DB->count($q)){
			header("Location: /?p=404");
			exit();
		}
		list($gallery_id,$gallery_name,$gallery_date,$gallery_exp,$gallery_owner) = $DB->fetch($q);
		if($gallery_name == "") $gallery_name = "Untitled";
		if($gallery_owner != $session_id){
			header("Location: /?p=401");
			exit();
		}
?>
	<div class="container block">
		<h3 class="cont_head">
			Add Paste to Gallery: <?php echo $gallery_name;?>
		</h3>
		<div class="cont_body">
			<?php
				if($gallery_owner == $session_id){
			?>
			<form action="/?p=addPaste2" method="post" class="editForm" id="editForm">
				<input type="hidden" name="index" value="<?php echo $gallery_id;?>"/>
				<table>
					<tr>
						<td class='label'>Syntax</td>
						<td class='input'>
							<select name="language" id="language">
								<option>None</option>
							<?php
								if (!($dir = @opendir(dirname(__FILE__) . '/geshi'))) {
									if (!($dir = @opendir(dirname(__FILE__) . '/../geshi'))) {
										echo '<option>No languages available!</option>';
									}
								}
								$languages = array();
								while ($file = readdir($dir)) {
									if ( $file[0] == '.' || strpos($file, '.', 1) === false) {
										continue;
									}
									$lang = substr($file, 0,  strpos($file, '.'));
									$languages[] = $lang;
								}
								closedir($dir);
								sort($languages);
								foreach ($languages as $lang) {
									if (isset($_POST['language']) && $_POST['language'] == $lang) {
										$selected = 'selected="selected"';
									} else {
										$selected = '';
									}
										echo '<option value="' . $lang . '" '. $selected .'>' . $lang . "</option>\n";
									}
?>
</select>
						</td>
					</tr>
					<tr>
						<td class='label'>Filename</td>
						<td class='input'>
							<input size="32" name="filename"/> (Optional)
						</td>
					</tr>
					<tr>
						<td class='label'>Text</td>
						<td class='input'>
							<textarea 	rows="20" cols="65" name="content"
										onkeydown="return insertTab(event,this);" 
										onkeyup="return insertTab(event,this);" 
										onkeypress="return insertTab(event,this);">
							</textarea>
						</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>
							<input type="submit" name="submit" value="Save Paste"/>
						</td>
					</tr>
				</table>
			</form>
		<?php
				}
			?>
		</div>
	</div>
<?php
	}
?>
