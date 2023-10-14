<?php
	$site_proc_id = $this->dbTableInfoArray["inst_site_app_proceedings"]->dbTableCurrentID;
	$site_visit_id = $this->dbTableInfoArray["inst_site_visit"]->dbTableCurrentID;

	$recomm_type = "";
	switch ($this->flowID){
	case '182':
		$this->createAction ("cancel", "Cancel without saving", "href", "javascript:cancelView('_startSiteACOutcome2');", "ico_cancel.gif");
		break;
	case '185':
		$this->createAction ("cancel", "Cancel without saving", "href", "javascript:cancelView('startSiteACoutcomeApprove');", "ico_cancel.gif");
		break;
	}

	$main_title = 'AC recommendation';
	$instr = 'Please update the AC recommendation for the following site:';
	$decision_fld = 'site_ac_decision_ref';
	$table = "inst_site_visit_ac_decision";
	$key = "inst_site_visit_ac_decision_id";

?>
	<input type='hidden' name='cmd' value=''>
	<input type='hidden' name='id' value=''>
<?php
	// The following is required for GridShowRowByRow as well as the inputs above.
	if (isset($_POST["cmd"]) && ($_POST["cmd"] > "")) {
		$cmd = explode("|", $_POST["cmd"]);
		$this->getCMD_action($cmd);
		echo '<!--script>';
		echo 'document.defaultFrm.action = "#'.$cmd[1].'";';
		echo 'document.defaultFrm.MOVETO.value = "stay";';
		echo 'document.defaultFrm.submit();';
		echo '</script-->';
	}
?>
<table width="95%" border="0" align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
		<br>
		<span class="loud"><?php echo $main_title; ?></span>
		<br>
		<br><?php echo $instr; ?> 	
	</td>
</tr>
<tr>
	<td>
		<?php echo $this->getSiteVisitTableTop($site_visit_id); ?>
	</td>
</tr>
<tr>
	<td>
		<hr>
	</td>
</tr>
<tr>
	<td>
		The Accreditation Directorate recommends that the above site be	<?php $this->showField($decision_fld); ?>
	</td>
</tr>
<tr>
	<td>
	<?php 
		$dFields = array();
		array_push($dFields, "type__textarea|cols__80|rows__7|name__decision_reason_condition");
		array_push($dFields, "type__text|size__25|name__criterion_min_standard");
		array_push($dFields, "type__select|name__condition_term_ref|description_fld__lkp_condition_term_desc|fld_key__lkp_condition_term_id|lkp_table__lkp_condition_term|lkp_condition__1|order_by__lkp_condition_term_id");

		$hFields = array("Reasons for deferral or non-accreditation or conditions for conditional accreditation", "Criterion and <br>Minimum Standards","Condition term<br> (if applicable)");
		
	?>
		<table>
			<?php $this->gridShowRowByRow($table, $key, "inst_site_visit_ref__".$site_visit_id, $dFields,$hFields, 10, 5, "true", "true", 1); ?>
		</table>
	</td>
</tr>
<tr>
	<td>
		<br>
		The Accreditation Directorate recommends that the institution be permitted to offer the following programmes at this site of delivery:
	</td>
</tr>
<tr>
	<td>
	<?php
		$fieldArr = array();
		array_push($fieldArr, "type__select|name__application_ref|status__3|size__30|description_fld__program_name|fld_key__application_id|lkp_table__Institutions_application|lkp_condition__1|order_by__program_name");
		// 2012-06-26: Checkbox is unreliable. Deletes all data on cancel.  Need to review checkbox in workflow engine. Using radio Yes/No instead.
		//array_push($fieldArr, "type__checkbox|name__recomm_offering_ind");
		// Value of 0 does not come up in radio type - forced to use Yes - No
		//array_push($fieldArr, "type__radio|name__recomm_offering_ind|description_fld__lkp_indicator_desc|fld_key__lkp_indicator_id|lkp_table__lkp_indicator|lkp_condition__lkp_indicator_id!=0|order_by__lkp_indicator_desc");
		array_push($fieldArr, "type__radio|name__recomm_offering_ind|description_fld__lkp_yn_desc|fld_key__lkp_yn_id|lkp_table__lkp_yes_no|lkp_condition__lkp_yn_id!=0|order_by__lkp_yn_desc");

		$headArr = array("Programme", "Permit offering at this site");

		?>
		<table>
			<?php $this->gridShowRowByRow("inst_site_visit_progs", "inst_site_visit_progs_id", "site_visit_ref__".$site_visit_id, $fieldArr,$headArr,'','', "", "", 0); ?>
		</table>
	</td>
</tr>
</table>
