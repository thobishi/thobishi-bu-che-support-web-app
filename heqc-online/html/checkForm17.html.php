<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr><td>
<?php $this->showInstitutionTableTop ()?>
	<br>
	<table width="100%" border=0 align="center" cellpadding="2" cellspacing="2">
		<tr>
			<td>
			To continue the process you need to send the following e-mail to SAQA to ascertain the status of the institution’s application for registering  the qualification on the NQF. Make sure that the information in the e-mail is correct and that you have included comments if necessary.
			</td>
		</tr>
		<tr>
			<td>
			<?php 
			$this->showEmailAsHTML("checkForm17", "PublicProvProgPendingSAQA");
			?>
			</td>
		</tr>

	</table>
</td></tr>

<tr>
	<td>
	To override this message check this box:
		<?php $this->showField("override_saqa_nqf_registered");?>
	</td>
</tr>

<tr>
	<td>
		<div id="override_div" style="display:none">
		<table><tr>
			<td>Please provide a reason for overriding this message:<br>
			<?php $this->showField("override_saqa_nqf_registered_comments");?>
			</td>
		</tr></table>
		</div>
	</td>
</tr>
</table>

<script>
try {
	if ((document.defaultFrm.FLD_override_compliance_prog_standards_edu.checked) && (document.all.override_div.style.display = "none")) {
		showHide (document.all.override_div);
	}
}catch(e){}
</script>
