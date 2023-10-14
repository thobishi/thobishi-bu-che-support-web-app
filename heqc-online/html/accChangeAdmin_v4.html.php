<br><br>
<table width="70%" align=center>
<tr>
	<td align=center>
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
	$adminName = $this->getValueFromTable("users", "user_id", $AdminRef, "name")." ".$this->getValueFromTable("users", "user_id", $AdminRef, "surname")." &lt".$this->getValueFromTable("users", "user_id", $AdminRef, "email")."&gt";
	?>
		Your Application will be sent back to the HEQC-online administrator for your institution:<br>
		<b><?php echo $adminName?> </b>.<br>
	      	Please click the "Next" button to proceed.
		<br><br>
<?php
} else {
	echo "There was a problem getting the administrator for your institution. Please click Previous to return to your application.";
	echo "Please contact HEQC-Online support if the problem persists.";
}
?>
	</td>
</tr>
</table>
