<br>
<table width="95%" cellpadding=2 cellspacing=2 border=0 align="center">
<tr><td>
		Edit a programme in the list below by clicking on the edit link.<br><br>
</td>
</tr>
<tr><td>

	<table width="100%" border=0 align="center" cellpadding="2" cellspacing="2">
	<tr>
	<td align=center class="oncolourb">Edit</td>
	<td align=center class="oncolourb" width="15%">Programme Acronym</td>
	<td align=center class="oncolourb">Programme Name</td>
	</tr>

<?
		$SQL = "SELECT * FROM lkp_directorate WHERE 1";
		$rs = mysqli_query($SQL);
		if (mysqli_num_rows($rs) > 0){
			while ($row = mysqli_fetch_array($rs)){
				echo "<tr class='onblue'>";
				echo "<td align='center'><a href='javascript:setDirectorate(\"".$row[0]."\")'><img src='images/ico_change.gif' border=no></a></td>";
				echo "<td>".$row["directorate_acronym"]."</td>";
				echo "<td>".$row["directorate_description"]."</td>";
				echo "</tr>";
			}

		}
?>
	</table>


</td></tr>
</table>
<br>
