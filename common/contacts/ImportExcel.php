<?php
  
require_once('contacts/ContactsDB.php');
require_once('Excel/ExcelData.class.php');

class ImportExcel {

  private $importCols=array();
  private $cdb;
  private $problems, $added, $updated, $errors, $line, $duplicates, $empty, $extras, $header;

    // the first field (key field) in the sub-arrays must be the name as is in the db, all the rest are aliases
  private $coreCols = array (
		array('email', 'e_mail', 'e-mail', 'emailaddress')
	);

  private	$optionalCols = array (
		array('name', 'firstname', 'voornaam'),
		array('surname', 'lastname', 'van'),
		array('phone', 'tel', 'businessphone', 'phone nr', 'phone no', 'tel no', 'tel nr', 'telno' ,'telnr', 'phonenr', 'phoneno'),
		array('fax', 'businessfax', 'fax nr', 'fax no', 'faxno', 'faxnr'),
		array('mobile', 'cell', 'cellphone', 'cellno', 'cellnr', 'cell no', 'cell nr'),
		array('company','institution'),
		array('job_title', 'designation', 'title'),
		array('postal_addr', 'postal address'),
		array('postal_code', 'postal code'),
		array('physical_addr', 'physical address'),
		array('physical_code', 'physical code'),
		array('email2'),
		array('email3'),
		array('keywords', 'notes')
 	);

  public function __construct() {
    $this->cdb=new ContactsDB();
    $this->problems="";
    $this->errors=0;
    $this->added=0;
    $this->updated=0;
    $this->line=0;
    $this->duplicates=0;
    $this->empty=0;
    $this->extras=0;
    $this->header=0;
  }

  public function importText($folderId, $importtext) {
    return $this->import($folderId, '', '', $importtext);
  }
  
  public function importFile($folderId, $fileloc, $filetype) {
    return $this->import($folderId, $fileloc, $filetype, '');
  }
  
  private function import($folderId, $fileloc, $filetype, $importtext) {
		$data=new ExcelData($fileloc, $filetype, $importtext);
    $originalList=array();
		if (!$this->getHeadings($data->dataHead)) {
      $this->errors++;
      $this->problems.="<br>Header line not found.";
      return false;
    }
    $this->header++;
    $this->line=1;
		while ($data->readNext()) {
      $this->line++;
			$emailcol = $data->dataRow[$this->importCols["email"]];

      $emails=array();
      if ($emailcol!="") $emails=preg_split("/[,;]+/", $emailcol); // split on , and ;
      $found_this_line=0;
      foreach ($emails as &$email) {
        $email=preg_replace("/[\"'](.*)[\"']/", "", $email);  // remove "..." or '...'
        $email=preg_replace("/<(.*)>/", "$1", $email);        // remove <>
        $email=preg_replace("/mailto:(.*)/i", "$1", $email);  // remove mailto:
        $email=trim($email, TRIM_CHARS);
        if ($email=='') continue;
        $found_this_line++;
        $result=preg_match_all(EMAIL_REGEXP, $email, $matches);
        if ($result!=1 || $email!=$matches[0][0]) { 
          $this->errors++;
          $this->problems.="<br>Line ".$this->line." : ".$emailcol;
        } else {
          if (isset($originalList[$email])) $this->duplicates++;
          else                              $originalList[$email]=true;
          $person=$this->cdb->getPerson($email);
          if ($person) {
            $this->populatePersonFromData($person, $data);
            $this->cdb->updatePerson($person);
            $this->updated++;
    			} else {
            $person=new Person();
            $person->email=$email;
            $this->populatePersonFromData($person, $data);
            $this->cdb->addPerson($person);
            $this->added++;
    			}
          $this->cdb->linkPersonToFolder($person->id, $folderId);
        }
      }
      if ($found_this_line==0) $this->empty++;
      if ($found_this_line>1) $this->extras+=$found_this_line-1;
		}
		$data->Close();
		return true;
	}

  // Copies data from csv data array to person record
  private function populatePersonFromData($person, $data) {
		foreach ($this->importCols as $col=>$n) {
      if ($col=="email") continue;
			if (isset($data->dataRow[$this->importCols[$col]]) && $data->dataRow[$this->importCols[$col]] > "") {
				$person->$col=$data->dataRow[$this->importCols[$col]];
			}
		}
  }

  // Builds up the importCols array which maps the key fields to the index of the field column in the import file
  private	function getHeadings($head) {
		if ($this->setHead ($head, $this->coreCols) != 1) return (false);
		$this->setHead ($head, $this->optionalCols);
		return (true);
	}

	private function setHead($head, $checkCols) {
		$colCount=0;
		foreach ($checkCols as $cols) {
			for ($i=0; $i<count($head); $i++) {
				if (in_array (strtolower($head[$i]), $cols)) {
					$this->importCols[$cols[0]] = $i;
					$colCount++;
				}
			}
		}
		if ($colCount == 0) return 0; // no matches found
		if ($colCount == count($checkCols)) return 1; // one match found
		return 2; // >2 or more matches found
	}

  public function getProblems()       { return $this->problems;   }
  public function getAdded()          { return $this->added;      }
  public function getUpdated()        { return $this->updated;    }
  public function getErrors()         { return $this->errors;     }
  public function getLines()          { return $this->line;       }
  public function getDuplicates()     { return $this->duplicates; }
  public function getEmpty()          { return $this->empty;      }
  public function getExtras()         { return $this->extras;     }
  public function getHeader()         { return $this->header;     }
  public function getLinesExHeading() { return $this->line-$this->getHeader(); }

}
