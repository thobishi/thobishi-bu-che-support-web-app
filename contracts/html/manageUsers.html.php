<br>
<table border='0' width="95%" cellpadding="2" cellspacing="2" align="center">
	<tr>
		<td colspan="2">
			<span class="loud">Manage Security Groups</span>
			<hr>
		</td>
	</tr>
	<tr>
		<td>
			Please select a group to manage:
		</td>
	</tr>
	<tr>
		<td>
<?php 
$SQL = "SELECT * FROM sec_Groups WHERE 1";
$rs = mysqli_query($SQL);
if (mysqli_num_rows($rs) > 0){
	while ($row = mysqli_fetch_array($rs)){
		echo "<a href='javascript:setGroup(\"".$row[0]."\")'>".$row[1]."</a><br>";

	}

}
?>
		</td>
	</tr>
</table>
<br>


<input type='hidden' name='groupID'>

<script>
function setGroup(val){
	document.defaultFrm.groupID.value = val;
	document.defaultFrm.submit();
}
</script>


