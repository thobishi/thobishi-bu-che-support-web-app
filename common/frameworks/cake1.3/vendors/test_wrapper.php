<?php 
require_once 'PHPExcel/PHPExcel/IOFactory.php';

class ExcelWrapper {
	private $fileName;
	private $curRow = 0;
	private $ExcelFile, $Worksheet, $Rows, $header;
	
	public function __construct($filename) {
		$this->fileName = $filename;
		
		$this->ExcelFile = PHPExcel_IOFactory::load($this->fileName);
		$this->Worksheet = $this->ExcelFile->setActiveSheetIndex(0);
		$this->Rows = $this->Worksheet->getRowIterator();
		
		return true;
	}
	
	public function readHead()
	{
		$dataHead = array();
		$this->Rows->rewind();
		$Row = $this->Rows->current();
		$Cells = $Row->getCellIterator();
		$count = 0;
		
		foreach($Cells as $index => $Cell) {
			$dataHead[$count]['name']   = trim($Cell->getCalculatedValue());
			$dataHead[$count]['column'] = trim($Cell->getColumn());
			$count++;
		}
		
		$this->header = $dataHead;
		
		return count($dataHead) > 0 ? $dataHead : false;
	}
	
	public function readNextRow()
	{
		if(!$this->Rows->valid()) {
			return false;
		}
		
		$count = 0;
		$dataRow = array();
		$header_data = array();
		$header = $this->header;
		
		foreach($header as $header_info){
			$header_data[$count]['column']  = $header_info['column'];
			$header_data[$count++]['title'] = $header_info['name'];
		}
		
		$this->Rows->next();
		$Row = $this->Rows->current();
		$Cells = $Row->getCellIterator();
		
		$columnNumber = 0;
		
		foreach($Cells as $index => $Cell) {
			foreach($header_data as $header){
				if(!isset($dataRow[Inflector::slug((low(preg_replace(array("/(.*)[\n\r]*.*/"), array('\\1'), $header['title']))))])){
					$dataRow[Inflector::slug((low(preg_replace(array("/(.*)[\n\r]*.*/"), array('\\1'), $header['title']))))] = '';
				}
				if($header['column'] == $Cell->getColumn()){
						$dataRow[Inflector::slug((low(preg_replace(array("/(.*)[\n\r]*.*/"), array('\\1'), $header['title']))))] = trim($Cell->getCalculatedValue());
				}
			}
		}
		return count($dataRow) > 0 ? $dataRow : false;
	}
	
	public function getHighestColumn()
	{
		return $this->Worksheet->getHighestColumn();
	}
	
	public function getHighestRow()
	{
		return $this->Worksheet->getHighestRow();
	}
	
	public function changeSheet($sheet){
	
		$this->Worksheet = $this->ExcelFile->setActiveSheetIndex($sheet);
		$this->Rows = $this->Worksheet->getRowIterator();
		
	}
	
	public function numSheets(){
	
		return count($this->ExcelFile->getAllSheets());
		
	}
	
	public function sheetTitle(){
	
		return $this->ExcelFile->getActiveSheet()->getTitle();
	
	}
	
	public function setHead($newHead)
	{
		$this->header = $newHead;
	}	
}