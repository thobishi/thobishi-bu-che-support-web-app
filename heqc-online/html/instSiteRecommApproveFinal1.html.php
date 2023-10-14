<?php
	$site_app_proc_id = $this->dbTableInfoArray["inst_site_app_proceedings"]->dbTableCurrentID;
?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
	<?php echo $this->getSiteApplicationTableTop($site_app_proc_id, "sites"); ?>
	<br>
	The recommendation users assigned to this application are displayed below.  The recommendation is available below.  
	You are responsible for the final approval of the recommendation for this site application and indicating whether this site application 
	may be assigned to an AC meeting.

<?php
		// Display recommendation users that confirmed "accepted" to do the Directorate recommendation for the site
		$criteria = array("lop_status_confirm = 1");
		$recomm = $this->getSelectedRecommUserForSiteApplication($site_app_proc_id, $criteria);
		
		// Process cannot continue without recommendation users having confirmed.
		
		echo $this->displayRecommUsers($recomm,'_siteRecommForm_final','site');
?>
	</td>
</tr>
<tr>
	<td>
	<span class="visi">
	<br>
	Please check this box to indicate that the Directorate recommendation has passed final approval and may be assigned to an AC Meeting.
	<?php $this->showField("readyForACMeeting");?>
	</span>
	<br>
	</td>
</tr>
</table>
<br>