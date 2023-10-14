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

$arr = array();
$this->formFields["application_ref"]->fieldValue = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
$this->formFields["eval_ref"]->fieldValue = $row[0];
$this->formFields["user_ref"]->fieldValue = $this->currentUserID;



$this->showInstitutionTableTop();
$this->showField('application_ref');
$this->showField('eval_ref');
$this->showField('user_ref');
?>
<br><Br>
Do you accept this evaluator's report:
<input type="checkbox" name="GRID_<?php echo $this->dbTableInfoArray["evalReport"]->dbTableCurrentID?>$evalReport_id$evalReport_accept$evalReport" value='1'>
<?php echo $this->showField('evalReport_accept');?> &nbsp; <i>(Tick to accept)</i>
<br><br>
Note that in order to reject this evaluator's report, you have to supply a comment below and click "Next".
<br><br>
Thank you for reading the evaluation, please make a comment about the evaluator, and then click "Next" to end the review.<br><br>
<?php 
$this->showField('comment');
?><br><br><br><br><br>
<br><br><br><br><br>
</td></tr></table>
</td></tr></table>
<script>
function checkComment(){
	if (document.defaultFrm.MOVETO.value == "next"){
		if (document.defaultFrm.FLD_comment.value == ""){
			alert("Please add a comment.");
			document.defaultFrm.MOVETO.value = "";
			return false;
		} else{
			if (document.defaultFrm.GRID_<?php echo $this->dbTableInfoArray["evalReport"]->dbTableCurrentID?>$evalReport_id$evalReport_accept$evalReport.checked){
				if (confirm("Please confirm that you accepted this report.")){
					return true;
				}else{
					return false;				
				}	
			}else{
				if (confirm("Please confirm that you reject this report")){
					return true;
				}else{
					return false;				
				}	
			}	
		}
		return false;
	}	
}
</script>
