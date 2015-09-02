<?php
	include "../sql.inc.php";
	include "../initdb.inc.php";
	include "../geshi.php";
	global $DB;
	if(!isset($_GET['i'])){
		exit();
	}
	$id = intval($_GET['i']);
	$paste_id=$id;
	$q=$DB->query("SELECT `Content`,`Date`,`Syntax`,`Filename`,`GalleryID` FROM `pastes` WHERE `Index` = '$id'");
	$r= $DB->fetch($q);
        $q2=$DB->query("SELECT `Private`,`Authkey` FROM `galleries` WHERE `Index`={$r[4]}");
        $r2=$DB->fetch($q2);
	$urlAdditional = "";
        if($r2[0] == "1" && $r2[1] != ""){
                if(!isset($_GET['a'])){
                        header("content-type: text/plain");
                        exit("Not authorized.");
                }
                else{
                        if(str_replace('/','',$_GET['a']) != $r2[1]){
                                header("content-type: text/plain");
                                exit("Not authorized.");
                        }
                }
		$urlAdditional="/{$r2[1]}";
        }
	$paste_filename = "";
	if($r[3] != "") $paste_filename = " - {$r[3]}";
	header("Last-Modified: ".gmdate("D, d M Y H:i:s",intval($r[1]))." GMT");
	header("Content-type: text/html");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

    <title>inky :: view paste</title>

    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />

    <link  href="http://static.inky.ws/favicon.ico" type="image/x-icon" rel="icon"/>

    <link href="http://static.inky.ws/combined.css" type="text/css" rel="stylesheet"/>

    <script type="text/javascript" src="http://static.inky.ws/combined.js"></script>

<link href='http://fonts.googleapis.com/css?family=Days+One|Titillium+Web:400,700' rel='stylesheet' type='text/css' />

</head>

<body>

	<div class="head block">

		<h1><a href='http://inky.ws/'>INKY.</a></h1>

		<h2>pastebin and image hosting</h2>

	</div>

    <div class="main">

	<div class="container block">

		<h3 class="cont_head">Paste</h3>

		<div class="cont_body">

			<div class="paste">

				<div class="pastehead">Paste #<?php echo $paste_id . $paste_filename;?> - <?php echo $r[2];?> - <a href="http://static.inky.ws/text/<?php echo $paste_id.$urlAdditional;?>">View Plaintext</a> - <a href="http://static.inky.ws/syn/<?php echo $paste_id.$urlAdditional;?>">Highlighted</a> - <a href="http://static.inky.ws/paste/<?php echo $paste_id.$urlAdditional;?>">Download</a></div>
				<?php
				$geshi=new GeSHi($r[0],$r[2]);
			        $geshi->enable_classes();
			        $geshi->set_header_type(GESHI_HEADER_DIV);
			        $geshi->enable_line_numbers(GESHI_NORMAL_LINE_NUMBERS);
			        echo $geshi->parse_code();
				?>
			</div>		
		</div>

	</div>



    </div>

    <div class="clear"></div>

    <div class="footer">

		&copy; 2011 Inky. All rights reserved.<br/>

		<a href="http://validator.w3.org/check?uri=referer">Valid XHTML</a> | <a href="http://jigsaw.w3.org/css-validator/check/referer">Valid CSS</a><br/>

		<a href="http://inky.ws/?p=legal">Privacy Policy / Legal</a> <br/>

		<a href="http://inky.ws/?p=contact">Contact</a>

    </div>

</body>

</html>
