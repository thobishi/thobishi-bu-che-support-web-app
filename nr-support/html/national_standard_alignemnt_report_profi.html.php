<?php
	/*$details = $this->getInstProgressDetails($_POST, Settings::get('template'));
    	$nr_programme_id = $details[2]['id'];
	// Set dbTableCurrentID to the value of $nr_programme_id
	$this->dbTableInfoArray["nr_programmes"]->dbTableCurrentID = $nr_programme_id;
	$prog_id = $this->dbTableInfoArray["nr_programmes"]->dbTableCurrentID;
	echo $prog_id;
	echo $details[0]['hei_name'];
	echo $details[0]['nr_programme_name'];
	echo $details[0]['che_reference_code'];
	echo $details[0]['heqsf_reference_no'];
	echo $details[0]['faculty_name'];
	echo $details[0]['school_name'];
	
	echo $nr_programme_id;
	echo $prog_id;
	//echo dbTableInfoArray["nr_programmes"]->dbTableCurrentID;*/
?>
<!-- <h3>Table 4.1 A1 Profile of programme</h3> -->
<?php

	/*echo '<div class="programme_profile">';
	$this->showBootstrapField('hei_name', '1. Name of institution');
	$this->showBootstrapField('nr_programme_name', '2. Name of programme');
	$this->showBootstrapField('che_reference_code', '3. HEQC reference code');
	$this->showBootstrapField('heqsf_reference_no', '4. HEQSF reference code');
	$this->showBootstrapField('faculty_name', '6. Name of Faculty');
	$this->showBootstrapField('school_name', '7. Name of School(if applicable)');
	
	//$this->showSaveAndContinue('_label_ser_validation_llb');
	echo '</div>';
	$this->cssPrintFile('print.css');*/
?>

<?php
	$details = $this->getInstProgressDetails($_POST, Settings::get('template'));
	$nr_programme_id = $details[0]['id'];
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
	echo hello;
	echo $nr_programme_id;


	echo '<h3>Table 4.1 A1 Profile of programme</h3>';
	echo '<div class="programme_profile">';
	
	// Display the values inside the bootstrap fields manually
	echo '<label>1. Name of institution:</label>';
	echo '<input type="text" name="hei_name" value="' . $detailsReal[0]['hei_name'] . '">';

	echo '<label>2. Name of programme:</label>';
	echo '<input type="text" name="nr_programme_name" value="' . $detailsReal[0]['nr_programme_name'] . '">';

	echo '<label>3. HEQC reference code:</label>';
	echo '<input type="text" name="che_reference_code" value="' . $detailsReal[0]['che_reference_code'] . '">';

	echo '<label>4. HEQSF reference code:</label>';
	echo '<input type="text" name="heqsf_reference_no" value="' . $detailsReal[0]['heqsf_reference_no'] . '">';

	echo '<label>6. Name of Faculty:</label>';
	echo '<input type="text" name="faculty_name" value="' . $detailsReal[0]['faculty_name'] . '">';

	echo '<label>7. Name of School (if applicable):</label>';
	echo '<input type="text" name="school_name" value="' . $detailsReal[0]['school_name'] . '">';

	//$this->showSaveAndContinue('_label_ser_validation_llb');
	echo '</div>';
	$this->cssPrintFile('print.css');
?>
