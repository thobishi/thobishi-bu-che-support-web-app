<?php
	$this->formFields["programme_ref"]->fieldValue = $this->dbTableInfoArray["nr_programmes"]->dbTableCurrentID;
	$this->formFields["active_user_ref"]->fieldValue = Settings::get('currentUserID');
	
	$prog_id = $this->dbTableInfoArray["nr_programmes"]->dbTableCurrentID;
	$this->savePanelUsers('panel_members', $prog_id, 'lnk_prelim_analysis_user', 'nr_programme_id');
	$additional_docArr = readPost('additional_doc');
	if(!empty($additional_docArr)){
		$this->saveProgrammeDocs($additional_docArr);
	}
	
	$siteVisit_completed = isset($prog_id) ? $this->db->getValueFromTable("nr_programmes","id",$prog_id,"siteVisit_completed") : '';
	$siteVisitSubmittedByAdmin_ind = $this->db->getValueFromTable("nr_programmes","id",$prog_id,"siteVisitSubmittedByAdmin_ind");
	$analystReportSubmittedByAdmin_ind = $this->db->getValueFromTable("nr_programmes","id",$prog_id,"analystReportSubmittedByAdmin_ind");
	$prelimAnalysis_completed = $this->db->getValueFromTable("nr_programmes","id",$prog_id,"prelimAnalysis_completed");
	
	// $this->pr("siteVisit_completed ".$siteVisit_completed);
	// $this->pr("siteVisitSubmittedByAdmin_ind ".$siteVisitSubmittedByAdmin_ind);
	// $this->pr("analystReportSubmittedByAdmin_ind ".$analystReportSubmittedByAdmin_ind);
	// $this->pr("prelimAnalysis_completed ".$prelimAnalysis_completed);
?>
<script>
	var siteVisit_completed = '<?php echo $siteVisit_completed; ?>';
	var siteVisitSubmittedByAdmin_ind = '<?php echo $siteVisitSubmittedByAdmin_ind; ?>';
	var analystReportSubmittedByAdmin_ind = '<?php echo $analystReportSubmittedByAdmin_ind; ?>';
	var prelimAnalysis_completed = '<?php echo $prelimAnalysis_completed; ?>';

	if( analystReportSubmittedByAdmin_ind != '1' && prelimAnalysis_completed != '1'){
		$("#action_next").hide();
		$("#action_ser_prelim_reports").closest("li").hide();
		$("#action_ser_prelim_additional_info").closest("li").hide();
	}
	
	if(siteVisit_completed != '1' && siteVisitSubmittedByAdmin_ind != '1'){
		$("#action_ser_managePanel_report").closest("li").hide();		
		$("#action_ser_prelim_criteria").closest("li").hide();
		$("#action_ser_prelim_validation").closest("li").hide();
	}
	
</script>	
