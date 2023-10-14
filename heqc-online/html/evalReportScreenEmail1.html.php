<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<table width="75%" border=0  cellpadding="2" cellspacing="2">
<tr><td>
<?php 
$SQL = "SELECT Persnr_ref FROM evalReport WHERE evalReport_id =? AND evalReport_status_confirm=1";

$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();
}

$sm = $conn->prepare($SQL);
$sm->bind_param("s", $this->dbTableInfoArray["evalReport"]->dbTableCurrentID);
$sm->execute();
$rs = $sm->get_result();

//$rs = mysqli_query($SQL);
$row = mysqli_fetch_array($rs);
$name = $this->getValueFromTable("Eval_Auditors","Persnr",$row["Persnr_ref"],"Names")."&nbsp;".$this->getValueFromTable("Eval_Auditors","Persnr",$row["Persnr_ref"],"Surname");
$this->showInstitutionTableTop();
if ($this->formFields["evalReport_accept"]->fieldValue) {
?>
</td></tr>
<tr><td>Please send an e-mail to the evaluator acknowledging the receipt of the report.<br><br>The following e-mail will be sent to <?php echo $name?>:</td></tr>
<tr><td>
<?php 
$this->showEmailAsHTML("evalReportScreenEmail1", "Eval_Report_accept");
}else{
?>
</td></tr>
<tr><td>Please send an e-mail to the evaluator informing him/her that the evaluation has been rejected.<br><br>The following e-mail will be sent to <?php echo $name?>:</td></tr>
<tr><td>
<?php 
$this->showEmailAsHTML("evalReportScreenEmail1", "Eval_Report_reject");
}?>
</td></tr>

</table>
</td></tr></table>

