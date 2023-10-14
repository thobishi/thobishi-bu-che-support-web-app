<?php
	// echo $this->element('filters/report_actions', array('filter' => $_POST));
?>
<fieldset class="searchFieldset">
	<legend>Filter options</legend>
	<?php
		$hei_id = readPOST('hei_id');
		$this->formFields['hei_id']->fieldValue = $hei_id;
		$nr_programme_name = readPOST('nr_programme_name');
		$this->formFields['nr_programme_name']->fieldValue = $nr_programme_name;
		
		$this->showBootstrapField('hei_id', 'Institution');
		$this->showBootstrapField('nr_programme_name', 'Programme name');

	?>
	<input type="button" class="btn searchForm" value="Apply filter"> | <a class="clearSearchLink" href="#">Clear filter</a>
</fieldset>