<h3>Additional Information</h3>
<div class="row-fluid">
<?php
	$prog_id = $this->dbTableInfoArray["nr_programmes"]->dbTableCurrentID;
	$this->displayProgrammeInfo();
	$this->view = 1;
	$url = "javascript:showSERreadOnly($prog_id);";
	$screeningHistory = $this->getScreeningDetails('programme_ref', $prog_id);
	$dbTableName = $this->dbTableInfoArray["nr_programmes"]->dbTableName;
	$dbTableKeyField = $this->dbTableInfoArray["nr_programmes"]->dbTableKeyField;
	
	echo $this->element('header_information', compact('dbTableName', 'dbTableKeyField', 'prog_id', 'screeningHistory', 'url'));
?>
</div>

<?php
	// $this->showField("active_user_ref");
	$this->view = 0;
?>

<div class="hero-unit">
	<h3>Additional Information</h3>
	<p>
		Institutions may provide additional information e.g. Budget or improvement plans.  All additional information must be uploaded here before proceeding..
	</p>
	<p>
		<?php 
			$detailArr = $this->getPrelimAdditionalInfo($prog_id);
//echo $prog_id;
			echo $this->element('additional_doc_info', compact('detailArr'));
		?>
	</p>
</div>

<div class="hero-unit">
	<p>Please indicate (By checking the box) if you would like to upload the on Behalf of the panel chair :<?php $this->showfield('siteVisitSubmittedByAdmin_ind'); ?></p>
</div>

<script>
	$(".delButton").click(function(e){
		$(this)
			.prev('input:first').prop('checked', true).end()
			.closest("tr").addClass("hidden");
		
	});
	
	$("input[name='FLD_siteVisitSubmittedByAdmin_ind'").click(function () {
		$("#action_next").toggle(this.checked);
		$("#action_ser_managePanel_report").closest("li").toggle(this.checked);	
		$("#action_ser_prelim_criteria").closest("li").toggle(this.checked);
		$("#action_ser_prelim_validation").closest("li").toggle(this.checked);	
	});	
</script>	