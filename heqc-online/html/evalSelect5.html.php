<?php
	$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	$app_proc_id = $this->dbTableInfoArray["ia_proceedings"]->dbTableCurrentID;
	// Temporary for cleanup of 'old' applications that have no proceedings record or reference to it in active_processes
	if ($app_proc_id == 'NEW'){
		$prog = $this->getValueFromTable("Institutions_application", "application_id", $app_id, "CHE_reference_code");
		die("<br><br>Programme: $prog is missing its proceedings reference.  Please inform Octoplus.");
	}
	$proc_type = $this->getValueFromTable("ia_proceedings","ia_proceedings_id",$app_proc_id,"lkp_proceedings_ref");
?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
<?php
		$this->showInstitutionTableTop ();
?>
	</td>
</tr>
<tr>
	<td>
		<br>
<?php
		// 2017-07-25 Richard: Included conditional re-accred
		if (($proc_type == '4')||($proc_type == '6')){   // Conditions evaluation - display format for conditions
			$helptext = 'The assigned evaluators will now have access to the current application through the HEQC-online system. They will be able 
			to indicate whether the conditions have been met or not.
			The list below displays whether evaluators have (<img src="images/check_mark.gif">) or have not (<img src="images/cross_mark.gif">) 
			evaluated the conditions. You, as HEQC manager, are able to access the conditions met form.
			To view the contact details of each evaluator, click on their name. The email address that appears is the login name of the evaluator, 
			and all system-generated emails will be sent to this address.';
		} else {
			$helptext = 'The assigned evaluators will now have access to the current application through the HEQC-online system. They will be able 
			to upload their own reports, and the chairperson (if there is one) will be able to upload the final report.
			The list below displays whether evaluators have (<img src="images/check_mark.gif">) or have not (<img src="images/cross_mark.gif">) 
			uploaded their reports. You, as HEQC manager, are able to view these reports, and if necessary, upload reports.
			To view the contact details of each evaluator, click on their name. The email address that appears is the login name of the evaluator, 
			and all system-generated emails will be sent to this address.';
		}
		
		echo $helptext;
?>
		<br><br>
		<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">

<?php

        $conn= $this->getDatabaseConnection();
			//set this to NULL for now, we don't have an evalReport_id for the chairperson's row yet
			$chair_evalRpt_ID = "";

			echo "<tr>";
			echo "<td class='oncolourb' valign='top' align='center' width='20%'>Proceeding</td>";
			echo "<td class='oncolourb' valign='top' align='center' width='20%'>Evaluator</td>";
			echo "<td class='oncolourb' valign='top' align='center' width='15%'>Date sent to evaluator</td>";
			echo "<td class='oncolourb' valign='top' align='center' width='15%'>Evaluator access until</td>";
			echo "<td class='oncolourb' valign='top' align='center' width='10%'>Date received from evaluator</td>";
			echo "<td class='oncolourb' valign='top' align='center' width='20%'>Report link</td>";
			echo "<td class='oncolourb' valign='top' align='center' width='15%'>May evaluators view this report</td>";
			echo "</tr>";
			
			// 2017-07-25 Richard: Included conditional re-accred
			if (($proc_type == '4')||($proc_type == '6')){
				$this->showField('condition_complete_ind');
			}

			//$SQL = "SELECT * FROM evalReport WHERE application_ref =".$app_id." AND evalReport_status_confirm=1";
			$SQL = "SELECT * FROM evalReport WHERE ia_proceedings_ref =$app_proc_id AND evalReport_status_confirm=1";

		//	$conn = $this->getDatabaseConnection();
		//	$sm = $conn->prepare($SQL);
			//$sm->bind_param("s", $app_proc_id);
