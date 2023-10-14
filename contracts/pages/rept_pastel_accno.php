<?php 
/*
Robin
2008-10-27
Displays a popup report of pastel account numbers and descriptions.
*/

	$path="../";

	require_once ("_systems/contract/contract.php");
	$dbConnect = new dbConnect();
	$app = new contractRegister (1);
	$ecrit = readGET("ecrit");

?>
<title>Pastel Accounts Report</title>
<link rel=STYLESHEET TYPE="text/css" href="<?php echo $path?>styles.css" title="Normal Style">
<script language="JavaScript" src="../js/che.js"></script>
</head>
<body bgcolor="#efe9e5">
<table width="98%" cellspacing="2" cellpadding="2" align="center">
	<tr>
	<td width="25%">Pastel Account number</td>
	<td width="25%">Description</td>
	<td width="25%">Date</td>
	<td width="25%">Amount</td>
	</tr>
<?php 
	$where = $app->build_where_criteria($ecrit);
	$sql = <<<SQL
	SELECT AccNumber, Description, DDate, Amount
	FROM pastel_ledger_transactions
	WHERE $where
SQL;
	$rs = mysqli_query($sql) or die(mysqli_error());
	while ($row = mysqli_fetch_array($rs)){
?>
		<tr>
			<td><?php echo echo $row["AccNumber"]; ?></td>
			<td><?php echo echo $row["Description"]; ?></td>
			<td><?php echo echo $row["DDate"]; ?></td>
			<td><?php echo echo $row["Amount"]; ?></td>
		</tr>
<?php 	
	}
?>
</td></tr></table>
</body>
