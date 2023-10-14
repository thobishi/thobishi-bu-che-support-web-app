<a name="application_form_admin_page"></a>
<table width="100%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
		<br>
<?php 

if ($this->workFlow_settings["DBINF_users___user_id"] != "NEW")
{
	 echo "Colleague detail list successfully updated";
}

else
	{
		echo '<table width="75%" border=0 align="center" cellpadding="2" cellspacing="2"><tr>';
		echo "<td>";
		echo "The email address (".$_POST["FLD_email"].") for the new user details you entered already exists in the system.";
		echo "<br><br>The user details registered under this email address are:";
		echo "<br><br><b>Full name:</b> ".$this->getValueFromTable ("users", "email", $_POST["FLD_email"], "name")." ".$this->getValueFromTable ("users", "email", $_POST["FLD_email"], "surname");
		echo "<br><b>Contact number:</b> ".$this->getValueFromTable ("users", "email", $_POST["FLD_email"], "contact_nr");

		$institution_ref = $this->getValueFromTable ("users", "email", $_POST["FLD_email"], "institution_ref");
		echo "<br><b>Institution:</b> ".$this->getValueFromTable ("HEInstitution", "HEI_id", $institution_ref, "HEI_name");

		$active = $this->getValueFromTable ("users", "email", $_POST["FLD_email"], "active");
		$active = $this->getValueFromTable ("lkp_active", "lkp_active_id", $active, "lkp_active_desc");
		echo "<br><b>Access to system:</b> ".$active;

		echo "<br><br>Consequently, they have <u>not</u> been added to your list of colleagues.";
		echo "<br><br>Please ensure that the address you enter is unique to this colleague, as well as valid, as it will be used in any system communication.";
		echo "<br><br>If the details listed above are the details of the user that you wish to add, please contact Octoplus on ".$this->getValueFromTable ("settings", "s_key", "support_technical_tel", "s_value")."  or email ".$this->getValueFromTable ("settings", "s_key", "support_technical_email", "s_value")." for assistance.";

		echo "<td>";
		echo "</tr></table>";
}

//added lkp_active_user table to workflow

?>
	</td>
</tr>
</table>

