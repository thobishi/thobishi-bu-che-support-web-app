<?php
	$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	if ($this->view != 1) {	$this->getApplicationInfoTableTopForHEI_sites($app_id); }
?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
<td>

	<br>
	<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr>
		<td><b>Please upload the directorate recommendation:</b></td>
	</tr><tr>
		<td>
			<?php
			$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	
			if ($this->getValueFromTable("Institutions_application", "application_id", $app_id, "secretariat_doc") > 0) {
				$this->createAction ("next", "Confirm upload", "submit", "", "ico_next.gif");
			}
			$this->makeLink("secretariat_doc");
			?>
			<br>
			Please note that once you click "Confirm upload", the directorate recommendation will be flagged as ready to be viewed by the HEQC.
		</td>
	</tr>
	
	</table>
	
</td>
</tr>
</table>


