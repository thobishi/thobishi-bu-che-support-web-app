<?php

	
class pageSecurity {
	private $commited;
	private $dbConn;
	private $pageID;

	function __construct ($conn) {
		$this->commited = false;
		$this->pageID = false;
		$this->dbConn = $conn;
	}

	public function setPage ($id) {
		$this->pageID = $id;
	}

	public function writeData () {
		if ($this->commited) 
			octoError::create ("Data allready written to page.", "PageSecurity");
		$this->commited = true;

		if (! $this->pageID) return;

		echo "<br>Page: ".$this->pageID;
	}
}

?>
