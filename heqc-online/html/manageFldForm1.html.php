<?php 
$this->formFields["wkf_name"]->fieldValue = $_POST["wkf_name"];
$this->showField("wkf_name");

if (! ($this->formFields["template_name"]->fieldValue > "") ) {
	 $this->formFields["template_name"]->fieldValue = $this->formFields["wkf_name"]->fieldValue;
}
?>
<br>
<br>
<table border='0'>
<tr><td>&nbsp;</td><td>
	<table border='0'>
	<tr>
		<td>template_name:</td>
		<td><?php echo $this->showField("template_name")?></td>
	</tr><tr>
		<td>fieldName:</td>
		<td><?php echo $this->showField("fieldName")?></td>
	</tr><tr>
		<td>fieldType:</td>
		<td><?php echo $this->showField("fieldType")?></td>
	</tr><tr>
		<td>fieldValue:</td>
		<td><?php echo $this->showField("fieldValue")?></td>
	</tr><tr>
		<td>fieldValuesArray:</td>
		<td><?php echo $this->showField("fieldValuesArray")?></td>
	</tr><tr>
		<td>fieldClass:</td>
		<td><?php echo $this->showField("fieldClass")?></td>
	</tr><tr>
		<td>fieldSize:</td>
		<td><?php echo $this->showField("fieldSize")?></td>
	</tr><tr>
		<td>fieldMaxFieldSize:</td>
		<td><?php echo $this->showField("fieldMaxFieldSize")?></td>
	</tr><tr>
		<td>fieldCols:</td>
		<td><?php echo $this->showField("fieldCols")?></td>
	</tr><tr>
		<td>fieldRows:</td>
		<td><?php echo $this->showField("fieldRows")?></td>
	</tr><tr>
		<td>fieldStyle:</td>
		<td><?php echo $this->showField("fieldStyle")?></td>
	</tr>
	<tr>
		<td>fieldOnClick:</td>
		<td><?php echo $this->showField("fieldOnClick")?></td>
	</tr><tr>
		<td>fieldOnChange:</td>
		<td><?php echo $this->showField("fieldOnChange")?></td>
	</tr><tr>
		<td>fieldStatus:</td>
		<td><?php echo $this->showField("fieldStatus")?></td>
	</tr><tr>
		<td>fieldDBconnected:</td>
		<td><?php echo $this->showField("fieldDBconnected")?></td>
	</tr><tr>
		<td>fieldNullValue:</td>
		<td><?php echo $this->showField("fieldNullValue")?></td>
	</tr><tr>
		<td>fieldOptions:</td>
		<td><?php echo $this->showField("fieldOptions")?></td>
	</tr><tr>
		<td>fieldSelectName:</td>
		<td><?php echo $this->showField("fieldSelectName")?></td>
	</tr><tr>
		<td>fieldSelectID:</td>
		<td><?php echo $this->showField("fieldSelectID")?></td>
	</tr><tr>
		<td>fieldSelectTable:</td>
		<td><?php echo $this->showField("fieldSelectTable")?></td>
	</tr><tr>
		<td>fieldSelectWhere:</td>
		<td><?php echo $this->showField("fieldSelectWhere")?></td>
	</tr><tr>
		<td>fieldMainTable:</td>
		<td><?php echo $this->showField("fieldMainTable")?></td>
	</tr><tr>
		<td>fieldMainFld:</td>
		<td><?php echo $this->showField("fieldMainFld")?></td>
	</tr><tr>
		<td>fieldMainVal:</td>
		<td><?php echo $this->showField("fieldMainVal")?></td>
	</tr><tr>
		<td>fieldRelationFld:</td>
		<td><?php echo $this->showField("fieldRelationFld")?></td>
	</tr><tr>
		<td>fieldRelationTable:</td>
		<td><?php echo $this->showField("fieldRelationTable")?></td>
	</tr><tr>
		<td>fieldRelationKey:</td>
		<td><?php echo $this->showField("fieldRelationKey")?></td>
	</tr><tr>
		<td>fieldRelationVal:</td>
		<td><?php echo $this->showField("fieldRelationVal")?></td>
	</tr><tr>
		<td>fieldDisplayName:</td>
		<td><?php echo $this->showField("fieldDisplayName")?></td>
	</tr><tr>
		<td>fieldValidationType:</td>
		<td><?php echo $this->showField("fieldValidationType")?></td>
	</tr><tr>
		<td>fieldValidationName:</td>
		<td><?php echo $this->showField("fieldValidationName")?></td>
	</tr>
	</table>
</td></tr></table>
