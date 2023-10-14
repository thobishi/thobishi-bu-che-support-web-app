<?php
	$currentUserID = Settings::get('currentUserID');
	
	$prelimDataAnalyst = $this->getPrelimAnalysisData('nr_programmes', 'analyst_user_ref', $currentUserID, 'analyst_start_date <= CURDATE() AND analyst_end_date >= CURDATE() AND prelimAnalysis_completed = 0 AND analystReportSubmittedByAdmin_ind = 0');
	
	echo $this->element('analyst_data', compact('prelimDataAnalyst'));
?>