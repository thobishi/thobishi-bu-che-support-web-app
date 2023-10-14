<br>
<table width='95%' align="center" border='0'>
<tr>
<td>
	Please select a user to manage:<br><br>

<?

	//$SQL = "SELECT * FROM users LEFT JOIN sec_UserGroups ON users.user_id = sec_UserGroups.sec_user_ref WHERE 1 ORDER BY sec_group_ref, surname,name";
	$SQL = "SELECT * FROM users WHERE 1 ORDER BY institution_ref";
	$rs = mysqli_query($SQL);
	if (mysqli_num_rows($rs) > 0){
		echo '<table width="100%" cellpadding=2 cellspacing=2 border=0>';
		echo "<tr class='oncolourb'>";
		echo '<td>Edit</td>';
		echo '<td>Programme</td>';
		echo '<td>Name</td>';
		echo "</tr>";
		while ($row = mysqli_fetch_array($rs)){
			echo "<tr class='onblue'>";
			echo "<td width='5%' align='center'><a href='javascript:setUser(\"".$row["user_id"]."\");'><img src='images/ico_change.gif' border=0></a></td>";
			//echo "<td width='15%'>".$this->getValueFromTable("sec_Groups", "sec_group_id", $row["sec_group_ref"], "sec_group_desc")."</td>";
			echo "<td width='15%'>".$this->getValueFromTable("lkp_directorate", "lkp_directorate_id", $row["institution_ref"], "directorate_description")."</td>";
			echo "<td>".$row["surname"].", ".$row["name"]."</td>";
			echo "</tr>";
		}
		echo '</table>';
	}

?>

</td>
</tr>
</table>
<br><br>

