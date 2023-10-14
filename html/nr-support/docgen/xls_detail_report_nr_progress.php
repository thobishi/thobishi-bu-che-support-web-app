<?php
	require_once("excel_report_head.php");
	
	$sheetTitle = 'NR Progress report';
	$fileName = 'progress_report_national_reviews-generated_on_' . date('d_m_Y');
	
	$details = $nrOnline->getNRProgressDetails($_GET, 'detail_report_nr_progress');

	$headings = array(
		'Institution code' => array(),
		'Institution name' => array(),
		'Programme name' => array(),
		'HEQSF number' => array(),
		'Active process and person' => array(),		
		'Submission date' => array(),
		'Screening' => array(
			'Screener, date, report',
			'Preliminary Analyst',
			'Access dates',
			'Report',
			'Site visit date',
			'Panel members',
			'Access dates',
			'Chair report',
			'Additional documents',
			'Recommendation writer',
			'Access dates',
			'Due date',
			'Report',
			'Reference Committee report',
			'National Review Committee report'
		)
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
	
	foreach($headings as $heading => $data){
		if(!empty($data)){
			$worksheet->mergeCells(chr(65 + $column) . $row . ':' . chr(65 + $column  + count($data) - 1) . $row);
			$worksheet->setCellValueByColumnAndRow(($column++), $row, $heading);
			$worksheet->getColumnDimensionByColumn($column)->setAutoSize(true);
			$column--;
			foreach($data as $subHeading){
				$worksheet->setCellValueByColumnAndRow(($column++), ($row + 1), $subHeading);
				$worksheet->getColumnDimensionByColumn($column)->setAutoSize(true);
			}
		}
		else{
			$worksheet->mergeCells(chr(65 + $column) . $row . ':' . chr(65 + $column) . ($row + 1));
			$worksheet->setCellValueByColumnAndRow(($column++), $row, $heading);
			$worksheet->getColumnDimensionByColumn($column)->setAutoSize(true);
		}
	}
	$style = $worksheet->getStyle('A1:'.(PHPExcel_Cell::stringFromColumnIndex($maxColumn)).($row))->applyFromArray($headerArray);
	$row = 2;
	$style = $worksheet->getStyle('A2:'.(PHPExcel_Cell::stringFromColumnIndex($column - 1)).($row))->applyFromArray($headerArray);
	$worksheet->freezePane('A3');
	$row = 3;
	
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
			$worksheet->getStyle((PHPExcel_Cell::stringFromColumnIndex($column)).($row))->getAlignment()->setWrapText(true);
			$worksheet->setCellValueByColumnAndRow($column++, $row, ((isset($info['screening'])) ? (str_replace('<hr>', "\n\r", strip_tags($info['screening'], '<hr>'))) : ''));

			$worksheet->getColumnDimensionByColumn($column)->setAutoSize(true);
			$worksheet->setCellValueByColumnAndRow($column++, $row, ((isset($info['prelimAnalysis']['analyst'])) ? ($info['prelimAnalysis']['analyst']) : ''));
			
			$worksheet->getColumnDimensionByColumn($column)->setAutoSize(true);
			$worksheet->setCellValueByColumnAndRow($column++, $row, ((isset($info['prelimAnalysis']['accessDates'])) ? ($info['prelimAnalysis']['accessDates']) : ''));

			$worksheet->getColumnDimensionByColumn($column)->setAutoSize(true);
			$worksheet->setCellValueByColumnAndRow($column++, $row, ((isset($info['prelimAnalysis']['link_analyst_report'])) ? strip_tags(($info['prelimAnalysis']['link_analyst_report'])) : ''));
			
			$worksheet->getColumnDimensionByColumn($column)->setAutoSize(true);
			$worksheet->setCellValueByColumnAndRow($column++, $row, ((isset($info['panelDetails']['site_visit_date'])) ? ($info['panelDetails']['site_visit_date']) : ''));
			
			$worksheet->getColumnDimensionByColumn($column)->setAutoSize(true);
			$worksheet->setCellValueByColumnAndRow($column++, $row, ((isset($info['panelDetails']['members'])) ? strip_tags($info['panelDetails']['members']) : ''));
			
			$worksheet->getColumnDimensionByColumn($column)->setAutoSize(true);
			$worksheet->setCellValueByColumnAndRow($column++, $row, ((isset($info['panelDetails']['accessDates'])) ? ($info['panelDetails']['accessDates']) : ''));

			$worksheet->getColumnDimensionByColumn($column)->setAutoSize(true);
			$worksheet->setCellValueByColumnAndRow($column++, $row, ((isset($info['panelDetails']['link_panel_report'])) ? strip_tags(($info['panelDetails']['link_panel_report'])) : ''));
			
			$additionaldoclink = "";
			if(!empty($info['additionalDocArr'])){
				$totalDocs = count($info['additionalDocArr']) - 1;
				foreach ($info['additionalDocArr']  as $index => $additionalDoc){				
					$additionaldoclink .= $additionalDoc['docLink'];
					$additionaldoclink .= ($index < $totalDocs) ? ' | ' : '';
				}
			}
			$heqcRecommReport = $nrOnline->createDocLink($info['heqc_recommendation_report_doc'], "Reference Committee Report");
			$heqc_nrc_report_doc = $nrOnline->createDocLink($info['heqc_nrc_report_doc'], "National Review Committee Report");
			$worksheet->getColumnDimensionByColumn($column)->setAutoSize(true);

			$worksheet->setCellValueByColumnAndRow($column++, $row, (($additionaldoclink) ? strip_tags(($additionaldoclink)) : ''));					

			$worksheet->getColumnDimensionByColumn($column)->setAutoSize(true);
			$worksheet->setCellValueByColumnAndRow($column++, $row, ((isset($info['recommDetails']['recommWriter'])) ? ($info['recommDetails']['recommWriter']) : ''));
			
			$worksheet->getColumnDimensionByColumn($column)->setAutoSize(true);
			$worksheet->setCellValueByColumnAndRow($column++, $row, ((isset($info['recommDetails']['accessDates'])) ? strip_tags($info['recommDetails']['accessDates']) : ''));
			
			$worksheet->getColumnDimensionByColumn($column)->setAutoSize(true);
			$worksheet->setCellValueByColumnAndRow($column++, $row, ((isset($info['recommDetails']['due-date'])) ? ($info['recommDetails']['due-date']) : ''));

			$worksheet->getColumnDimensionByColumn($column)->setAutoSize(true);
			$worksheet->setCellValueByColumnAndRow($column++, $row, ((isset($info['recommDetails']['link_recomm_report'])) ? strip_tags(($info['recommDetails']['link_recomm_report'])) : ''));
			
			$worksheet->getColumnDimensionByColumn($column)->setAutoSize(true);
			$worksheet->setCellValueByColumnAndRow($column++, $row, ((isset($info['heqc_recommendation_report_doc'])) ? strip_tags($heqcRecommReport) : ''));			

			$worksheet->getColumnDimensionByColumn($column)->setAutoSize(true);
			$worksheet->setCellValueByColumnAndRow($column++, $row, ((isset($info['heqc_nrc_report_doc'])) ? strip_tags($heqc_nrc_report_doc) : ''));				
			$row++;
		}
	}
	
	require_once("excel_report_foot.php");
?>