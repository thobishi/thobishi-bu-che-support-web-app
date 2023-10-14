<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>

<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr>
	<td><b>Please upload the evaluator report:</b></td>
</tr><tr>
	<td>
		<?php 
		if ($this->getValueFromTable("evalReport", "evalReport_id", $this->dbTableInfoArray["evalReport"]->dbTableCurrentID, "evalReport_doc") > 0) {
			$this->createAction ("next", "Confirm upload", "submit", "", "ico_next.gif");
		}
			$this->makeLink("evalReport_doc");
		?>
		<br><br>
		Please be sure to click "Confirm upload" once you have uploaded the report.
	</td>
</tr></table>
</td></tr></table>


