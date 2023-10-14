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

	Screening Report &nbsp;&nbsp;&nbsp;
	<?php 

$dbTableName = $this->dbTableInfoArray["screening"]->dbTableName;
	$dbTableKeyField =  $this->dbTableInfoArray["screening"]->dbTableKeyField;
$prog_id = $this->dbTableInfoArray["screening"]->dbTableCurrentID;

	 $this->makeLink("checklist_report_doc", "Checklist report",$dbTableName, $dbTableKeyField , $prog_id);
		
	
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
