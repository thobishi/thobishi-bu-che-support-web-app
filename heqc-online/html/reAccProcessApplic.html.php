<?php 

	function formatDate($ddate,$cdate){
		$dt_format = "&nbsp;";

		$date_today = date("Y-m-d");  // used to flag dates that are nearing or passed end dates.
		$di2w = date ("Y-m-d", mktime (0,0,0,date("m"),date("d")-14,date("Y")));

		$colr = "#000000";
		$color_green = "#33ff00";
		$color_orange = "#ff6600";
		$color_red = "#ff0000";
		$warn_time_period = 2; //weeks
		if ($ddate > '1970-01-01'){
			// Due date passed and complete date not set. Order is important.
			if ($ddate > $date_today) $colr = $color_green;
			if ($ddate < $date_today && $cdate=='1970-01-01') $colr = $color_red;
			if ($ddate > $date_today && $ddate > $di2w && $cdate=='1970-01-01') $colr = $color_orange;
			
			$dt_format = '<span style="color:'.$colr.'">'.$ddate.'</span>';
		}

		return $dt_format;
	}


	$fc_arr = $this-> build_reacc_search_criteria($_POST);
	$ser_fc_arr = base64_encode(serialize($fc_arr));

	$filter_criteria = (count($fc_arr) > 0) ? ' AND ' . implode(' AND ',$fc_arr) : "";

file_put_contents('php://stderr', print_r("xml \n".$ser_fc_arr, TRUE));
?>
<br>
<table width="100%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td class="loud">Process Re-Accreditation applications:</td>
</tr>
<tr>
	<td>
		<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
		<tr>
			<td align="right">
			Submission date: From
			</td>
			<td>
			<?php $this->showField('subm_start_date');	?>
			</td>
		</tr>
		<tr>
			<td align="right">
			To: 
			</td>
			<td>
			<?php $this->showField('subm_end_date');	?>
			</td>
		</tr>
		<tr>
			<td align="right">
			HEQC Reference Number:  
			</td>
			<td>
			<?php $this->showField('search_HEQCref');	?>
			</td>
		</tr>
		<tr>
			<td align="right">
			Programme name:  
			</td>
			<td>
			<?php $this->showField('search_progname');	?>
			</td>
		</tr>
		<tr>
			<td align="right">
			Institution:  
			</td>
			<td>
			<?php //print_r($this->formFields['search_institution']	);
			$this->showField('search_institution');	?>
			</td>
		</tr>
		<tr>
			<td align="right">Show who currently has the process:<br><span class="specialsi">Note: Takes a while to run if checked</span></td>
			<td><?php $this->showField('display_process');	?></td>
		</tr>		
		<tr>
			<td align="center" colspan="2">
				<br><input type="submit" class="btn" name="submitButton" value="Search" onClick="javascript:moveto('_label_reaccProcessApplic');">
			</td>
		</tr>
		</table>
	</td>
