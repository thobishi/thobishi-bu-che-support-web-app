<?php
	$prog_id = $this->dbTableInfoArray['nr_programmes']->dbTableCurrentID;
	$recommendationCompleted = isset($prog_id) ? $this->db->getValueFromTable("nr_programmes","id",$prog_id,"recommendation_completed") : '';
	$recommendationSubmittedByAdmin_ind = $this->db->getValueFromTable("nr_programmes","id",$prog_id,"recommendationSubmittedByAdmin_ind");
?>
<script>
	var checkCompletionStatus = '<?php echo $recommendationCompleted; ?>';
	var recommendationSubmittedByAdmin = '<?php echo $recommendationSubmittedByAdmin_ind; ?>';
	if(checkCompletionStatus != '1' && recommendationSubmittedByAdmin != '1'){
		$("#action_next").hide();
		$("#action_ser_recomm_report").closest("li").hide();
		$("#action_ser_recomm_criteria").closest("li").hide();
		$("#action_ser_recomm_validation").closest("li").hide();
	}
</script>