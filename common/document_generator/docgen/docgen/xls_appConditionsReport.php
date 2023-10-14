<?php
	ini_set("memory_limit","128M");
	require_once("/var/www/html/common/xls_generator/cl_xls_generator.php");
	require_once ('/var/www/html/common/_systems/heqc-online.php');
	require_once('/var/www/html/common/workflow-1.0/class.octoToken.php');
	require_once('/var/www/html/common/workflow-1.0/class.octoDoc.php');
	$app = new HEQConline (1);
	octoDB::connect ();
	include '/var/www/html/common/PHPExcel/PHPExcel.php';

	// Create new PHPExcel object
	$objPHPExcel = new PHPExcel();

	// Add some data
	$objPHPExcel->setActiveSheetIndex(0);
	/*Split panes*/
	$objPHPExcel->getActiveSheet()->freezePane('A2');
	/*Background colour for headings, background*/
	$objPHPExcel->getActiveSheet()->getStyle('A1:O1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
	$objPHPExcel->getActiveSheet()->getStyle('A1:O1')->getFill()->getStartColor()->setRGB('C0C0C0');
	$styleArray = array(
		'borders' => array(
			'allborders' => array(
				'style' => PHPExcel_Style_Border::BORDER_THIN,
				'color' => array('argb' => '00000000'),
			),
		),
	);
	$objPHPExcel->getActiveSheet()->getStyle('A1:O1')->applyFromArray($styleArray);

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

	/*Headings*/
	$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Institution Code');
	$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Institution name');
	$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Programme name');
	$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Reference');
	$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Mode of delivery');
	$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Proceeding type');
	$objPHPExcel->getActiveSheet()->SetCellValue('G1', 'AC meeting');
	$objPHPExcel->getActiveSheet()->SetCellValue('H1', 'HEQC meeting');
	$objPHPExcel->getActiveSheet()->SetCellValue('I1', 'Outcome');
	$objPHPExcel->getActiveSheet()->SetCellValue('J1', 'Final Outcome');
	$objPHPExcel->getActiveSheet()->SetCellValue('K1', 'Condition type');
	$objPHPExcel->getActiveSheet()->SetCellValue('L1', 'Condition');
	$objPHPExcel->getActiveSheet()->SetCellValue('M1', 'Condition due');
	$objPHPExcel->getActiveSheet()->SetCellValue('N1', 'Criterion');
	$objPHPExcel->getActiveSheet()->SetCellValue('O1', 'Met/Not met');
	
	/*DATA*/
	$data = readGet('data');
	$filter_criteria = '';
	
	$conditionLkp = array(
		'Short-term' => 'condition_short_due_date',
		'Long-term' => 'condition_long_due_date',
		'Prior to commencement' => 'condition_prior_due_date',
		'Not applicable' => 'Not applicable'
	);
	
	if ($data > ''){
		$fc_arr = unserialize(base64_decode($data));
		$filter_criteria = (count($fc_arr) > 0) ? ' AND ' . implode(' AND ',$fc_arr) : "";
	}

	$sql = <<<SQL
			SELECT HEInstitution.HEI_id, HEInstitution.HEI_code, HEInstitution.HEI_name, Institutions_application.application_id, 
			Institutions_application.CHE_reference_code, Institutions_application.program_name,lkp_mode_of_delivery.lkp_mode_of_delivery_desc, ia_proceedings.ia_proceedings_id, 
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
SQL;
	// $app->printVars($sql);
	// exit;
	
	$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();

}

	$rs = mysqli_query($conn, $sql);
	$n_app = mysqli_num_rows($rs);
	
	if ($rs){


		$rowNum = 2;
		// $chrCount = 65;
		while ($row = mysqli_fetch_array($rs)){
			$chrCount = 65;
			$app_id = $row["application_id"];
			$row["Not applicable"] = '';
			$app_proc_id = $row["ia_proceedings_id"];
			$hei_code = $row["HEI_code"];
			$hei_name = $row["HEI_name"];
			$program_name = $row["program_name"];
			$proceedings_type = $row["lkp_proceedings_desc"];
			$reference = $row["CHE_reference_code"];
			$ac_meeting = ($row["ac_start_date"] > "1970-01-01") ? $row["ac_start_date"] : "";
			$heqc_meeting = ($row["heqc_start_date"] > "1970-01-01") ? $row["heqc_start_date"] : "";
			$outcome = $row["outcome"];
			$finalOutcome = $row["finalOutcome"];			 
			$cond_type = $row["lkp_condition_term_desc"];
			$condition = $row["decision_reason_condition"];
			$criterion = $row["criterion_min_standard"];
			$conditionDueDate = ($row[$conditionLkp[$cond_type]] > "1970-01-01") ? $row[$conditionLkp[$cond_type]] : "";
			$is_met = $app->is_condition_met($app_id, $condition);
			$mode_deliveryDesc = $row["lkp_mode_of_delivery_desc"];

			$objPHPExcel->getActiveSheet()->getStyle(chr($chrCount).$rowNum)->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->setCellValue(chr($chrCount++).$rowNum, $hei_code);
			$objPHPExcel->getActiveSheet()->setCellValue(chr($chrCount++).$rowNum, $hei_name);
			$objPHPExcel->getActiveSheet()->setCellValue(chr($chrCount++).$rowNum, $program_name);
			$objPHPExcel->getActiveSheet()->setCellValue(chr($chrCount++).$rowNum, $reference);
			$objPHPExcel->getActiveSheet()->setCellValue(chr($chrCount++).$rowNum, $mode_deliveryDesc);
			$objPHPExcel->getActiveSheet()->setCellValue(chr($chrCount++).$rowNum, $proceedings_type);
			$objPHPExcel->getActiveSheet()->setCellValue(chr($chrCount++).$rowNum, $ac_meeting);
			$objPHPExcel->getActiveSheet()->setCellValue(chr($chrCount++).$rowNum, $heqc_meeting);
			$objPHPExcel->getActiveSheet()->setCellValue(chr($chrCount++).$rowNum, $outcome);
			$objPHPExcel->getActiveSheet()->setCellValue(chr($chrCount++).$rowNum, $finalOutcome);
			$objPHPExcel->getActiveSheet()->setCellValue(chr($chrCount++).$rowNum, $cond_type);
			// $objPHPExcel->getActiveSheet()->getStyle(chr($chrCount).$rowNum)->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->setCellValue(chr($chrCount++).$rowNum, $condition);
			$objPHPExcel->getActiveSheet()->setCellValue(chr($chrCount++).$rowNum, $conditionDueDate);
			$objPHPExcel->getActiveSheet()->setCellValue(chr($chrCount++).$rowNum, $criterion);
			$objPHPExcel->getActiveSheet()->setCellValue(chr($chrCount++).$rowNum, $is_met);
			
			$rowNum++;
		}
	}
	
	// Rename sheet
	$date = date('d_m_Y');
	$objPHPExcel->getActiveSheet()->setTitle('Application Conditions Report');
	
	//ob_end_clean();
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	//need to add a header here for XLS or XLSX
	header('Content-Disposition: attachment;filename="Application_conditions_report.xls"');
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	//$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
	//ob_end_clean();
	$objWriter->save('php://output');