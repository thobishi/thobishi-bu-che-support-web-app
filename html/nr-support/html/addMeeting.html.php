<h3>Manage NRC Meetings</h3>
<?php
	$this->showField("nr_national_review_id");
?>

<div class="alert alert-block alert-error fade in" style="display:none;">
	<h4 class="alert-heading">Date error!</h4>
	<p>The end date cannot be smaller than the start date.</p>
</div>

<div class="hero-unit">
	<h3>Meeting</h3>
	<p>
		National Review Committee (NRC) meeting date:  <?php $this->showField("nr_meeting_start_date"); ?> to: <?php $this->showField("nr_meeting_end_date"); ?>
	</p>
	<p>
		NRC members will have access to the National Review information from: <?php $this->showField("nrc_access_start_date");?> to: <?php $this->showField("nrc_access_end_date"); ?>
		<br /><span class="infoSmall"> (According to National Review and NRC Members specified below)</span>
	</p>
</div>

<div class="hero-unit">
<?php 
	$nrc_groupId = $this->getGroupId('NRC member');
	// $this->pr($nrc_groupId);
	// $this->pr($_POST);
	echo '<div class = "row-fluid">';
	$this->manageProgrammeDropdown('', 'National Review','selected');
	$this->manageProgrammeDropdown('WHERE nr_national_reviews.start_date <= CURDATE() AND nr_national_reviews.end_date >= CURDATE() ', '(Click to add programmes)','available');
	echo '<button id = "btn-all-programme" class="btn btn-small btn-primary" type="button">Add all programmes</button>';	

	echo '</div>';
	echo '<div class="clear"></div><br><br>';
	echo '<div class = "row-fluid">';
	$this->manageSecGroupSelect($nrc_groupId, "NRC Members", 'selectedMember');
	$this->manageSecGroupSelect($nrc_groupId, "(Click to add members)", 'availableMember');
	echo '</div>';
	
?>
</div>
<?php
	$this->makeLink("nrc_meeting_minutes_doc", "Upload meeting minutes document");
?>
<script>
	$(".date").each(function(){
		if($(this).val() == '1970-01-01'){
			$(this).val('');
		}
	});
	
	$("#FLD_nr_meeting_start_date").datepicker({
		format: 'yyyy-mm-dd',
		autoclose: true
	});
	
	$("#FLD_nr_meeting_end_date").datepicker({
		format: 'yyyy-mm-dd',
		autoclose: true
	});
	
	$("#FLD_nrc_access_start_date").datepicker({
		format: 'yyyy-mm-dd',
		autoclose: true
	});
	$("#FLD_nrc_access_end_date").datepicker({
		format: 'yyyy-mm-dd',
		autoclose: true
	});	
		
    $('#FLD_nr_meeting_end_date').datepicker().on('changeDate', function(e){
		clearValues = new Array();
		clearValues.push($('#FLD_nr_meeting_start_date'));
		clearValues.push($('#FLD_nr_meeting_end_date'));
		smallerThan($(this).val(), $("#FLD_nr_meeting_start_date").val(), clearValues);
    });
	
    $('#FLD_nrc_access_end_date').datepicker().on('changeDate', function(e){
		clearValues = new Array();
		clearValues.push($('#FLD_nrc_access_start_date'));
		clearValues.push($('#FLD_nrc_access_end_date'));
		smallerThan($(this).val(), $("#FLD_nrc_access_start_date").val(), clearValues);
    });
	
    $('#FLD_nr_meeting_start_date').datepicker().on('changeDate', function(e){
		clearValues = new Array();
		clearValues.push($('#FLD_nr_meeting_start_date'));
		clearValues.push($('#FLD_nr_meeting_end_date'));
		biggerThan($(this).val(), $("#FLD_nr_meeting_end_date").val(), clearValues);
    });
	
    $('#FLD_nrc_access_start_date').datepicker().on('changeDate', function(e){
		clearValues = new Array();
		clearValues.push($('#FLD_nrc_access_start_date'));
		clearValues.push($('#FLD_nrc_access_end_date'));
		biggerThan($(this).val(), $("#FLD_nrc_access_end_date").val(), clearValues);
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

function selectAll() {
	sLength = document.defaultFrm.elements['progSelected[]'].length;
	mLength = document.defaultFrm.elements['memSelected[]'].length;
	for (i=0; i<sLength; i++) {
		document.defaultFrm.elements['progSelected[]'].options[i].selected = true;
	}
	for (i=0; i<mLength; i++) {
		document.defaultFrm.elements['memSelected[]'].options[i].selected = true;
	}	
	return true;
}

function optionsTranfer(availableArr, selectedArr, btnText){
	progAvailableLength = document.defaultFrm.elements[availableArr].length;
	if(progAvailableLength != 0){
		for (i=0; i< progAvailableLength; i++){
			document.defaultFrm.elements[availableArr].options[i].selected = true;
			document.defaultFrm.elements[selectedArr].options[document.defaultFrm.elements[selectedArr].length] = new Option(document.defaultFrm.elements[availableArr].options[i].text, document.defaultFrm.elements[availableArr].options[i].value);
		}
		for ( i=(progAvailableLength-1); i>=0; i--) {
			if (document.defaultFrm.elements[availableArr].options[i].selected == true ) {
				document.defaultFrm.elements[availableArr].options[i] = null;
				$('#btn-all-programme').html(btnText);
			}
		}
	}else if(progAvailableLength == 0){
		optionsTranfer( "progSelected[]", "progAvailable[]","Add all programmes");			
	}
}
	
$(document).ready(function(){
	$('#btn-all-programme').on('click', function(e){
		e.preventDefault();
		optionsTranfer("progAvailable[]", "progSelected[]","Remove all programmes" );
	});
	
	$('select').change(function(){
		var $this = $(this),
			optionSelected = $this.find(':selected'),
			parentId = optionSelected.parent('select')[0].id,
			newId,
			newOption = new Option(optionSelected.text(), optionSelected.val());
			 if(parentId == 'selected'){
				newId = 'available';
			}else if( parentId == 'available'){
				newId = 'selected';
			}else if(parentId == 'selectedMember'){
				newId = 'availableMember';
			}else{
				newId = 'selectedMember';
			}	

		var s = document.getElementById(newId);
		s.options[s.options.length] = newOption;

		if(optionSelected){
			optionSelected.remove();
		}
	});
	/*$('select').on('click', 'option', function(){
		var $this = $(this),
			parentId = $this.parent('select')[0].id,
			newId,
			newOption = new Option($this.text(), $this.val());
			 if(parentId == 'selected'){
				newId = 'available';
			}else if( parentId == 'available'){
				newId = 'selected';
			}else if(parentId == 'selectedMember'){
				newId = 'availableMember';
			}else{
				newId = 'selectedMember';
			}	

		$('#' + newId).append(newOption);
		if($this.is(':selected')){
			$this.remove();
		}
	});

	$('#generalActions').on('click', function(e) {
		$('#selected option').prop('selected', true);
		$('#selectedMember option').prop('selected', true);
	});*/
})
</script>