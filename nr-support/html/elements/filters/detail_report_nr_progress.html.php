<?php
	echo $this->element('filters/report_actions', array('filter' => $_POST));
?>
<fieldset class="searchFieldset">
	<legend>Filter options</legend>
	<?php
		$hei_id = readPOST('hei_id');
		$this->formFields['hei_id']->fieldValue = $hei_id;
		$nr_programme_name = readPOST('nr_programme_name');
		$this->formFields['nr_programme_name']->fieldValue = $nr_programme_name;
		// $nr_national_review_id = readPOST('nr_national_review_id');
		// $this->formFields['nr_national_review_id']->fieldValue = $nr_national_review_id;
		$heqsf_reference_no = readPOST('heqsf_reference_no');
		$this->formFields['heqsf_reference_no']->fieldValue = $heqsf_reference_no;

		$nr_national_review_id = readPOST('nr_national_review_id');
		$this->formFields['nr_national_review_id']->fieldValue = $nr_national_review_id;
	
		$this->showBootstrapField('hei_id', 'Institution');
		$this->showBootstrapField('nr_programme_name', 'Programme name');
		// $this->showBootstrapField('nr_national_review_id', 'National review id');
		$this->showBootstrapField('heqsf_reference_no', 'HEQSF reference code');
		$this->showBootstrapField('nr_national_review_id', 'National Review');
	?>
	<input type="button" class="btn searchForm" value="Apply filter"> | <a class="clearSearchLink" href="#">Clear filter</a>
</fieldset>