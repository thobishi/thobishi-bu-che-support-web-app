<?php
	function connect(){
		$Hostname = "localhost";
		$Username = "CHE_heqfonline";
		$Password = "align4me";
		$DBName   = "CHE_heqfonline";
//		$DBName   = "CHE_heqf_live";

		$count    = 0; //to get how many rows there exists in the table
			
		$conn = mysqli_connect($Hostname, $Username, $Password); //connect to the database
		if (!$conn) {
			echo 'Could not connect to mysql';//die('Could not connect: ' . mysqli_error());
		}

		if (!mysqli_select_db($DBName, $conn)) {		//select correct database		
			echo 'Could not select database';
			//exit;
		}
		
		return $conn;
	}
	
	function pr($array){
		echo '<pre>';
		print_r($array);
		echo '</pre>';
	}
	

	function recat_qual($conn, $table){
		$SQLQuery = <<<SQL
			SELECT {$table}.* , heqf_qualifications.s1_lkp_heqf_align_id
			FROM {$table} , heqf_qualifications
			WHERE heqf_qualifications.id = {$table}.heqf_qual_id
			AND `Submitted Category` <> `Proposed re-categorisation`
SQL;
echo $SQLQuery;
		//Select all from the database
		$result = mysqli_query($SQLQuery);
		$nr_qual_ins = 0;
		$nr_qual_upd = 0;
		$nr_app_ins = 0;
		$nr_app_upd = 0;
		$catg_eq_new = "";
		$list_recat = "";
		while($data = mysqli_fetch_array($result, MYSQL_ASSOC)){
			// pr($data);
			// exit;
			//$updateQuery .= "AND `heqf_qualifications`.`qualification_reference_no`='" . mysqli_real_escape_string($data['let_qual_ref_no']) . "'";
			$orig_qual_id = trim($data["heqf_qual_id"]);
			$orig_app_id = trim($data["application_id"]);
			$catg = trim($data["Submitted Category"]);  // Ignoring because it may not be accurate - time based - and may have changed in the interim.
			$new_catg = trim($data["Proposed re-categorisation"]);
			$current_catg = trim($data["s1_lkp_heqf_align_id"]);

			/* if the category in the database is already set to the 'new' category then categorisation is not necessary */
			if ($current_catg == $new_catg){
				$catg_eq_new .= <<<HTML
					<tr>
						<td>{$data["Institution Name"]}</td>
						<td>{$data["Qualification Title"]}</td>
						<td>{$catg}</td>
						<td>{$new_catg}</td>
						<td>{$current_catg}</td>
						<td>{$data["heqf_qual_id"]}</td>
						<td>{$data["application_id"]}</td>
					</tr>
HTML;
				continue;
			}

			/* recat necessary */
			$recat_desc = $data["Submitted Category"] . " to " . $data["Proposed re-categorisation"];
			$catg_A_to_B_ind = $catg_A_to_C_ind = $catg_B_to_A_ind = $catg_B_to_C_ind = $catg_C_to_A_ind = $catg_C_to_B_ind = 0;
			$recat_desc = "";
			switch ($current_catg){
			case 'A':
				$catg_A_to_B_ind = ($new_catg == 'B') ? 1 : 0;
				$catg_A_to_C_ind = ($new_catg == 'C') ? 1 : 0;
				$recat_desc = ($catg_A_to_B_ind == 1) ? "A to B" : "A to C";
				break;
			case 'B':
				$catg_B_to_A_ind = ($new_catg == 'A') ? 1 : 0;
				$catg_B_to_C_ind = ($new_catg == 'C') ? 1 : 0;
				$recat_desc = ($catg_B_to_A_ind == 1) ? "B to A" : "B to C";
				break;
			case 'C':
				$catg_C_to_A_ind = ($new_catg == 'A') ? 1 : 0;
				$catg_C_to_B_ind = ($new_catg == 'B') ? 1 : 0;
				$recat_desc = ($catg_C_to_A_ind == 1) ? "C to A" : "C to B";
				break;
			}
			

			/** Archive a copy of heqf_qualifications record.  Set categorisation information. **/
			$ins1 = <<<INSERT
				INSERT INTO heqf_qualifications (`id`, `institution_id`, `heqf_reference_no`, `qualification_reference_no`, `heqc_reference_no`, `qualification_title`, `qualification_title_short`, `institution_qualification_title`, `heqc_application_id`, `saqa_qualification_id`, `replace_qual`, `lkp_qualification_type_id`, `lkp_designator_id`, `other_designator`, `motivation_other_designator`, `lkp_cesm1_code_id`, `lkp_delivery_mode_id`, `lkp_professional_class_id`, `professional_body`, `lkp_nqf_level_id`, `credits_total`, `credits_nqf5`, `credits_nqf6`, `credits_nqf7`, `credits_nqf8`, `credits_nqf9`, `credits_nqf10`, `minimum_admission_requirements`, `minimum_years_full`, `minimum_years_part`, `first_qualifier`, `lkp_cesm2_code_id`, `first_qualifier_credits`, `first_qualifier_credits_final`, `second_qualifier`, `lkp_cesm3_code_id`, `second_qualifier_credits`, `second_qualifier_credits_final`, `wil_el_credits`, `research_credits`, `qualification_purpose`, `qualification_rationale`, `struct_elect`, `exit_level_outcome`, `int_assess`, `articulation_progression`, `moderation`, `rpl`, `international_comparability`, `hemis_lkp_cesm3_code_id`, `lkp_hemis_heqf_qualification_type_id`, `hemis_minimum_exp_time`, `hemis_total_subsidy_units`, `lkp_hemis_funding_level_id`, `s1_qualification_reference_no`, `s1_heqc_reference_no`, `s1_qualification_title`, `s1_qualification_title_short`, `s1_institution_qualification_title`, `s1_heqc_application_id`, `s1_saqa_qualification_id`, `s1_lkp_delivery_mode_id`, `s1_lkp_nqf_level_id`, `s1_credits_total`, `s1_credits_nqf5`, `s1_credits_nqf6`, `s1_credits_nqf7`, `s1_credits_nqf8`, `s1_credits_nqf9`, `s1_credits_nqf10`, `s1_minimum_admission_requirements`, `s1_minimum_years_full`, `s1_minimum_years_part`, 
					`s1_lkp_heqf_align_id`, `s1_teachout_date`, `s1_lkp_hemis_qualifier_id`, `s1_lkp_hemis_qualification_type_id`, `s1_hemis_minimum_exp_time`, `s1_hemis_total_subsidy_units`, `s1_lkp_hemis_funding_level_id`, `s1_error`, `s2_error`, `s3_error`, `edited_online`, `upload_status`, `created`, `modified`, `apx_A`, `apx_B`, `let_hei_id`, `let_hei_code`, `let_hei_name`, `let_qual_ref_no`, `let_qual_title_abbr`, `let_qual_title`, `let_dupl_ind`, `let_saqa_qual_id`, `let_qual_designator`, `let_motivation_other_designator`, `let_cesm`, `let_cesm_ind`, `let_mode_of_delivery`, `let_prof_class`, `let_nqf_exit_level`, `let_total_credits`, `let_wil_el_credits`, `let_research_credits`, `let_rc_ind`, `let_major_field_of_study`, `let_mfos_ind`, `qualification_title_201302`, `qualification_title_short_201302`, `motivation_duplicate`, 
					`catg_B_to_A_ind`, `catg_A_to_C_ind`, `catg_B_to_C_ind`, `catg_A_to_B_ind`, `catg_C_to_A_ind`, `catg_C_to_B_ind`, 
					`qualification_title_201306`, `lkp_cesm1_code_id_dirty`, `qualification_title_orig`, `qualification_title_short_orig`, `s3_modules`, `s3_purpose`, `s3_curriculum`, `s3_assessment`, `s3_direct_contact_time`, `s3_wil_time`, `s3_workplace_learning_time`, `s3_self_study_time`, `s3_learning_assessment_time`, `s3_learning_other_time`, `s3_learning_other_text`, `s3_learning_activities`, `s3_has_wil`, `s3_q7`, `s3_q8`, `final_qual_title_short`, `final_qual_title`, `new_cesm`, `new_mfos`, `qualification_title_20131020`, `qualification_title_short_20131020`, `lkp_cesm1_code_id_20131020`, `hemis_lkp_cesm3_code_id_20131020`, `s3_guideline_explained`, `s3_placement_explained`, `s3_workplace_explained`, 
					`archived`, `archive_date`, `archived_by`, `disable_section1`, `disable_delete`, `archive_reason`)
				SELECT   UUID(), `institution_id`, NULL, `qualification_reference_no`, `heqc_reference_no`, `qualification_title`, `qualification_title_short`, `institution_qualification_title`, `heqc_application_id`, `saqa_qualification_id`, `replace_qual`, `lkp_qualification_type_id`, `lkp_designator_id`, `other_designator`, `motivation_other_designator`, `lkp_cesm1_code_id`, `lkp_delivery_mode_id`, `lkp_professional_class_id`, `professional_body`, `lkp_nqf_level_id`, `credits_total`, `credits_nqf5`, `credits_nqf6`, `credits_nqf7`, `credits_nqf8`, `credits_nqf9`, `credits_nqf10`, `minimum_admission_requirements`, `minimum_years_full`, `minimum_years_part`, `first_qualifier`, `lkp_cesm2_code_id`, `first_qualifier_credits`, `first_qualifier_credits_final`, `second_qualifier`, `lkp_cesm3_code_id`, `second_qualifier_credits`, `second_qualifier_credits_final`, `wil_el_credits`, `research_credits`, `qualification_purpose`, `qualification_rationale`, `struct_elect`, `exit_level_outcome`, `int_assess`, `articulation_progression`, `moderation`, `rpl`, `international_comparability`, `hemis_lkp_cesm3_code_id`, `lkp_hemis_heqf_qualification_type_id`, `hemis_minimum_exp_time`, `hemis_total_subsidy_units`, `lkp_hemis_funding_level_id`, `s1_qualification_reference_no`, `s1_heqc_reference_no`, `s1_qualification_title`, `s1_qualification_title_short`, `s1_institution_qualification_title`, `s1_heqc_application_id`, `s1_saqa_qualification_id`, `s1_lkp_delivery_mode_id`, `s1_lkp_nqf_level_id`, `s1_credits_total`, `s1_credits_nqf5`, `s1_credits_nqf6`, `s1_credits_nqf7`, `s1_credits_nqf8`, `s1_credits_nqf9`, `s1_credits_nqf10`, `s1_minimum_admission_requirements`, `s1_minimum_years_full`, `s1_minimum_years_part`, 
					`s1_lkp_heqf_align_id`, `s1_teachout_date`, `s1_lkp_hemis_qualifier_id`, `s1_lkp_hemis_qualification_type_id`, `s1_hemis_minimum_exp_time`, `s1_hemis_total_subsidy_units`, `s1_lkp_hemis_funding_level_id`, `s1_error`, `s2_error`, `s3_error`, `edited_online`, `upload_status`, `created`, `modified`, `apx_A`, `apx_B`, `let_hei_id`, `let_hei_code`, `let_hei_name`, `let_qual_ref_no`, `let_qual_title_abbr`, `let_qual_title`, `let_dupl_ind`, `let_saqa_qual_id`, `let_qual_designator`, `let_motivation_other_designator`, `let_cesm`, `let_cesm_ind`, `let_mode_of_delivery`, `let_prof_class`, `let_nqf_exit_level`, `let_total_credits`, `let_wil_el_credits`, `let_research_credits`, `let_rc_ind`, `let_major_field_of_study`, `let_mfos_ind`, `qualification_title_201302`, `qualification_title_short_201302`, `motivation_duplicate`, 
					{$catg_B_to_A_ind}, {$catg_A_to_C_ind}, {$catg_B_to_C_ind}, {$catg_A_to_B_ind}, {$catg_C_to_A_ind}, {$catg_C_to_B_ind}, 
					`qualification_title_201306`, `lkp_cesm1_code_id_dirty`, `qualification_title_orig`, `qualification_title_short_orig`, `s3_modules`, `s3_purpose`, `s3_curriculum`, `s3_assessment`, `s3_direct_contact_time`, `s3_wil_time`, `s3_workplace_learning_time`, `s3_self_study_time`, `s3_learning_assessment_time`, `s3_learning_other_time`, `s3_learning_other_text`, `s3_learning_activities`, `s3_has_wil`, `s3_q7`, `s3_q8`, `final_qual_title_short`, `final_qual_title`, `new_cesm`, `new_mfos`, `qualification_title_20131020`, `qualification_title_short_20131020`, `lkp_cesm1_code_id_20131020`, `hemis_lkp_cesm3_code_id_20131020`, `s3_guideline_explained`, `s3_placement_explained`, `s3_workplace_explained`, 
					1, NOW(), '4e55faee-e68c-43ef-96a0-6aa4c4d39072', `disable_section1`, `disable_delete`, 're-categorisation: {$recat_desc}'
				FROM heqf_qualifications
				WHERE id = '{$orig_qual_id}'
INSERT;
			$insnewResult = mysqli_query($ins1) or die($ins1 . "<br /><br />" . mysqli_error());
			$new_qual_id = mysqli_insert_id();
			if(mysqli_affected_rows() > 0){
				$nr_qual_ins += 1;
			}

			/** Change category on heqf_qualifications record (on old id) **/
			$upd1 = <<<UPDATE
				UPDATE heqf_qualifications
				SET s1_lkp_heqf_align_id = '{$new_catg}', s1_error = 1, s2_error = 0, s3_error = 0, edited_online = 1
				WHERE id = '{$orig_qual_id}'
UPDATE;
			$updrs = mysqli_query($upd1) or die($upd1 . "<br /><br />" . mysqli_error());
			if(mysqli_affected_rows() > 0){
				$nr_qual_upd += 1;
			}

			/** Copy applications record **/
			$insapp = <<<INSERT
				INSERT INTO applications (`id`, `institution_id`, `heqf_qualification_id`, `application_status`, `submission_date`, `submission_user_id`, `user_id`)
				SELECT   UUID(), `institution_id`, `heqf_qualification_id`, IF(`application_status`='New','New','Submitted'), `submission_date`, `submission_user_id`, ''
				FROM applications
				WHERE id = '{$orig_app_id}'
INSERT;

//			SELECT   UUID(), `institution_id`, `heqf_qualification_id`, `application_status`, `submission_date`, `submission_user_id`, `user_id`

			$insnew = mysqli_query($insapp) or die($insapp . "<br /><br />" . mysqli_error());
			$new_app_id = mysqli_insert_id();
			if(mysqli_affected_rows() > 0){
				$nr_app_ins += 1;
			}
			
			/** Archive old applications record **/
			$updapp = <<<UPDATE
				UPDATE applications
				SET archived = 1, archive_date = NOW(), archived_by = '4e55faee-e68c-43ef-96a0-6aa4c4d39072', archive_reason = 're-categorisation: {$recat_desc}'
				WHERE id = '{$orig_app_id}'
UPDATE;
			$updrs = mysqli_query($updapp) or die($updapp . "<br /><br />" . mysqli_error());
			if(mysqli_affected_rows() > 0){
				$nr_app_upd += 1;
			}
			
			$list_recat .= <<<HTML
				<tr>
					<td>{$data["Institution Name"]}</td>
					<td>{$data["Qualification Title"]}</td>
					<td>{$catg}</td>
					<td>{$new_catg}</td>
					<td>{$current_catg}</td>
					<td>{$data["heqf_qual_id"]}</td>
					<td>{$data["application_id"]}</td>
				</tr>
HTML;
		}

		$html = <<<HTML
			<br>Status of update
			<table>
			<tr><td>Number of qualifications inserted</td><td>{$nr_qual_ins}</td></tr>
			<tr><td>Number of qualifications archived</td><td>{$nr_qual_upd}</td></tr>
			<tr><td>Number of applications inserted</td><td>{$nr_app_ins}</td></tr>
			<tr><td>Number of applications archived</td><td>{$nr_app_upd}</td></tr>
			</tr>
			</table>
			<table>
			<tr>
				<th align="left" colspan="7">Qualifications re-categorised</th>
			</tr>
			<tr>
				<th>Institution</th>
				<th>Qualification Title</th>
				<th>Category</th>
				<th>New category</th>
				<th>Current category</th>
				<th>qual_id</th>
				<th>application_id</th>
			</tr>
			$list_recat
			</table>
			<table>
			<tr>
				<th align="left" colspan="4">Qualifications that already have their category set as the new category</th>
			</tr>
			<tr>
				<th>Institution</th>
				<th>Qualification Title</th>
				<th>Category</th>
				<th>New category</th>
				<th>Current category</th>
				<th>qual_id</th>
				<th>application_id</th>
			</tr>
			$catg_eq_new
			</table>
HTML;

		echo $html;
		
		mysqli_free_result($result);
		
	}
	

	

	
?>
