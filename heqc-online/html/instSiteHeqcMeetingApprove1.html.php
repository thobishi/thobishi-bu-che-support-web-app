<?php
	$site_proc_id = $this->dbTableInfoArray["inst_site_app_proceedings"]->dbTableCurrentID;
	$proc_type = $this->getValueFromTable("inst_site_app_proceedings", "inst_site_app_proc_id", $site_proc_id, "lkp_site_proceedings_ref");
	$this->showField('application_status_ref');
?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
		<?php echo $this->getSiteApplicationTableTop($site_proc_id, "sites"); ?>
	</td>
</tr>
<tr>
	<td>
	<br>
		This site application has been through an AC Meeting and the outcome has been updated.  Please confirm that this site application may be assigned to 
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