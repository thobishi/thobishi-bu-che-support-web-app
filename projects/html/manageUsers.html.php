<br>
<table width='95%' align='center' border='0'>
	<tr><td>
		Please select a group to manage:<br><br>
<?
		$SQL = "SELECT * FROM sec_Groups WHERE 1";
		$rs = mysqli_query($SQL);
		if (mysqli_num_rows($rs) > 0){
			while ($row = mysqli_fetch_array($rs)){
				echo "<a href='javascript:setGroup(\"".$row[0]."\")'>".$row[1]."</a><br>";

			}

		}
?>
		<input type='hidden' name='groupID'>

<script>
		function setGroup(val){
			document.defaultFrm.CHANGE_TO_RECORD.value = 'sec_Groups|'+val;
			document.defaultFrm.submit();
		}
</script>

	</td></tr>
</table>

<br>
