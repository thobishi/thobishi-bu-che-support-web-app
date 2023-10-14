<head>
<link rel=STYLESHEET TYPE="text/css" href="../styles.css" title="Normal Style">
<title><?php echo $_GET["title"]?></title>
</head>
<body>
<br>

<?php 

$path = '../';
require_once ('/var/www/html/common/_systems/heqc-online.php');
$app = new HEQConline (1);
$str = base64_decode($_GET["workflow_settings"]);
$app->parseWorkFlowString($str);

        $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);

        if ($conn->connect_errno) {
            $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
            printf("Error: %s\n".$conn->error);
            exit();
        }
        
	$SQL  = "SELECT *, users.title_ref FROM Eval_Auditors, users WHERE Eval_Auditors.Persnr='".$_GET["userid"]."'";
	//$SQL .= " AND users.user_id = Eval_Auditors.user_ref";
        $SQL .= " AND users.name = Eval_Auditors.Names";
    
       // $sm = $conn->prepare($SQL);
      //  $sm->bind_param("s", $_GET["userid"]);
//	$sm->execute();
	//$rs = $sm->get_result();
	
	//echo $SQL;
	
	$rs = mysqli_query($conn, $SQL);

?>

<table border=0 width="95%" cellpadding="2" cellspacing="2" align="center">
	<tr>
		<td class="loud">
			Evaluator Contact Details
		</td>
	</tr>
	<tr>
		<td>

	<table border=0 width="95%" cellpadding="2" cellspacing="3" align="center">
	<tr><td>
	<?php
            while ($row = mysqli_fetch_array($rs)) {
//title ref from Eval_Auditors is not the same lookup as the one users table uses (lkp_title)
		$job_title 		= $row["Job_title"];
		$employer 		= $row["Empoloyer"];

		$position  = $job_title;
		$position .= (($job_title) && ($employer)) ? ", ".$employer : $employer;

		echo "<tr><td width='15%' class='onblueb' align='right'>";
		echo "Name: </td>";
		echo "<td class='oncolour'>";
		echo $app->getValueFromTable ("lkp_title", "lkp_title_id", $row['title_ref'], "lkp_title_desc")." ".$row['Names']." ".$row['Surname'];
		echo "</td>";
		echo "</tr>";

		echo "<tr><td class='onblueb' align='right'>";
		echo "Work number: </td>";
		echo "<td class='oncolour'>";
		echo $row['Work_Number'];
		echo "</td></tr>";

		echo "<tr><td class='onblueb' align='right'>";
		echo "Email: </td>";
		echo "<td class='oncolour'>";
		echo $row['E_mail'];
		echo "</td>";
		echo "</tr>";

		echo "<tr><td class='onblueb' align='right'>";
		echo "Position: </td>";
		echo "<td class='oncolour'>";
		echo $position;
		echo "</td>";
		echo "</tr>";

	}
	?>
	</td></tr>
	</table>
	<br><hr>
	</td></tr>
	</table>
</body>
