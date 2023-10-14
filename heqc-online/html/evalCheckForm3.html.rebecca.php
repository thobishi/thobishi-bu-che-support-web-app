<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<?php $this->showInstitutionTableTop()?>
<br><br>
<center>The letter of appointment was sent out to each evaluator.</center>
<br><br>
<center><b>Indicate who of the following evaluators have accepted to take part in the programme evaluation.</b></center>
<br><br>
<table width="45%" border=0 align="center" cellpadding="2" cellspacing="2">
<?php 
$headingArray = array();
array_push($headingArray,"Evaluator");
array_push($headingArray,"Confirmed");

$refDispArray = array();
array_push($refDispArray,"Names");
array_push($refDispArray,"Surname");

$dispFields = array();
array_push($dispFields,"evalReport_status_confirm");

$ref = "";
if ($only_1_eval) {
	$ref = " AND Persnr_ref=".$only_1_eval." ";
	echo '<input type="hidden" name="eval_id" value="'.$only_1_eval.'">';
}
//$this->makeGRID("Eval_Auditors,evalReport",$refDispArray,"Persnr","Persnr_ref=Persnr".$ref." AND evalReport_status_confirm!=-1 AND application_ref=".$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID,"evalReport","evalReport_id","Persnr_ref","application_ref",$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID,$dispFields,$headingArray,"javascript:checkEvaluators();");
//$this->makeGRID("Eval_Auditors,evalReport",$refDispArray,"Persnr","Persnr_ref=Persnr".$ref." AND application_ref=".$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID,"evalReport","evalReport_id","Persnr_ref","application_ref",$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID,$dispFields,$headingArray,"javascript:checkEvaluators();", "", "", "", "", "", "contactEvaluator");
echo "Display list of chosen evaluators here.<br>Check that CHOOSE EVALS works.";
?>
<input type="hidden" name="rec_id">
<input type="hidden" name="contact_eval">
<script>
function makeContact (id) {
	document.defaultFrm.contact_eval.value=id;
}
function checkEvaluators(){
	var obj = document.defaultFrm;
	var docArrChecked = new Array();
	var docArr = new Array();
	var docArrCount = 0;
	var docArrCheckedCount = 0;
	for (i=0; i<obj.elements.length; i++) {
		if ((obj.elements[i].type == "radio") && obj.elements[i].checked) {
			docArr[docArrCount] = obj.elements[i];
			docArrCount++;
			if (obj.elements[i].value == 1) {
				docArrChecked[docArrCheckedCount]	= obj.elements[i];
				docArrCheckedCount++;
			}
		}
	}
	if (docArrChecked.length/docArr.length == 1 ) {
		showHideAction("next", true);
	}else {
		showHideAction("next", false);
	}
}
checkEvaluators();
</script>
<?php 

	$SQL = "SELECT Persnr_ref, evalReport_id, Names, Surname FROM `Eval_Auditors`, evalReport WHERE application_ref=? AND Persnr_ref=Persnr";
echo "<br><br>".$SQL;
echo "<br><br>Not setting application ref in evalReport";

	$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
	if ($conn->connect_errno) {
	    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
	    printf("Error: %s\n".$conn->error);
	    exit();
	}

	$sm = $conn->prepare($SQL);
	$sm->bind_param("s", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID);
	$sm->execute();
	$RS_evalReport = $sm->get_result();
	//$RS_evalReport = mysqli_query($SQL);
	while ($RS_evalReport && ($row=mysqli_fetch_array($RS_evalReport))) {
		echo "<li>".$row["Surname"].", ".$row["Names"]."</li><br>";
	}

?>
</table>
<br><br>
<center><b>If all evaluators have confirmed, click "Next".</b></center>
<br><br>
"Choose evaluators" should go back to a populated list of the choosing of evaluators.
<br>
MAYBE WE SHOULD LEAVE THIS CONFIRMATION OUT - KEEP IT SIMPLE... therefore added no condition to "Next" (in template)
<br>
<fieldset>
<legend>User is emailed at this point:</legend>
Dear Annatjie Erasmus,
<br><br>
Programme Name: Certificate in Adventure Tourism Management
<br>
NQF Level:Level 5
<br>
Institution:		 MSC PRIVATE COLLEGE
<br>
Reference Number: H/PR258/E017CAN
<br><br>
Your application for the Accreditation Phase Accreditation of the above programme has been received and accepted for the Accreditation Phase at the Accreditation and Coordination Directorate. For all queries cite the above reference number
</fieldset>

</td></tr></table>
