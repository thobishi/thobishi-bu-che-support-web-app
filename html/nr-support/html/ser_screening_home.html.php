<h3>Screening</h3>

<div class="row-fluid">
<?php
	$this->view = 1;
	$currentUserID = Settings::get('currentUserID');
	$prog_id = $this->dbTableInfoArray["nr_programmes"]->dbTableCurrentID;
	$nr_type = $this->db->getValueFromTable("nr_programmes","id",$prog_id,"nr_national_review_id");
	switch ($nr_type) {
	 	case 'BSW':
	 		$template_location = 'html_documents/Checklist_template.docx';
	 		break;
	 	case 'LLB':
	 		$template_location =  "html_documents/$nr_type/Checklist_template.docx";
	 		break;
	 	default:  // Always set to the latest national review SER process
	 		$template_location = "html_documents/$nr_type/Checklist_template.docx";
	 }

	$dbTableName = $this->dbTableInfoArray["nr_programmes"]->dbTableName;
	$dbTableKeyField = $this->dbTableInfoArray["nr_programmes"]->dbTableKeyField;
	$this->displayProgrammeInfo();
	$url = "javascript:showSERreadOnly(" . $prog_id . ");";
	$screeningHistory = $this->getScreeningDetails('programme_ref', $prog_id);
	
	echo $this->element('header_information', compact('dbTableName', 'dbTableKeyField', 'prog_id', 'screeningHistory', 'url'));
?>
</div>

<?php
	$this->showField("programme_ref");
	$this->showField("active_user_ref");
	$this->view = 0;
?>

<table class="table table-bordered  screeningTable">
	<tr>
		<td class="scrNumber">
			1
		</td>
		<td class="scrDescription">
			Download the Checklist Feedback template and complete
		</td>
		<td class="fieldsetData">
			<fieldset><legend>Download the checklist template</legend>
				<a target="_blank" href="<?php echo $template_location;?>"><img src="images/DOC.png" alt="DOC">&nbsp;Download the template</a>
			</fieldset>
		</td>
	</tr>
	<tr>
		<td class="scrNumber">
			2
		</td>
		<td  class="scrDescription">
			Does the SER report satisfy the screening requirements?
		</td>
		<td class="fieldsetData">
			<fieldset><legend>Is the SER ready for desktop evaluation? </legend>
			<?php 
				$this->showField("ser_ready_yn");
			?>
			</fieldset>
		</td>
	</tr>
	<tr>
		<td class="scrNumber">
			3
		</td>
		<td class="scrDescription">
			Upload your Checklist Feedback Report
		</td>
		<td class="fieldsetData upload_report">
			<fieldset><legend>Upload the Checklist Report</legend>
				<?php
					$this->makeLink("checklist_report_doc", "Checklist report");
				?>
			</fieldset>
		</td>
	</tr>
	<tr>
		<td class="scrNumber">
			4
		</td>
		<td class="scrDescription">
			Sign-off checklisting
		</td>
		<td class="fieldsetData sign-off">
			<fieldset><legend>Sign-off</legend>
			<?php
				$this->showField("signoff_checklisting_ind");
				echo ' I acknowledge that I, '.$this->getUserFullName($currentUserID).' , have completed checklisting this SER on '.date("Y-m-d").' ';
				?>
			</fieldset>
		</td>
	</tr>
</table>

<script>
	$(".sign-off input:checkbox").change(function(){
		if($(this).is(':checked')){
			$(this).val("1");
		}
		else{
			$(this).val("0");
		}
	});
	
	$(".sign-off input:checkbox").each(function(){
		if($(this).is(':checked')){
			$(this).val("1");
		}
		else{
			$(this).val("0");
		}
	});
	
	var atLeastOneChecked = false;
	var countRadios = $(".nowrap input:radio[name=FLD_ser_ready_yn]").length;
	var count = 0;
	var radioValues = new Array();
	
	checkRadios();
	
	$(".nowrap input:radio").change(function(){
		var count = 0;
		checkRadios();
	});
	
	function checkRadios(){
		$(".nowrap input:radio[name=FLD_ser_ready_yn]").each(function(){
			count++;
			$(this).attr('value', $(this).attr('id'));
			if($(this).is(':checked')){
				atLeastOneChecked = true;
			}
			
			if(count == countRadios){
				$(".nowrap input:radio[name=FLD_ser_ready_yn]").each(function(){
					if(!atLeastOneChecked && $(this).attr('value') == $(this).attr('id')){
						$(this).attr('value', 0);
					}
				});
			}
		});
	}
</script>
