<?php 
/*
Rebecca
2007-09-26
Displays a comment made on an contract
*/

	$path="../";

	require_once ("_systems/contract/contract.php");
	$dbConnect = new dbConnect();
	$app = new contractRegister (1);
	$item_id = readGET("item_id");
	$id_name = readGET("id_name");
	$table = readGET("table");
	$return_field = readGET("return_field");

?>
<title>Report</title>
<link rel=STYLESHEET TYPE="text/css" href="<?php echo $path?>styles.css" title="Normal Style">
<script language="JavaScript" src="../js/che.js"></script>
</head>
<body bgcolor="#efe9e5">
<table width="98%" cellspacing="2" cellpadding="2" align="center">
<tr><td colspan='6'>
<?php 
	$comment = simple_text2html($app->getValueFromTable($table, $id_name, $item_id, $return_field));
	echo "<span class='loud'>Contract Register system comment: </span>";
	echo "<br><hr>";
	echo $comment;

?>
</td></tr></table>
</body>
