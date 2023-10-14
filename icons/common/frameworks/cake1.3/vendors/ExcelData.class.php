<?php 
require_once 'PHPExcel/PHPExcel/IOFactory.php';

class ExcelData {
	var $fileName;
	var $fileType;
	var $dataHead, $dataRow, $curRow, $highestColumnm, $highestRow;
	var $ExcelFile;
	
	function ExcelData($filename) {
		$this->fileName = $filename;
			
		$this->ExcelFile = PHPExcel_IOFactory::load($this->fileName);

		$this->ExcelFile->setActiveSheetIndex(0);

		$highestColumn = $this->ExcelFile->getActiveSheet()->getHighestColumn();
		$this->highestColumn = 26*(strlen($highestColumn) - 1) + ord(substr($highestColumn, strlen($highestColumn)-1, 1))-ord('A');
		$this->highestRow = $this->ExcelFile->getActiveSheet()->getHighestRow();

		$this->readHead();
		
		return true;
	}
	
	function readHead()
	{
		$this->dataHead = array();
		for($column = 0;$column <= $this->highestColumn; $column++)
		{
			$this->dataHead[] = $this->ExcelFile->getActiveSheet()->getCellByColumnAndRow($column, 1)->getCalculatedValue();
		}
		
		$this->curRow = 2;
	}
	
	function readNext()
	{
		if($this->curRow <= $this->highestRow)
		{
			$this->dataRow = array();
			for($column = 0;$column <= $this->highestColumn; $column++)
			{
				$this->dataRow[] = $this->ExcelFile->getActiveSheet()->getCellByColumnAndRow($column, $this->curRow)->getValue();
			}
			$this->curRow++;
			
			return true;
		}
		
		return false;
	}
	
	//For backwards compatipility
	function close()
	{
		
	}
}

/*require_once 'Excel/reader.php';

class ExcelData {
	var $fileName;
	var $fileType;
	var $csvDelimiter, $fileHandle;
	var $xlsData;
	var $dataHead, $dataRow, $curRow;
	
	function ExcelData ($filename) {
		$goodFile=true;
		$this->fileName = $filename;
		$this->curRow = 0;

		$path_parts = pathinfo ($this->fileName);
		$this->fileType = strtoupper($path_parts["extension"]);

		switch ($this->fileType) {
			case "CSV":
				$this->readCSVhead ();
				break;
			case "XLS":
				$this->readXLShead ();
				break;
			default:
				$goodFile=false;
				break;
		}

		return ($goodFile);
	}

	function Close () {
		if ($this->fileType == "CSV") {
			fclose($this->fileHandle);
		}
	}

	function readXLShead () {
		$this->xlsData = new Spreadsheet_Excel_Reader();	
		$this->xlsData->setOutputEncoding('CP1251');
		$this->xlsData->read($this->fileName);

		$this->dataHead = array();
		
		for ($j = 1; $j <= $this->xlsData->sheets[0]['numCols']; $j++) {
			$this->dataHead[$j-1] = (isset($this->xlsData->sheets[0]['cells'][1][$j]))?($this->xlsData->sheets[0]['cells'][1][$j]):("");
		}
	}
	
	function readCSVhead () {
		$this->csvDelimiter = ",";
		$this->fileHandle = fopen ($this->fileName, "r");
		$head = fgets ($this->fileHandle, 8192);

		$csvComma = substr_count ($head, ",");
		$csvSemiColon = substr_count ($head, ";");

		if ($csvSemiColon > $csvComma) {
			$this->csvDelimiter = ";";
		}

		rewind ($this->fileHandle);
		$this->dataHead = fgetcsv($this->fileHandle, 8192, $this->csvDelimiter);
	}

	function readNext () {
		$this->curRow++;

		switch ($this->fileType) {
			case "CSV":
				$this->dataRow = fgetcsv($this->fileHandle, 8192, $this->csvDelimiter);
				break;
			case "XLS":
				$this->dataRow = false;

				if ($this->xlsData->sheets[0]['numRows'] > $this->curRow) {
					$this->dataRow = array();
					for ($j = 1; $j <= $this->xlsData->sheets[0]['numCols']; $j++) {
						$this->dataRow[$j-1] = (isset($this->xlsData->sheets[0]['cells'][$this->curRow+1][$j]))?($this->xlsData->sheets[0]['cells'][$this->curRow+1][$j]):("");
					}
				}
				break;
		}

		if ($this->dataRow) {
			return(true);
		} else {
			return(false);
		}
	}
	
// Class END
}*/
?>
