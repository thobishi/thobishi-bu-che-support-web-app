<?php
	$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	$app_proc_id = $this->dbTableInfoArray["ia_proceedings"]->dbTableCurrentID;
	$prev_prov_id = $this->getValueFromTable("ia_proceedings", "ia_proceedings_id",$app_proc_id,"prev_ia_proceedings_ref");
	$this->showInstitutionTableTop ();
?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
		<br>
		Your institution should have received formal notification that the HEQC has approved that a decision on the accreditation of the programme be deferred 
		pending the submission of additional information: 
	</td>
</tr>
<tr>
	<td>
	<br>
		<?php $this->displayOutcome($prev_prov_id);  ?>
	</td>
</tr>
<tr>
	<td>
		The institution is requested to submit information in response to the reasons as set out above. 
	</td>
</tr>
<tr>
	<td>
		Upload document: <?php $this->makeLink('deferral_doc'); ?>
	</td>
</tr>
<tr>
	<td>
		
	</td>
</tr>
</table>
<br>