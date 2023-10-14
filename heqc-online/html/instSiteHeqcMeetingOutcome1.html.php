<?php
	$site_app_proc_id = $this->dbTableInfoArray["inst_site_app_proceedings"]->dbTableCurrentID;
	$heqc_meeting_ref = $this->getValueFromTable("inst_site_app_proceedings", "inst_site_app_proc_id", $site_app_proc_id, "heqc_meeting_ref");
	$heqc_start = $this->getValueFromTable("HEQC_Meeting", "heqc_id", $heqc_meeting_ref, "heqc_start_date");
	
	// Disable next action until HEQC meeting has passed.
	$this->formActions["next"]->actionMayShow = false;
?>
<table width="95%" border="0" align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>&nbsp;</td>
</tr>
<tr>
	<td>
<?php
		// This programme has been assigned to an HEQC meeting
		if ($heqc_meeting_ref > 0)
		{

			echo '<p class="loud">This programme has been assigned to the following HEQC meeting:</p>';
			$this->getHEQCMeetingTableTop($heqc_meeting_ref);

			echo "Once the meeting has taken place please continue to update the HEQC meeting outcomes for this application.";
						
			$today = date('Y-m-d');
			if ($heqc_start <= $today){
				$this->formActions["next"]->actionMayShow = true;
			}
			

		}
		// This programme has not been assigned to an HEQC meeting
		if ($heqc_meeting_ref == 0)
		{

			echo '<p class="loud">This site application has not been assigned to a HEQC meeting yet.</p><p>Further processing can only continue once it has been to an HEQC meeting</p>';

			echo "<p>To assign a site application to an HEQC meeting please use the HEQC meeting menu option.</p>";

		}

?>
	</td>
</tr>
</table>
<br>