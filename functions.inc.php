<?php
	function abbr($str,$len){
		if(strlen($str)>$len){
			return substr($str,0,$len-3)."...";
		}
		else return $str;
	}
	function time_ago($time){
		$s = time()-$time;
		$m = 0;
		$h = 0;
		$d = 0;
		$str = "";
		while($s>=60*60*24){
			$d++;
			$s-=60*60*24;
		}
		while($s>=60*60){
			$h++;
			$s-=60*60;
		}
		while($s>=60){
			$m++;
			$s-=60;
		}
		if($d) $str.="{$d}d ";
		if($h) $str.="{$h}h ";
		if($m) $str.="{$m}m ";
		if($s) $str.="{$s}s ";
		$str.="ago";
		return $str;
	}
function sanitize_output($buffer)
{
    $search = array(
        '/\>[^\S ]+/s', //strip whitespaces after tags, except space
        '/[^\S ]+\</s', //strip whitespaces before tags, except space
        '/(\s)+/s'  // shorten multiple whitespace sequences
        );
    $replace = array(
        '>',
        '<',
        '\\1'
        );
  $buffer = preg_replace($search, $replace, $buffer);
    return $buffer;
}


function push_message($session_id,$message){
global $DB;
$session_id = $DB->esc($session_id);
$message = $DB->esc($message);
$DB->query("INSERT INTO `messages` (`SessionId`,`Message`) VALUES('$session_id','$message')");
}

function get_messages($session_id){
global $DB;
$session_id = $DB->esc($session_id);
$q = $DB->query("SELECT `Message`,`Index` FROM `messages` WHERE `SessionId`='$session_id' ORDER BY `Index` ASC");
if($DB->count($q)){
	$listType="ul";
	if($DB->count($q) > 1) $listType="ol";
	echo "<$listType class='msg'>";
	while(list($msg,$id)=$DB->fetch($q)){
		$msg = htmlentities($msg);
		echo "<li>$msg</li>";
		$DB->query("DELETE FROM `messages` WHERE `Index`='$id'");
	}
	echo "</$listType>";
}
}

function formatHits($hitcount,$date){
	$hovertext = "";
	$plural    = "";
	if($date < 1353204500){
		$hovertext = "title='Counted since November 17, 2012'";
	}
	if($hitcount != 1){
		$plural = "s";
	}
	echo "<span class='hitcounter' $hovertext>$hitcount view$plural.</span>";
}

?>
