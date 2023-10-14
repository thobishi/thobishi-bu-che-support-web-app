<?php 
	$isPanelChair = $this->sec_partOfGroup(6);
	if ($isPanelChair){
?>	
	<div class="well PanelInfo">
<?php
		$accessDate = ($panel_start_date == "1970-01-01" || $panel_end_date == "1970-01-01" ) ? "Not assigned" : $panel_start_date . " to " . $panel_end_date;
		$dueDate = ($chair_report_due_date == "1970-01-01") ?  "Not assigned"  : $chair_report_due_date;
		echo '<strong>Chair report due date:</strong> ' . $dueDate;
		echo '<br /><br />';
		echo '<strong>Panel Chair dates:</strong> ' . $accessDate;
?>
	</div>
<?php	} ?>
