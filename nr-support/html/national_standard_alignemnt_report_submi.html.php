<?php
	echo $this->element('filters/' . Settings::get('template'), $_POST);
    $details = $this->getInstProgressDetails($_POST, Settings::get('template'));

	$detailsReal = array(); // Initialize the new array

foreach ($details as $row) {
    if ($row['nr_programme_name'] === 'Bachelor of Social Work') {
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

    $nr_programme_id = $detailsReal[0]['id'];
	
	// Set dbTableCurrentID to the value of $nr_programme_id
	$this->dbTableInfoArray["nr_programmes"]->dbTableCurrentID = $nr_programme_id;

	$prog_id = $this->dbTableInfoArray["nr_programmes"]->dbTableCurrentID;
echo $prog_id;

?>
<h3>Submission to the CHE</h3>
<p>
	<strong>Please note that once you have submitted, you will no longer be able to make any changes or re-upload any documents.</strong>
</p>
<p>
	To <strong>recheck</strong> your information press the blue "PREVIOUS" button top left.
</p>
<p>
	To <strong>submit</strong> to the CHE, press the red "CHE SUBMIT" button top left.
</p>