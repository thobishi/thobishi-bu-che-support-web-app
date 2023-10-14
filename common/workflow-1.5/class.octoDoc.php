<?php

if (!defined('OCTODOC_DIR')) die ('ERROR: system can not read the doc path');
if (!defined('OCTODOC_DOWNLOADFILE')) define ('OCTODOC_DOWNLOADFILE', 'download.php');

// we use SYS_PATH for the relative path

class octoDoc {
	private $documentNumber, $documentName;
	private $dateCreated, $dateUpdated;
	private $diskName;
	private $path;
	

	function __construct($docID) {
		$this->documentNumber = $docID;
		$this->readDocumentAttrib();
		$this->path = ( defined('SYS_PATH') )?(SYS_PATH):("");
	}

	public function isDoc () {
		if (!$this->documentNumber) return false;

		return true;
	}

	public function getDocID () {
		if (!$this->documentNumber) return false;

		return $this->documentNumber;
	}

	public function getDateCreated () {
		if ($this->isDoc ()) return $this->dateCreated;
		
		return false;
	}
	
	public function getDateUpdated () {
		if ($this->isDoc ()) return $this->dateUpdated;
		
		return false;
	}
	
	public function getFilename () {
		return $this->documentName;
	}

	public function getFilesize () {
		return filesize($this->getDiskpath());
	}
	
	public function getMimeType () {

		$known_ext = array (

		  // archives
		  'zip' => 'application/zip',

		  // documents
		  'pdf' => 'application/pdf',
		  'doc' => 'application/msword',
		  'xls' => 'application/vnd.ms-excel',
		  'ppt' => 'application/vnd.ms-powerpoint',
  
		  // executables
		  'exe' => 'application/octet-stream',

		  // images
		  'gif' => 'image/gif',
		  'png' => 'image/png',
		  'jpg' => 'image/jpeg',
		  'jpeg' => 'image/jpeg',

		  // audio
		  'mp3' => 'audio/mpeg',
		  'wav' => 'audio/x-wav',

		  // video
		  'mpeg' => 'video/mpeg',
		  'mpg' => 'video/mpeg',
		  'mpe' => 'video/mpeg',
		  'mov' => 'video/quicktime',
		  'avi' => 'video/x-msvideo'
		);
		
		$ext = strtolower(substr(strrchr($this->documentName,"."),1));
		
		if (array_key_exists($ext, $known_ext)) {
			$mime = $known_ext[$ext];
		} else {
			$mime = "application/force-download";
		  if (function_exists('mime_content_type')) {
				$mime = mime_content_type($this->getDiskpath());
		  } else if (function_exists('finfo_file')) {
		    $finfo = finfo_open(FILEINFO_MIME); // return mime type
		    $mime = finfo_file($finfo, $this->getDiskpath());
		    finfo_close($finfo);  
  		  }
		}
		
		return $mime;
	}

	public function downloadFile () {
		if (!$this->documentNumber) return false;

		set_time_limit(0);
					
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: public");
		header("Content-Description: File Transfer");
		header("Content-Type: ".$this->getMimeType());
		header("Content-Disposition: attachment; filename=\"".$this->documentName."\"");
		header("Content-Transfer-Encoding: binary");
		header("Content-Length: " . $this->getFilesize());

		$file = @fopen($this->getDiskpath(),"rb");
		if ($file) {
		  while(!feof($file)) {
		    print(fread($file, 1024*8));
		    flush();
		    if (connection_status()!=0) {
		      @fclose($file);
		      die();
		    }
		  }
		  @fclose($file);
		}

		return true;
	}

	public function url () {
		if (!$this->documentNumber) return false;

		return ($this->path.OCTODOC_DOWNLOADFILE."?file=".$this->documentNumber."&token=".octoToken::create($this->documentNumber, "Document"));
	}

	private function readDocumentAttrib() {
		$SQL = "SELECT * FROM documents WHERE document_id = ".$this->documentNumber;
		$rs = mysqli_query ($SQL) or $this->documentNumber = false;
		if ($rs && $row = mysqli_fetch_array ($rs)) {
			$this->documentName = $row['document_name'];
			$this->dateCreated = $row['creation_date'];
			$this->dateUpdated = $row['last_update_date'];
			$this->diskName = $row['document_url'];
		} else {
			$this->documentNumber = false;
		}
	}

	private function getDiskpath () {
		return OCTODOC_DIR.$this->diskName;
	}

}
?>