//$sm->execute();
		//	$rs = $sm->get_result();
			//echo "$SQL";
			$rs = mysqli_query($conn, $SQL);
			if (mysqli_num_rows($rs) > 0){
				while ($row = mysqli_fetch_array($rs)){
					$name = $this->getValueFromTable("Eval_Auditors","Persnr",$row["Persnr_ref"],"Names")."&nbsp;".$this->getValueFromTable("Eval_Auditors","Persnr",$row["Persnr_ref"],"Surname");
					$tmpSettings = "DBINF_Institutions_application___application_id=".$app_id."&DBINF_evalReport___evalReport_id=".$row["evalReport_id"];
					$compDate = $row["evalReport_date_completed"];
					//$access_end = $this->getValueFromTable("Institutions_application", "application_id", $app_id, "evaluator_access_end_date");
					$access_end = $this->getValueFromTable("ia_proceedings", "ia_proceedings_id", $app_proc_id, "evaluator_access_end_date");
					$proc_description = $this->getValueFromTable("lkp_proceedings","lkp_proceedings_id",$proc_type,"lkp_proceedings_desc");
					$view_by_eval = $this->getValueFromTable("lkp_yes_no", "lkp_yn_id", $row["view_by_other_eval_yn_ref"], "lkp_yn_desc");

					$finalReportLink = "";
					$finalDone_img = "";
					$done_img = "";
					$finalReportLink = "";
					
					// 2017-07-25 Richard: Included conditional re-accred
					if (($proc_type == '4')||($proc_type == '6')){
						$cond_complete = $this->getValueFromTable("ia_proceedings", "ia_proceedings_id", $app_proc_id, "condition_complete_ind");
						$plink = $this->scriptGetForm ('ia_proceedings', $app_proc_id, '_condForm_evalmanage');
						$reportLink = "<a href='".$plink."'><img border=\'0\' src=\"images/ico_print.gif\"></a> Edit conditions met";
						$done_img =  ($cond_complete == "0") ? "&nbsp;<img src='images/cross_mark.gif'>" : "&nbsp;<img src='images/check_mark.gif'>";
						$rl = $reportLink.$done_img;
					} else {
						//$evalReportDoc = new octoDoc($row['evalReport_doc']);
						$evalReportID = $row["evalReport_id"];
						//$reportLink  = "<a href='javascript:setID(\"".$row["evalReport_id"]."\");moveto(\"1053\");'><img border=\'0\' src=\"images/ico_print.gif\"></a> Upload/view report";
						$plink = $this->scriptGetForm ('evalReport', $row["evalReport_id"], '_startLoadEvalReport');
						$reportLink = "<a href='".$plink."'><img border=\'0\' src=\"images/ico_print.gif\"></a> Upload/view report";
						$done_img =  (($row["evalReport_completed"] == "0") || ($row["evalReport_completed"] == "1")) ? "&nbsp;<img src='images/cross_mark.gif'>" : "&nbsp;<img src='images/check_mark.gif'>";
						//if evaluator is chairperson, add final report link
						if ($row["do_summary"]==2) {
							//$finalReportLink = "<br><a href='javascript:setID(\"".$row["evalReport_id"]."\");moveto(\"1123\");'><img border=\'0\' src=\"images/ico_print.gif\"></a> Upload/view final report";
							$clink = $this->scriptGetForm ('evalReport', $row["evalReport_id"], '1123');
							$finalReportLink = "<br><a href='".$clink."'><img border=\'0\' src=\"images/ico_print.gif\"></a> Upload/view final report";
							
							//$finalDone_img =  ($this->getValueFromTable("evalReport", "evalReport_id", $row["evalReport_id"], "application_sum_doc") != 0) ? "&nbsp;<img src='images/check_mark.gif'>" : "&nbsp;<img src='images/cross_mark.gif'>";
							$finalDone_img =  ($row["application_sum_doc"] != 0) ? "&nbsp;<img src='images/check_mark.gif'>" : "&nbsp;<img src='images/cross_mark.gif'>";
						}
						$name .= ($row["do_summary"]==2)?(" (Chair)"):("");
						$chair_evalRpt_ID .= ($row["do_summary"]==2)? $row["evalReport_id"] : "";
						$rl = $reportLink.$done_img.$finalReportLink.$finalDone_img;
					}

					echo "<tr class='onblue'>";
					echo "<td valign='top' align='left'>".$proc_description."</td>";
					echo "<td valign='top' align='left'>";
					echo ' <a href="javascript:winEvalContactDetails(\'Evaluator Contact Details\',\''.$row['Persnr_ref'].'\', \''.base64_encode($tmpSettings).'\', \'\');">';
					echo $name;
					echo "</a></td>";
					echo "<td valign='top' align='center'>".$row["evalReport_date_sent"]."</td>";
					echo "<td valign='top' align='center'>".$access_end."</td>";
					echo "<td valign='top' align='center'>".$compDate."</td>";
					echo "<td valign='top' align='left'>".$rl."</td>";
					echo "<td valign='top' align='center'>".$view_by_eval."</td>";
					echo "</tr>";
				}
			}
?>
		</table>
	</td>
</tr>
<tr>
	<td>
		<br>
		<b>List of previous evaluations for this application</b>
		<?php 
			echo $this->displayListofEvaluations($app_id);
		?>
	</td>
</tr>
<tr>
	<td>
		<br>
		<span class="visi">If this application is ready for management approval, please check this box to continue. <?php $this->showField("readyForApproval");?></span>
		<br>Please note if you check this box and click on Next, the application will be passed to management and the evaluators will no longer have access to the application to ensure consistence of information during management approval.
	</td>
</tr>
</table>
<br>

<!--
<script>
function setID(val){
	document.defaultFrm.CHANGE_TO_RECORD.value='evalReport|'+val;
}

//</script>
-->


