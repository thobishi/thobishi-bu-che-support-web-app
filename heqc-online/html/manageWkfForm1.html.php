<?php 
$this->formFields["proc_nr"]->fieldValue = $_POST["proc_nr"];
$this->showField("proc_nr");

if (! ($this->formFields["processes_ref"]->fieldValue > "") ) {
	 $this->formFields["processes_ref"]->fieldValue = $this->formFields["proc_nr"]->fieldValue;
}
?>
<br>
<br>
<table border='0'>
<tr><td>&nbsp;</td><td>
	<table border='0'>
	<tr>
		<td>Process Ref:</td>
		<td><?php echo $this->showField("processes_ref")?></td>
	</tr><tr>
		<td>Sequence:</td>
		<td><?php echo $this->showField("sec_no")?></td>
	</tr><tr>
		<td>Workflow type:</td>
		<td><?php echo $this->showField("workFlowType_ref")?></td>
	</tr><tr>
		<td>Command:</td>
		<td><?php echo $this->showField("command")?></td>
	</tr><tr>
		<td>Condition:</td>
		<td><?php echo $this->showField("condition")?></td>
	</tr><tr>
		<td>Template:</td>
		<td><?php echo $this->showField("template")?></td>
	</tr><tr>
		<td>TaskName:</td>
		<td><?php echo $this->showField("taskName")?></td>
	</tr><tr>
		<td>Validation:</td>
		<td><?php echo $this->showField("validation")?></td>
	</tr><tr>
		<td>Security Level:</td>
		<td><?php echo $this->showField("securityLevel")?></td>
	</tr><tr>
		<td>Html name:</td>
		<td><?php echo $this->showField("html_name")?></td>
	</tr><tr>
		<td>dbTableName:</td>
		<td><?php echo $this->showField("template_dbTableName")?></td>
	</tr>
	<tr>
		<td>dbTableKeyField:</td>
		<td><?php echo $this->showField("template_dbTableKeyField")?></td>
	</tr>
	</table>
</td></tr></table>
