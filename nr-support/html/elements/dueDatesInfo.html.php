<?php 
	$isPanelChair = $this->sec_partOfGroup(6);
	$isRecommWriter = $this->sec_partOfGroup(7);
	$dueDateDesc = ($isPanelChair && !$isRecommWriter) ? "Chair report due date:" : "Recommendation report due date:";
	$accessDateDesc = ($isPanelChair && !$isRecommWriter) ? "Panel Chair access dates:" : "Recommendation Writer access dates:";

?>	
	<div class="well datesInfo span6">
<?php
		$accessDate = ($startDate == "1970-01-01" || $endDate == "1970-01-01" ) ? "Not assigned" : $startDate . " to " . $endDate;
		$duedateVal = ($dueDate == "1970-01-01") ?  "Not assigned"  : $dueDate;
		echo '<strong>' . $dueDateDesc  . '</strong> ' . $duedateVal;
		echo '<br /><br />';
		echo '<strong>' . $accessDateDesc . '</strong> ' . $accessDate;
?>
	</div>

