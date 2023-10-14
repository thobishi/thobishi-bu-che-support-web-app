<table width=95% border=0 align="center" cellpadding="2" cellspacing="2"><tr><td><br>
<br>
<table width=75% border=0 align="center" cellpadding="2" cellspacing="2">
<?php 
	$SQL = "SELECT * FROM settings ORDER BY s_key";
	$rs = mysqli_query($this->getDatabaseConnection(), $SQL);
	while ($row = mysqli_fetch_array($rs)) {
?>
		<tr>
			<td class="oncolour" title='<?php echo $row["s_description"]?>'>&nbsp;<a href="javascript:document.defaultFrm.CHANGE_TO_RECORD.value='settings|<?php echo $row["s_key"]?>';moveto(175);"><?php echo $row["s_key"]?></a></td>
			<td class="oncolour"><?php echo $row["s_value"]?></td>
		</tr>
<?php 
	}
?>
</table>

<br><br>
</td></tr></table>
