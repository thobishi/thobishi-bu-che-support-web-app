<?php
	ini_set("memory_limit","256M");
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
	
	//file_put_contents('php://stderr', print_r("xml \n".$objPHPExcel, TRUE));
	/*Split panes*/
	$objPHPExcel->getActiveSheet()->freezePane('A3');
	/*Background colour for headings, background*/
	$objPHPExcel->getActiveSheet()->getStyle('A1:P2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
	$objPHPExcel->getActiveSheet()->getStyle('A1:P2')->getFill()->getStartColor()->setRGB('C0C0C0');
	$styleArray = array(
		'borders' => array(
			'allborders' => array(
				'style' => PHPExcel_Style_Border::BORDER_THIN,
				'color' => array('argb' => '00000000'),
			),
		),
	);
	$objPHPExcel->getActiveSheet()->getStyle('A1:P2')->applyFromArray($styleArray);

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

	/*Headings*/
	$objPHPExcel->getActiveSheet()->getStyle('A1:A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->mergeCells('A1:A2');
	$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Programme');
	$objPHPExcel->getActiveSheet()->getStyle('B1:B2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->mergeCells('B1:B2');
	$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Submission date');
	$objPHPExcel->getActiveSheet()->getStyle('C1:C2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->mergeCells('C1:C2');
	$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Invoice date');
	$objPHPExcel->getActiveSheet()->getStyle('D1:D2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->mergeCells('D1:D2');
	$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Checklist');
	$objPHPExcel->getActiveSheet()->getStyle('E1:F1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->mergeCells('E1:F1');
	$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Evaluation');
	$objPHPExcel->getActiveSheet()->getStyle('E2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->SetCellValue('E2', 'Appoint');
	$objPHPExcel->getActiveSheet()->getStyle('F2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->SetCellValue('F2', 'Complete');
	$objPHPExcel->getActiveSheet()->getStyle('G1:G2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->mergeCells('G1:G2');
	$objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Site visit');
	$objPHPExcel->getActiveSheet()->getStyle('H1:H2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->mergeCells('H1:H2');
	$objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Secret recomm');
	$objPHPExcel->getActiveSheet()->getStyle('I1:J1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->mergeCells('I1:J1');
	$objPHPExcel->getActiveSheet()->SetCellValue('I1', 'Deferral');
	$objPHPExcel->getActiveSheet()->getStyle('I2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->SetCellValue('I2', 'Due');
	$objPHPExcel->getActiveSheet()->getStyle('J2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->SetCellValue('J2', 'Complete');
	$objPHPExcel->getActiveSheet()->getStyle('K1:K2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->mergeCells('K1:K2');
	$objPHPExcel->getActiveSheet()->SetCellValue('K1', 'AC meeting');
	$objPHPExcel->getActiveSheet()->getStyle('L1:M1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->mergeCells('L1:M1');
	$objPHPExcel->getActiveSheet()->SetCellValue('L1', 'Condition');
	$objPHPExcel->getActiveSheet()->getStyle('L2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->SetCellValue('L2', 'Due');
	$objPHPExcel->getActiveSheet()->getStyle('M2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->SetCellValue('M2', 'Met');
	$objPHPExcel->getActiveSheet()->getStyle('N1:O1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->mergeCells('N1:O1');
	$objPHPExcel->getActiveSheet()->SetCellValue('N1', 'Representation');
	$objPHPExcel->getActiveSheet()->getStyle('N2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->SetCellValue('N2', 'Submit');
	$objPHPExcel->getActiveSheet()->getStyle('O2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->SetCellValue('O2', 'Complete');
	$objPHPExcel->getActiveSheet()->getStyle('P1:P2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->mergeCells('P1:P2');
	$objPHPExcel->getActiveSheet()->SetCellValue('P1', 'Outcome');
	
	/*DATA*/
	//$filter_criteria = $_GET['data'];
	$data = readGet('data');
	$filter_criteria = '';
	if ($data > ''){
		$fc_arr = unserialize(base64_decode($data));
		$filter_criteria = (count($fc_arr) > 0) ? "WHERE ". implode(' AND ',$fc_arr) : "";
	}
	$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
        if ($conn->connect_errno) {
            $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
            printf("Error: %s\n".$conn->error);
            exit();
        }
	$sql = <<<REACCAPP
		SELECT * 
		FROM Institutions_application_reaccreditation
		LEFT JOIN lkp_reacc_decision ON reacc_decision_ref = lkp_reacc_id
		$filter_criteria
		ORDER BY referenceNumber
REACCAPP;

	$rs = mysqli_query($conn, $sql);
	$n_app = mysqli_num_rows($rs);
	
	if ($rs){

		$criteria = array("evalReport_status_confirm = 1");
		$rowNum = 3;
		$chrCount = 65;
		while ($row = mysqli_fetch_array($rs)){
			$chrCount = 65;
			$reaccred_id = $row["Institutions_application_reaccreditation_id"];
			$link1 = $app->scriptGetForm ('Institutions_application_reaccreditation', $reaccred_id, 'next');

			$pay_invoice_date = $app->getValueFromTable("payment","reaccreditation_application_ref",$reaccred_id,"date_invoice");

			$dash = 'N';
			$check = 'Y';
			$submission_date = ($row["reacc_submission_date"] > '1970-01-01') ? $row["reacc_submission_date"] : $dash;
			$invoice_date = ($pay_invoice_date > '1970-01-01') ? $pay_invoice_date : $dash;
			$checklist_date = ($row["reacc_checklist_date"] > '1970-01-01') ? $row["reacc_checklist_date"] : $dash;
			$a_evals = $app->getSelectedEvaluatorsForApplication($reaccred_id, $criteria, "Reaccred");
			$evaluator_sel = (count($a_evals) > 0) ? $check . ' ' : $dash;
			$evaluation_date = ($row["reacc_evaluation_date"] > '1970-01-01') ? $row["reacc_evaluation_date"] : $dash;
			$site_visit_date = ($row["reacc_sitevisit_date"] > '1970-01-01') ? $row["reacc_sitevisit_date"] : "";
			$secr_recomm_date = ($row["reacc_secretariate_date"] > '1970-01-01') ? $row["reacc_secretariate_date"] : $dash;
			

			$pdate = $row["reacc_deferdue_date"];
			
			$defer_due_date = $row["reacc_deferdue_date"];
//					$defer_due_date = ($row["reacc_deferdue_date"] > '1970-01-01') ? $row["reacc_deferdue_date"] : "&nbsp;";
			$defer_complete_date = ($row["reacc_defercomplete_date"] > '1970-01-01') ? $row["reacc_defercomplete_date"] : "";
			$ac_meeting_date = ($row["reacc_acmeeting_date"] > '1970-01-01') ? $row["reacc_acmeeting_date"] : $dash;
			$cond_due_date = $row["reacc_conditiondue_date"];
//					$cond_due_date = ($row["reacc_conditiondue_date"] > '1970-01-01') ? $row["reacc_conditiondue_date"] : "&nbsp;";
			$cond_met_date = ($row["reacc_conditionmet_date"] > '1970-01-01') ? $row["reacc_conditionmet_date"] : "";
			$repr_submit_date = ($row["reacc_reprsubmit_date"] > '1970-01-01') ? $row["reacc_reprsubmit_date"] : "";
			$repr_complete_date = ($row["reacc_reprcomplete_date"] > '1970-01-01') ? $row["reacc_reprcomplete_date"] : "";
			$outcome = ($row["lkp_reacc_title"] > '0') ? $row["lkp_reacc_title"] : $dash;

			$objPHPExcel->getActiveSheet()->getStyle(chr($chrCount).$rowNum)->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->setCellValue(chr($chrCount++).$rowNum, $row['referenceNumber'].' '.$row['programme_name']);
			$objPHPExcel->getActiveSheet()->setCellValue(chr($chrCount++).$rowNum, $submission_date);
			$objPHPExcel->getActiveSheet()->setCellValue(chr($chrCount++).$rowNum, $invoice_date);
			$objPHPExcel->getActiveSheet()->setCellValue(chr($chrCount++).$rowNum, $checklist_date);
			$objPHPExcel->getActiveSheet()->setCellValue(chr($chrCount++).$rowNum, $evaluator_sel);
			$objPHPExcel->getActiveSheet()->setCellValue(chr($chrCount++).$rowNum, $evaluation_date);
			$objPHPExcel->getActiveSheet()->setCellValue(chr($chrCount++).$rowNum, $site_visit_date);
			$objPHPExcel->getActiveSheet()->setCellValue(chr($chrCount++).$rowNum, $secr_recomm_date);
			$objPHPExcel->getActiveSheet()->setCellValue(chr($chrCount++).$rowNum, $defer_due_date);
			$objPHPExcel->getActiveSheet()->setCellValue(chr($chrCount++).$rowNum, $defer_complete_date);
			$objPHPExcel->getActiveSheet()->setCellValue(chr($chrCount++).$rowNum, $ac_meeting_date);
			$objPHPExcel->getActiveSheet()->setCellValue(chr($chrCount++).$rowNum, $cond_due_date);
			$objPHPExcel->getActiveSheet()->setCellValue(chr($chrCount++).$rowNum, $cond_met_date);
			$objPHPExcel->getActiveSheet()->setCellValue(chr($chrCount++).$rowNum, $repr_submit_date);
			$objPHPExcel->getActiveSheet()->setCellValue(chr($chrCount++).$rowNum, $repr_complete_date);
			$objPHPExcel->getActiveSheet()->setCellValue(chr($chrCount++).$rowNum, $outcome);
			
			$rowNum++;
		}
	}
	
	// Rename sheet
	$date = date('d_m_Y');
	$objPHPExcel->getActiveSheet()->setTitle('Re-Accreditation applications');
	
	//ob_end_clean();
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	//need to add a header here for XLS or XLSX
	header('Content-Disposition: attachment;filename="Re_Accreditation_applications.xls"');
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	//$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
	//ob_end_clean();
	$objWriter->save('php://output');