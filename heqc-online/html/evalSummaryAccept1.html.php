<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<?php $this->showInstitutionTableTop ()?>
<br>
<table><tr>
	<td><b>Please indicate wheather you accept the summary of the evaluations:</b></td>
</tr><tr>
	<td>&nbsp;</td>
</tr><tr>
	<td><?php $this->showField("accept_summary");?>
			<?php 
				$this->formFields["active_process_ref"]->fieldValue = $this->workFlow_settings["ACTPROC"];
				$this->showField("active_process_ref");
			?>
	</td>
</tr><tr>
	<td><b>If you decline, please indicate why.</b><br>
			<?php $this->showField("decline_reason");?>
	</td>
</tr></table>
</td></tr></table>