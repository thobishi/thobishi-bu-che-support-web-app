<?

	$year = readPOST('detail_budget_year');
	$ind_id = readPOST('detail_lkp_indicator_ref');

	if ($year == '' or $ind_id == 0) {
		$ind_id = $this->dbTableInfoArray["perf_ind_detail"]->dbTableCurrentID;
		$year = $this->getValueFromTable("perf_ind_detail","perf_ind_detail_id", $ind_id, "detail_budget_year");
		$ind_id = $this->getValueFromTable("perf_ind_detail","perf_ind_detail_id", $ind_id, "detail_lkp_indicator_ref");
	}
	
	$this->formFields['detail_budget_year']->fieldValue = $year;
	$this->formFields['detail_lkp_indicator_ref']->fieldValue = $ind_id;
	$this->showField('detail_budget_year');
	$this->showField('detail_lkp_indicator_ref');
	
	$whr_arr = array();
	if ($year > '') array_push($whr_arr,"detail_budget_year = '".$year."'");
	if ($ind_id > '') array_push($whr_arr,'detail_lkp_indicator_ref = '.$ind_id);
	$where = (count($whr_arr)) ? implode(' AND ', $whr_arr) : 1;

	$ind_desc = $this->getValueFromTable("lkp_indicator", "lkp_indicator_id", $ind_id, "indicator_desc");?>
<br>
<table width='95%' align="center" border='0'>
<tr>
<td>
	<span class="speciale">Maintain Indicator List for <?php echo echo $ind_desc; ?></span>
	<hr>
	Please click on Add new item in the actions menu to add an item to the list or Edit next to the required item to edit that item:<br><br>

<?

	$SQL = "SELECT * FROM perf_ind_detail WHERE $where ORDER BY perf_ind_detail_title";
	$rs = mysqli_query($SQL);
	if (mysqli_num_rows($rs) > 0){
		echo '<table width="100%" cellpadding=2 cellspacing=2 border=0>';
		echo "<tr class='oncolourb'>";
		echo '<td>Edit</td>';
		echo '<td>Year</td>';
		echo '<td>Name or Title</td>';
		echo '<td>Del</td>';
		echo "</tr>";
		while ($row = mysqli_fetch_array($rs)){


			echo "<tr class='onblue'>";
			echo "<td width='5%' align='center'><a href='".$this->scriptGetForm ('perf_ind_detail', $row["perf_ind_detail_id"], 'next').";'><img src='images/ico_change.gif' border=0></a></td>";
			echo "<td width='15%'>".$row["detail_budget_year"]."</td>";
			echo "<td>".$row["perf_ind_detail_title"]."</td>";
			echo '<td><a href="javascript:deleteRecord('. $row["perf_ind_detail_id"] .',\''. $row["perf_ind_detail_title"] .'\')">[delete]</a></td>';
			echo "</tr>";
		}
		echo '</table>';
	}

?>

</td>
</tr>
</table>
<br><br>

<SCRIPT>
//function deleteRecord(table,pk,val,recDesc){
//	if (confirm("Are you sure that you would like to delete "+ recDesc)) {
//		document.defaultFrm.DELETE_RECORD.value = table + '|' + pk + '|' + val;
//		moveto('stay');
//	}
//}
function deleteRecord(val,recDesc){
	if (confirm("Are you sure that you would like to delete "+ recDesc)) {
		document.defaultFrm.DELETE_RECORD.value = 'perf_ind_detail|perf_ind_detail_id|' + val;
		moveto('stay');
	}
}
</SCRIPT>
