<?php 
/*
Rebecca
2007-09-26
Displays a comment made on an application
*/

	$path="../";

	require_once ("/var/www/html/common/_systems/heqc-online.php");
	$dbConnect = new dbConnect();
	$app = new HEQConline (1);
	$item_id = readGET("item_id");
	$id_name = readGET("id_name");
	$table = readGET("table");
	$return_field = readGET("return_field");

?>
<title>Report</title>
<link rel=STYLESHEET TYPE="text/css" href="<?php echo $path?>styles.css" title="Normal Style">
<script language="JavaScript" src="../js/che.js"></script>
</head>
<body bgcolor="#EAEFF5">
<table width="98%" cellspacing="2" cellpadding="2" align="center">
<tr><td colspan='6'>
<?php 
	$comment = simple_text2html($app->getValueFromTable($table, $id_name, $item_id, $return_field));
	echo "<span class='loud'>HEQC-online system comment: </span>";
	echo "<br><hr>";
	     mysqli_set_charset($comment,"utf8");	
	echo $comment;

?>
</td></tr></table>
</body>
