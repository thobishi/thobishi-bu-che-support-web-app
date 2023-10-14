<?php 
	$this->showInstitutionTableTop ();

	$id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	$this->formFields['application_ref']->fieldValue = $id;
	$this->showField('application_ref');

	$this->formFields['user_from_ref']->fieldValue = $this->currentUserID;
	$this->showField('user_from_ref');

	$inst_adm_arr = $this->getInstitutionAdministrator($id);
	if ($inst_adm_arr[0] == 0){
		echo $inst_adm_arr[1];
	} else {
		$this->formFields['user_to_ref']->fieldValue = $inst_adm_arr[0];
		$this->showField('user_to_ref');
	}
?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
	<tr>
		<td class="loud">
		<br>
		New request:
		</td>
	</tr>
	<tr>
		<td>
		<br>
		To send a request for additional information to the institution please type in your request and click on <b>Send request to institution administrator</b>.  The institutional administrator will receive an email
		with the request and instructions for uploading the required information.  They will have an active process in their
		processes list when they login which will enable them to attend to this request.
		</td>
	</tr>
	<tr>
	<td>
		<table>
		<tr>
			<td>Request Date: </td><td><?php $this->showField('request_date'); ?></td>
		</tr>
		<tr>
			<td valign="top">Request: </td><td><?php $this->showField('request_text'); ?></td>
		</tr>
		</table>
	</td>
	</tr>
</table>
<br>
