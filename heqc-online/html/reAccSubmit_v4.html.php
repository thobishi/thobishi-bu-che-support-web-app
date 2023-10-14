<?php

	$current_id = $this->dbTableInfoArray["Institutions_application_reaccreditation"]->dbTableCurrentID;

	// Do not display next if this is not the administrator.
	if (!$this->sec_userInGroup("Institution")) {
		$this->formActions["next"]->actionMayShow = false;
	}

?>
<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
	<tr>
		<td class="loud">Submission of re-accreditation application form to HEQC<br><hr></td>
	</tr>
	<tr>
		<td>
			<br><br>Only the HEQC-Online administrator for your institution may submit this application for re-accreditation.
			<br>
			<br>By submitting this application you are declaring that the information provided in this application and its supporting documents is accurate and verifiable.
			You declare that your head of institution has taken all reasonable steps to confirm the accuracy of statements.
			<br>
			<br><b>Once you click on "Submit Re-accreditation Application and Log out", your application will be sent to the HEQC Accreditation Directorate.</b>
			<br><br>Please use the following reference number in all future queries: <b><?=$this->getFieldValue("referenceNumber")?></b>
			You can view / print your application form by clicking on the "View / Print Application Form" in the actions menu.
			<br><br><br><br>
		</td>
	</tr>
</table>

<script>
	var printed = '<?=$this->getValueFromTable("Institutions_application_reaccreditation", "Institutions_application_reaccreditation_id", $current_id, "reacc_applic_printed");?>';
</script>
