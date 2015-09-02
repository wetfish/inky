<?php
$id = base_convert(intval($_GET['id']),10,36);
header("Location: /g/$id");
//echo $id;
exit();
?>

