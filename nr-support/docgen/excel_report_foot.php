<?php
	// Rename sheet
	$date = date('d_m_Y');
	$worksheet->setTitle($sheetTitle);
	
	//ob_end_clean();
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	
	//need to add a header here for XLS or XLSX
	header('Content-Disposition: attachment;filename="' . $fileName . '.xls"');
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	
	//$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
	//ob_end_clean();
	$objWriter->save('php://output');
?>