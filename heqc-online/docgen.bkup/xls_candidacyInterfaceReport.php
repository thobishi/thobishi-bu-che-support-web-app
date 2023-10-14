<?php
	ini_set("memory_limit","128M");
	require_once("/var/www/html/common/xls_generator/cl_xls_generator.php");
	require_once ('/var/www/html/common/_systems/heqc-online.php');
	require_once('/var/www/html/common/workflow-1.0/class.octoToken.php');
	require_once('/var/www/html/common/workflow-1.0/class.octoDoc.php');
    require_once('/var/www/html/common/workflow-1.0/class.dbConnect.php');

$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();

}


	$app = new HEQConline (1);
	octoDB::connect ();
	include '/var/www/html/common/PHPExcel/PHPExcel.php';

	/** PHPExcel_Writer_Excel5 */
	include '/var/www/html/common/PHPExcel/PHPExcel/Writer/Excel5.php';

	// Create new PHPExcel object
	$objPHPExcel = new PHPExcel();

	// Add some data
	$objPHPExcel->setActiveSheetIndex(0);
	/*Split panes*/
	$objPHPExcel->getActiveSheet()->freezePane('A2');
	/*Background colour for headings*/
	$objPHPExcel->getActiveSheet()->getStyle('A1:X1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
	$objPHPExcel->getActiveSheet()->getStyle('A1:X1')->getFill()->getStartColor()->setRGB('C0C0C0');

	/*Column widths*/
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(30);
	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(30);
	$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(30);
	$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(30);
	$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(30);
	$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(30);
	$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(30);
	$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(30);
	$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(30);
	$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(30);
	$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(30);
	$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(30);
	$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(30);
	$objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(30);
	$objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth(30);
	$objPHPExcel->getActiveSheet()->getColumnDimension('V')->setWidth(30);
	$objPHPExcel->getActiveSheet()->getColumnDimension('W')->setWidth(30);
	$objPHPExcel->getActiveSheet()->getColumnDimension('X')->setWidth(30);
	/*Headings*/
	$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Institution name');
	$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'HEQC reference number');
	$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Programme name');
	$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Mode of delivery');
	$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Submission date');
	$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Progress status');
	$objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Invoice date');
	$objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Payment/PQM confirmed');
	$objPHPExcel->getActiveSheet()->SetCellValue('I1', 'Screening');
	$objPHPExcel->getActiveSheet()->SetCellValue('J1', 'Evaluators appointed date');
	$objPHPExcel->getActiveSheet()->SetCellValue('K1', 'Evaluators report due');
	$objPHPExcel->getActiveSheet()->SetCellValue('L1', 'Evaluators report received');
	$objPHPExcel->getActiveSheet()->SetCellValue('M1', 'Directorate recommendation due');
	$objPHPExcel->getActiveSheet()->SetCellValue('N1', 'Directorate recommendation');
	$objPHPExcel->getActiveSheet()->SetCellValue('O1', 'Date of AC meeting');
	$objPHPExcel->getActiveSheet()->SetCellValue('P1', 'Deferral due date');
	$objPHPExcel->getActiveSheet()->SetCellValue('Q1', 'Deferral Information received');
	$objPHPExcel->getActiveSheet()->SetCellValue('R1', 'Date of HEQC Board meeting');
	$objPHPExcel->getActiveSheet()->SetCellValue('S1', 'Outcome');
	$objPHPExcel->getActiveSheet()->SetCellValue('T1', 'Conditions due');
	$objPHPExcel->getActiveSheet()->SetCellValue('U1', 'Conditions report');
	$objPHPExcel->getActiveSheet()->SetCellValue('V1', 'Representations Submission date');
	$objPHPExcel->getActiveSheet()->SetCellValue('W1', 'Representations due');
	$objPHPExcel->getActiveSheet()->SetCellValue('X1', 'Representations report');

	$data = readGet('data');
	$filter_criteria = '';
	if ($data > ''){
		// 2012-02-06 Robin: base64_decode failing on decoding the date strings causing serailize to fail. Removing serialize.
		// 2014-09-26 < and > are not part of base64 alphabet.  Replace < and > with alternate characters.
		//$fc_arr = unserialize(base64_decode($data));

		$fc_criteria = base64_decode($data);
		$fc_replace = str_replace("+gt+",">",$fc_criteria);
		$filter_criteria = str_replace("+lt+","<",$fc_replace);
	}

	$sql = <<<APP
		SELECT Distinct HEInstitution.HEI_name,
			HEInstitution.HEI_code,
			Institutions_application.institution_id,
			Institutions_application.application_id,
			Institutions_application.submission_date,
			Institutions_application.CHE_reference_code,
			Institutions_application.program_name,
			Institutions_application.mode_delivery,
			Institutions_application.secretariat_doc,
			Institutions_application.AC_Meeting_date,
			Institutions_application.AC_desision,
			Institutions_application.AC_conditions_doc,
			lkp_application_status.lkp_application_status_desc,
			Institutions_application.evaluator_access_end_date
		FROM Institutions_application
		LEFT JOIN ia_proceedings ON ia_proceedings.application_ref = Institutions_application.application_id
		LEFT JOIN AC_Meeting AS iap ON iap.ac_id = ia_proceedings.ac_meeting_ref
		LEFT JOIN AC_Meeting AS app ON app.ac_id = Institutions_application.AC_Meeting_ref
		LEFT JOIN HEQC_Meeting ON HEQC_Meeting.heqc_id = ia_proceedings.heqc_meeting_ref
		LEFT JOIN payment ON payment.application_ref = Institutions_application.application_id		
		LEFT JOIN HEInstitution ON HEInstitution.HEI_id = Institutions_application.institution_id
		LEFT JOIN evalReport ON evalReport.application_ref = Institutions_application.application_id
		LEFT JOIN lkp_application_status ON lkp_application_status.lkp_application_status_id = Institutions_application.application_status
		WHERE Institutions_application.submission_date > '1000-01-01'
		$filter_criteria
		ORDER BY Institutions_application.CHE_reference_code
