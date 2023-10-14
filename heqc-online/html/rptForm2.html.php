<?php 
$inst_id = $this->dbTableInfoArray["institutional_profile"]->dbTableCurrentID;
// Don't display next if this is not the administrator.
if (!$this->sec_userInGroup("Institution")) {
	$this->formActions["next"]->actionMayShow = false;
}
?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td><br><br>
<table width="95%" border=0 cellpadding="2" cellspacing="2"><tr>
	<td>
	<span class="specialb">
	The following list indicates the fields you have not completed. Please complete these fields. Submission may <font color="red">only proceed</font> once validation of the entire institutional profile and programme application is successful.
	<br><br>
	At this point, if you are the <font color="red">institutional administrator and all validation is successful</font>, a <font color="red">Next</font> Button will appear for you to continue with the submission process.
	<br><br>
If the application form is completed to your satisfaction send it to the administrator so that it can be submitted to the HEQC. To do this use ‘Send application back to administrator’ feature in the right navigation bar.
<br><br>
If the information is not complete, send it back to the relevant person.  To do this use ‘Send application to a colleague’ feature in the right navigation bar.
	</span>
	</td>
</tr>
</table>
<br><br>
<b>Institutional Profile:</b>
<table width=75% border=0 align="center" cellpadding="2" cellspacing="2"><tr>
	<td>
	<?php	$instProfileFlag = $this->checkInstitutionalProfileContactInfo ($inst_id); ?>

		<table cellpadding="3">
			<tr>
	<?php 	if (! $instProfileFlag ) { ?>

			<td class="oncolour"><b>Your Institutional Profile's site information does not seem to be up-to-date.</b></td>
			</tr><tr>
			<td class="oncolour">Please make sure that <font color="red">all contact information as well as all addresses of your sites</font> are complete.</td>
			<tr>
			<td class="oncolour">You can update it by clicking on the <b>Tools</b> menu and selecting the <b>"Institutional Profile"</b> option.</td>
			</tr><tr>
			<td class="oncolour">After the update, you can return to this page to complete the application.</td>
			</tr>

<?php 
			$this->formActions["next"]->actionMayShow = false;
		}else {
?>
			<td><b>Your institutional Profile's site information seems to be up-to-date.</b></td>
<?php 
		}
?>
			<tr>
		</table>

	</td>
</tr></table>
<br><br>
	<i>Note that you can click on the <img src="images/question_mark.gif"> next to the incomplete field, to go to the specific field.</i>
<br><br>
<b>Application Form:</b>
<table width=75% border=0 align="center" cellpadding="2" cellspacing="2">

<?php 

$this->validateFields("accForm1");
$this->validateFields("accForm1b");
$this->validateFields("accForm2");
$this->validateFields("accForm3-1");
$this->validateFields("accForm6");
$this->validateFields("accForm8");
$this->validateFields("accForm8b");
$this->validateFields("accForm9");
$this->validateFields("accForm14");
$this->validateFields("accForm15");
$this->validateFields("accForm17");

$nqf = $this->getValueFromTable("Institutions_application", "application_id", $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, "NQF_ref");
if ($nqf > 3) {
	$this->validateFields("accForm19");
}


?>
</table><br><br>
<script>
	document.defaultFrm.VALIDATION.value = 136;
</script>
</td></tr></table>
<br><br>
