<br><br>
<table width="70%" align=center>
<tr>
	<td align=center>
	<?php 
	$AdminRef = $this->getValueFromTable("Institutions_application", "application_id", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "user_ref");

	$adminName = $this->getValueFromTable("users", "user_id", $AdminRef, "surname").", ".$this->getValueFromTable("users", "user_id", $AdminRef, "name")." &lt".$this->getValueFromTable("users", "user_id", $AdminRef, "email")."&gt";

	?>
		Your Application will be sent back to the Administrator <br>
		<b><?php echo $adminName?> </b>.<br>
	      	Please click the "Next" button to proceed.
		<br><br>
	</td>
</tr>
</table>