</tr>
</table>
<?php
if ($filter_criteria > "" OR isset($_POST['submitButton'])){
	$s_displayProcess = readPost('display_process');

	$sql = <<<REACCAPP
		SELECT * 
		FROM Institutions_application_reaccreditation
		LEFT JOIN lkp_reacc_decision ON reacc_decision_ref = lkp_reacc_id
		WHERE reacc_submission_date > '1970-01-01'
		$filter_criteria
		ORDER BY referenceNumber
REACCAPP;

	$rs = mysqli_query($this->getDatabaseConnection(), $sql);
	$n_app = mysqli_num_rows($rs);
?>
	<hr>
	<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
	<tr class="Loud">
		<td class="Loud">
			List of re-accreditation applications
		</td>
		<td class="Loud" align="right"><?php echo "Number of applications: " . $n_app; ?>
		</td>
	</tr>
	<tr class="Loud">
		<td class="Loud">
		</td>
		<td class="Loud" align="right">
			<a href="docgen/xls_re_accreditationAppsReport.php?data=<?php echo $ser_fc_arr; ?>" target\"_blank\">Download report in Excel</a>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<?php 
			$html = <<<HTMLHEAD
				<table class="saphireframe" width="100%" border=0  cellpadding="2" cellspacing="0">
				<tr class="doveblox">
					<td class="doveblox" rowspan="2">Edit</td>
					<td class="doveblox" rowspan="2">Programme</td>
					<td class="doveblox" rowspan="2">Submission<br>date</td>
					<td class="doveblox" rowspan="2">Process</td>
					<td class="doveblox" rowspan="2">Invoice<br>date</td>
					<td class="doveblox" rowspan="2">Checklist</td>
					<td class="doveblox" colspan="2">Evaluation</td>
					<td class="doveblox" rowspan="2">Site<br>visit</td>
					<td class="doveblox" rowspan="2">Secret<br>recomm</td>
					<td class="doveblox" colspan="2">Deferral</td>
					<td class="doveblox" rowspan="2">AC<br>meeting</td>
					<td class="doveblox" colspan="2">Condition</td>
					<td class="doveblox" colspan="2">Representation</td>
					<td class="doveblox" rowspan="2">Outcome</td>
				</tr>
				<tr class="doveblox">
					<td class="doveblox">Appoint</td>
					<td class="doveblox">complete</td>
					<td class="doveblox">due</td>
					<td class="doveblox">complete</td>
					<td class="doveblox">due</td>
					<td class="doveblox">met</td>
					<td class="doveblox">submit</td>
					<td class="doveblox">complete</td>
				</tr>
HTMLHEAD;

			if ($rs){

				$criteria = array("evalReport_status_confirm = 1");
				while ($row = mysqli_fetch_array($rs)){
					$reaccred_id = $row["Institutions_application_reaccreditation_id"];
					$link1 = $this->scriptGetForm ('Institutions_application_reaccreditation', $reaccred_id, 'next');

					$pay_invoice_date = $this->getValueFromTable("payment","reaccreditation_application_ref",$reaccred_id,"date_invoice");

					$dash = '<img src="images/dash_mark.gif">';
					$check = '<img src="images/check_mark.gif">';
					$submission_date = ($row["reacc_submission_date"] > '1970-01-01') ? $row["reacc_submission_date"] : $dash;
					$invoice_date = ($pay_invoice_date > '1970-01-01') ? $pay_invoice_date : $dash;
					$checklist_date = ($row["reacc_checklist_date"] > '1970-01-01') ? $row["reacc_checklist_date"] : $dash;
					$a_evals = $this->getSelectedEvaluatorsForApplication($reaccred_id, $criteria, "Reaccred");
					$evaluator_sel = (count($a_evals) > 0) ? $check . ' ' : $dash;
					$evaluation_date = ($row["reacc_evaluation_date"] > '1970-01-01') ? $row["reacc_evaluation_date"] : $dash;
					$site_visit_date = ($row["reacc_sitevisit_date"] > '1970-01-01') ? $row["reacc_sitevisit_date"] : "&nbsp;";
					$secr_recomm_date = ($row["reacc_secretariate_date"] > '1970-01-01') ? $row["reacc_secretariate_date"] : $dash;
					

					$pdate = $row["reacc_deferdue_date"];
					
					$defer_due_date = formatDate($row["reacc_deferdue_date"],$row["reacc_defercomplete_date"]);
//					$defer_due_date = ($row["reacc_deferdue_date"] > '1970-01-01') ? $row["reacc_deferdue_date"] : "&nbsp;";
					$defer_complete_date = ($row["reacc_defercomplete_date"] > '1970-01-01') ? $row["reacc_defercomplete_date"] : "&nbsp;";
					$ac_meeting_date = ($row["reacc_acmeeting_date"] > '1970-01-01') ? $row["reacc_acmeeting_date"] : $dash;
					$cond_due_date = formatDate($row["reacc_conditiondue_date"],$row["reacc_conditionmet_date"]);
//					$cond_due_date = ($row["reacc_conditiondue_date"] > '1970-01-01') ? $row["reacc_conditiondue_date"] : "&nbsp;";
					$cond_met_date = ($row["reacc_conditionmet_date"] > '1970-01-01') ? $row["reacc_conditionmet_date"] : "&nbsp;";
					$repr_submit_date = ($row["reacc_reprsubmit_date"] > '1970-01-01') ? $row["reacc_reprsubmit_date"] : "&nbsp;";
					$repr_complete_date = ($row["reacc_reprcomplete_date"] > '1970-01-01') ? $row["reacc_reprcomplete_date"] : "&nbsp;";
					$outcome = ($row["lkp_reacc_title"] > '0') ? $row["lkp_reacc_title"] : $dash;

					$process = '-';
					if ($s_displayProcess == 1){
						$proc_arr = $this->getActiveProcessforApp($reaccred_id, "reacc");
						$process =  '(<span class="specialsi">'. $proc_arr['name'] .'</span>)';
					}

				
					$html .= <<<HTML
						<tr>
							<td class="saphireframe"><a href='$link1'><img src="images/ico_change.gif"></a></td>
							<td class="saphireframe">$row[referenceNumber] $row[programme_name]</td>
							<td class="saphireframe">$submission_date</td>
							<td class="saphireframe">$process</td>
							<td class="saphireframe">$invoice_date</td>
							<td class="saphireframe">$checklist_date</td>
							<td class="saphireframe">$evaluator_sel</td>
							<td class="saphireframe">$evaluation_date</td>
							<td class="saphireframe">$site_visit_date</td>
							<td class="saphireframe">$secr_recomm_date</td>
							<td class="saphireframe">$defer_due_date</td>
							<td class="saphireframe">$defer_complete_date</td>
							<td class="saphireframe">$ac_meeting_date</td>
							<td class="saphireframe">$cond_due_date</td>
							<td class="saphireframe">$cond_met_date</td>
							<td class="saphireframe">$repr_submit_date</td>
							<td class="saphireframe">$repr_complete_date</td>
							<td class="saphireframe">$outcome</td>
						</tr>
HTML;
				}
			}
			$html .= <<<HTMLFOOT
					</td>
				</tr>
				</table>
HTMLFOOT;
	
			echo $html;
	
			?>
		</td>
	</tr>
	</table>
<?php 
}
?>
<hr>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
	Guidelines for searching:
	<ul>
		<li>applications submitted to CHE <b>from a certain date</b>, enter the date in the "From:" date field.</li>
		<li>all applications submitted <b>up until a certain date</b>, enter the date in the "To:" date field.</li>
		<li>applications submitted in a certain <b>date range</b>, fill in both the "From" or "To" submission date fields.</li>
		<li>a <b>specific HEQC reference number</b>, enter either all or part of the reference number in the relevant field. In this way, you are able to search for all applications submitted by a specific institution - by entering the reference code of the institution (e.g. PR064).</li>
		<li>a <b>specific institution</b>, select the insitution from the drop down list.</li>
		<li><b>ALL applications</b> submitted through the HEQC-online system, click "Search" without entering anything into any of the fields.</li>
	</ul>
	Please note that the applications are sorted according to HEQC reference number.
	<br><br>
	</td>
</tr>
</table>
<hr>