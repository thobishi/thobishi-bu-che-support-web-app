<?php
require_once 'reader.php';

class ExcelData {

	private $fileName, $fileType;
  private $csvDelimiter, $fileHandle;
	private $xlsData, $curRow;
  private $textLines, $nrTextLines;
  public $dataHead; // array of the headings in the file
  public $dataRow;  // array of the contents of one row in the file
	
  // Fill in either the filename and filetype or the importtext field
	public function __construct($filename, $filetype, $importtext) {
		$goodFile=true;
		$this->curRow = 0;
    if ($filename!='') {
  		$this->fileName=$filename;
	  	$this->fileType=strtoupper($filetype);
    } else {
      $this->fileName='';
	  	$this->fileType='TEXT';
    }

		switch ($this->fileType) {
      case "TEXT":
        $this->textLines=preg_split("/\r?\n/", $importtext);
        $this->nrTextLines=count($this->textLines);
        while ($this->nrTextLines>0 && $this->textLines[$this->nrTextLines-1]=='') {
          unset($this->textLines[$this->nrTextLines]);
          $this->nrTextLines--;
        }
        $this->readTEXThead();
        break;
			case "CSV":
				$this->readCSVhead();
				break;
			case "XLS":
				$this->readXLShead();
				break;
			default:
				$goodFile=false;
				break;
		}

		return ($goodFile);
	}

	public function Close() {
		if ($this->fileType == "CSV") {
			fclose($this->fileHandle);
		}
	}

	private function readXLShead () {
		$this->xlsData = new Spreadsheet_Excel_Reader();	
		$this->xlsData->setOutputEncoding('CP1251');
		$this->xlsData->read($this->fileName);

		$this->dataHead = array();
		
		for ($j = 1; $j <= $this->xlsData->sheets[0]['numCols']; $j++) {
			$this->dataHead[$j-1] = (isset($this->xlsData->sheets[0]['cells'][1][$j]))?($this->xlsData->sheets[0]['cells'][1][$j]):("");
		}
	}
	
	private function readCSVhead() {
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

  private function readTEXThead() {
		$head=$this->textLines[0];
    $cols=preg_split("/[,;]+/", $head); // split on , and ;
    foreach ($cols as &$col) {
      $col=preg_replace("/\"(.*)\"/", "$1", $col);
    }
    unset($col);
    $this->dataHead=$cols;
  }

	public function readNext() {
		$this->curRow++;

		switch ($this->fileType) {
      case "TEXT":
        if ($this->curRow<$this->nrTextLines) {
          $cols=preg_split("/[,;]+/", $this->textLines[$this->curRow]); // split on , and ;
          foreach ($cols as &$col) {
            $col=preg_replace("/\"(.*)\"/", "$1", $col);
          }
          unset($col);
          $this->dataRow=$cols;
        } else $this->dataRow=false;
        break;
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
	
}
?>
