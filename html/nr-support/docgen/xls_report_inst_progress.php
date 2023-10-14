<?php
	require_once("excel_report_head.php");
	
	$sheetTitle = 'Institution progress report';
	$fileName = 'institutions_progress_report-generated_on_' . date('d_m_Y');
	
	$details = $nrOnline->getInstProgressDetails($_GET, 'report_inst_progress');
	
	$headings = array(
		'Institution code',
		'Institution name',
		'Programme name',
		'HEQSF number',
		'Active process and person',
		'Submission date',
		'Site visit date'
	);
	
	$headerArray = array(
		'font' => array(
			'bold' => true
		),
		'fill' => array(
			'type' => PHPExcel_Style_Fill::FILL_SOLID,
			'startcolor' => array(
				'rgb' => 'E0E0E0'
			)
		)
	);
	
	$maxColumn = count($headings) - 1;
	
	$column = 0;
	$row = 1;
	$style = $worksheet->getStyle('A1:'.(PHPExcel_Cell::stringFromColumnIndex($maxColumn)).($row))->applyFromArray($headerArray);
	$worksheet->freezePane('A2');
	
	foreach($headings as $heading) {
		$worksheet->setCellValueByColumnAndRow(($column++),$row, $heading);
	}
	
	$row = 2;
	
	if(!empty($details)){
		foreach($details as $info){
			$column = 0;
			
			$worksheet->getColumnDimensionByColumn($column)->setAutoSize(true);
			$worksheet->setCellValueByColumnAndRow($column++, $row, ((isset($info['hei_code'])) ? ($info['hei_code']) : ''));
			
			$worksheet->getColumnDimensionByColumn($column)->setAutoSize(true);
			$worksheet->setCellValueByColumnAndRow($column++, $row, ((isset($info['hei_name'])) ? ($info['hei_name']) : ''));
			
			$worksheet->getColumnDimensionByColumn($column)->setAutoSize(true);
			$worksheet->setCellValueByColumnAndRow($column++, $row, ((isset($info['nr_programme_name'])) ? ($info['nr_programme_name']) : ''));
			
			$worksheet->getColumnDimensionByColumn($column)->setAutoSize(true);
			$worksheet->setCellValueByColumnAndRow($column++, $row, ((isset($info['heqsf_reference_no'])) ? ($info['heqsf_reference_no']) : ''));
			
			$worksheet->getColumnDimensionByColumn($column)->setAutoSize(true);
			$worksheet->setCellValueByColumnAndRow($column++, $row, ((isset($info['active_process_person'])) ? ($info['active_process_person']) : ''));
			
			$worksheet->getColumnDimensionByColumn($column)->setAutoSize(true);
			$worksheet->setCellValueByColumnAndRow($column++, $row, ((isset($info['date_submitted']) && $info['date_submitted'] != '1970-01-01') ? ($info['date_submitted']) : 'Not submitted'));
			
			$worksheet->getColumnDimensionByColumn($column)->setAutoSize(true);
			$worksheet->setCellValueByColumnAndRow($column++, $row, 'Not visited');
			
			$row++;
		}
	}
	
	require_once("excel_report_foot.php");
?>