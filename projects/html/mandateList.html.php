<br>
<table width="95%" cellpadding=2 cellspacing=2 border=0 align="center">
<tr><td>
		Edit a Core Mandate in the list below by clicking on the edit link.<br><br>
</td>
</tr>
<tr><td>

	<table width="100%" border=0 align="center" cellpadding="2" cellspacing="2">
	<tr>
	<td align=center class="oncolourb">Edit</td>
	<td align=center class="oncolourb">Mandate (short title)</td>
	<td align=center class="oncolourb">Mandate (full title)</td>
	</tr>

<?
		$SQL = "SELECT * FROM lkp_che_mandate WHERE 1";
		$rs = mysqli_query($SQL);
		if (mysqli_num_rows($rs) > 0){
			while ($row = mysqli_fetch_array($rs)){
				echo "<tr class='onblue'>";
				echo "<td align='center'><a href='javascript:setMandate(\"".$row[0]."\")'><img src='images/ico_change.gif' border=no></a></td>";
				echo "<td>".$row[1]."</td>";
				echo "<td>".$row[2]."</td>";
				echo "</tr>";
			}

		}
?>
	</table>


</td></tr>
</table>
<br>
