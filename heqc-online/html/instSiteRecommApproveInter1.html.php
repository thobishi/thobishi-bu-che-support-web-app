<?php
	$site_app_proc_id = $this->dbTableInfoArray["inst_site_app_proceedings"]->dbTableCurrentID;
?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
	<?php echo $this->getSiteApplicationTableTop($site_app_proc_id, "sites"); ?>
	<br>
	The recommendation users assigned to this application are displayed below.  The recommendation is available below.  
	You are responsible for intermediate approval of this application.
	<br>
	<br>

<?php
		// Display recommendation users that confirmed "accepted" to do the Directorate recommendation for the site
		$criteria = array("lop_status_confirm = 1");
		$recomm = $this->getSelectedRecommUserForSiteApplication($site_app_proc_id, $criteria);
		
		// Process cannot continue without recommendation users having confirmed.
		
		echo $this->displayRecommUsers($recomm,'_siteRecommForm_inter','site');
?>
	</td>
</tr>
<tr>
	<td>
	<br>
	<span class="visi">Please check this box to indicate that the Directorate recommendation has passed intermediate approval: <?php $this->showField("interRecommApproval");?></span>
	<br><i>Please note if you check this box and click on <span class="specialb">Proceed to next process and user</span>, the application will be passed to management for final approval of the Directorate recommendation.</i>
	</td>
</tr>
</table>
<br>