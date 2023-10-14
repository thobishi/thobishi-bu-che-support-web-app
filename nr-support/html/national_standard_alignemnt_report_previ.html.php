<h3>Preview and submit</h3>

<?php	
	//echo $this->dbTableInfoArray["nr_programmes"]->dbTableKeyField;
    //echo $this->element('filters/' . Settings::get('template'), $_POST);
    $details = $this->getInstProgressDetails($_POST, Settings::get('template'));

	$detailsReal = array(); // Initialize the new array

foreach ($details as $row) {
    if ($row['nr_programme_name'] === 'Bachelor of Social Work') {
	//echo $row['id'];
        $detailsReal[] = $row; // Add the matching row to the new array
    }
}

// Access the desired value(s) in the new array
if (!empty($detailsReal)) {
    foreach ($detailsReal as $row) {
        echo $row['che_reference_code'] . "\n";
    }
} else {
    echo "No matching row found.";
}

//var_dump($this);
//echo $this->dbTableInfoArray["nr_programmes"]->dbTableKeyField;

    $nr_programme_id = $detailsReal[0]['id'];
	//$_SESSION["ses_keyVal"] = $nr_programme_id;
	echo $nr_programme_id;
	
	// Set dbTableCurrentID to the value of $nr_programme_id
	$this->dbTableInfoArray["nr_programmes"]->dbTableCurrentID = $nr_programme_id;

	$prog_id = $this->dbTableInfoArray["nr_programmes"]->dbTableCurrentID;
	//$this->previewReadOnly();
?>

<div class="back-to-top">[<a href="#">Back to Top</a>]</div>

<link rel="stylesheet" type="text/css" media="print" href="css_print/print.css">
