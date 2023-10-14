<a name="application_form_admin_page"></a>
<table width="100%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
		<br>
		<b>INSTITUTION ADMINISTRATION PAGE:</b>
		<br><br>
		As the administrator of the system within your institution you have four main functions:
		<ol>
		<li>To give users of your institution access to the system.</li>
		<li>To monitor the approval of the programme by the necessary internal structures. </li>
		<li>To submit the application for programme accreditation to the HEQC.</li>
		<li>To construct and update the institutional profile.</li>
		</ol>
		In order to start filling in the application for programme accreditation for this particular programme, you need to give access to this system to the relevant individuals and structures/offices who will provide information and/or approve the programme internally..
		<br><br>
		By default, the system sees you as responsible for completing the programme information, so in order to send the online application form to the programme coordinator (or to any other person) who will complete the rest of the form, select the person's email address from the list below and click <b>Next</b>.
		<br><br>
		<b>Nominate a colleague to fill in the rest of the Application form:</b>

<?php 
		$AdminRef = $this->currentUserID;
		$InstRef = $this->getValueFromTable("users", "user_id", $AdminRef, "institution_ref");
		$this->createInputFromDB("user_ref","SELECT", "users", "user_id", "email",1,"institution_ref = ".$InstRef." AND active=1", "email");
		$this->formFields["user_ref"]->fieldValue = $AdminRef;
		$this->showField("user_ref");
?>

	<br><br>
	</td>
	</tr>
	<tr class="oncolour">
	<td>If the user/s to whom you wish to send the application form to are not in the drop-down list, please add their details by clicking on the "Tools" menu, and clicking on <b>"Add colleague details"</b>..</td>
</tr>
</table>

