<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr><td>

<?php 
	echo "This page allows you to manage the AC members on the system.<br>You can add new AC meeting members and manage AC meeting member details from this screen.<br><br>";
?>
</td></tr>

<tr><td>
	Please click on <img src='images/ico_print.gif' border=0> to edit/view the AC members' details:<br><br>
<table border=0 cellspacing=2 cellpadding=2 width="70%">
	<tr class="oncolourb">
		<td>Edit/View</td>
		<td>Committee Member</td>
		<td>Restrictions on system</td>
		<td>Status on system</td>
	</tr>
<?php 
$SQL = "SELECT * FROM AC_Members ORDER BY ac_mem_surname,ac_mem_name";
$rs = mysqli_query($this->getDatabaseConnection(), $SQL);
if (mysqli_num_rows($rs) > 0){
	while ($row = mysqli_fetch_array($rs)){
		$ACtitle = $this->getValueFromTable("lkp_title", "lkp_title_id", $row['ac_mem_title_ref'], "lkp_title_desc");

		$restrictions_arr = $this->getRestrictionsList($row["ac_mem_id"]);
		$restrictionsStr = implode(", <br>", $restrictions_arr);


		echo "<tr class='onblue'>";
		echo "<td align='center' width='7%' valign='top'><a href='javascript:setUser(\"".$row["ac_mem_id"]."\");moveto(\"next\");'><img src='images/ico_print.gif' border=0></a></td>";
		echo "<td valign='top'>".$ACtitle." ".$row["ac_mem_name"]." ".$row["ac_mem_surname"]."</a></td>";
		echo "<td valign='top'>".$restrictionsStr."</td>";
		echo "<td align='center' width='20%' valign='top'>".$this->getValueFromTable("lkp_active", "lkp_active_id", $row['ac_mem_active'], "lkp_active_desc")."</td>";
		echo "</tr>";
	}

}
?>
</table>
</td></tr>
</table>
<br>

<script>
function setUser(val){
	document.defaultFrm.CHANGE_TO_RECORD.value='AC_Members|'+val;
}
</script>