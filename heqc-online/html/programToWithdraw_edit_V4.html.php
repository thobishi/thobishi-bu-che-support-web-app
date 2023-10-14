

<?php
		$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;

		$date_withdrawn = date("Y-m-d");
		$user = $this->getValueFromTable("users","user_id", $this->currentUserID,"name");
		$this->formFields["application_ref"]->fieldValue = $app_id;
		$this->formFields["user_ref"]->fieldValue = $this->currentUserID;
		$this->formFields["date_withdrawn"]->fieldValue = $date_withdrawn;
		$this->showField("application_ref");
		$this->showField("user_ref");
		$this->showField("date_withdrawn");

     $this->showInstitutionTableTop ();

	// $this->getApplicationInfoTableTop($app_id);

?>	
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">

<tr>
	
	
	</tr>
				
				<tr>
					<td class="" colspan="2">
					It will be recorded that  withdrew this programme with the above details on .
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>
					
					</td>
				</tr>
				<tr>
					<td>Reason for the programme withdrawal</td><td><?php $this->showField('reason'); ?></td>
				</tr>		
				<tr>
					<td>Document as evidence for the programme withdrawal</td><td><?php $this->makeLink('reason_doc'); ?></td>
				</tr>
				<tr>
					<td class="" colspan="2">
						Please indicate that you confirm to withdraw this programme by checking the box.<?php $this->showField('chk_withdraw'); ?>
					</td>	
				</tr>
				</table>
			

