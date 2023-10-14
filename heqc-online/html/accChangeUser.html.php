<br><br>
<table>
<tr>
	<td>
	Once you have completed your part, send it to the next person/structure that needs to be involved in the process or send it back to the administrator. To do this, select an email address from the list below:<br><br>
	<b>Colleague: </b>
<?php
	$AdminRef = $this->getValueFromTable("Institutions_application", "application_id", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "user_ref");
	$InstRef = $this->getValueFromTable("Institutions_application", "application_id", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "institution_id");
	$this->createInputFromDB("user_ref","SELECT", "users", "user_id", "email",1," active = 1 AND institution_ref = ".$InstRef);
	$this->formFields["user_ref"]->fieldValue = $AdminRef;
	$this->showField("user_ref"); 
?>
<br><br>
	</td>
</tr>
</table>
