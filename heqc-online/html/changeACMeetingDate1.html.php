<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<table width="100%" border=0 align="center" cellpadding="2" cellspacing="2"><tr>
<td>
<b>If you want to change the current AC Meeting date, do so below:</b>
<br><br>
<table cellpadding="2" cellspacing="2" width="80%" align="center" border="0">
<tr>
<td>
<fieldset>
	<table cellpadding="2" cellspacing="2" border="0"><tr>
		<td><b>Please select a date for the new meeting:</b> </td>
	</tr><tr>
		<td><?php $this->showField('ac_start_date');?></td>
	</tr><tr>
		<td>&nbsp;</td>
	</tr><tr>
		<td valign="top"><b>Enter the venue for the meeting:</b></td>
	</tr><tr>
		<td><?php $this->showField('ac_meeting_venue');?></td>
	</tr></table>
</fieldset>
</td></tr></table>
</td>
</tr></table>
</td></tr></table>

<script>
function checkDate(){
	if (document.defaultFrm.MOVETO.value == "next"){
		if (document.defaultFrm.FLD_ac_start_date.value <= ""){
			alert("Please select a date for the AC Meeting");
			return false;
		}else{
			return true;	
		}
	}	
}
</script>
