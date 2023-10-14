<?php
	$s_progname = readPost('search_progname');
	$s_heqcref = readPost('search_HEQCref');
	$s_inst = readPost('search_institution');
	$s_outcome = readPost('search_heqc_decision');
	$mode_delivery = readPost('mode_delivery');
	
	$report_ind = readPost('report_ind');

	$fc_arr = array();
	
	if ($s_progname > ''){
		array_push($fc_arr,"( program_name like '%".$s_progname."%')");
		$this->formFields["search_progname"]->fieldValue = $s_progname;
	}

	if ($s_heqcref > ''){
		array_push($fc_arr,"( CHE_reference_code like '%".$s_heqcref."%')");
		$this->formFields["search_HEQCref"]->fieldValue = $s_heqcref;
	}
	
	if ($s_inst > 0){
		array_push($fc_arr," institution_id = ".$s_inst);
		$this->formFields["search_institution"]->fieldValue = $s_inst;
	}

	if ($mode_delivery > 0){
		array_push($fc_arr," mode_delivery = ".$mode_delivery);
		$this->formFields["mode_delivery"]->fieldValue = $mode_delivery;
	}	

	if ($s_outcome > 0){
		array_push($fc_arr," ia_proceedings.heqc_board_decision_ref = ".$s_outcome);
		$this->formFields["search_heqc_decision"]->fieldValue = $s_outcome;
	}
	
	$ser_fc_arr = base64_encode(serialize($fc_arr));
	$filter_criteria = (count($fc_arr) > 0) ? ' AND ' . implode(' AND ',$fc_arr) : "";

	echo $filter_criteria;
?>
<br>
<table width="100%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td class="loud">Application Conditions Report</td>
</tr>
<tr>
	<td>
		<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
		<tr>
			<td align="right">Institution:</td>
			<td><?php $this->showField('search_institution');	?></td>
		</tr>
		<tr>
			<td align="right">Mode of delivery:</td>
			<td><?php $this->showField('mode_delivery');	?></td>
		</tr>		
		<tr>
			<td align="right">HEQC Reference Number:  </td>
			<td><?php $this->showField('search_HEQCref');	?></td>
		</tr>
		<tr>
			<td align="right">Programme name:  </td>
			<td><?php $this->showField('search_progname');	?></td>
		</tr>
		<tr>
			<td align="right">HEQC meeting decision:  </td>
			<td><?php $this->showField('search_heqc_decision');	?></td>
		</tr>
		<tr>
			<td align="center" colspan="4">
				<br><input type="submit" class="btn" name="submitButton" value="Search" onClick="javascript:moveto('_label_report_conditions');">
				<input type="button" class="btn" name="clear" value="Clear fields" onclick="clearFields(document.defaultFrm);">
				<input type="hidden" id="report_ind" name="report_ind" value="report">
			</td>
		</tr>
		</table>
</tr>
</table>
<?php

	$conditionLkp = array(
		'Short-term' => 'condition_short_due_date',
		'Long-term' => 'condition_long_due_date',
		'Prior to commencement' => 'condition_prior_due_date',
		'Not applicable' => 'Not applicable'
	);
	
	$html = "Please enter search criteria and click on search to find a specific program or click on search to obtain all applications.";
	$prev_reference = "";
	$n = 1;
	if ($report_ind == 'report'):
		$sql = "SELECT HEInstitution.HEI_id, HEInstitution.HEI_code, HEInstitution.HEI_name, Institutions_application.application_id, 
			Institutions_application.CHE_reference_code, Institutions_application.program_name, lkp_mode_of_delivery.lkp_mode_of_delivery_desc, ia_proceedings.ia_proceedings_id, 
			ia_proceedings.lkp_proceedings_ref, lkp_proceedings_desc, ia_proceedings.heqc_meeting_ref, lkp_condition_term.lkp_condition_term_desc,
			AC_Meeting.ac_start_date, HEQC_Meeting.heqc_start_date, ia_proceedings_heqc_decision. * , 
			ia_proceedings.heqc_board_decision_ref, d1.lkp_title AS outcome,ia_proceedings.condition_prior_due_date, ia_proceedings.condition_short_due_date, ia_proceedings.condition_long_due_date, d2.lkp_title AS finalOutcome
			FROM (HEInstitution, Institutions_application, ia_proceedings, ia_proceedings_heqc_decision)
			LEFT JOIN lkp_desicion AS d1 ON ia_proceedings.heqc_board_decision_ref = d1.lkp_id
			LEFT JOIN lkp_desicion AS d2 ON Institutions_application.AC_desision = d2.lkp_id
			LEFT JOIN HEQC_Meeting ON ia_proceedings.heqc_meeting_ref = HEQC_Meeting.heqc_id
			LEFT JOIN AC_Meeting ON AC_Meeting.ac_id = ia_proceedings.ac_meeting_ref
			LEFT JOIN lkp_proceedings ON lkp_proceedings.lkp_proceedings_id = ia_proceedings.lkp_proceedings_ref
			LEFT JOIN lkp_condition_term ON ia_proceedings_heqc_decision.condition_term_ref = lkp_condition_term.lkp_condition_term_id
			LEFT JOIN lkp_mode_of_delivery ON Institutions_application.mode_delivery = lkp_mode_of_delivery.lkp_mode_of_delivery_id
			WHERE HEInstitution.HEI_id = Institutions_application.institution_id
			AND Institutions_application.application_id = ia_proceedings.application_ref
			AND ia_proceedings.ia_proceedings_id = ia_proceedings_heqc_decision.ia_proceedings_ref
			AND (ia_proceedings_heqc_decision.condition_term_ref IN ('l','s','p')
				OR (ia_proceedings.heqc_board_decision_ref = 4 AND ia_proceedings_heqc_decision.condition_term_ref IN ('a')))
			$filter_criteria
			ORDER BY HEInstitution.HEI_name, Institutions_application.program_name, ia_proceedings.ia_proceedings_id, ia_proceedings_heqc_decision.decision_reason_condition
