<h3>Submission to the CHE</h3>
<?php
	$prog_id = $this->dbTableInfoArray["nr_programmes"]->dbTableCurrentID;
	$panel_end_date = $this->db->getValueFromTable("nr_programmes","id",$prog_id ,"panel_end_date");
	$this->displayProgrammeInfo();
?>
<div class ="span6">
	<p>
		Your virtual site visit has been uploaded successfully.
	</p>
	<p class="text-warning">
		You have until <?php echo '<strong>'. $panel_end_date .' 23:59 </strong>'; ?> to make amendments.
	</p>
	<p>
		To recheck you information press the blue "PREVIOUS" button top left.
	</p>
	<p>
		To return to the home page, press the "HOME" button top left.
	</p>
	<p>
		To <strong>submit</strong> to the CHE, press the red "CHE SUBMIT" button top left.
	</p>	
	
</div>