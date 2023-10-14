<br>
<br>
<table width="100%" border='0'>
<tr>
	<td>
		&nbsp;<br>
	</td>
	<td>

		<table border='0'>
		<tr>
			<td>Institution name:</td>
			<td><?php echo $this->showField("HEI_name")?></td>
		</tr>
		<tr>
			<td>Public or private institution:</td>
			<td><?php echo $this->showField("priv_publ")?></td>
		</tr>
		<tr>
			<td>Indicate the institution user role i.e. Whether the institution needs access to view information or to apply for accreditation:</td>
			<td><?php echo $this->showField("inst_user_role_ref")?></td>
		</tr>
		</table>
	</td>
</tr>
</table>
<script>
if (document.defaultFrm.FLD_priv_publ.selectedIndex != 0){
	document.defaultFrm.FLD_priv_publ.disabled = true;
}

function doCheckForm(){
	if (document.defaultFrm.MOVETO.value == "next"){
		if (document.defaultFrm.FLD_priv_publ.selectedIndex == 0){
			alert('Plese select institution type.');
			return false;
		}
	}else{
		return true;
	}
	return true;
}

</script>
