<h3>Manage recommendation writers</h3>
<div class="row-fluid">
<?php
	$prog_id = $this->dbTableInfoArray["nr_programmes"]->dbTableCurrentID;
	$this->displayProgrammeInfo();
	$this->view = 1;
	$url = "javascript:showSERreadOnly(" . $prog_id . ");";
	// $screeningHistory = $this->getScreeningDetails('programme_ref', $prog_id);
	$dbTableName = $this->dbTableInfoArray["nr_programmes"]->dbTableName;
	$dbTableKeyField = $this->dbTableInfoArray["nr_programmes"]->dbTableKeyField;
	
	echo $this->element('header_information', compact('dbTableName', 'dbTableKeyField', 'prog_id', 'url'));
?>
</div>

<?php
	$this->view = 0;
?>

<div class="alert alert-block alert-error fade in" style="display:none;">
	<h4 class="alert-heading">Date error!</h4>
	<p>The end date cannot be smaller than the start date.</p>
</div>

<div class="hero-unit">
	<h3>Recommendation writer</h3>
	<p>
		Recommendation writer will have access to this SER from: <?php $this->showField("recommendation_start_date"); ?> to: <?php $this->showField("recommendation_end_date"); ?>
	</p>
	<p>
		Due date for the recommendation writers report: <?php $this->showField("recommendation_report_due_date"); ?>
	</p>	
	<p>
		Select Recommendation writer for this SER <?php $this->showField("recommendation_user_ref"); ?>
		<br /><span class="infoSmall">(Additional Recommendation writer may be added from user administration)</span>
	</p>
</div>
<div class="hero-unit">
	<p>Please indicate (By checking the box) if you would like to upload the on Behalf of the recommendation write:<?php $this->showfield('recommendationSubmittedByAdmin_ind'); ?><p>
</div>

<script>
	$("input[name='FLD_recommendationSubmittedByAdmin_ind'").click(function () {
		$("#action_next").toggle(this.checked);
		$("#action_ser_recomm_report").closest("li").toggle(this.checked);
		$("#action_ser_recomm_criteria").closest("li").toggle(this.checked);
		$("#action_ser_recomm_validation").closest("li").toggle(this.checked);	
	});
	
	$(".date").each(function(){
		if($(this).val() == '1970-01-01'){
			$(this).val('');
		}
	});
	
	$("#FLD_recommendation_start_date").datepicker({
		format: 'yyyy-mm-dd',
		autoclose: true
	});
	
	$("#FLD_recommendation_end_date").datepicker({
		format: 'yyyy-mm-dd',
		autoclose: true
	});
	
	$("#FLD_recommendation_report_due_date").datepicker({
		format: 'yyyy-mm-dd',
		autoclose: true
	});

	
    $('#FLD_recommendation_end_date').datepicker().on('changeDate', function(e){
		clearValues = new Array();
		clearValues.push($('#FLD_recommendation_start_date'));
		clearValues.push($('#FLD_recommendation_end_date'));
		smallerThan($(this).val(), $("#FLD_recommendation_start_date").val(), clearValues);
    });
	
	
    $('#FLD_recommendation_start_date').datepicker().on('changeDate', function(e){
		clearValues = new Array();
		clearValues.push($('#FLD_recommendation_start_date'));
		clearValues.push($('#FLD_recommendation_end_date'));
		biggerThan($(this).val(), $("#FLD_recommendation_end_date").val(), clearValues);
    });
	
	
	function biggerThan(thisValue, compareValue, clearValues){
		if((thisValue > '') && (compareValue > '') && (new Date(thisValue).getTime() > new Date(compareValue).getTime())){
			for(index in clearValues){
				clearValues[index].val('');
			}
			$(".alert").show("slow");
		}else{
			$(".alert").hide("slow");
		}
	}
	
	function smallerThan(thisValue, compareValue, clearValues){
		if((thisValue > '') && (compareValue > '') && (new Date(thisValue).getTime() < new Date(compareValue).getTime())){
			for(index in clearValues){
				clearValues[index].val('');
			}
			$(".alert").show("slow");
		}else{
			$(".alert").hide("slow");
		}
	}
</script>