<div class="well headerInfo span6">
	<strong>SER Submission made by the institution:</strong>
	<br /><br />
	SER Submission &nbsp;&nbsp;&nbsp;
	<?php 
		$this->makeLink("ser_doc", "SER document", $dbTableName, $dbTableKeyField, $prog_id);
		//echo $dbTableName;
//echo $dbTableKeyField;
	?>
	
	<br /><br />
	Appendix A &nbsp;&nbsp;&nbsp;
	<?php 
		$this->makeLink("appendix_A_doc", "Appendix A", $dbTableName, $dbTableKeyField, $prog_id);
//echo ' AA# ' . $prog_id;		
		//echo $dbTableName;
//echo $dbTableKeyField;

	?>
	<br /><br />
	
	
	
	 Appendix B &nbsp;&nbsp;&nbsp;
	<?php 
		$this->makeLink("appendix_B_doc", "Appendix B", $dbTableName, $dbTableKeyField, $prog_id);
		//echo $dbTableName;
	?>
	<br /><br />
	
	
	
	Appendix C &nbsp;&nbsp;&nbsp;
	<?php 
		$this->makeLink("appendix_C_doc", "Appendix C", $dbTableName, $dbTableKeyField, $prog_id);
		//echo $dbTableName;
	?>
	<br /><br />
	
	
	Appendix D &nbsp;&nbsp;&nbsp;
	<?php 
		$this->makeLink("appendix_D_doc", "Appendix D", $dbTableName, $dbTableKeyField, $prog_id);
		//echo $dbTableName;
	?>
	<br /><br />
	
	
	Appendix E &nbsp;&nbsp;&nbsp;
	<?php 
		$this->makeLink("appendix_E_doc", "Appendix E", $dbTableName, $dbTableKeyField, $prog_id);
		//echo $dbTableName;
	?>
	<br /><br />
	
	Additional Document &nbsp;&nbsp;&nbsp;
	<?php 
		$this->makeLink("additional_doc", "Additional document", $dbTableName, $dbTableKeyField, $prog_id);
		//echo $dbTableName;
	?>
	<br /><br />

	Prior Site Visit - Q1-3 of SER &nbsp;&nbsp;&nbsp;
	<?php 
		$this->makeLink("additional_doc1", "Additional Document Request 1", $dbTableName, $dbTableKeyField, $prog_id);
		//echo $dbTableName;
	?>
	<br /><br />

	Prior Site Visit - Q4 of SER &nbsp;&nbsp;&nbsp;
	<?php 
		$this->makeLink("additional_doc2", "Additional Document Request 2", $dbTableName, $dbTableKeyField, $prog_id);
		
		//echo $dbTableName;
	?>
	<br /><br />

	Prior Site Visit - Q5-8 and other support documents &nbsp;&nbsp;&nbsp;
	<?php
	// echo '<pre> AD3# ' . $prod_id; print_r($this->dbTableInfoArray);echo '</pre>';
		$this->makeLink("additional_doc3", "Additional Document Request 3", $dbTableName, $dbTableKeyField, $prog_id);
		//echo $dbTableName;
	?>
	<br /><br />

	Site Visit Schedule &nbsp;&nbsp;&nbsp;
	<?php
		$this->makeLink("site_visit_schedule", "Site Visit Schedule", $dbTableName, $dbTableKeyField, $prog_id);
		//echo $dbTableName;
	?>
	<br /><br />

	List of Interviewees &nbsp;&nbsp;&nbsp;
	<?php
		$this->makeLink("list_of_interviewees", "List of Interviewees", $dbTableName, $dbTableKeyField, $prog_id);
		//echo $dbTableName;
	?>
	<br /><br />

	Draft Evaluation Report &nbsp;&nbsp;&nbsp;
	<?php
		$this->makeLink("draft_evaluation_report", "Draft Evaluation Report", $dbTableName, $dbTableKeyField, $prog_id);
		//echo $dbTableName;
	?>
	<br /><br />

	Improvement Plan Approval letter &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<?php 
			$this->makeLink("improvement_plan_approval_letter", "Improvement Plan", "nr_programmes", $dbTableKeyField, $prog_id);
	?>
	<br /><br />

	 Improvement Plan Report Document 1 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<?php 
			$this->makeLink("improvement_plan_additional_doc1", "Improvement Plan", "nr_programmes", $dbTableKeyField, $prog_id);
	?>
	<br /><br />

	Improvement Plan Report Document 2 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<?php 
			$this->makeLink("improvement_plan_additional_doc2", "Improvement Plan", "nr_programmes", $dbTableKeyField, $prog_id);
	?>
	<br /><br />

	Improvement Plan Report Document 3 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<?php 
			$this->makeLink("improvement_plan_additional_doc3", "Improvement Plan", "nr_programmes", $dbTableKeyField, $prog_id);
	?>
	<br /><br />

	 NSAR Sign Off &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<?php 
			$this->makeLink("uploadser_national_standard_alignemnt_report_NSAR_Sign_Off", "NSAR Sign-Off", $dbTableName, $dbTableKeyField, $prog_id);
	?>

	<br /><br />
	 NSAR Additional Document 1 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<?php 
			$this->makeLink("uploadser_national_standard_alignemnt_report_doc_1", "National Standard Alignment Report Document 1" , $dbTableName, $dbTableKeyField, $prog_id);
	?>	
        <br /><br />
	 NSAR Additional Document 2 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<?php 
			$this->makeLink("uploadser_national_standard_alignemnt_report_doc_2", "National Standard Alignment Report Document 2" , $dbTableName, $dbTableKeyField, $prog_id);
	?>

	<br /><br />
	 NSAR Additional Document 3 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<?php 
			$this->makeLink("uploadser_national_standard_alignemnt_report_doc_3", "National Standard Alignment Report Document 3" , $dbTableName, $dbTableKeyField, $prog_id);
			
	?>

	<br /><br />
	 SER Progress Report &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<?php 
			$this->makeLink("uploadser_progress_report", "Upload SER Progress Report", "nr_programmes", $dbTableKeyField, $prog_id);
	?>	
	<br /><br />

	Additional SER Progress Report &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<?php 
			$this->makeLink("uploadser_progress_report_1", "Upload SER Progress Report", "nr_programmes", $dbTableKeyField, $prog_id);
	?>	
	<br /><br />

	Additional SER Progress Report &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<?php 
			$this->makeLink("uploadser_progress_report_2", "Upload SER Progress Report", "nr_programmes", $dbTableKeyField, $prog_id);
	?>	
	<br /><br />

	Screening Report &nbsp;&nbsp;&nbsp;
	<?php 
