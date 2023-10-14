<?php 
	$reaccred_id = $this->dbTableInfoArray["Institutions_application_reaccreditation"]->dbTableCurrentID;
?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
<td>
	<br>
	<?php echo $this->displayReaccredHeader ($reaccred_id);?>
	<br>

	<br>
	The assigned evaluators will now have access to the current application through the HEQC-online system. They will be able to upload their own reports, and the chairperson (if there is one) will be able to upload the final report.
	<br><br>
	The list below displays whether evaluators have (<img src="images/check_mark.gif">) or have not (<img src="images/cross_mark.gif">) uploaded their reports. You, as HEQC manager, are able to view these reports, and if necessary, upload reports.
	<br><br>
	To view the contact details of each evaluator, click on their name. The email address that appears is the login name of the evaluator, and all system-generated emails will be sent to this address.
	<br><br>
	<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">

<?php 
	//set this to NULL for now, we don't have an evalReport_id for the chairperson's row yet
	$chair_evalRpt_ID = "";

	echo "<tr>";
	echo "<td class='oncolourb' valign='top' align='center' width='20%'>Evaluator</td>";
	echo "<td class='oncolourb' valign='top' align='center' width='20%'>Date sent to evaluator</td>";
	echo "<td class='oncolourb' valign='top' align='center' width='20%'>Evaluator access until</td>";
	echo "<td class='oncolourb' valign='top' align='center' width='10%'>Date received from evaluator</td>";
	echo "<td class='oncolourb' valign='top' align='center' width='20%'>Report link</td>";
	echo "</tr>";

	$SQL = "SELECT * FROM evalReport WHERE reaccreditation_application_ref =? AND evalReport_status_confirm=1";

	$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
	if ($conn->connect_errno) {
	    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
	    printf("Error: %s\n".$conn->error);
	    exit();
	}

	$sm = $conn->prepare($SQL);
	$sm->bind_param("s", $reaccred_id);
	$sm->execute();
	$rs = $sm->get_result();


	//$rs = mysqli_query($SQL);
	if (mysqli_num_rows($rs) > 0){
		while ($row = mysqli_fetch_array($rs)){

			$evalReportDoc = new octoDoc($row['evalReport_doc']);
			$evalReportID = $row["evalReport_id"];
			$name = $this->getValueFromTable("Eval_Auditors","Persnr",$row["Persnr_ref"],"Names")."&nbsp;".$this->getValueFromTable("Eval_Auditors","Persnr",$row["Persnr_ref"],"Surname");
			$tmpSettings = "DBINF_Institutions_application_reaccreditation___Institutions_application_reaccreditation_id=".$reaccred_id."&DBINF_evalReport___evalReport_id=".$row["evalReport_id"];
			$reportLink  = "<a href='javascript:setID(\"".$row["evalReport_id"]."\");moveto(\"1641\");'><img border=\'0\' src=\"images/ico_print.gif\"></a> Upload/view report";
			$finalReportLink = "";
			$finalDone_img = "";
			$done_img = "";
			$finalReportLink = "";


			$compDate = $row["evalReport_date_completed"];
			$access_end = $this->getValueFromTable("Institutions_application_reaccreditation", "Institutions_application_reaccreditation_id", $reaccred_id, "evaluator_access_end_date");

			$done_img =  (($row["evalReport_completed"] == "0") || ($row["evalReport_completed"] == "1")) ? "&nbsp;<img src='images/cross_mark.gif'>" : "&nbsp;<img src='images/check_mark.gif'>";

			//if evaluator is chairperson, add final report link
			if ($row["do_summary"]==2) {
				$finalReportLink = "<br><a href='javascript:setID(\"".$row["evalReport_id"]."\");moveto(\"1651\");'><img border=\'0\' src=\"images/ico_print.gif\"></a> Upload/view final report";
				$finalDone_img =  ($this->getValueFromTable("evalReport", "evalReport_id", $row["evalReport_id"], "application_sum_doc") != 0) ? "&nbsp;<img src='images/check_mark.gif'>" : "&nbsp;<img src='images/cross_mark.gif'>";
			}

			$name .= ($row["do_summary"]==2)?(" (Chair)"):("");
			$chair_evalRpt_ID .= ($row["do_summary"]==2)? $row["evalReport_id"] : "";

			echo "<tr  class='onblue'>";
			echo "<td valign='top' align='left'>";
			echo ' <a href="javascript:winEvalContactDetails(\'Evaluator Contact Details\',\''.$row['Persnr_ref'].'\', \''.base64_encode($tmpSettings).'\', \'\');">';
			echo $name;
			echo "</a></td>";
			echo "<td valign='top' align='center'>".$row["evalReport_date_sent"]."</td>";
			echo "<td valign='top' align='center'>".$access_end."</td>";
			echo "<td valign='top' align='center'>".$compDate."</td>";
			echo "<td valign='top' align='left'>".$reportLink.$done_img.$finalReportLink.$finalDone_img."</td>";
			echo "</tr>";
		}
	}

?>

</table>

</td>
</tr>
</table>
<br>

<script>
function setID(val){
	document.defaultFrm.CHANGE_TO_RECORD.value='evalReport|'+val;
}

</script>



