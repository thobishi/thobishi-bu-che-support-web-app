<?php
	$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	$app_proc_id = $this->dbTableInfoArray["ia_proceedings"]->dbTableCurrentID;
	$proc_type = $this->getValueFromTable("ia_proceedings","ia_proceedings_id",$app_proc_id,"lkp_proceedings_ref");
	if ($proc_type != 1){ // User should not be able to return the application to the institution for deferrals, representations or conditions
		$this->formActions['gotoInstitution']->actionMayShow = false;
	}

?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
<?php
		$this->showInstitutionTableTop ();
		//$id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
		$chair_report_head = "";
		// 2017-09-28 Richard: Included conditional re-accred
		if (($proc_type != 4)&&($proc_type != 6)){	
			$chair_report_head = '<td class="oncolourb" valign="top" align="center" width="20%">Final Report</td>';
		}
		$helptext = <<<HELP
			<br>
			The Project Manager has indicated that this application is ready for approval by management.<br>
			The following tasks must be completed by you in the system:
			<ul>
				<li>Approve the evaluator reports.  If you do not approve the reports send the application back to the Project Administrator with instructions.</li>
				<li>Ensure that the evaluators have received payment.</li>
				<li>Indicate that you approve that this application is ready for the Directorate recommendation to be done.</li>
			</ul>
			The list below displays the evaluator reports. You are able to view these reports by clicking on the link.
			<br>
			<br>
			<span class="visi">Please note that applications evaluated without a chairman will not have a final report.</span>
			<br><br>
HELP;
		echo $helptext;

		$html = <<<HTML
			<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
			<tr>
				<td class="oncolourb" valign="top" align="center" width="20%">Evaluator</td>
				<td class="oncolourb" valign="top" align="center" width="20%">Date sent to evaluator</td>
				<td class="oncolourb" valign="top" align="center" width="10%">Date received from evaluator</td>
				<td class="oncolourb" valign="top" align="center" width="20%">Report link</td>
				$chair_report_head
			</tr>		
HTML;
			echo $html;

			// 2017-07-25 Richard: Included conditional re-accred
			if (($proc_type == 4)||($proc_type == 6)){
				$this->showField('condition_confirm_ind');
			}

			//$SQL = "SELECT * FROM evalReport WHERE application_ref =".$app_id." AND evalReport_status_confirm=1";
			$SQL = "SELECT * FROM evalReport WHERE ia_proceedings_ref = ? AND evalReport_status_confirm=1";

			$isChairID = "";

			$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
			if ($conn->connect_errno) {
			    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
			    printf("Error: %s\n".$conn->error);
			    exit();
			}

			$sm = $conn->prepare($SQL);
			$sm->bind_param("s", $app_proc_id);
			$sm->execute();
			$rs = $sm->get_result();

		
			//$rs = mysqli_query($SQL);
			if (mysqli_num_rows($rs) > 0){
				while ($row = mysqli_fetch_array($rs)){
		
					$evalReportID = $row["evalReport_id"];
					$name = $this->getValueFromTable("Eval_Auditors","Persnr",$row["Persnr_ref"],"Names")."&nbsp;".$this->getValueFromTable("Eval_Auditors","Persnr",$row["Persnr_ref"],"Surname");
					$tmpSettings = "DBINF_Institutions_application___application_id=".$app_id."&DBINF_evalReport___evalReport_id=".$row["evalReport_id"];
					$compDate = $row["evalReport_date_completed"];
					$a_eDoc = "No final report uploaded";
					$dhtml = "";

					// 2017-07-25 Richard: Included conditional re-accred
					if (($proc_type == 4)||($proc_type == 6)){ // conditional proceedings
						//$this->showField('condition_confirm_ind');
						$cond_complete = $this->formFields['condition_confirm_ind']->fieldValue;
						$plink = $this->scriptGetForm ('ia_proceedings', $app_proc_id, '_condForm_recomm');
						$reportLink = "<a href='".$plink."'><img border=\'0\' src=\"images/ico_print.gif\"></a> View conditions evaluation";
						$done_img =  ($cond_complete != 1) ? "&nbsp;<img src='images/cross_mark.gif'>" : "&nbsp;<img src='images/check_mark.gif'>";
						$a_eDoc = $reportLink.$done_img;
						$next_process = "AC meeting";

					} else {
						$eDoc = new octoDoc($row['evalReport_doc']);
						$a_sDoc = "No final report uploaded";
			
						if ($row["do_summary"]==2){
							$name .= " (Chair)";
							$isChairID = $row['Persnr_ref'];
							$sDoc = new octoDoc($row['application_sum_doc']);
								if ($sDoc->isDoc()) {
									$a_sDoc = '<a href="'.$sDoc->url().'" target="_blank">'.$sDoc->getFilename().'</a>';
								}
						}
						if ($eDoc->isDoc()) {
							$a_eDoc = '<a href="'.$eDoc->url().'" target="_blank">'.$eDoc->getFilename().'</a>';
						}
						$dhtml = <<<HTML
							<td valign='top' align='center'>
								$a_sDoc
							</td>
HTML;
						$next_process = "Directorate recommendation";
					}
					
					echo "<tr  class='onblue'>";
					echo "<td valign='top' align='left'>";
					echo ' <a href="javascript:winEvalContactDetails(\'Evaluator Contact Details\',\''.$row['Persnr_ref'].'\', \''.base64_encode($tmpSettings).'\', \'\');">';
					echo $name;
					echo "</a></td>";
					echo "<td valign='top' align='center'>".$row["evalReport_date_sent"]."</td>";
					echo "<td valign='top' align='center'>".$compDate."</td>";
					echo '<td valign="top" align="center">'.$a_eDoc.'</td>';
					echo $dhtml;
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
	<span class="visi">
	Please check this box to indicate that you have approved the evaluator reports for this application and to indicate that it may proceed to the <?php echo $next_process; ?> processing.
	<?php $this->showField("readyForRecomm"); ?>
	</span>
	<br>
	<br>
	</td>
</tr>
</table>




