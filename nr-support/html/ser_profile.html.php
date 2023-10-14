<?php
	$prog_id = $this->dbTableInfoArray["nr_programmes"]->dbTableCurrentID;
	$nr_type = $this->db->getValueFromTable("nr_programmes","id",$prog_id,"nr_national_review_id");
?>
<h3>
	<?php
		switch ($nr_type) {
	 	case 'BSW':
			echo "Table 4.1 A1 Profile of programme";
			break;
	 	default:  // Always set to the latest national review SER process
			echo "Profile of the programme";
		 }
	?>
</h3>
<?php
	echo '<div class="programme_profile">';
	$this->showBootstrapField('hei_name', '1. Name of institution');
	$this->showBootstrapField('nr_programme_name', '2. Name of programme');
	$this->showBootstrapField('che_reference_code', '3. HEQC reference code');
	$this->showBootstrapField('heqsf_reference_no', '4. HEQSF reference code');
	$this->showBootstrapField('saqa_qualification_id', '5. SAQA qualification code');
	$this->showBootstrapField('faculty_name', '6. Name of Faculty');
	$this->showBootstrapField('school_name', '7. Name of School(if applicable)');
	
	$this->showSaveAndContinue('_label_ser_contact_head');
	echo '</div>';
	$this->cssPrintFile('print.css');
?>
