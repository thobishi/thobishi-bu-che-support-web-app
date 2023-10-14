<div class="well headerInfo span6">
	<strong>SER Submission made by the institution:</strong>
	<br /><br />
	SER Submission &nbsp;&nbsp;&nbsp;
	<?php 
		$this->makeLink("ser_doc", "SER document", $dbTableName, $dbTableKeyField, $prog_id);
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