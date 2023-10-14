<?php
	$prog_id = $this->dbTableInfoArray["nr_programmes"]->dbTableCurrentID;
?>
<h3>Table 4.1 A1 Profile of programme</h3>
<?php
	echo '<div class="programme_profile">';
	$this->showBootstrapField('hei_name', '1. Name of institution');
	$this->showBootstrapField('nr_programme_name', '2. Name of programme');
	$this->showBootstrapField('che_reference_code', '3. HEQC reference code');
	$this->showBootstrapField('heqsf_reference_no', '4. HEQSF reference code');
	$this->showBootstrapField('faculty_name', '6. Name of Faculty');
	$this->showBootstrapField('school_name', '7. Name of School(if applicable)');
	
	$this->showSaveAndContinue('_label_ser_validation_llb');
	echo '</div>';
	$this->cssPrintFile('print.css');
?>
