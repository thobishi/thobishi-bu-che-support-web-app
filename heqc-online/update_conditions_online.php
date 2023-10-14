<?php
	include_once('database.php');
	$conn = connect();

	$app_array = array(
					array("ref" => 'H/H10/E036CAN', "ac" => 25, "heqc" => 11, "outcome_date" => "2013-04-17"),
					array("ref" => 'H/H10/E039CAN', "ac" => 25, "heqc" => 11, "outcome_date" => "2013-04-17")
	);
					
	foreach($app_array as $app):
		echo "<br>" . $app["ref"];
		echo all_conditions_met_no_conditional_proceedings($app);
	endforeach;

	function all_conditions_met_no_conditional_proceedings($app){
		// Get application_id
		$sql = <<<SQL
			SELECT application_id, institution_id FROM Institutions_application WHERE CHE_reference_code IN ('{$app["ref"]}');
SQL;
echo "<br><br>" . $sql;
		$rs = mysqli_query($sql) or die($sql . "<br>" . mysqli_error());
		$n = mysqli_num_rows($rs);
		if ($n != 1){
			$msg = "Application " . $app["ref"] . ": Cannot extract corresponding application_id";
			return $msg;
		} else {
			$row = mysqli_fetch_array($rs);
			$app_id = $row["application_id"];
			$inst_id = $row["institution_id"];
		}
		
		// Insert all conditions if they have not been inserted
		$sql = <<<SQL
			SELECT count(*) AS n_cond FROM `ia_conditions` WHERE `application_ref` in ({$app_id});
SQL;
echo "<br><br>" . $sql;
		$rs = mysqli_query($sql) or die($sql . "<br>" . mysqli_error());
		$row = mysqli_fetch_array($rs);
		$n_cond = $row["n_cond"];
		if ($n_cond > 0){
			$msg = "Application " . $app["ref"] . ": Conditions have already been inserted. Not processed. Exited.";
			return $msg;
		}
		
		// get previous proceedings
		$sql = <<<SQL
			SELECT MAX(ia_proceedings_id) AS prev_proc_id
			FROM ia_proceedings
			WHERE application_ref = {$app_id}
SQL;
echo "<br><br>" . $sql;
		$rs = mysqli_query($sql) or die($sql . "<br>" . mysqli_error());
		$row = mysqli_fetch_array($rs);
		$prev_proc_id = $row["prev_proc_id"];
		if ($prev_proc_id <= 0){
			$msg = "Application " . $app["ref"] . ": Invalid previous proceedings. Not processed. Exited.";
			return $msg;
		}
		
		$upd1 = <<<SQL
			UPDATE ia_proceedings 
			SET `proceeding_status_ind` = '1',
				`proceeding_status_date` = CURDATE() 
			WHERE `ia_proceedings`.`ia_proceedings_id` = {$prev_proc_id};
SQL;
echo "<br><br>" . $upd1;
		$rs = mysqli_query($upd1) or die($upd1 . "<br>" . mysqli_error());

		$upd2 = <<<SQL
			UPDATE Institutions_application 
			SET AC_desision = 1, AC_meeting_date = '{$app["outcome_date"]}' 
			WHERE application_id = {$app_id};
SQL;
echo "<br><br>" . $upd2;
		$rs = mysqli_query($upd2) or die($upd2 . "<br>" . mysqli_error());

		$ins1 = <<<SQL
			INSERT INTO  `ia_conditions` (  `ia_conditions_id` ,  `application_ref` ,  `decision_reason_condition` ,  `condition_term_ref` ,  `criterion_min_standard`,`condition_met_yn_ref`) 
			SELECT NULL , application_ref,  `decision_reason_condition` ,  `condition_term_ref` ,  `criterion_min_standard`, 2
			FROM ia_proceedings,  `ia_proceedings_heqc_decision` 
			WHERE ia_proceedings.ia_proceedings_id = ia_proceedings_heqc_decision.ia_proceedings_ref
			AND `condition_term_ref` IN ('p','s','l')
			AND application_ref IN ({$app_id})
SQL;
echo "<br><br>" . $ins1;
		$rs = mysqli_query($ins1) or die($ins1 . "<br>" . mysqli_error());
		echo "<br><br>" . $ins1 . "<br>" . mysqli_affected_rows() . " rows inserted";

		$ins2 = <<<SQL
			INSERT INTO `ia_proceedings` (`ia_proceedings_id`, `application_ref`,  `lkp_proceedings_ref`,  `ac_decision_ref`, `heqc_board_decision_ref`, 
			`ac_meeting_ref`, `heqc_meeting_ref`, `proceeding_status_ind`, `proceeding_status_date`, `prev_ia_proceedings_ref`, `condition_complete_ind`) 
			VALUES (NULL, {$app_id},  4, 1, 1, 
			{$app["ac"]}, {$app["heqc"]}, 0, '1970-01-01', {$prev_proc_id}, 1);
SQL;
echo "<br><br>" . $ins2;
		$rs = mysqli_query($ins2) or die($ins2 . "<br>" . mysqli_error());
		$proc_id = mysqli_insert_id();
		echo "<br><br>" . $ins2 . "<br>" . mysqli_affected_rows() . " rows inserted";
		
		$ins3 = <<<SQL
			INSERT INTO ia_conditions_proceedings (ia_conditions_proceedings_id, ia_conditions_ref, ia_proceedings_ref, decision_reason_condition,  condition_term_ref,  criterion_min_standard, eval_condition_met_yn_ref, eval_comment, recomm_condition_met_yn_ref, recomm_comment)
				SELECT NULL, ia_conditions_id, ia_proceedings_id, decision_reason_condition,  condition_term_ref,  criterion_min_standard , 
				2,'Load offline conditions 2013-10-14', 2, 'Load offline conditions 2013-10-14'
				FROM ia_conditions, ia_proceedings
				WHERE ia_conditions.application_ref = ia_proceedings.application_ref 
				AND ia_proceedings.ia_proceedings_id = {$proc_id}
SQL;
echo "<br><br>" . $ins3;
		$rs = mysqli_query($ins3) or die($ins3 . "<br>" . mysqli_error());
		echo "<br><br>" . $ins3 . "<br>" . mysqli_affected_rows() . " rows inserted";

		// Close current active process
		$upda = <<<SQL
			UPDATE active_processes
			SET status = 1 
			WHERE active_processes_id IN 
				(SELECT active_processes_id 
				 FROM tmp_aps 
				 WHERE status = 0 
				 AND application_id = {$app_id})
SQL;
echo "<br><br>" . $upda;
		$rs = mysqli_query($upda) or die($upda . "<br>" . mysqli_error());
		echo "<br><br>" . $upda . "<br>" . mysqli_affected_rows() . " rows updated";
		
		//Insert active process with conditional proceedings for Stella
		$ins4 = <<<SQL
			INSERT INTO `active_processes` (`active_processes_id`, `processes_ref`, `work_flow_ref`, `user_ref`, 
			`workflow_settings`, 
			`status`, `last_updated`, `active_date`, `due_date`, `expiry_date`) 
			VALUES (NULL, 170, 0, 284, 
			'PREV_WORKFLOW=168%7C11067&ACTPROC=&CURRENT_TABLE=ia_proceedings&DBINF____=NEW&DBINF_HEInstitution___HEI_id={$inst_id}&DBINF_Institutions_application___application_id={$app_id}&DBINF_users___user_id=NEW&DBINF_ia_proceedings___ia_proceedings_id={$proc_id}', 
			0, NOW(), '1970-01-01', '1970-01-01', '1970-01-01')
SQL;
echo "<br><br>" . $ins4;
		$rs = mysqli_query($ins4) or die($ins4 . "<br>" . mysqli_error());
		echo "<br><br>" . $ins4 . "<br>" . mysqli_affected_rows()  . " rows inserted";
		
		return true;
	}
?>