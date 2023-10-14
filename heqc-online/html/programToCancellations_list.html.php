<?php
	$s_progname = readPost('search_progname');
	$s_heqcref = readPost('search_HEQCref');
	$s_inst = readPost('search_institution');
	$report_ind = readPost('report_ind');
	
	$search_progWithdrawnVal = (isset($_POST['search_progWithdrawn'])) ? 1 : 0;
	$this->showField("data");

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
	
	if($search_progWithdrawnVal > ''){
		array_push($fc_arr," withdrawn_ind = ".$search_progWithdrawnVal);
	}

	$filter_criteria = (count($fc_arr) > 0) ? ' AND ' . implode(' AND ',$fc_arr) : "";
?>
<br>
<table width="100%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td class="loud">Withdraw programmes:</td>
</tr>
<tr>
	<td>
		<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
		<tr>
			<td align="right">Institution:</td>
			<td><?php $this->showField('search_institution');	?></td>
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
			<td align="right">Programme withdrawn:  </td>
			<td><input type="checkbox" name="search_progWithdrawn" value="<?php echo $search_progWithdrawnVal ;?>" <?php if(isset($_POST['search_progWithdrawn'])) echo "checked='checked'"; ?>  /></td>
		</tr>
		<tr>
			<td align="center" colspan="4">
				<br><input type="submit" class="btn" name="submitButton" value="Search" onClick="javascript:moveto('_label_programToWithdraw');">
				<input type="button" class="btn" name="clear" value="Clear fields" onclick="clearFields(document.defaultFrm);">
				<input type="hidden" id="report_ind" name="report_ind" value="report">
			</td>
		</tr>
		</table>
</tr>
</table>
<?php
	$html = "Please enter search criteria and click on search to find a specific program or click on search to obtain all applications.";
	if ($report_ind == 'report'):
		$sql = <<<SQL
			SELECT application_id, HEI_code, HEI_name, CHE_reference_code, program_name, lkp_qualification_type_desc, 
			lkp_mode_of_delivery_desc, num_credits, SpecialisationCESM_code1.Description, lkp_title, prev_program_name, withdrawn_ind, application_status, ia_withdrawals.user_ref, ia_withdrawals.reason, ia_withdrawals.reason_doc
			FROM Institutions_application 
			LEFT JOIN HEInstitution ON HEInstitution.HEI_id = Institutions_application.institution_id
			LEFT JOIN lkp_desicion ON lkp_desicion.lkp_id = Institutions_application.AC_desision
			LEFT JOIN lkp_qualification_type ON lkp_qualification_type_id = Institutions_application.qualification_type_ref
			LEFT JOIN lkp_mode_of_delivery ON lkp_mode_of_delivery.lkp_mode_of_delivery_id = mode_delivery
			LEFT JOIN SpecialisationCESM_code1 ON SpecialisationCESM_code1.CESM_code1 = Institutions_application.CESM_code1
			LEFT JOIN ia_withdrawals ON ia_withdrawals.application_ref = Institutions_application.application_id
			WHERE Institutions_application.institution_id = HEInstitution.HEI_id
			AND submission_date > '1970-01-01'
			AND Institutions_application.AC_desision = 0
			AND CHE_reference_code NOT LIKE '%K%'
			$filter_criteria
			ORDER BY HEI_name, program_name
SQL;
                $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
                if ($conn->connect_errno) {
                    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
                    printf("Error: %s\n".$conn->error);
                    exit();
                }
		$rs = mysqli_query($conn, $sql); // or die(mysqli_error());
				
		$html = <<<HTML
			<table width="90%">
			<tr class="oncolourb">
				<td width="5%">&nbsp;</td>
				<td width="5%">Institution<br>code</td>
				<td width="20%">Institution name</td>
				<td width="5%">HEQC reference</td>
				<td width="15%">Programme name</td>
				<td width="10%">Qualification type</td>
				<td width="5%">Outcome</td>
				<td width="5%">Status</td>
				<td width="10%">Withdrawal user</td>
				<td width="20%">Reason</td>
				<td width="15%">Withdrawal support document</td>
			</tr>
HTML;
		while ($row = mysqli_fetch_array($rs)){
			$link = $this->scriptGetForm ('ia_withdrawals', 'NEW', '_label_programToWithdraw_edit',$row["application_id"]);
			$tlink = ($row['withdrawn_ind'] == 1) ? '' : "<a href='".$link."'>Withdraw</a>";
			$qual_type = ($row["lkp_qualification_type_desc"] > '') ? $row["lkp_qualification_type_desc"] : "&nbsp;";
			$prev_program_name = ($row["prev_program_name"] > '') ? $row["prev_program_name"] : "&nbsp;";
			$status = ($row['withdrawn_ind'] == 1) ? 'Withdrawn' : '';
			$rept = new octoDoc($row["reason_doc"]);
			$reptLink = "<a href='".$rept->url()."' target='_blank'>".$rept->getFilename()."</a>";
			$withdrawnDoc = ($row['reason_doc'] > '') ? $reptLink : '';
			$withdrawalUsr = ($row['user_ref'] > '') ? $this->getUserName($row['user_ref'],2) : '';
			$html .= <<<HTML
				<tr class="onblue">
					<td>{$tlink}</td>
					<td>{$row["HEI_code"]}</td>
					<td>{$row["HEI_name"]}</td>
					<td>{$row["CHE_reference_code"]}</td>
					<td>{$row["program_name"]}</td>
					<td>{$row["lkp_qualification_type_desc"]}</td>
					<td>{$row["lkp_title"]}</td>
					<td>{$status}</td>
					<td>{$withdrawalUsr}</td>
					<td>{$row["reason"]}</td>
					<td>{$withdrawnDoc}</td>
				</tr>
HTML;
		}
		$html .= <<<HTML
			</table>
HTML;
	endif;
	echo $html;
?>
