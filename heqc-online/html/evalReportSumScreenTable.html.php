<input type="hidden" name="do_summary" value="0">
<input type="hidden" name="eval_id" value="0">
<input type="hidden" name="change_eval" value="0">

<?php 
echo '<table width=95% border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>';
$this->showInstitutionTableTop();
echo '<br><br>';
echo '<table width="95%" border=0 cellpadding="2" cellspacing="2">';
echo '<tr><td valign="top">';
echo "This screen indicates the evaluators' reports received and pending.<br><br><strong>Status of the evaluation reports:</strong><br><br>";
echo "<table border='1' cellpadding='2' cellspacing='2' align='center'>";
echo "<tr>";
echo "<td class='oncolourb' valign='top' align='center'>Evaluator</td>";
echo "<td class='oncolourb' valign='top' align='center'>Date Sent to Evaluator</td>";
echo "<td class='oncolourb' valign='top' align='center'>Date Received From Evaluator</td>";
echo "<td class='oncolourb' valign='top' align='center'>Report Link</td>";
echo "<td class='oncolourb' valign='top' align='center'>Completed<br><img src='images/check_mark.gif'> or <img src='images/cross_mark.gif'></td>";
echo "</tr>";
$this->formActions["next"]->actionMayShow = false;
$allComp = true;
$SQL = "DELETE FROM evalReport WHERE application_ref =? and evalReport_status_confirm=0";
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
$SQL = "SELECT * FROM evalReport WHERE application_ref =? and evalReport_status_confirm=1";

$sm = $conn->prepare($SQL);
$sm->bind_param("s", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID);
$sm->execute();
$rs = $sm->get_result();

//$rs = mysqli_query($SQL);
if (mysqli_num_rows($rs) > 0){
	while ($row = mysqli_fetch_array($rs)){
		$QQQ = "UPDATE evalReport SET eval_change_status=0 WHERE evalReport_id=".$row["evalReport_id"];
		$RRR = mysqli_query($QQQ);
		$name = $this->getValueFromTable("Eval_Auditors","Persnr",$row["Persnr_ref"],"Names")."&nbsp;".$this->getValueFromTable("Eval_Auditors","Persnr",$row["Persnr_ref"],"Surname");
		$tmpSettings = "DBINF_Institutions_application___application_id=".$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID."&DBINF_evalReport___evalReport_id=".$row["evalReport_id"];
		//the following was the previous javascript action when you click to view eval report.
		//<a href='javascript:document.all.CHANGE_TO_RECORD.value=\"evalReport|".$row["evalReport_id"]."\";goto(71);'>View Report</a>
		$compDate = ($row["evalReport_date_completed"] == "1970-01-01")?("-"):("<a href='javascript:winPrintEvalReportForm(\"Evaluation\",\"".$row["evalReport_id"]."\",\"".base64_encode($tmpSettings)."\",\"\");'>View Report</a>");
		$screenDate = ($row["evalReport_date_screen"] == "1970-01-01")?("-"):($row["evalReport_date_screen"]);
		if ($row["evalReport_completed"] == "0"){
			$img = "images/cross_mark.gif";
			$allComp = false;
		}else{
			$img = "images/check_mark.gif";
		}
		$name .= ($row["do_summary"]==1)?(" (Chair)"):("");
		echo "<tr>";
		echo "<td valign='top'>".$name;
//		if ($row["evalReport_completed"] == "0") echo "<br><a href='javascript: setEval(\"".$row["Persnr_ref"]."\");'>[Change Evaluator]</a>";
		echo "</td>";
		echo "<td valign='top' align='center'>".$row["evalReport_date_sent"]."</td>";
		echo "<td valign='top' align='center'>".$screenDate."</td>";
		echo "<td valign='top' align='center'>".$compDate."</td>";
		echo "<td valign='top' align='center'><img src='".$img."'></td>";
		echo "</tr>";
	}
}

echo "</table>";
$showSummaryLink = 0;
$summary_report_id = 0;
$completed_array = array();
$SQL = "SELECT do_summary, summary_done, application_sum_ref, Persnr_ref, evalReport_completed FROM evalReport WHERE application_ref =? and evalReport_status_confirm=1";

$sm = $conn->prepare($SQL);
$sm->bind_param("s", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID);
$sm->execute();
$rs = $sm->get_result();


//$rs = mysqli_query($SQL);
if (mysqli_num_rows($rs) > 0){
	while ($row = mysqli_fetch_array($rs)) {
		array_push($completed_array, $row["evalReport_completed"]);
		if (($row["summary_done"] == 1)) {
			$this->formActions["next"]->actionMayShow = true;
			$showSummaryLink = 1;
			$summary_report_id = $row["application_sum_ref"];
		}
		if ($this->getValueFromTable("evalReport", "evalReport_id", $this->dbTableInfoArray["evalReport"]->dbTableCurrentID, "Persnr_ref") == $row["Persnr_ref"]) {
			if ($row["do_summary"] == 1) {
				$show = false;
				foreach ($completed_array AS $value) {
					if ($value == 1) $show = true;
					else $value = false;
				}
				if ($show) $this->formActions["next"]->actionMayShow = true;
				if ($row["summary_done"] != 1) echo '<script>document.all.do_summary.value = 1;</script>';
			}
		}
	}
}

if ($showSummaryLink == 1) {
	echo '<br><br>';
	echo '<b>The summary of the evaluation reports is now finished.</b> ';
	//the following was the previous javascript action when you click to view summary report.
	//'<a href="javascript:document.all.do_summary.value=1;document.defaultFrm.CHANGE_TO_RECORD.value=\'application_summery_comments|'.$summary_report_id.'\';goto(72);">View Evaluation Summary</a>'
	$tmpSettings = "DBINF_Institutions_application___application_id=".$this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID."&DBINF_application_summery_comments___application_sum_id=".$summary_report_id;
	echo "<a href='javascript:winPrintEvalSumReportForm(\"Evaluation\",\"".$summary_report_id."\",\"".base64_encode($tmpSettings)."\",\"\");'>View Summary Report</a>";
}
echo '</td></tr>';
echo '</table>';
echo '</td></tr>';
echo '</table>';
?>
<script>
function setEval(id){
	document.defaultFrm.change_eval.value = id;
	document.defaultFrm.submit();
}
</script>
