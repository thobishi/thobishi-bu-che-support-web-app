<?php
	require_once("excel_report_head.php");
	
	$sheetTitle = 'Institutions contact list';
	$fileName = 'institutions_contact_list-generated_on_' . date('d_m_Y');
	
	$details = $nrOnline->getInstContactDetails($_GET, 'report_contact');
	
	$headings = array(
		'Institution code',
		'Institution name',
		'Programme abbr.',
		'Contact type',
		'Title',
		'Name',
		'Surname',
		'Initials',
		'Email',
		'Tel.',
		'Fax',
		'Mobile'
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
		foreach($details as $institution => $instInfo){
			foreach($instInfo as $type => $info){
				$column = 0;
				
				$worksheet->getColumnDimensionByColumn($column)->setAutoSize(true);
				$worksheet->setCellValueByColumnAndRow($column++, $row, ((isset($info['hei_code'])) ? ($info['hei_code']) : ''));
				
				$worksheet->getColumnDimensionByColumn($column)->setAutoSize(true);
				$worksheet->setCellValueByColumnAndRow($column++, $row, ((isset($info['hei_name'])) ? ($info['hei_name']) : ''));
				
				$worksheet->getColumnDimensionByColumn($column)->setAutoSize(true);
				$worksheet->setCellValueByColumnAndRow($column++, $row, ((isset($info['nr_programme_abbr'])) ? ($info['nr_programme_abbr']) : ''));
				
				$worksheet->getColumnDimensionByColumn($column)->setAutoSize(true);
				$worksheet->setCellValueByColumnAndRow($column++, $row, $type);
				
				$worksheet->getColumnDimensionByColumn($column)->setAutoSize(true);
				$worksheet->setCellValueByColumnAndRow($column++, $row, ((isset($info['title'])) ? ($info['title']) : ''));
				
				$worksheet->getColumnDimensionByColumn($column)->setAutoSize(true);
				$worksheet->setCellValueByColumnAndRow($column++, $row, ((isset($info['name'])) ? ($info['name']) : ''));
				
				$worksheet->getColumnDimensionByColumn($column)->setAutoSize(true);
				$worksheet->setCellValueByColumnAndRow($column++, $row, ((isset($info['surname'])) ? ($info['surname']) : ''));
				
				$worksheet->getColumnDimensionByColumn($column)->setAutoSize(true);
				$worksheet->setCellValueByColumnAndRow($column++, $row, ((isset($info['initials'])) ? ($info['initials']) : ''));
				
				$worksheet->getColumnDimensionByColumn($column)->setAutoSize(true);
				$worksheet->setCellValueByColumnAndRow($column++, $row, ((isset($info['email'])) ? ($info['email']) : ''));
				
				$worksheet->getColumnDimensionByColumn($column)->setAutoSize(true);
				$worksheet->setCellValueByColumnAndRow($column++, $row, ((isset($info['tel'])) ? ($info['tel']) : ''));
				
				$worksheet->getColumnDimensionByColumn($column)->setAutoSize(true);
				$worksheet->setCellValueByColumnAndRow($column++, $row, ((isset($info['fax'])) ? ($info['fax']) : ''));
				
				$worksheet->getColumnDimensionByColumn($column)->setAutoSize(true);
				$worksheet->setCellValueByColumnAndRow($column++, $row, ((isset($info['mobile'])) ? ($info['mobile']) : ''));
				
				$row++;
			}
		}
	}
	
	require_once("excel_report_foot.php");
?>