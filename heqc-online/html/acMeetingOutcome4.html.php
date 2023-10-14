<?php
	$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	$app_proc_id = $this->dbTableInfoArray["ia_proceedings"]->dbTableCurrentID;
	$this->showInstitutionTableTop ();
	
?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
		<br>
		The recommendation for this application is deferral.  This application may be returned to the institution for the deferral proceedings to begin.
		
		This application will bypass the HEQC Board meeting and will proceed to the confirmation of the outcome.
		
		Please click Proceed to next user and process to continue.		
	</td>
</tr>
</table>
<br>