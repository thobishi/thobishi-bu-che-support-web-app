<?php 
/*
Rebecca
2007-08-23
Displays a list of applications that have been assigned to a specific AC meeting, along with their relevant documents.
*/

	$path="../";

	require_once ("/var/www/html/common/_systems/heqc-online.php");
	$dbConnect = new dbConnect();
	$app = new HEQConline (1);
	$audit_trail_id = readGET("audit_id");


?>
<title>Report</title>
<link rel=STYLESHEET TYPE="text/css" href="<?php echo $path?>styles.css" title="Normal Style">
<script language="JavaScript" src="../js/che.js"></script>
</head>
<body>
<table width="98%" cellspacing="2" cellpadding="2" align="center" bgcolor="#EAEFF5">
<tr><td colspan='6'>
<?php 
	$message = nl2br($app->getValueFromTable("workflow_audit_trail", "workflow_audit_trail_id", $audit_trail_id, "audit_text"));
	//$length = strlen($message);

	//$header_pos = strripos($message, "Body");
	//$header = substr($message, 0, $header_pos);
	//$header_length = strlen($header);

	//$body_pos = strripos($message, "Body")+19;  //trim the "Body:" from the main body of the email
	//$body = substr($message, $body_pos, $length);

	//$subject_pos = strripos($header, "Sent-");
	//$subject = trim(substr($header, 0, $subject_pos), "Subject"); //had to take away the "-" because it was cutting of "St" of "Status"

	//$sentBy = trim(substr($header, $subject_pos, $header_length), "Sent-");

	echo "<span class='loud'>HEQC-online system-generated email: </span>";
	echo "<br><hr>";
	//echo "Subject: ".$subject;
	//echo "<br>";
	//echo "Sent to: ".$sentBy;
	//echo "<br><hr>";
	echo $message;

?>
</td></tr></table>
</body>