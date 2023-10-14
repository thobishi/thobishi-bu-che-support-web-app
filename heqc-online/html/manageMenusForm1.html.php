<br>
<br>
<table border='0'>
<tr><td>&nbsp;</td><td>
	<table border='0'>
	<tr>
		<td>Name:</td>
		<td><?php echo $this->showField("HEI_name")?></td>
	</tr>
	<tr>
		<td>Type:</td>
		<td><?php echo $this->showField("priv_publ")?></td>
	</tr>
	</table>
</td></tr></table>
<script>
if (document.defaultFrm.FLD_priv_publ.selectedIndex != 0){
	document.defaultFrm.FLD_priv_publ.disabled = true;
}

function doCheckForm(){
	if (document.all.MOVETO.value == "next"){
		if (document.defaultFrm.FLD_priv_publ.selectedIndex == 0){
			alert('Plese lelect institution type.');
			return false;
		}
		
	}else{
		return true;
	}
}

</script>
