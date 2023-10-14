<h3>Additional Information upload</h3>

<?php
	$prog_id = $this->dbTableInfoArray['nr_programmes']->dbTableCurrentID;

	$this->showField("nr_programme_id");
	$this->showBootstrapField('additional_doc_title', 'Document Title:');
	$this->showBootstrapField('additional_doc_description', 'Document description:');
	
?>

<div class="hero-unit">

	<?php 
		$this->makeLink("additional_doc", "Upload additional document", "", "", "", "date_uploaded");
	?>

</div>
