<?php
//exit("down for maintenance");
include "functions.inc.php";
include "sql.inc.php";
include "geshi.php";

include "initdb.inc.php";
global $DB;

include "session.inc.php";
include "page.inc.php";
include "nav.inc.php";
include "login.inc.php";

include "ad_if_guest.inc.php";

ob_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title><?php title(); ?></title>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <meta name="keywords" content="pastebin,gallery,image hosting,image upload, free image hosting, free image upload, image gallery,free image gallery"/>
    <meta name="description" content="Combined Pastebin and Image Hosting"/>
    <link  href="http://static.inky.ws/favicon.ico" type="image/x-icon" rel="icon"/>
    <link href="http://static.inky.ws/combined.css" type="text/css" rel="stylesheet"/
    <script type="text/javascript" src="http://static.inky.ws/combined.js"></script>
    <link href='http://fonts.googleapis.com/css?family=Days+One|Titillium+Web:400,700' rel='stylesheet' type='text/css'/>
	<?php if(function_exists('head')) head(); ?>
</head>
<body>
	<div class="head block">
		<div>
		<h1><a href="/">INKY.</a></h1>
		<h2>pastebin and image hosting</h2>
		</div>
		<?php if(function_exists('head_inject')) head_inject(); ?>
		<div class='clear'></div>
	</div>
    <div class="main">
		<?php body();?>
    </div>
    <div class="clear"></div>
    <div class="footer">
		&copy; <?php echo date('Y');?> You. <img src=""><br/>
		<a href="https://github.com/wetfish/inky">GitHub</a> |
		<a href="http://validator.w3.org/check?uri=referer">Valid XHTML</a> | <a href="http://jigsaw.w3.org/css-validator/check/referer">Valid CSS</a><br/>
		<a href="/?p=legal">Privacy Policy / Legal</a> <br/>
		<a href="/?p=contact">Contact</a>
    </div>
</body>
</html>
<?php
	$tidy=new tidy();
	$tidy->ParseString(sanitize_output(ob_get_clean()),array('output-xhtml'=>true,'wrap'=>256),'utf8');
	$tidy->cleanRepair();
	echo tidy_get_output($tidy);
?>
