<?php
$run_in_script_mode = true;

define ('CONFIG', 'CHEPROD');

require_once ('/var/www/common/_systems/heqc-online.php');

$app = new HEQConline (1);

$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    printf("Error: %s\n".$conn->error);
    exit();
}

$required_process = 165; // AC meeting and outcome
$required_ac = 87;
$required_user = 2870; // Louie


function pr($array){
	echo '<pre>';
	print_r($array);
	echo '</pre>';
}

/**************************************************************************************************************************************************************************
*  Identify the active_process to copy in order to create an active_process in the required process (e.g. evaluation, recommendation or AC.  The process to be copied may *
*  already be closed or there may be multiple of them.  Select the latest one.
*  This active process will contain the ia_proceedings_id of the proceedings to be copied.
			"H/H15/E205CAN",
			"H/H04/E182CAN"
************************************************************************************************************************************************************************/
	$sql_src = <<<SQLSRC
			SELECT CHE_reference_code, active_processes_id, application_id, tmp_aps.ia_proceedings_id, lkp_proceedings_ref
			FROM tmp_aps, ia_proceedings
			WHERE tmp_aps.ia_proceedings_id = ia_proceedings.ia_proceedings_id
			AND CHE_reference_code IN (
				"H/H15/E205CAN",
				"H/H04/E182CAN")
			AND processes_ref = {$required_process}
			AND proceeding_status_ind = 0
			AND active_processes_id IN (SELECT MAX(active_processes_id) FROM tmp_aps t WHERE t.CHE_reference_code = tmp_aps.CHE_reference_code GROUP BY processes_ref )
			ORDER BY CHE_reference_code, active_processes_id DESC
