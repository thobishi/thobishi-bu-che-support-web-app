<br>

<?php 
	$appRequestsID = $this->dbTableInfoArray["appTable_requests"]->dbTableCurrentID;
	$app_id = $this->getValueFromTable("appTable_requests", "appTable_requests_id", $appRequestsID,"application_ref");

	$this->getApplicationInfoTableTop($app_id);
?>

<table width="95%" cellpadding="2" cellspacing="2" border="0" align="center">
<tr class="onblue">
	<td class="onblueb" width="20%" valign="top">Request:</td>
	<td>
		<?php 
			$request_text = simple_text2html($this->getValueFromTable("appTable_requests", "appTable_requests_id", $appRequestsID,"request_text"));
			echo $request_text;
		?>
	</td>
</tr>
<tr class="onblue">
	<td class="onblueb" width="20%" valign="top">Date of your response:</td>
	<td><?php $this->showField("response_date");?></td>
</tr>
<tr class="onblue">
	<td class="onblueb" width="20%" valign="top">Response:</td>
	<td><?php $this->showField("response_text");?></td>
</tr>
<tr class="onblue">
	<td class="onblueb" width="20%" valign="top">Upload docs</td>
	<td><?php $this->makeLink("response_doc");?></td>
</tr>
</table>

<br>

