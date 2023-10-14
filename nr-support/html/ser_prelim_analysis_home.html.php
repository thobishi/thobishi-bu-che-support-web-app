<h3>Process Virtual Site Visit - Assign date and users</h3>

<div class="row-fluid">
<?php
	/*$prog_id = $this->dbTableInfoArray["nr_programmes"]->dbTableCurrentID;
//echo $prog_id;
	$this->displayProgrammeInfo();
	$this->view = 1;
	$url = "javascript:showSERreadOnly(" . $prog_id . ");";
	$screeningHistory = $this->getScreeningDetails('programme_ref', $prog_id);
	$dbTableName = $this->dbTableInfoArray["nr_programmes"]->dbTableName;
	$dbTableKeyField = $this->dbTableInfoArray["nr_programmes"]->dbTableKeyField;
	
	echo $this->element('header_information', compact('dbTableName', 'dbTableKeyField', 'prog_id', 'screeningHistory', 'url'));*/
	
	$prog_id = $this->dbTableInfoArray["nr_programmes"]->dbTableCurrentID;
 $details = $this->getInstProgressDetails($_POST, Settings::get('template'));
 $nr_programme_id = $details[0]['id'];

//echo $prog_id;

?>
</div>
<div class="hero-unit">
	<p>Please indicate (By checking the box) if you would like to upload the on Behalf of the Virtual Site Visitor:<?php $this->showfield('analystReportSubmittedByAdmin_ind'); ?></p>
</div>
<?php
	$this->showField("active_user_ref");
	$this->view = 0;
?>

<div class="alert alert-block alert-error fade in" style="display:none;">
	<h4 class="alert-heading">Date error!</h4>
	<p>The end date cannot be smaller than the start date.</p>
</div>

<div class="hero-unit">
	<h3>Virtual Site Visit</h3>
	<p>
		Site visitor will have access to this SER from: <?php $this->showField("analyst_start_date"); ?> to: <?php $this->showField("analyst_end_date"); ?>
	</p>
	<p>
		Select evaluator for this SER <?php $this->showField("analyst_user_ref"); ?>
		<br /><span class="infoSmall">(Additional Virtual Site Visitor may be added from user administration)</span>
		
	</p>
</div>

<div class="hero-unit">
	<h3>Virtual Site Visit and panel members</h3>
	<p>
		Select Virtual Site Visit date: <?php $this->showField("site_visit_date"); ?>
	</p>
	<p>
		Select Chair report due date: <?php $this->showField("chair_report_due_date"); ?>
	</p>
	<p>
		Panel members will have access to this SER from:  <?php $this->showField("panel_start_date"); ?> to: <?php $this->showField("panel_end_date"); ?>
	</p>
	
	<p>
		<?php
			echo $this->element('panel_member_select', compact('prog_id'));
		?>
	</p>

</div>

<table class="table table-bordered table-striped serTable">
    
    <tr>

		
		<td  class="serDescription">
			Upload Site Visit Schedule <br/> and List of Interviewees
		</td>
		<td class="fieldsetData">
            
			<fieldset><legend>Upload Site Visit Schedule and List of Interviewees</legend>
			<?php 
				$this->makeLink("site_visit_schedule", "Site Visit Schedule");
				$this->makeLink("list_of_interviewees", "List of Interviewees");
				
			?>
			</fieldset>

		</td>
	</tr>

</table>

<script>	
	$("input[name='FLD_analystReportSubmittedByAdmin_ind'").click(function () {
		$("#action_next").toggle(this.checked);
		$("#action_ser_prelim_reports").closest("li").toggle(this.checked);
		$("#action_ser_prelim_additional_info").closest("li").toggle(this.checked);
	});	
	

	$(".date").each(function(){
		if($(this).val() == '1970-01-01'){
			$(this).val('');
		}
	});
	
	$("#FLD_analyst_start_date").datepicker({
		format: 'yyyy-mm-dd',
		autoclose: true
	});
	
	$("#FLD_analyst_end_date").datepicker({
		format: 'yyyy-mm-dd',
		autoclose: true
	});
	
	$("#FLD_site_visit_date").datepicker({
		format: 'yyyy-mm-dd',
		autoclose: true
	});
	$("#FLD_chair_report_due_date").datepicker({
		format: 'yyyy-mm-dd',
		autoclose: true
	});	
	
	$("#FLD_panel_start_date").datepicker({
		format: 'yyyy-mm-dd',
		autoclose: true
	});
	
	$("#FLD_panel_end_date").datepicker({
		format: 'yyyy-mm-dd',
		autoclose: true
	}); 
	
    $('#FLD_analyst_end_date').datepicker().on('changeDate', function(e){
		clearValues = new Array();
		clearValues.push($('#FLD_panel_start_date'));
		clearValues.push($('#FLD_panel_end_date'));
		smallerThan($(this).val(), $("#FLD_analyst_start_date").val(), clearValues);
    });
	
    $('#FLD_panel_end_date').datepicker().on('changeDate', function(e){
	clearValues = new Array();
	clearValues.push($('#FLD_panel_start_date'));
		clearValues.push($('#FLD_panel_end_date'));
		smallerThan($(this).val(), $("#FLD_panel_start_date").val(), clearValues);
    });
	
    $('#FLD_analyst_start_date').datepicker().on('changeDate', function(e){
		clearValues = new Array();
		clearValues.push($('#FLD_panel_start_date'));
		clearValues.push($('#FLD_panel_end_date'));
		biggerThan($(this).val(), $("#FLD_analyst_end_date").val(), clearValues);
    });
	
    $('#FLD_panel_start_date').datepicker().on('changeDate', function(e){
		clearValues = new Array();
		clearValues.push($('#FLD_panel_start_date'));
		clearValues.push($('#FLD_panel_end_date'));
		biggerThan($(this).val(), $("#FLD_panel_end_date").val(), clearValues);
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
	
	$(".roleSelect").change(function(){
		var selectedRole = $(this).val();
		var values = $("#panelRole_" + selectedRole).html();
		if(selectedRole != '' && values != 'undefined'){
			$('#selectedPanelUsers').select2({
				data: $.parseJSON(values),
				placeholder: "Search from list of panel/chair members",
				allowClear: true
			});
		}
	}).trigger('change');
	
	$(".addButton").click(function(e){
		e.preventDefault();
		$selectedUser = $("#selectedPanelUsers").val();
		if($selectedUser == '' || $selectedUser == null){
			$(".alert-selectPanel").show("slow");
		}
		else{
			$(".alert-selectPanel").hide("slow");
			$(".panelUsersTable :checkbox").each(function(){
				var $this = $(this);
				$inputValue = $this.val();
				if($inputValue == $selectedUser){
					$this
						.prop('checked', true)
						.closest("tr").removeClass("hidden");
				}
			});
		}
	});
	
	$(".delButton").click(function(e){

		$(this)
			.prev('input:first').prop('checked', false).end()
			.closest("tr").addClass("hidden");

	});
</script>
