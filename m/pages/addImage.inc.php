<?php
	function title(){
		echo "inky :: add image";
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
	<script type="text/javascript">
		var nFields = 1;
		function addField(){
			var submitElement = document.getElementById('submit');
			var prototype     = document.getElementById('img0');
			var newElement    = document.createElement('tr');
			newElement.id = "img"+nFields;
			nFields++;
			newElement.innerHTML = prototype.innerHTML;
			newElement.getElementsByTagName('input')[0].value='';
			newElement.getElementsByTagName('td')[0].innerHTML='Image '+nFields;
			submitElement.parentNode.insertBefore(newElement,submitElement);
		}
		function delField(){
			if(nFields<2) return;
			var element = document.getElementById('formtable').getElementsByTagName('tr')[nFields-1];
			element.parentNode.removeChild(element);
			nFields--;
		}
	</script>
	<div class="container block">
		<h3 class="cont_head">
			Add Image to Gallery: <?php echo $gallery_name;?>
		</h3>
		<div class="cont_body">
			<?php
				if(isset($_GET['msg'])){
			?>
				<span class='msg'>
			<?php
				$msgs=array("saved" => "Changes saved.");
				if(isset($msgs[$_GET['msg']])){
					echo $msgs[$_GET['msg']];
				}
				else{
					echo $_GET['msg'];
				}
				}
			?>
				</span>
			<?php
				if($gallery_owner == $session_id){
			?>
			<form action="/?p=addImage2" method="post" enctype="multipart/form-data" class="editForm" id="editForm">
				<input type="hidden" name="index" value="<?php echo $gallery_id;?>"/>
				<table id="formtable">
					<tr id="img0">
						<td class='label'>Image 1</td>
						<td class='input'>
							<input name="image[]" type="file"/>
						</td>
					</tr>
					<tr id="submit">
						<td>&nbsp;</td>
						<td>
							<input type="submit" name="submit" value="Begin Upload"/> 
							<input type="button" onclick="addField(); return false;" value="More files"/> 
							<input type="button" onclick="delField(); return false;" value="Less files"/>
						</td>
					</tr>
				</table>
				<div style="font-size:small;margin-top:10px">Maximum size 2MB</div>
			</form>
		<?php
				}
			?>
		</div>
	</div>
<?php
	}
?>
