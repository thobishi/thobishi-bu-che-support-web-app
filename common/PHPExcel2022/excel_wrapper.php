<?php 
require_once '/User/mphophokontsi/Sites/common/PHPExcel/PHPExcel/IOFactory.php';

class ExcelWrapper {
	private $fileName;
	private $curRow = 0;
	private $ExcelFile;
	
	public function __construct($filename) {
		$this->fileName = $filename;
		
		$this->ExcelFile = PHPExcel_IOFactory::load($this->fileName);
		
		return true;
	}
	
	public function readHead()
	{
		$dataHead = array();
		$highestColumn = $this->getHighestColumn();
		for($column = 'A';$column <= $highestColumn; $column++)
		{
			$cell = $column . '1';
			$dataHead[] = trim($this->ExcelFile->getActiveSheet()->getCell($cell)->getCalculatedValue());
		}
		
		return count($dataHead) > 0 ? $dataHead : false;
	}
	
	public function readNextRow($namedColumn = true)
	{
		if($this->curRow < 2) $this->curRow = 2;
		
		if($this->curRow <= $this->getHighestRow())
		{
			$dataRow = array();
			$header = $this->readHead();
			$highestColumn = $this->getHighestColumn();
			for($column = 'A', $columnNumber = 0;$column <= $highestColumn; $column++, $columnNumber++)
			{
				$cell = $column . $this->curRow;
				$columnName = trim((strtolower(preg_replace(array("/(.*)[\n\r]*.*/"), array('\\1'), $header[$columnNumber]))));
				$dataRow[$namedColumn ? $columnName : $columnNumber] = $this->ExcelFile->getActiveSheet()->getCell($cell)->getCalculatedValue();
			}
			$this->curRow++;
			
			return count($dataRow) > 0 ? $dataRow : false;
		}
		
		return false;
	}
	
	public function getHighestColumn()
	{
		return $this->ExcelFile->getActiveSheet()->getHighestColumn();
	}
	
	public function getHighestRow()
	{
		return $this->ExcelFile->getActiveSheet()->getHighestRow();
	}
}
?>