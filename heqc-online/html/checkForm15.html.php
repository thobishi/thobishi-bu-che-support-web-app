<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<?php $this->showInstitutionTableTop ()?>
<br>
<table width="100%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
<td>
The above programme belongs to the field of teacher education and it needs to be checked by the DoE in relation to the Norms and Standards for Educators. Make sure that the information is correct and that you have added comments if necessary.
</td>
</tr>
<tr>
<td>
<?php 
$this->showEmailAsHTML("checkForm15", "PrivateProvTeacherEduProg");
?>
</td>
</tr>
<tr>
<td>
To override this message check this box:
	<?php $this->showField("override_compliance_prog_standards_edu");?>
</td>
</tr>
<tr>
<td>
		<div id="override_div" style="display:none">
		<table><tr>
			<td>Please provide a reason for overriding this message:<br>
			<?php $this->showField("override_compliance_prog_standards_edu_comments");?>
			</td>
		</tr></table>
		</div>
</td>
</tr>
</table>
</td></tr></table>
<script>
try {
	if ((document.defaultFrm.FLD_override_compliance_prog_standards_edu.checked) && (document.all.override_div.style.display = "none")) {
		showHide (document.all.override_div);
	}
}catch(e){}
</script>
