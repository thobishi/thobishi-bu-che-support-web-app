<?

if (isset($_POST["printed"]) && ($_POST["printed"] == 1)) {
	$this->setValueInTable ("Institutions_application", "application_id", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "application_printed", 1);
}

?>
