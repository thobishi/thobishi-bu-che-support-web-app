<?php
	$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	$app_proc_id = $this->dbTableInfoArray["ia_proceedings"]->dbTableCurrentID;
	$proc_type = $this->getValueFromTable("ia_proceedings", "ia_proceedings_id", $app_proc_id, "lkp_proceedings_ref");
	//if ($proc_type == 4){
	//2017-10-20 Richard: Include conditional re-accred
	if (($proc_type == 4) || ($proc_type == 6)){
		$this->formActions['previous']->actionMayShow = false;
	} else {
		$this->formActions['previous1']->actionMayShow = false;
	}
	$this->showField('application_status_ref');
?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
		<?php $this->showInstitutionTableTop (); ?>
	</td>
</tr>
<tr>
	<td>
	<br>
		This application has been through an AC Meeting and the outcome has been updated.  Please confirm that this application may be assigned to 
		a HEQC meeting.
	</td>
</tr>
<tr>
	<td>
	<span class="visi">
	<br>
	Please check this box to indicate that this application may be assigned to a HEQC Meeting.
	<?php $this->showField("heqc_meeting_ready_ind");?>
	</span>
	<br>
	</td>
</tr>
</table>
<br>
