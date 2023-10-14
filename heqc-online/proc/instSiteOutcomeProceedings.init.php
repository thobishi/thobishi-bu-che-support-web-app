<?php
	$site_proc_id = $this->dbTableInfoArray["inst_site_app_proceedings"]->dbTableCurrentID;
	$sites_arr = $this->getSiteVisitsForApp($site_proc_id);
	
	// If a site has been approved set the site status to approved
	foreach ($sites_arr as $site){
		if ($site["site_heqc_decision_ref"] == 1){
			$this->setValueInTable("institutional_profile_sites","institutional_profile_sites_id",$site["institutional_profile_sites_ref"],"site_status_ref",'approved');
		}
	}
?>
