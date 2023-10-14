<?php
	$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	$app_proc_id = $this->dbTableInfoArray["ia_proceedings"]->dbTableCurrentID;
	$this->showInstitutionTableTop ();
	$ac_ref = $this->getValueFromTable("ia_proceedings", "ia_proceedings_id", $app_proc_id, "ac_meeting_ref");
	$ac_start = $this->getValueFromTable("AC_Meeting", "ac_id", $ac_ref, "ac_start_date");
	$proc_type = $this->getValueFromTable("ia_proceedings", "ia_proceedings_id", $app_proc_id, "lkp_proceedings_ref");
	//if ($proc_type == 4){
	//2017-10-20 Richard: Include conditional re-accred
	if (($proc_type == 4) || ($proc_type == 6)){
		$this->formActions['previous']->actionMayShow = false;
	} else {
		$this->formActions['previous1']->actionMayShow = false;
	}
	// Disable next action until AC meeting has passed.
	$this->formActions["next"]->actionMayShow = false;

?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr><td>&nbsp;</td></tr>
<tr>
	<td>
<?php
		// This programme has been assigned to an AC meeting
		if ($ac_ref > 0)
		{

			echo '<p class="loud">This programme has been assigned to the following AC meeting:</p>';
			$this->getACMeetingTableTop($ac_ref);

			echo "Once the meeting has taken place please continue to update the AC meeting outcomes for this application.";
						
			$today = date('Y-m-d');
			if ($ac_start <= $today){
				$this->formActions["next"]->actionMayShow = true;
			} 
			//2017-11-02 Richard: Allowed for conditional applications
			if (($proc_type == 4) || ($proc_type == 6)) {
				$this->formActions["next"]->actionMayShow = true;
			}
			

		}
		// This programme has not been assigned to an AC meeting
		if ($ac_ref == 0)
		{

			echo '<p class="loud">This programme has not been assigned to an AC meeting yet.</p><p>Further processing can only continue once it has been to an AC meeting</p>';

			echo "<p>To assign a programme to an AC meeting please use the AC meeting menu option.</p>";

		}

?>
	</td>
</tr>
</table>
<br>
