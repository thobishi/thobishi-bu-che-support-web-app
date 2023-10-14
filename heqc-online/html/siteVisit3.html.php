<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<?php $this->showInstitutionTableTop()?>
<table width="75%" border=0  cellpadding="2" cellspacing="2">
<tr>
	<td width="40%" align="right" valign="top"><b>Site Name:</b> </td>
	<td class="oncolour" valign="top" width="60%"><?php echo $this->getValueFromTable("institutional_profile_sites", "institutional_profile_sites_id", $this->getValueFromTable("siteVisit", "siteVisit_id", $this->dbTableInfoArray[$this->dbTableCurrent]->dbTableCurrentID, "site_ref"), "location")?></td>
</tr></table>
<br><br>
<b>Indicate who of the following evaluators have accepted to take part in the site visit evaluation.
<br>If you have not received a reply or if the proposed evaluator cannot do the site visit, click on the appropriate button  to continue the process.</b>
<br><br>
<table width="45%" border=0 align="center" cellpadding="2" cellspacing="2">
<?php 

$SQL = "UPDATE evalReport SET evalReport_status_confirm=-1 WHERE application_ref =? and evalReport_status_confirm=0";

$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();
}

$sm = $conn->prepare($SQL);
$sm->bind_param("s", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID);
$sm->execute();
$rs = $sm->get_result();

//$rs = mysqli_query($SQL);

$headingArray = array();
array_push($headingArray,"Evaluator");
array_push($headingArray,"Confirmed");

$refDispArray = array();
array_push($refDispArray,"Names");
array_push($refDispArray,"Surname");

$dispFields = array();
array_push($dispFields,"eval_site_visit_status_confirm");

$this->makeGRID("Eval_Auditors,evalReport",$refDispArray,"Persnr","Persnr=Persnr_ref AND do_sitevisit_checkbox=1 AND evalReport_status_confirm=1 AND application_ref=".$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID,"evalReport","evalReport_id","Persnr_ref","application_ref",$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID,$dispFields,$headingArray,"javascript:checkEvaluators();", 10, 40, 4, "","changeEvaluator", "contactEvaluator");
?>
<input type="hidden" name="change_eval">
<input type="hidden" name="contact_eval">
<script>
function changeEvalID (id) {
	document.defaultFrm.change_eval.value=id;
}
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
</script>
</table>
<br><br>
<center><b>If all evaluators have confirmed, start the logistic arrangements for the visit, by clicking "Next".</b></center>
<br><br>
</td></tr></table>
