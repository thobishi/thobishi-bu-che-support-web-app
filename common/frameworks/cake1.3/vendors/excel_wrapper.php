<?php
require_once 'PHPExcel/PHPExcel/IOFactory.php';

class ExcelWrapper {
	private $fileName;
	private $curRow = 0;
	private $headerRow = 1;
	private $ExcelFile, $Worksheet, $Rows, $header;
	
	public function __construct($filename, $headerRow = 1) {
		$this->fileName = $filename;
		$this->headerRow = $headerRow;
		
		$this->ExcelFile = PHPExcel_IOFactory::load($this->fileName);
		$this->Worksheet = $this->ExcelFile->setActiveSheetIndex(0);
		$this->Rows = $this->Worksheet->getRowIterator();
		
		return true;
	}
	
	public function readHead()
	{
		$dataHead = array();
		$this->Rows->rewind();
		for($i=0;$i<$this->headerRow-1;$i++) {
			$this->Rows->next();
		}
		$Row = $this->Rows->current();
		$Cells = $Row->getCellIterator();
		
		foreach($Cells as $index => $Cell) {
			$dataHead[] = trim($Cell->getCalculatedValue());
		}
		
		$this->header = $dataHead;
		
		return count($dataHead) > 0 ? $dataHead : false;
	}
	
	public function readNextRow()
	{
		if(!$this->Rows->valid()) {
			return false;
		}
		
		$dataRow = array();
		$headers = empty($this->header) ? $this->readHead() : $this->header;
		foreach($headers as $key => $header) {
			$headers[$key] = Inflector::slug((low(preg_replace(array("/(.*)[\n\r]*.*/"), array('\\1'), $header))));
		}
		
		$this->Rows->next();
		$Row = $this->Rows->current();
		$Cells = $Row->getCellIterator();
		foreach($Cells as $columnNumber => $Cell) {
			if(isset($headers[$columnNumber])) {
				$dataRow[$headers[$columnNumber]] = trim($Cell->getCalculatedValue());
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
}
