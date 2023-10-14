<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<br><br>
<?php 
	if (isset($_POST["temp"]) && $_POST["temp"] > ""){
		$this->formFields["template_ref"]->fieldValue = $_POST["temp"];
		$this->showField("template_ref");
	}
?>
<table width="75%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td align="right" width="30%" valign="top"><b>Text Type:</b></td>
	<td width="70%" class="oncolour"><?php $this->showField("text_type_ref");?></td>
</tr><tr>
	<td align="right" width="30%" valign="top"><b>Short Description:</b></td>
	<td width="70%" class="oncolour"><?php $this->showField("template_text_desc") ?></td>
</tr><tr>
	<td align="right" width="30%" valign="top"><b>Programming Text:</b></td>
	<td width="70%" class="oncolour"><?php $this->showField("text_programming") ?></td>
</tr><tr>
	<td align="right" width="30%" valign="top"><b>Template Text:</b></td>
	<td width="70%" class="oncolour"><?php $this->showField("text_actual") ?></td>
</tr></table>
<br><br>
</td></tr></table>
