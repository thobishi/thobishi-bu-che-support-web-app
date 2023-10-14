<?php
	$currentUserID = Settings::get('currentUserID');
	
	$prelimDataPanel = $this->getPrelimPanelData('nr_programmes', 'panel_start_date <= CURDATE() AND panel_end_date >= CURDATE()   AND siteVisit_completed = 0  AND siteVisitSubmittedByAdmin_ind = 0',  'lnk_prelim_analysis_user', 'user_ref', $currentUserID, 'lnk_prelim_analysis_user.nr_programme_id = nr_programmes.id');
	
	echo $this->element('panel_member_data', compact('prelimDataPanel'));
?>