";
echo "hello";

//print_r($sql);
		// $this->printVars($sql);
		// exit;
		$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
                if ($conn->connect_errno) {
                    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
                    printf("Error: %s\n".$conn->error);
                    exit();
                }
		$rs = mysqli_query($conn, $sql);
		if (mysqli_num_rows($rs) > 0):
?>
<hr>
	<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
	<tr class="Loud">
		<td class="Loud">
		</td>
		<td class="Loud" align="right">
			<a href="docgen/xls_appConditionsReport.php?data=<?php echo $ser_fc_arr; ?>" target\"_blank\">Download report in Excel</a>
		</td>
	</tr>
	<tr>
		<td colspan="2">
<?php
			$html = <<<HTML
				<table width="100%" border=1  cellpadding="2" cellspacing="0">
				<tr class="doveblox">
					<td class="doveblox">Inst.<br>Code</td>
					<td class="doveblox">Institution name</td>
					<td class="doveblox">Programme name</td>
					<td class="doveblox">Reference</td>
					<td class="doveblox">Mode of delivery</td>
					<td class="doveblox">Proceeding type</td>
					<td class="doveblox">AC meeting</td>
					<td class="doveblox">HEQC meeting</td>
					<td class="doveblox">Outcome</td>
					<td class="doveblox">Final Outcome</td>
					<td class="doveblox">Condition type</td>
					<td class="doveblox">Condition</td>
					<td class="doveblox">Condition due</td>
					<td class="doveblox">Criterion</td>
					<td class="doveblox">Met/Not met</td>
				</tr>
HTML;
			while ($row = mysqli_fetch_array($rs)){
				$app_id = $row["application_id"];
				$row["Not applicable"] = '';
				$app_proc_id = $row["ia_proceedings_id"];
				$hei_code = $row["HEI_code"];
				$hei_name = $row["HEI_name"];
				$program_name = $row["program_name"];
				$proceedings_type = $row["lkp_proceedings_desc"];
				$reference = $row["CHE_reference_code"];
				$ac_meeting = ($row["ac_start_date"] > "1000-01-01") ? $row["ac_start_date"] : "&nbsp;";
				$heqc_meeting = ($row["heqc_start_date"] > "1000-01-01") ? $row["heqc_start_date"] : "&nbsp;";
				$outcome = $row["outcome"];
				$finalOutcome = $row["finalOutcome"];			 
				$cond_type = $row["lkp_condition_term_desc"];
				$condition = $row["decision_reason_condition"];
				$criterion = $row["criterion_min_standard"];
				$conditionDueDate = ($row[$conditionLkp[$cond_type]] > "1000-01-01") ? $row[$conditionLkp[$cond_type]] : "&nbsp;";
				$is_met = $this->is_condition_met($app_id, $condition);
				$mode_deliveryDesc = $row["lkp_mode_of_delivery_desc"];

				//$is_met = 2;
				
				if ($reference <> $prev_reference):
					$n += 1;
					$bgColor = (fmod($n,2)) ?("#EAEFF5"):("#d6e0eb");
				endif;

				$html .= <<<HTML
					<tr bgcolor="{$bgColor}">
						<td class="saphireframe">{$hei_code}</td>
						<td class="saphireframe">{$hei_name}</td>
						<td class="saphireframe">{$program_name}</td>
						<td class="saphireframe">{$reference}</td>
						<td class="saphireframe">{$mode_deliveryDesc}</td>
						<td class="saphireframe">{$proceedings_type}</td>
						<td class="saphireframe">{$ac_meeting}</td>
						<td class="saphireframe">{$heqc_meeting}</td>
						<td class="saphireframe">{$outcome}</td>
						<td class="saphireframe">{$finalOutcome}</td>
						<td class="saphireframe">{$cond_type}</td>
						<td class="saphireframe">{$condition}</td>
						<td class="saphireframe">{$conditionDueDate}</td>
						<td class="saphireframe">{$criterion}</td>
						<td class="saphireframe">{$is_met}</td>
					</tr>
HTML;
				$prev_reference = $reference;
			}
			$html .= <<<HTML
				</table>
HTML;
		echo '</td>';
		echo '</tr>';
		echo '</table>';
		else:
			$html .= "<br><br>No conditions found for the specified criteria.";
		endif;
	endif;
	echo $html;
	

?>
