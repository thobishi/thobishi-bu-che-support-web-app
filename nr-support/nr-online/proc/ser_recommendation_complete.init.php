<?php
	$prog_id = $this->dbTableInfoArray['nr_programmes']->dbTableCurrentID;
	// $recommendationCompleted = isset($prog_id) ? $this->db->getValueFromTable("nr_programmes","id",$prog_id,"recommendation_completed") : '';
	$recommendationSubmittedByAdmin_ind = $this->db->getValueFromTable("nr_programmes","id",$prog_id,"recommendationSubmittedByAdmin_ind");
	if($recommendationSubmittedByAdmin_ind == '1'){
		$this->db->setValueInTable('nr_programmes','id',$prog_id,'recommendation_completed','1');
		$this->db->setValueInTable('nr_programmes','id',$prog_id,'recommendation_date_submitted', date('Y-m-d'));
	}
?>
<script>

</script>