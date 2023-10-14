<br><br>
<table>
<tr>
	<td>
	Once you have completed your part, send it to the next person/structure that needs to be involved in the process or send it back to the administrator. To do this, select an email address from the list below:<br><br>
	<b>Colleague: </b>
<?php
switch ($this->flowID){
case 130:
	$InstRef = $this->getValueFromTable("Institutions_application_reaccreditation", "Institutions_application_reaccreditation_id", $this->dbTableInfoArray["Institutions_application_reaccreditation"]->dbTableCurrentID, "institution_ref");
	break;
case 113:
default:
	$InstRef = $this->getValueFromTable("Institutions_application", "application_id", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "institution_id");
}
// 2008-09-14 Robin: rather get actual administrator than user on application record.
//	$AdminRef = $this->getValueFromTable("Institutions_application", "application_id", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "user_ref");
if ($InstRef > 0){
	$AdminArr = $this->getInstitutionAdministrator(0,$InstRef);
	$AdminRef = $AdminArr[0];
	$this->createInputFromDB("user_ref","SELECT", "users", "user_id", "email",1," active = 1 AND institution_ref = ".$InstRef,"email");
	$this->formFields["user_ref"]->fieldValue = $AdminRef;
	$this->showField("user_ref"); 
} else {
	echo "There was a problem building the list of users for your institution. Please click Previous to return to your application.";
	echo "Please contact HEQC-Online support if the problem persists.";
}
?>
<br><br>
	</td>
</tr>
</table>
