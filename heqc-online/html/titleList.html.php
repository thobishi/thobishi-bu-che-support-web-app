<?php
	$s_progname = readPost('search_progname');
	$s_heqcref = readPost('search_HEQCref');
	$s_inst = readPost('search_institution');
	$report_ind = readPost('report_ind');
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

	$filter_criteria = (count($fc_arr) > 0) ? ' AND ' . implode(' AND ',$fc_arr) : "";
?>
<br>
<table width="100%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td class="loud">Change title:</td>
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
			<td align="center" colspan="4">
				<br><input type="submit" class="btn" name="submitButton" value="Search" onClick="javascript:moveto('_label_title_list');">
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
			lkp_mode_of_delivery_desc, num_credits, SpecialisationCESM_code1.Description, lkp_title, prev_program_name
			FROM Institutions_application 
			LEFT JOIN HEInstitution ON HEInstitution.HEI_id = Institutions_application.institution_id
			LEFT JOIN lkp_desicion ON lkp_desicion.lkp_id = Institutions_application.AC_desision
			LEFT JOIN lkp_qualification_type ON lkp_qualification_type_id = Institutions_application.qualification_type_ref
			LEFT JOIN lkp_mode_of_delivery ON lkp_mode_of_delivery.lkp_mode_of_delivery_id = mode_delivery
			LEFT JOIN SpecialisationCESM_code1 ON SpecialisationCESM_code1.CESM_code1 = Institutions_application.CESM_code1
			WHERE Institutions_application.institution_id = HEInstitution.HEI_id
			AND submission_date > '1970-01-01'
			AND application_status <> -1
			$filter_criteria
			ORDER BY HEI_name, program_name
SQL;
		$rs = mysqli_query($this->getDatabaseConnection(), $sql); // or die(mysqli_error());
		
		$html = <<<HTML
			<table width="90%">
			<tr class="oncolourb">
				<td width="5%">&nbsp;</td>
				<td width="5%">Institution<br>code</td>
				<td width="20%">Institution name</td>
				<td width="5%">HEQC reference</td>
				<td width="15%">Programme name</td>
				<td width="15%">Previous programme name</td>
				<td width="10%">Qualification type</td>
				<td width="5%">Mode of delivery</td>
				<td width="5%">Credits</td>
				<td width="10%">CESM</td>
				<td width="5%">Outcome</td>
			</tr>
HTML;
		while ($row = mysqli_fetch_array($rs)){
			$link = $this->scriptGetForm ('ia_title_history', 'NEW', '_label_title_edit',$row["application_id"]);
			$tlink = "<a href='".$link."'>Edit</a>";
			$qual_type = ($row["lkp_qualification_type_desc"] > '') ? $row["lkp_qualification_type_desc"] : "&nbsp;";
			$prev_program_name = ($row["prev_program_name"] > '') ? $row["prev_program_name"] : "&nbsp;";
			$html .= <<<HTML
				<tr class="onblue">
					<td>{$tlink}</td>
					<td>{$row["HEI_code"]}</td>
					<td>{$row["HEI_name"]}</td>
					<td>{$row["CHE_reference_code"]}</td>
					<td>{$row["program_name"]}</td>
					<td>{$prev_program_name}</td>
					<td>{$row["lkp_qualification_type_desc"]}</td>
					<td>{$row["lkp_mode_of_delivery_desc"]}</td>
					<td>{$row["num_credits"]}</td>
					<td>{$row["Description"]}</td>
					<td>{$row["lkp_title"]}</td>
				</tr>
HTML;
		}
		$html .= <<<HTML
			</table>
HTML;
	endif;
	echo $html;
?>
