<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<br><br>
<?php 
	if (isset($_POST["Tproc"]) && $_POST["Tproc"] > ""){
		$this->formFields["processes_ref"]->fieldValue = $_POST["Tproc"];
	}
?>

<table width="75%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td align="right" width="30%" valign="top"><b>Template name:</b></td>
	<td width="70%" class="oncolour"><?php $this->showField("template") ?></td>
</tr><tr>
	<td align="right" width="30%" valign="top"><b>Process:</b></td>
	<td width="70%" class="oncolour"><?php $this->showField("processes_ref");?></td>
</tr><tr>
	<td align="right" width="30%" valign="top"><b>Workflow Type:</b></td>
	<td width="70%" class="oncolour"><?php $this->showField("workFlowType_ref");?></td>
</tr><tr>
	<td align="right" width="30%" valign="top"><b>HTML Name:</b></td>
	<td width="70%" class="oncolour"><?php $this->showField("html_name");?></td>
</tr><tr>
	<td align="right" width="30%" valign="top"><b>DB Table:</b></td>
	<td width="70%" class="oncolour"><?php $this->showField("template_dbTableName");?></td>
</tr><tr>
	<td align="right" width="30%" valign="top"><b>DB Key Field:</b></td>
	<td width="70%" class="oncolour"><?php $this->showField("template_dbTableKeyField");?></td>
</tr><tr>
	<td align="right" width="30%" valign="top"><b>Sequence Nr:</b></td>
	<td width="70%" class="oncolour"><?php $this->showField("sec_no");?></td>
</tr><tr>
	<td align="right" width="30%" valign="top"><b>Security Level:</b></td>
	<td width="70%" class="oncolour"><?php $this->showField("securityLevel");?></td>
</tr><tr>
	<td align="right" width="30%" valign="top"><b>Template Description:</b></td>
	<td width="70%" class="oncolour"><?php $this->showField("taskName") ?></td>
</tr><tr>
	<td align="right" width="30%" valign="top"><b>Template Command:</b></td>
	<td width="70%" class="oncolour"><?php $this->showField("command") ?></td>
</tr><tr>
	<td align="right" width="30%" valign="top"><b>Template Condition:</b></td>
	<td width="70%" class="oncolour"><?php $this->showField("condition") ?></td>
</tr></table>
<br><br>
<?php 	echo $program;?>
<br><br>
</td></tr></table>
<input type='hidden' name='cmd' value=''>
<input type='hidden' name='id' value=''>
<script>
	function changeCMD (newCMD) {
		document.defaultFrm.cmd.value = newCMD;
	}
	function changeID (newID) {
		document.defaultFrm.id.value = newID;
	}
	function makeReport() {
		document.defaultFrm.report.value = '1';
	}
</script>
