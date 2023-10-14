<?php
	echo $this->element('filters/report_actions', array('filter' => $_POST));
?>
<fieldset class="searchFieldset">
	<legend>Filter options</legend>
	<?php
		$hei_id = readPOST('hei_id');
		$this->formFields['hei_id']->fieldValue = $hei_id;

		$nr_national_review_id = readPOST('nr_national_review_id');
		$this->formFields['nr_national_review_id']->fieldValue = $nr_national_review_id;

		$this->showBootstrapField('hei_id', 'Institution');
		$this->showBootstrapField('nr_national_review_id', 'National Review');
	?>
	<input type="button" class="btn searchForm" value="Apply filter"> | <a class="clearSearchLink" href="#">Clear filter</a>
</fieldset>