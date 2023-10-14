<?php
	$site_app_proc_id = $this->dbTableInfoArray["inst_site_app_proceedings"]->dbTableCurrentID;
	$ac_ref = $this->getValueFromTable("inst_site_app_proceedings", "inst_site_app_proc_id", $site_app_proc_id, "ac_meeting_ref");
	$ac_start = $this->getValueFromTable("AC_Meeting", "ac_id", $ac_ref, "ac_start_date");
	
	// Disable next action until AC meeting has passed.
	$this->formActions["next"]->actionMayShow = false;

?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
		<?php echo $this->getSiteApplicationTableTop($site_app_proc_id, "sites"); ?>
	<br>
</tr>
<tr><td>&nbsp;</td></tr>
<tr>
	<td>
<?php
		// This site visit has been assigned to an AC meeting
		if ($ac_ref > 0)
		{

			echo '<p class="loud">This site application has been assigned to the following AC meeting:</p>';
			$this->getACMeetingTableTop($ac_ref);

			echo "Once the meeting has taken place please continue to update the AC meeting outcomes for this application.";
						
			$today = date('Y-m-d');
			if ($ac_start <= $today){
				$this->formActions["next"]->actionMayShow = true;
			}

		}
		// This site visit has not been assigned to an AC meeting
		if ($ac_ref == 0)
		{

			echo '<p class="loud">This site visit has not been assigned to an AC meeting yet.</p><p>Further processing can only continue once it has been to an AC meeting</p>';

			echo "<p>To assign a site visit to an AC meeting please use the AC meeting menu option.</p>";

		}

?>
	</td>
</tr>
</table>
<br>