SQLSRC;
	echo $sql_src;
	$ref_arr = array();
	$rs_src = mysqli_query($conn, $sql_src) or die(mysqli_error($conn));
	while ($row = mysqli_fetch_array($rs_src)){
		array_push($ref_arr, $row);
	}
	pr($ref_arr);

	foreach ($ref_arr as $ref){
		pr($ref);

		/* 5. Close the old open active process for the proceedings*/
		$sql = <<<SQL
			UPDATE active_processes 
				SET status = 1 
				WHERE active_processes_id = (SELECT active_processes_id FROM tmp_aps WHERE ia_proceedings_id = {$ref["ia_proceedings_id"]} AND status = 0);
SQL;
		echo $sql;

		$upd = mysqli_query($conn, $sql) or die(mysqli_error($conn));
		echo "<br><br>Close the old open active process: " . mysqli_affected_rows($conn) . " rows affected" . "<br><br>";
	
	/* 6. Close the old proceedings */
		$sql = <<<SQL
			UPDATE ia_proceedings 
				SET proceeding_status_ind = 1, proceeding_status_date = CURDATE() 
				WHERE ia_proceedings_id = {$ref["ia_proceedings_id"]}
SQL;
		echo $sql;

		$upd = mysqli_query($conn, $sql) or die(mysqli_error($conn));
		echo "<br><br>Close the old open proceedings: " . mysqli_affected_rows($conn) . " rows affected" . "<br><br>";
		
	
		/* 1. Add a copy of the proceedings */
		$sql = <<<INSPROC
			INSERT INTO ia_proceedings 
				(ia_proceedings_id, application_ref, recomm_user_ref, lop_isSent, lop_isSent_date, lop_status_confirm, portal_sent_date, recomm_access_end_date, lkp_proceedings_ref, applic_background, eval_report_summary, 
				recomm_decision_ref, recomm_approve_comment, recomm_doc, minutes_discussion, ac_decision_ref, ac_meeting_ref, application_status_ref, proceeding_status_ind, prev_ia_proceedings_ref, 
				submission_date, recomm_complete_ind, applic_background_ac, eval_report_summary_ac, finind_complete_25, finind_complete_50, finind_complete_75, finind_complete_100, 
				screened_date, reaccreditation_application_ref, evaluator_access_end_date, lkp_AC_agenda_type_ref, 
                                	heqc_board_decision_ref, heqc_meeting_ready_ind, heqc_meeting_ref, decision_doc, decision_approved_ind, representation_ind, representation_doc,
                                	condition_doc, deferral_ind, deferral_doc, heqc_minutes_discussion, inst_outcome_accept_doc, applic_background_heqc, eval_report_summary_heqc,
                                	condition_complete_ind, condition_confirm_ind, heqc_outcome_approved_user_ref)
			SELECT NULL, application_ref, recomm_user_ref, lop_isSent, lop_isSent_date, lop_status_confirm, portal_sent_date, recomm_access_end_date, lkp_proceedings_ref, applic_background, eval_report_summary,
				recomm_decision_ref, recomm_approve_comment, recomm_doc, minutes_discussion, ac_decision_ref, {$required_ac}, application_status_ref, 0, {$ref["ia_proceedings_id"]},   
				submission_date, recomm_complete_ind, applic_background_ac, eval_report_summary_ac, finind_complete_25, finind_complete_50, finind_complete_75, finind_complete_100, 
				screened_date, reaccreditation_application_ref, evaluator_access_end_date, lkp_AC_agenda_type_ref, 
                                0, heqc_meeting_ready_ind, 0, decision_doc, decision_approved_ind, representation_ind, representation_doc, 
                                condition_doc, deferral_ind, deferral_doc, heqc_minutes_discussion, inst_outcome_accept_doc, applic_background_heqc, eval_report_summary_heqc,
                                condition_complete_ind, condition_confirm_ind, heqc_outcome_approved_user_ref
			FROM ia_proceedings
			WHERE ia_proceedings_id = {$ref["ia_proceedings_id"]};
INSPROC;
		echo $sql;

		$ins = mysqli_query($conn, $sql) or die(mysqli_error($conn));

		$new_proc_id = mysqli_insert_id($conn);
		echo "<br>ADD NEW PROCEEDINGS AS COPY OF OLD<br>New proceeding_id: " . $new_proc_id . "<br><br>";	

		/* Copy reasons and conditions for new proceedings recommendation */
		$sql = <<<RECOMM
			INSERT INTO ia_proceedings_recomm_decision
				(ia_proceedings_recomm_decision_id, ia_proceedings_ref,
				ia_conditions_proceedings_ref,
				decision_reason_condition,
				condition_term_ref,
				criterion_min_standard,
				condition_met_yn_ref,
				ia_conditions_ref)
			SELECT NULL, {$new_proc_id},
				ia_conditions_proceedings_ref,
				decision_reason_condition,
				condition_term_ref,
				criterion_min_standard,
				condition_met_yn_ref,
				ia_conditions_ref
			FROM ia_proceedings_recomm_decision WHERE ia_proceedings_ref = {$ref["ia_proceedings_id"]}
RECOMM;
		echo $sql;

		$ins = mysqli_query($conn, $sql) or die(mysqli_error($conn));
		echo "<br>RECOMM: ADD NEW PROCEEDINGS REASONS/CONDITIONS AS COPY OF OLD<br>New proceeding_id: " . mysqli_affected_rows($conn) . " rows affected" . "<br><br>";	

		/* Copy reasons and conditions for new proceedings AC */
		$sql = <<<AC
			INSERT INTO ia_proceedings_ac_decision
				(ia_proceedings_ac_decision_id, ia_proceedings_ref,
				ia_conditions_proceedings_ref,
				decision_reason_condition,
				condition_term_ref,
				criterion_min_standard,
				condition_met_yn_ref,
				ia_conditions_ref)
			SELECT NULL, {$new_proc_id},
				ia_conditions_proceedings_ref,
				decision_reason_condition,
				condition_term_ref,
				criterion_min_standard,
				condition_met_yn_ref,
				ia_conditions_ref
			FROM ia_proceedings_ac_decision WHERE ia_proceedings_ref = {$ref["ia_proceedings_id"]}
AC;
		echo $sql;

		$ins = mysqli_query($conn, $sql) or die(mysqli_error($conn));
		echo "<br>AC: ADD NEW PROCEEDINGS REASONS/CONDITIONS AS COPY OF OLD<br>New proceeding_id: " . mysqli_affected_rows($conn) . " rows affected" . "<br><br>";	

		$sql_eval = <<<EVALUATION
			SELECT application_ref, ia_proceedings_ref, evalReport_id 
			FROM evalReport
			WHERE evalReport.ia_proceedings_ref = {$ref["ia_proceedings_id"]}
			AND evalReport_doc > 0
EVALUATION;
		echo $sql_eval;
		$rs_eval = mysqli_query($conn, $sql_eval) or die(mysqli_error($conn));
		while ($eval = mysqli_fetch_array($rs_eval)){
			/* 2. Add a copy of the evaluator report/s */
			$sql = <<<INSEVAL
				INSERT INTO evalReport (evalReport_id, evalReport_q1, evalReport_q2, evalReport_q3, evalReport_q4, evalReport_q5, evalReport_q6, evalReport_q7, evalReport_q8, evalReport_q9, evalReport_q1_comp, 
					evalReport_q2_comp, evalReport_q3_comp, evalReport_q4_comp, evalReport_q5_comp, evalReport_q6_comp, evalReport_q7_comp, evalReport_q8_comp, evalReport_q9_comp, evalReport_comp, evalReport_comment1, 
					evalReport_comment2, evalReport_comment3, evalReport_comment4, evalReport_comment5, evalReport_comment6, evalReport_comment7, evalReport_comment8, evalReport_comment9, 1_eval_question_1, 
					1_eval_question_2, 1_eval_question_3, 1_eval_question_4, 1_eval_question_5, 1_eval_question_6, 1_eval_question_7, 1_eval_question_8, 2_eval_question_1, 2_eval_question_2, 2_eval_question_3, 
					2_eval_question_4, 2_eval_question_5, 3_eval_question_1, 3_eval_question_2, 3_eval_question_3, 3_eval_question_4, 4_eval_question_1, 4_eval_question_2, 4_eval_question_3, 5_eval_question_1, 
					5_eval_question_2, 5_eval_question_3, 5_eval_question_4, 6_eval_question_1, 6_eval_question_2, 6_eval_question_3, 6_eval_question_4, 6_eval_question_5, 6_eval_question_6, 7_eval_question_1, 
					7_eval_question_2, 7_eval_question_3, 7_eval_question_4, 7_eval_question_5, 8_eval_question_1, 8_eval_question_2, 8_eval_question_3, 9_eval_question_1, 9_eval_question_2, 9_eval_question_3, 
					9_eval_question_4, 9_eval_question_5, evalReport_completed, application_ref, ia_proceedings_ref, Persnr_ref, evalReport_date_sent, evalReport_date_screen, evalReport_date_completed, 
					evalReport_status_confirm, eval_site_visit_status_confirm, evalReport_accept, lop_isSent, lop_isSent_date, is_manager, eval_change_status, paper_eval_complete, active_process_ref, 
					accept_summary, decline_reason, do_summary, summary_done, application_sum_ref, application_sum_doc, pre_chosen_checkbox, do_sitevisit_checkbox, AC_desision_recommend, AC_conditions_recommend, 
					evalReport_doc, reaccreditation_application_ref, view_by_other_eval_yn_ref, eval_contract_doc)
				SELECT NULL, evalReport_q1, evalReport_q2, evalReport_q3, evalReport_q4, evalReport_q5, evalReport_q6, evalReport_q7, evalReport_q8, evalReport_q9, evalReport_q1_comp, 
					evalReport_q2_comp, evalReport_q3_comp, evalReport_q4_comp, evalReport_q5_comp, evalReport_q6_comp, evalReport_q7_comp, evalReport_q8_comp, evalReport_q9_comp, evalReport_comp, evalReport_comment1, 
					evalReport_comment2, evalReport_comment3, evalReport_comment4, evalReport_comment5, evalReport_comment6, evalReport_comment7, evalReport_comment8, evalReport_comment9, 1_eval_question_1, 
					1_eval_question_2, 1_eval_question_3, 1_eval_question_4, 1_eval_question_5, 1_eval_question_6, 1_eval_question_7, 1_eval_question_8, 2_eval_question_1, 2_eval_question_2, 2_eval_question_3, 
					2_eval_question_4, 2_eval_question_5, 3_eval_question_1, 3_eval_question_2, 3_eval_question_3, 3_eval_question_4, 4_eval_question_1, 4_eval_question_2, 4_eval_question_3, 5_eval_question_1, 
					5_eval_question_2, 5_eval_question_3, 5_eval_question_4, 6_eval_question_1, 6_eval_question_2, 6_eval_question_3, 6_eval_question_4, 6_eval_question_5, 6_eval_question_6, 7_eval_question_1, 
					7_eval_question_2, 7_eval_question_3, 7_eval_question_4, 7_eval_question_5, 8_eval_question_1, 8_eval_question_2, 8_eval_question_3, 9_eval_question_1, 9_eval_question_2, 9_eval_question_3, 
					9_eval_question_4, 9_eval_question_5, evalReport_completed, application_ref, {$new_proc_id} , Persnr_ref, evalReport_date_sent, evalReport_date_screen, evalReport_date_completed, 
					evalReport_status_confirm, eval_site_visit_status_confirm, evalReport_accept, lop_isSent, lop_isSent_date, is_manager, eval_change_status, paper_eval_complete, active_process_ref, 
					accept_summary, decline_reason, do_summary, summary_done, application_sum_ref, application_sum_doc, pre_chosen_checkbox, do_sitevisit_checkbox, AC_desision_recommend, AC_conditions_recommend, 
					evalReport_doc, reaccreditation_application_ref, view_by_other_eval_yn_ref, eval_contract_doc
				FROM evalReport
				WHERE evalReport_id = {$eval["evalReport_id"]}
INSEVAL;
			echo $sql . '<br><br>';

			$ins = mysqli_query($conn, $sql) or die(mysqli_error($conn));
			$new_eval_id = mysqli_insert_id($conn);
			echo "<br>ADD EVALUATION REPORT <br>New evalReport_id: " . $new_eval_id . "<br><br>";
			$ref["evalReport_id"] = $eval["evalReport_id"];

		}


		/* 3. Add a new active process as a copy of an active process that is in the required process. Note this id may be totally different from the currently open process */
		$sql = <<<SQL
			INSERT INTO active_processes (active_processes_id, processes_ref, work_flow_ref, user_ref, workflow_settings, status, last_updated, active_date, due_date, expiry_date) 
				SELECT  NULL,  processes_ref, work_flow_ref, $required_user, workflow_settings, 0, CURDATE(), active_date, due_date, expiry_date
				FROM active_processes 
				WHERE active_processes_id = {$ref["active_processes_id"]};
SQL;
		echo $sql . '<br><br>';

		$ins = mysqli_query($conn, $sql) or die(mysqli_error($conn));
		$new_ap_id = mysqli_insert_id($conn);
		echo "<br><br>New active_processes_id: " . $new_ap_id . "<br><br>";	

		
		/* 4. Update the active process to link to the new copied proceedings and new copied evaluator report/s */
		$sql = <<<UPDAP
			UPDATE active_processes
			SET workflow_settings = REPLACE(workflow_settings, "&DBINF_ia_proceedings___ia_proceedings_id={$ref["ia_proceedings_id"]}", "&DBINF_ia_proceedings___ia_proceedings_id={$new_proc_id}")
			WHERE active_processes_id = {$new_ap_id}
UPDAP;
		echo $sql . '<br><br>';
		$upd = mysqli_query($conn, $sql) or die(mysqli_error($conn));
		echo "<br><br>Update ia_proceedings_id in new active process" . mysqli_affected_rows($conn) . " rows affected" . "<br><br>";		

		$sql = <<<UPDAP
			UPDATE active_processes
			SET workflow_settings = REPLACE(workflow_settings, "&DBINF_evalReport___evalReport_id={$ref["evalReport_id"]}", "&DBINF_evalReport___evalReport_id={$new_eval_id}")
			WHERE active_processes_id = {$new_ap_id}
UPDAP;
		echo $sql . '<br><br>';
		$upd = mysqli_query($conn, $sql) or die(mysqli_error($conn));
		echo "<br><br>Update evalReport_id in " . mysqli_affected_rows($conn) . " rows affected" . "<br><br>";		

	}

//disconnect();
?>