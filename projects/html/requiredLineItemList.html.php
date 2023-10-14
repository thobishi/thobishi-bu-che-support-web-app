<br>
<table width="95%" cellpadding=2 cellspacing=2 border=0 align="center">
<tr><td>
		Financial data will be extracted on a monthly basis for the line items listed below.<br><br>
</td>
</tr>
<tr><td>

	<table width="100%" border=0 align="center" cellpadding="2" cellspacing="2">
	<tr>
	<td align=center class="oncolourb">Edit</td>
	<td align=center class="oncolourb">Budget year</td>
	<td align=center class="oncolourb">Line Item Code</td>
	<td align=center class="oncolourb">Line Item Description</td>
	<td align=center class="oncolourb">Status</td>
	</tr>

<?
		$SQL = "SELECT * FROM project_required_line_item  WHERE 1 ORDER BY budget_year, line_item_code";
		$rs = mysqli_query($SQL);
		if (mysqli_num_rows($rs) > 0){
			while ($row = mysqli_fetch_array($rs)){
				echo "<tr class='onblue'>";
				echo "<td width='5%' align='center'><a href='javascript:setRequiredLineItem(\"".$row[0]."\")'><img src='images/ico_change.gif' border=no></a></td>";
				echo "<td width='10%'>".$row["budget_year"]."</td>";
				echo "<td>".$row["line_item_code"]."</td>";
				echo "<td>".$row["line_item_description"]."</td>";
				echo "<td>".$this->getValueFromTable("lkp_line_item_status", "lkp_line_item_status_id", $row["line_item_status"], "lkp_line_item_status_desc")."</td>";
				echo "</tr>";
			}

		}
?>
	</table>


</td></tr>
</table>
<br>
