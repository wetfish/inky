<?php
	if(isset($_POST['pass'])){
		if(md5($_POST['pass'])=="40f5349989409e9a4460f296a4f97279"){
			global $DB;
			$matches=array();
			preg_match("/^http\:\/\/inky.ws\/g\/([a-z0-9]+)$/",$_POST['url'],$matches);
			$gallery_id = base_convert($matches[1],36,10);
			if($_POST['expiry']>-1){
				$q=$DB->query("SELECT `SessionID` FROM `galleries` WHERE `Index` = '$gallery_id'");
				$r=$DB->fetch($q);
				$user_session = $r['SessionID'];
				$DB->query("INSERT INTO `bans` (`SessionID`,`Date`,`DateExpires`,`Reason`) ".
							"VALUES ('$user_session','".time()."','".$DB->esc($_POST['expiry'])."','".$DB->esc($_POST['reason'])."')");
			}
			$DB->query("DELETE FROM `images` WHERE `GalleryID` = '$gallery_id'");
			$DB->query("DELETE FROM `pastes` WHERE `GalleryID` = '$gallery_id'");
			$DB->query("DELETE FROM `galleries` WHERE `Index` = '$gallery_id'");
		}
		else{
			header("Location: /?p=401");
			exit();
		}
	}
	
	function title(){
		echo "inky :: administration";
	}
	function body(){
?>
<div class="container block">
	<h3 class="cont_head">Administration</h3>
</div>
<div class="container block">
	<h3 class="cont_head">Delete Gallery</h3>
	<div class="cont_body">
		<form action="./?p=admin" method="post">
			<table>
				<tr>
					<td class="label">Gallery URL</td>
					<td class="input"><input name="url"/></td>
				</tr>
				<tr>
					<td class="label">Ban Length</td>
					<td class="input">
						<select name="expiry">
							<?php
								$expiry=array(	"Don't ban"		=> -1,
												"Never" 		=> 0,
												"1 Day"			=> time()+60*60*24,
												"1 Week"		=> time()+60*60*24*7,
												"30 Days"		=> time()+60*60*24*30,
												"1 Year"		=> time()+60*60*24*365
								);
								foreach($expiry as $i=>$v){
							?>
							<option value="<?php echo $v;?>"><?php echo $i;?></option>
							<?php
								}
							?>
						</select>
					</td>
				</tr>
				<tr>
					<td class="label">Ban Reason</td>
					<td class="input"><input name="reason"/></td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td class="label">Admin Password</td>
					<td class="input"><input name="pass" type="password"/></td>
				</tr>
				<tr>
					<td class="label">&nbsp;</td>
					<td class="input"><input type="submit"/></td>
				</tr>
			</table>
		</form>
	</div>
</div>
<?php
	}
?>