//echo 'this is MPHO';
//echo $dbTableName . ' tb ' . $prog_id . '  <br> ';
	if (empty($this->dbTableInfoArray["screening"]->dbTableKeyField)) {
//echo 'this is if case <br>';
	  $prog_id = $this->getScreeningIdForDesktopEvalution($prog_id);
echo $prog_id;
	 $this->makeLink("checklist_report_doc", "Checklist report",'screening', 'screening_id', $prog_id);
	}
	else {
	 $dbTableName = $this->dbTableInfoArray["screening"]->dbTableName;
	 $dbTableKeyField =  $this->dbTableInfoArray["screening"]->dbTableKeyField;
	 $prog_id = $this->dbTableInfoArray["screening"]->dbTableCurrentID;
	 $this->makeLink("checklist_report_doc", "Checklist report",$dbTableName, $dbTableKeyField , $prog_id);
	}

	 // $this->makeLink("checklist_report_doc", "Checklist report",$dbTableName, $dbTableKeyField , $prog_id);
		
	//echo $dbTableKeyField;
	?>
	<br /><br />
	
	Sign-off page &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<?php 
		$this->makeLink("signoff_doc", "SER sign-off document", $dbTableName, $dbTableKeyField, $prog_id);
	?>
	<br /><br />
	Data Tables &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<?php 
		echo '<a href="' . $url . '"> Preview online tables</a>';
	?>
	<br /><br />
	 Panel report &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<?php 
		$siteVisit_completed = isset($prog_id) ? $this->db->getValueFromTable("nr_programmes","id",$prog_id,"siteVisit_completed") : '';
		if($siteVisit_completed == '1'){
			$this->makeLink("chair_report_doc", "Panel report", $dbTableName, $dbTableKeyField, $prog_id);
		}
	?>	
	<br /><br />
	 Pre-lim report &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<?php 
		$prelimAnalysis_completed = isset($prog_id) ? $this->db->getValueFromTable("nr_programmes","id",$prog_id,"prelimAnalysis_completed") : '';
		if($prelimAnalysis_completed == '1'){
			$this->makeLink("analyst_report_doc", "Pre-lim report", $dbTableName, $dbTableKeyField, $prog_id);
		}
	?>	
	<br /><br />
	 Recommendation report &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<?php 
		$recommendationCompleted = isset($prog_id) ? $this->db->getValueFromTable("nr_programmes","id",$prog_id,"recommendation_completed") : '';
		if($recommendationCompleted == '1'){
			$this->makeLink("recommendation_report_doc", "Recommendation report", $dbTableName, $dbTableKeyField, $prog_id);
		}
	?>

	<br /><br />
	 Reference Committee report &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<?php 
			$this->makeLink("heqc_recommendation_report_doc", "RC report", $dbTableName, $dbTableKeyField, $prog_id);
	?>	
        	




	
</div>
<div class="clear"></div>
<?php
	if(!empty($screeningHistory)){
?>
		<div class="accordion" id="screen_history">
			<div class="accordion-group">
				<div class="accordion-heading">
					<a class="accordion-toggle" data-toggle="collapse" data-parent="#screen_history" href="#history_data">
					Screening history
					</a>
				</div>
				<div id="history_data" class="accordion-body collapse">
					<div class="accordion-inner">
						<?php echo $screeningHistory; ?>
					</div>
				</div>
			</div>
		</div>
<?php
	}
?>
