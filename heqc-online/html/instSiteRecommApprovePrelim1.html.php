<?php
	$site_app_proc_id = $this->dbTableInfoArray["inst_site_app_proceedings"]->dbTableCurrentID;
	$this->showField("recomm_complete_ind");
?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
	<?php echo $this->getSiteApplicationTableTop($site_app_proc_id, "sites"); ?>
	<br>
	The recommendation users assigned to this application are displayed below.  They will now have access to the current site application through the HEQC-online system. 
	They will be able to capture the site Directorate recommendation for the application.
	<br>
	<br>

<?php

		// Display recommendation users that confirmed "accepted" to do the Directorate recommendation for the site
		$criteria = array("lop_status_confirm = 1");
		$recomm = $this->getSelectedRecommUserForSiteApplication($site_app_proc_id, $criteria);
		
		// Process cannot continue without recommendation users having confirmed.
		
		echo $this->displayRecommUsers($recomm,'_siteRecommForm_prelim','site');
		
?>
	</td>
</tr>
<tr>
	<td>
	The above users will have access to the programme until: <?php $this->showfield('recomm_access_end_date'); ?>
	</td>
</tr>
<tr>
	<td>
	<br>
	<span class="visi">Please check this box to indicate that the Directorate recommendation has passed preliminary approval: <?php $this->showField("prelimRecommApproval");?></span>
	<br><i>Please note if you check this box and click on <span class="specialb">Proceed to next process and user</span>, the application will be passed to management for intermediate approval of the Directorate recommendation.</i>
	</td>
</tr>
</table>
<br>