APP;
//echo $sql;
	$rs = mysqli_query($conn, $sql) or die(mysqli_error());
	$n_app = mysqli_num_rows($rs);
	//echo 'Number of rows : '.$n_app;
	$cross = 'N';
	$check = 'Y';
	$notapplic = 'n/a';
	$historic = 'historic';
	
	$rowNum = 2;
	while ($row = mysqli_fetch_assoc($rs)){

		/*
		echo '<pre>';
		print_r($row);
		echo '</pre>';
		*/
		$chrCount = 65;
		$app_id = $row["application_id"];
		$inst_id = $row["institution_id"];
		$link1 = $app->scriptGetForm ('Institutions_application', $app_id, 'next');
		$mode_deliveryDesc = $app->getValueFromTable("lkp_mode_of_delivery","lkp_mode_of_delivery_id",$row["mode_delivery"],"lkp_mode_of_delivery_desc");

		$tmpSettings = "PREV_WORKFLOW=36%7C213&DBINF_HEInstitution___HEI_id=".$inst_id."&DBINF_institutional_profile___institution_ref=".$inst_id."&DBINF_Institutions_application___application_id=".$app_id;
		$applicationLink = '<a href="javascript:winPrintApplicationForm(\'Application Form\',\''.$app_id.'\', \''.base64_encode($tmpSettings).'\', \'\');">'.$row["CHE_reference_code"].'</a>';

		$submission_date = $row["submission_date"];

		// Get screening id for this application
		$screen_id = $app->getValueFromTable("screening","application_ref",$app_id,"screening_id");

		// For checking if checklisting is complete.  Value in table implies that process has been done.
		// User can answer yes or no to the question.
		//$checklist = $this->getValueFromTable('screening_completion','screening_ref',$screen_id,'yes_no');

		// For checking if screening process has been done.  On the last screening page there is a checklist
		// of whether each criteria has been complied with.  If the screening_compliance table has values in it
		// for this application then assume that screening is done.
		$screen_ind = $app->getValueFromTable('screening_compliance','screening_ref',$screen_id,'regulation_ref');
		$screening = ($screen_ind >= 1) ? $check . ' ' : $cross;

		$proc_arr = getActiveProcessforApp($app_id);
		$process =  '('. $proc_arr['name'] .')';
		//$process = '';

		$invoice_date = $notapplic;
		$recv_confirm = $notapplic;
		
		// Only private applications have payment information
		// 2013-11-25 robin: Display payment information if there is payment information
		//if (strpos($row["CHE_reference_code"],"/PR") > 0){  // Private applications
		$app_pay_data = $app->getPayData($app_id, "application_ref");
		if (count($app_pay_data) > 0){	
			if (strpos($row["CHE_reference_code"],"/K") > 0){  // Re-accreditation applications needed an application loaded with core details. Gave them reference numbers containing a K.
				$invoice_date = $historic;
				$recv_confirm = $historic;
			}else{
				$invoice_date = "";
				$recv_confirm = "";
				foreach($app_pay_data as $app_pay){
					$invoice_sent = ($app_pay["invoice_sent"] == 1) ? $check . ' ' : $cross;
					$invoice_date .= (($app_pay["date_invoice"] > '1000-01-01') ? $app_pay["date_invoice"] : $invoice_sent) . "\n";
					$recv_confirm .= (($app_pay["received_confirmation"] == 1) ? $check . "(paid)" : (($app_pay["date_cancelled"] > '1000-01-01') ? "cancelled-" . $app_pay["date_cancelled"] : $cross . "(not paid)")) . "\n";
				}
			}
		}

		// Get values for evaluation
		// 1. Get evaluators appointed for this application
		$criteria = array("(evalReport_status_confirm = 1 OR evalReport_doc > 0)");  // Evaluators has confirmed that he will evaluate this application
		$a_evals = $app->getSelectedEvaluatorsForApplication($app_id, $criteria);
		$evaluator_sel = "";
		$eval_appoint_date = "";

		// Get evaluators evaluation report due date.  This co-incides with the evaluator access portal end date.  They may no longer have access
		// once there report is due.  They need access while doing their report.  The date is specified per application.
		$eval_report_due = "";
		
		foreach ($a_evals as $a_eval){
			$eval_name = $a_eval['Surname'];
			$evaluator_sel .= $eval_name."\n";
			$eval_report_due .= $row["evaluator_access_end_date"]."\n";
			$eval_appoint_date .= ($a_eval["evalReport_date_sent"] > '1000-01-01') ? $a_eval["evalReport_date_sent"]."\n" : "\n";
		}
		$evaluator_sel = ($evaluator_sel == "") ? '' : $evaluator_sel;
		$eval_appoint_date = ($eval_appoint_date == "") ? '' : $eval_appoint_date;
		$eval_report_due = ($eval_report_due == "") ? '' : $eval_report_due;
		
		$dir_recomm = $cross;
		if ($row["secretariat_doc"] > 0){
			$dir_recomm_doc = new octoDoc($row["secretariat_doc"]);
			$dir_recomm	 = $dir_recomm_doc->getFilename();
		}
		
		//$ac_meeting_date = ($row["AC_Meeting_date"] > '1000-01-01') ? $row["AC_Meeting_date"] : $cross;
		$ac_meeting_date =  $row["AC_Meeting_date"];


		$d_outcome = "";
		if ($row["AC_desision"] > 0){
			$d_outcome = $app->getValueFromTable('lkp_desicion','lkp_id',$row["AC_desision"],'lkp_title');
		}
		
		$objPHPExcel->getActiveSheet()->getStyle(chr($chrCount).$rowNum)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->setCellValue(chr($chrCount++).$rowNum, $row['HEI_name']);
		$objPHPExcel->getActiveSheet()->setCellValue(chr($chrCount++).$rowNum, $row["CHE_reference_code"]);
		$objPHPExcel->getActiveSheet()->getStyle(chr($chrCount).$rowNum)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->setCellValue(chr($chrCount++).$rowNum, $row['program_name']);
		$objPHPExcel->getActiveSheet()->setCellValue(chr($chrCount++).$rowNum, $mode_deliveryDesc);
		$objPHPExcel->getActiveSheet()->setCellValue(chr($chrCount++).$rowNum, $submission_date);
		$objPHPExcel->getActiveSheet()->getStyle(chr($chrCount).$rowNum)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->setCellValue(chr($chrCount++).$rowNum, $row['lkp_application_status_desc'].' '.$process);
		$objPHPExcel->getActiveSheet()->setCellValue(chr($chrCount++).$rowNum, $invoice_date);
		$objPHPExcel->getActiveSheet()->setCellValue(chr($chrCount++).$rowNum, $recv_confirm);
		$objPHPExcel->getActiveSheet()->setCellValue(chr($chrCount++).$rowNum, $screening);
		$objPHPExcel->getActiveSheet()->getStyle(chr($chrCount).$rowNum)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->setCellValue(chr($chrCount++).$rowNum, $eval_appoint_date);
		$objPHPExcel->getActiveSheet()->getStyle(chr($chrCount).$rowNum)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->setCellValue(chr($chrCount++).$rowNum, $eval_report_due);
		$objPHPExcel->getActiveSheet()->getStyle(chr($chrCount).$rowNum)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->setCellValue(chr($chrCount++).$rowNum, $evaluator_sel);
		$objPHPExcel->getActiveSheet()->setCellValue(chr($chrCount++).$rowNum, '');
		$objPHPExcel->getActiveSheet()->setCellValue(chr($chrCount++).$rowNum, $dir_recomm);
		$objPHPExcel->getActiveSheet()->setCellValue(chr($chrCount++).$rowNum, $ac_meeting_date);
		$objPHPExcel->getActiveSheet()->setCellValue(chr($chrCount++).$rowNum, '');
		$objPHPExcel->getActiveSheet()->setCellValue(chr($chrCount++).$rowNum, '');
		$objPHPExcel->getActiveSheet()->setCellValue(chr($chrCount++).$rowNum, '');
		$objPHPExcel->getActiveSheet()->setCellValue(chr($chrCount++).$rowNum, $d_outcome);
		$objPHPExcel->getActiveSheet()->setCellValue(chr($chrCount++).$rowNum, '');
		$objPHPExcel->getActiveSheet()->setCellValue(chr($chrCount++).$rowNum, '');
		$objPHPExcel->getActiveSheet()->setCellValue(chr($chrCount++).$rowNum, '');
		$objPHPExcel->getActiveSheet()->setCellValue(chr($chrCount++).$rowNum, '');
		$objPHPExcel->getActiveSheet()->setCellValue(chr($chrCount++).$rowNum, '');
		
		$psql = <<<PROCEEDINGS
		SELECT *
		FROM ia_proceedings
		LEFT JOIN lkp_proceedings ON lkp_proceedings_id = lkp_proceedings_ref
		LEFT JOIN lkp_application_status ON lkp_application_status.lkp_application_status_id = ia_proceedings.application_status_ref
		LEFT JOIN AC_Meeting ON AC_Meeting.ac_id = ia_proceedings.ac_meeting_ref
		LEFT JOIN HEQC_Meeting ON HEQC_Meeting.heqc_id = ia_proceedings.heqc_meeting_ref
		WHERE application_ref = $app_id
PROCEEDINGS;
			
		$prs = mysqli_query($conn, $psql);
		if ($prs){
			while ($prow = mysqli_fetch_array($prs)){	

				$app_proc_id = $prow["ia_proceedings_id"];
				$aplink = "&nbsp;";
				if ($prow["proceeding_status_ind"] == 0){ // Only editable if proceedings has not closed.
					$plink = $app->scriptGetForm ('ia_proceedings', $app_proc_id, '_startEditProceedings');
					$aplink = "<a href='".$plink."'>Edit</a>";
				}
				$p_submission_date = "";
				$p_invoice_date = "";
				$p_recv_confirm = "";
				$p_pay_data = $app->getPayData($app_proc_id, "ia_proceedings_ref");
				if (count($p_pay_data) > 0){	
					foreach($p_pay_data as $p_pay){
						$p_invoice_date = $p_pay["date_invoice"] . "\n";
						$p_recv_confirm .= (($p_pay["received_confirmation"] == 1) ? $check . "(paid)" : (($p_pay["date_cancelled"] > '1000-01-01') ? "cancelled-" . $p_pay["date_cancelled"] : $cross . "(not paid)")) . "\n";
					}
				}
				$p_screening = "";
				$p_eval_appoint_date = "";
				$p_eval_report_due = "";
				$p_evaluator_sel = "";
				$p_recomm_access_due_date = $prow["recomm_access_end_date"];
				$p_dir_recomm = $cross;
				if ($prow["recomm_doc"] > 0){
					$p_dir_recomm_doc = new octoDoc($prow["recomm_doc"]);
					$p_dir_recomm = $p_dir_recomm_doc->getFilename();
				}
				$p_ac_meeting_date = ($prow["ac_start_date"] > "") ? $prow["ac_start_date"] : "";
				$p_heqc_meeting_date = ($prow["heqc_start_date"] > "") ? $prow["heqc_start_date"] : "";
				$decision = $prow["heqc_board_decision_ref"];
				$decision_due_date = $prow["heqc_decision_due_date"];
				$defer_due_date = $condition_due_date = $representation_due_date = "";
				$p_heqc_outcome = "";
				$defer_doc = $condition_doc = $representation_doc = "";
				$defer_doc_ind = $condition_doc_ind = $representation_doc_ind = 0;
				if ($prow["deferral_doc"] > 0){
					$defer_octoDoc = new octoDoc($prow["deferral_doc"]);
					$defer_doc = $defer_octoDoc->getFilename();
				}
				if ($prow["condition_doc"] > 0){
					$condition_octoDoc = new octoDoc($prow["condition_doc"]);
					$condition_doc = $condition_octoDoc->getFilename();
				}
				if ($prow["representation_doc"] > 0){
					$repr_octoDoc = new octoDoc($prow["representation_doc"]);
					$repr_doc = $repr_octoDoc->getFilename();
				}
				if ($decision > 0){
					$p_heqc_outcome = $app->getValueFromTable('lkp_desicion','lkp_id',$prow["heqc_board_decision_ref"],'lkp_title');
					if ($prow["decision_doc"] > 0){
						$p_octoDoc = new octoDoc($prow["decision_doc"]);
						$p_heqc_outcome	 = $p_heqc_outcome;
					}
					// Get due dates
					if ($decision == 4){ 
						$defer_due_date = $decision_due_date;//formatDate($decision_due_date,$defer_doc_ind);
					}
					if ($decision == 2){ 
						$condition_due_date = $decision_due_date;//formatDate($decision_due_date, $condition_doc_ind);
					}
					if ($decision == 3){
						$representation_due_date = $decision_due_date;//formatDate($decision_due_date, $repr_doc_ind);
					}
				}
				$representation_submission_date = $prow["representation_submission_date"];
				
				$rowNum++;
				$chrCount = 65;
				$objPHPExcel->getActiveSheet()->getStyle(chr($chrCount).$rowNum.':'.chr($chrCount+2).$rowNum)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle(chr($chrCount).$rowNum.':'.chr($chrCount+22).$rowNum)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
				$objPHPExcel->getActiveSheet()->getStyle(chr($chrCount).$rowNum.':'.chr($chrCount+22).$rowNum)->getFill()->getStartColor()->setRGB('C1CFF1');				
				$objPHPExcel->getActiveSheet()->getStyle(chr($chrCount).$rowNum.':'.chr($chrCount+2).$rowNum)->getAlignment()->setWrapText(true);
				$objPHPExcel->getActiveSheet()->setCellValue(chr($chrCount).$rowNum, 'Proceedings: '.$prow['lkp_proceedings_desc']);
				$objPHPExcel->getActiveSheet()->mergeCells(chr($chrCount).$rowNum.':'.chr($chrCount+=2).$rowNum);
				$chrCount++;
				$objPHPExcel->getActiveSheet()->setCellValue(chr($chrCount++).$rowNum, $prow['submission_date']);
				$objPHPExcel->getActiveSheet()->getStyle(chr($chrCount).$rowNum)->getAlignment()->setWrapText(true);
				$objPHPExcel->getActiveSheet()->setCellValue(chr($chrCount++).$rowNum, $prow['lkp_application_status_desc']);
				$objPHPExcel->getActiveSheet()->setCellValue(chr($chrCount++).$rowNum, $p_invoice_date);
				$objPHPExcel->getActiveSheet()->setCellValue(chr($chrCount++).$rowNum, $p_recv_confirm);
				$objPHPExcel->getActiveSheet()->setCellValue(chr($chrCount++).$rowNum, $p_screening);
				$objPHPExcel->getActiveSheet()->getStyle(chr($chrCount).$rowNum)->getAlignment()->setWrapText(true);
				$objPHPExcel->getActiveSheet()->setCellValue(chr($chrCount++).$rowNum, $p_eval_appoint_date);
				$objPHPExcel->getActiveSheet()->getStyle(chr($chrCount).$rowNum)->getAlignment()->setWrapText(true);
				$objPHPExcel->getActiveSheet()->setCellValue(chr($chrCount++).$rowNum, $p_eval_report_due);
				$objPHPExcel->getActiveSheet()->getStyle(chr($chrCount).$rowNum)->getAlignment()->setWrapText(true);
				$objPHPExcel->getActiveSheet()->setCellValue(chr($chrCount++).$rowNum, $p_evaluator_sel);
				$objPHPExcel->getActiveSheet()->setCellValue(chr($chrCount++).$rowNum, $p_recomm_access_due_date);
				$objPHPExcel->getActiveSheet()->setCellValue(chr($chrCount++).$rowNum, $p_dir_recomm);
				$objPHPExcel->getActiveSheet()->setCellValue(chr($chrCount++).$rowNum, $p_ac_meeting_date);
				$objPHPExcel->getActiveSheet()->setCellValue(chr($chrCount++).$rowNum, $defer_due_date);
				$objPHPExcel->getActiveSheet()->setCellValue(chr($chrCount++).$rowNum, $defer_doc);
				$objPHPExcel->getActiveSheet()->setCellValue(chr($chrCount++).$rowNum, $p_heqc_meeting_date);
				$objPHPExcel->getActiveSheet()->setCellValue(chr($chrCount++).$rowNum, $p_heqc_outcome);
				$objPHPExcel->getActiveSheet()->setCellValue(chr($chrCount++).$rowNum, $condition_due_date);
				$objPHPExcel->getActiveSheet()->setCellValue(chr($chrCount++).$rowNum, $condition_doc);
				$objPHPExcel->getActiveSheet()->setCellValue(chr($chrCount++).$rowNum, $representation_submission_date);
				$objPHPExcel->getActiveSheet()->setCellValue(chr($chrCount++).$rowNum, $representation_due_date);
				$objPHPExcel->getActiveSheet()->setCellValue(chr($chrCount++).$rowNum, $representation_doc);

			}
		}
		$rowNum++;
	}

	// Rename sheet
	$date = date('d_m_Y');
	$objPHPExcel->getActiveSheet()->setTitle('accreditation_applications');
	
	//ob_end_clean();
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	//need to add a header here for XLS or XLSX
	header('Content-Disposition: attachment;filename="accreditation_applications.xls"');
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	//$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
	//ob_end_clean();
	$objWriter->save('php://output');
	
	function getActiveProcessforApp($app_id){
		$process['name'] = '';
		$sql = <<<PROCESS
			SELECT processes.processes_desc, users.name
			FROM active_processes
			LEFT JOIN processes ON active_processes.processes_ref = processes.processes_id
			LEFT JOIN users ON active_processes.user_ref = users.user_id
			WHERE active_processes.status = 0
			AND (active_processes.workflow_settings like '%application_id={$app_id}&%')
PROCESS;
//OR active_processes.workflow_settings like '%application_id={$app_id}'  - REMOVED BECAUSE OF PERFORMANCE

 		$rs = mysqli_query($conn, $sql);
		$n = mysqli_num_rows($rs);
		if ($n > 0){
			while ($row = mysqli_fetch_array($rs)){
				$process['name'] .= $row['processes_desc'] . "-" . $row['name'];
			}
		} else {
			$process['name'] = 'closed';
		}
		return $process;
	}	
	
	/*
	Crosses and ticks
	*/
?>