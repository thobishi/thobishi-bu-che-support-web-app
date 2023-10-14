<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<?php $this->showInstitutionTableTop ()?>
<br>
<table width="100%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
<td>
<b>To continue the process you need to ascertain with the DoE the status of the application for registration of this private provider. Make sure that the information in the e-mail below is correct and that you have included comments if necessary.</b>
</td>
</tr>
<tr>
<td>
<?php 
$this->showEmailAsHTML("checkForm13", "PrivateProvRegPendingDOE");
?>
</td>
</tr>
<tr>
<td>
To override this message check this box:
	<?php $this->showField("override_status_doe_registration");?>
</td>
</tr>
<tr>
<td>
		<div id="override_div" style="display:none">
		<table><tr>
			<td>Please provide a reason for overriding this message:<br>
			<?php $this->showField("override_status_doe_registration_comments");?>
			</td>
		</tr></table>
		</div>
</td>
</tr>
</table>
</td></tr></table>
<script>
try {
	if ((document.defaultFrm.FLD_override_status_doe_registration.checked) && (document.all.override_div.style.display = "none")) {
		showHide (document.all.override_div);
	}
}catch(e){}
</script>
