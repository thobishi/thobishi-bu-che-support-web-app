<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<?php $this->showInstitutionTableTop ()?>
<br>
<input type="hidden" name="decline" value="0">
<table><tr>
	<td>
<?php 
$SQL = "SELECT * FROM evalReport WHERE application_ref =? and evalReport_status_confirm=1";
$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();
}

$sm = $conn->prepare($SQL);
$sm->bind_param("s", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID);
$sm->execute();
$RS = $sm->get_result();

//$RS = mysqli_query($SQL);
$decline = false;
$answer = false;
while ($RS && ($row=mysqli_fetch_array($RS))) {
	if ($row["accept_summary"] == 1) {
		$decline = true;
		$answer = true;
?>
	<table><tr>
		<td><b>Your summary report has been declined by <?php echo $this->getValueFromTable("Eval_Auditors", "Persnr", $row["Persnr_ref"], "Names")." ".$this->getValueFromTable("Eval_Auditors", "Persnr", $row["Persnr_ref"], "Surname");?> with the following reason(s):</b></td>
	</tr><tr>
		<td><?php echo nl2br($row["decline_reason"])?></td>
	</tr></table>
	<br><br>
	<script>
		document.all.decline.value = 1;
	</script>
<?php 
	}
	if ($row["accept_summary"] == 2) {
		$answer = true;
?>
	<table><tr>
		<td><b>Your summary report has been accepted by <?php echo $this->getValueFromTable("Eval_Auditors", "Persnr", $row["Persnr_ref"], "Names")." ".$this->getValueFromTable("Eval_Auditors", "Persnr", $row["Persnr_ref"], "Surname");?>.</b></td>
	</tr></table>
	<br><br>
<?php 
	}
}

if (! $answer ) {
		$this->formActions["next"]->actionMayShow = false;
?>
	<table><tr>
		<td>&nbsp;</td>
	</tr><tr>
		<td><b>No one has excepted or declined yet. Click on "Home" to return to your work tasks</b></td>
	</tr></table>
	<br><br>
<?php 
}


if ($decline) {
	echo '<b>The summary of evaluations will now be given back to you.</b>';
}
?>
	</td>
</tr><tr>
	<td>&nbsp;</td>
</tr><tr>
	<td></td>
</tr></table>
</td></tr></table>