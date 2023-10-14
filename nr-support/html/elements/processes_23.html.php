<?php
	$currentUserID = Settings::get('currentUserID');
	$RecommendationDataPanel = $this->getRecommendationData('nr_programmes', 'recommendation_start_date <= CURDATE() AND recommendation_end_date >= CURDATE() AND recommendation_completed = 0  AND recommendationSubmittedByAdmin_ind = 0', 'recommendation_user_ref', $currentUserID);	
	
	echo $this->element('recomendation_writer_data', compact('RecommendationDataPanel'));
?>