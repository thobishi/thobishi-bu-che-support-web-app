<br>
<table width="95%" cellpadding=2 cellspacing=2 border=0 align="center">

<tr><td>
		Financial data will be extracted on a monthly basis for the projects listed below.<br><br>
</td>
</tr>
<tr><td>

	<table width="100%" border=0 align="center" cellpadding="2" cellspacing="2">
	<tr>
	<td align=center class="oncolourb">Edit</td>
	<td align=center class="oncolourb">Budget Year</td>
	<td align=center class="oncolourb">Project Code</td>
	<td align=center class="oncolourb">Project Title (short)</td>
	</tr>

<?
		$SQL = "SELECT * FROM project_required_list  WHERE 1 ORDER BY budget_year, proj_code";
		$rs = mysqli_query($SQL);
		if (mysqli_num_rows($rs) > 0){
			while ($row = mysqli_fetch_array($rs)){
				echo "<tr class='onblue'>";
				echo "<td align='center' width='10%'><a href='javascript:setRequiredProject(\"".$row[0]."\")'><img src='images/ico_change.gif' border=no></a></td>";
				echo "<td width='10%'>".$row["budget_year"]."</td>";
				echo "<td width='10%'>".$row["proj_code"]."</td>";
				echo "<td>".$row["proj_description"]."</td>";
				echo "</tr>";
			}

		}
?>
	</table>


</td></tr>
</table>
<br>
