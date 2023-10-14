<?php
	$site_id = $this->dbTableInfoArray["inst_site_visit"]->dbTableCurrentID;		
	
	if ($this->getValueFromTable("inst_site_visit", "inst_site_visit_id", $site_id, "site_visit_report_doc") > 0) {
		$this->createAction ("next", "Confirm upload", "submit", "", "ico_next.gif");
	}
	?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
		<br>
		<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
		<tr>
			<td><b>Please upload the evaluator report:</b></td>
		</tr>
		<tr>
			<td>
				<?php
				$this->makeLink("site_visit_report_doc");
				?>
				<br>
				Please note that once you have uploaded the evaluator report it will be flagged as ready to be viewed by the HEQC.
			</td>
		</tr>
		</table>
	</td>
</tr>
</table>

