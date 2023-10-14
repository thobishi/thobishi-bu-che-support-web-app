<br>
<table width="95%" cellpadding=2 cellspacing=2 border=0 align="center">
<tr><td>
		Edit a Performance Indicator in the list below by clicking on the edit link.<br><br>
</td>
</tr>
<tr><td>

	<table width="100%" border=0 align="center" cellpadding="2" cellspacing="2">
	<tr>
	<td align=center class="oncolourb">Edit</td>
	<td align=center class="oncolourb">Required<br>order</td>
	<td align=center class="oncolourb">Performance Indicator type</td>
	<td align=center class="oncolourb">Performance Indicator description</td>
	<td align=center class="oncolourb">Status</td>	</tr>

<?
		$SQL = "SELECT * FROM lkp_indicator LEFT JOIN lkp_active ON lkp_active_id = indicator_active_ref WHERE 1";
		$rs = mysqli_query($SQL);
		if (mysqli_num_rows($rs) > 0){
			while ($row = mysqli_fetch_array($rs)){
				echo "<tr class='onblue'>";
				echo "<td align='center'><a href='javascript:setPerformance(\"".$row[0]."\")'><img src='images/ico_change.gif' border=no></a></td>";
				echo "<td>".$row["indicator_order"]."</td>";
				echo "<td>".$row["indicator_type"]."</td>";
				echo "<td>".$row["indicator_desc"]."</td>";
				echo "<td>".$row["lkp_active_desc"]."</td>";
				echo "</tr>";
			}

		}
?>
	</table>


</td></tr>
</table>
<br>
