<?php 
	$this->showField("application_ref");
?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<?php $this->showInstitutionTableTop ()?>
<br>
<table width="85%" border=0  cellpadding="2" cellspacing="2">
<tr>
	<td><b>The above application has been submitted for accreditation.</b></td>
</tr><tr>
	<td>
	<br>
	<b>Indicate to registry that they:</b>
	<ul>
		<li>Need to create a file for application: Reference No: <b><?php echo $this->table_field_info($this->active_processes_id, "HEQC_ref")?></b></li>
		<li>Print and file the application</li>
	</ul>
	
	In order to send this process to registry for checklisting, click on the "Send Process to Colleague" link on the Actions menu.
	<br><br>
	If you decide to continue with this process yourself, click on the "Next" link on the Actions menu.
	</td>
</tr></table>
<br><br>
</td></tr></table>

