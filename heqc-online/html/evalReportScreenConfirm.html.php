<?php 
$this->showInstitutionTableTop();
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
?>
<table width=95% border=0 align="center" cellpadding="2" cellspacing="2"><tr><td><br><br>
<table width="95%" border=0 cellpadding="2" cellspacing="2"><tr><td><span class="specialb">Summary of <?php echo $name?>'s report:</span></td></tr>
<?php if ($this->formFields["evalReport_accept"]->fieldValue){?>
<tr><td>Overall compliance suggested by the evaluator:<strong> <?php echo $avg?>?</strong></td></tr>
<tr><td>Thank you for reading this report. Please, click "Next" to continue.</td></tr>
<?php 
$this->formFields["evalReport_date_completed"]->fieldValue = date("Y-m-d");
$this->showField('evalReport_date_completed');

$this->formFields["evalReport_completed"]->fieldValue = "1";
$this->showField('evalReport_completed');
}else{
?>
<tr><td>The evaluator's report has been rejected. Please, click "Next" to notify the evaluator, in order for him/her to re-evaluate the application.</td></tr>
<tr><td>&nbsp;</td></tr>
<?php }?>

</table>
</table>

