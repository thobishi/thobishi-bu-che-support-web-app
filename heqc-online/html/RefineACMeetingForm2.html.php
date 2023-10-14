<script>

function openReportWin(page){
	var width = 392;
	var height = 234;
	var left = (screen.width-width)/2;
	var top = (screen.height-height)/3;
	hlpWidth = (screen.width);
	var win = open(page,'null', "scrollbars=yes, toolbar=no, status=no, menubar=no, width="+width+", height="+height+", left="+left+", top="+top+"");
}
</script>	
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<br>
<table width="100%" border=0 align="center" cellpadding="2" cellspacing="2"><tr>
<td>
This AC Meeting is scheduled for: <strong><?php echo $this->formFields["ac_start_date"]->fieldValue ?></strong><br><br>
It is necessary to prepare the supporting documentation:<br><br>
The following applications will be discussed on the AC Meeting:<br><br>
<table cellpadding="2" cellspacing="2" border="1" align="center">
<tr>
<td valign="top" class="oncolourb" align="center">#</td>
<td valign="top" class="oncolourb" align="center">INSTITUTION</td>
<td valign="top" class="oncolourb" align="center">PROGRAMME</td>
<td valign="top" class="oncolourb" align="center">REFERENCE</td>
<td valign="top" class="oncolourb" align="center">PAPER EVALUATION REPORT</td>
<td valign="top" class="oncolourb" align="center">SITE VISIT REPORT</td>
</tr>
<tr>
<?php 
$i=1;
$SQL = "UPDATE Institutions_application SET AC_Meeting_ref=? WHERE application_status=1 and AC_Meeting_ref=0";
$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();
}

$sm = $conn->prepare($SQL);
$sm->bind_param("s", $this->dbTableInfoArray["AC_Meeting"]->dbTableCurrentID);
$sm->execute();
$rs = $sm->get_result();


//$rs = mysqli_query($SQL);
$SQL = "SELECT application_id,HEI_name,CHE_reference_code,program_name,HEI_id FROM Institutions_application,HEInstitution WHERE HEI_id=institution_id and application_status=1 and AC_Meeting_ref=? ORDER BY HEI_name,program_name";

$sm = $conn->prepare($SQL);
$sm->bind_param("s", $this->dbTableInfoArray["AC_Meeting"]->dbTableCurrentID);
$sm->execute();
$rs = $sm->get_result();

//$rs = mysqli_query($SQL);
while ($row = mysqli_fetch_array($rs)){
	$tmpSettings = "PREV_WORKFLOW=36%7C213&DBINF_HEInstitution___HEI_id=".$row["HEI_id"]."&DBINF_Institutions_application___application_id=".$row["application_id"];
	?>
	<td valign="top" width="5%" align="center"><?php echo $i?></td>
	<td valign="top" width="15%"><?php echo $row["HEI_name"]?>&nbsp;</td>
	<td valign="top" width="15%"><?php echo $row["program_name"]?>&nbsp;</td>
	<td valign="top" width="15%"><a href="javascript:winPrintApplicationForm('Application Form','<?php echo $row["application_id"]?>','<?php echo base64_encode($tmpSettings)?>','');"><?php echo $row["CHE_reference_code"]?></a></td>
	<td valign="top" width="25%"><a href="pages/displayReport.php?type=paperEval&id=<?php echo $row["application_id"]?>" target="_blank">View Report</a> (Once you have read the report, close the pop-up window)</td>
	<td valign="top" width="25%"><a href="pages/displayReport.php?type=site&id=<?php echo $row["application_id"]?>" target="_blank">View Report</a> (Once you have read the report, close the pop-up window)</td></tr>
	<?php 
	$i++;
} ?>
</table>
<br><br>
General comments about all programmes: <i>(optional)</i><br><br>
<?php echo $this->makeLink('general_programme_comments') ?>

<br><br>
Final Evaluation report: <a href="javascript: openReportWin('pages/generateReoprt.php?type=paperEval&ddate=<?php echo $this->formFields["ac_start_date"]->fieldValue ?>&id=<?php echo $this->dbTableInfoArray["AC_Meeting"]->dbTableCurrentID ?>');">Generate Report</a><br><br>
<?php $this->makeLink('paper_eval_doc') ?>
<br><br>
Site visit report: <a href="javascript: openReportWin('pages/generateReoprt.php?type=SiteVisits&ddate=<?php echo $this->formFields["ac_start_date"]->fieldValue?>&id=<?php echo $this->dbTableInfoArray["AC_Meeting"]->dbTableCurrentID?>');">Generate Report</a><br><br>
<?php $this->makeLink('site_visit_doc') ?>
<br><br>
Agenda: <a href="javascript: openReportWin('pages/generateReoprt.php?type=agenda&ddate=<?php echo $this->formFields["ac_start_date"]->fieldValue?>&id=<?php echo $this->dbTableInfoArray["AC_Meeting"]->dbTableCurrentID?>');">Generate Agenda</a><br><br>
<?php $this->makeLink('agenda_doc')?>
<br><br>
Minutes of Previous AC Meeting:<br><br>
<?php $this->makeLink('prev_minutes_doc')?>
<br><br>
Original submissions (to be ready at the meeting venue)
<br><br>
</td>
</tr></table>
</td></tr></table>
<script>
function checkFiles(){
	if (document.defaultFrm.MOVETO.value == "next"){
		if (document.defaultFrm.FLD_paper_eval_doc.value == "0"){
			alert("Please upload the paper evaluation file for the AC Meeting");
			document.defaultFrm.MOVETO.value = "";
			return false;
		}else if (document.defaultFrm.FLD_site_visit_doc.value == "0"){
			alert("Please upload the site visit file for the AC Meeting");
			document.defaultFrm.MOVETO.value = "";
			return false;
		}else if (document.defaultFrm.FLD_agenda_doc.value == "0"){
			alert("Please upload the agenda file for the AC Meeting");
			document.defaultFrm.MOVETO.value = "";
			return false;
		} else{
		return true;	
//		alert(document.defaultFrm.FLD_paper_eval_doc.value);
		//return false;

		}
	}	
}
</script>
