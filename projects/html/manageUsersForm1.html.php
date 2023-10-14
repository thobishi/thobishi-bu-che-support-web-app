<table width="95%" cellpadding=2 cellspacing=2 border=0 align="center">
<tr><td>
<br>
<script>
	function changeCMD (newCMD) {
		document.defaultFrm.cmd.value = newCMD;
	}
</script>
<?php
$current_id = $this->dbTableInfoArray[$this->dbTableCurrent]->dbTableCurrentID;
?>
<table border='0'>
<tr>
	<td>
		<table border='0'>
		<tr>
		<td valign="top"><strong>Group name:</strong></td>
		<td>
<?
		$this->showField("sec_group_desc");
?>
		</td>
	<tr>
		<td valign="top"><strong>Group members:</strong></td>
		<td>
		<table align="left" width="50%">
		<?
			$headArr = array();
			array_push($headArr, "User name");

			$fieldArr = array();
			array_push($fieldArr, "type__select|name__sec_user_ref|description_fld__name__surname|fld_key__user_id|lkp_table__users|lkp_condition__active=1|order_by__surname");

			$this->gridShowRowByRow("sec_UserGroups", "sec_UserGroups_id", "sec_group_ref__".$current_id, $fieldArr, $headArr, 4, 2, true, "add user");
			?>
		</table>
		</td>
	</tr>
	<tr>
	<td valign="top"><strong>Menu items:</strong></td>
	<td>
		<br>
		<br>
<?
			// Get top level menu items
			$SQL = <<<sql
			SELECT parent.processes_id as main_pi, parent.processes_desc as main_pd
			FROM (processes AS parent, lnk_SecGroup_process)
			WHERE process_ref = parent.processes_id
			AND secGroup_ref = $current_id
			AND parent.menu_perant =0
			ORDER BY parent.menu_sequence_number
sql;

			$rs = mysqli_query($SQL) or die(mysqli_error());
			while ($row = mysqli_fetch_array($rs)){
				echo "<b>".$row["main_pd"]."</b><br>" ;				
				$pmenu = $row["main_pi"];
				
				$sql = <<<sqlc
				SELECT child.processes_id as sub_pi, child.processes_desc as sub_pd
				FROM processes as child, lnk_SecGroup_process
				WHERE lnk_SecGroup_process.process_ref = child.processes_id
				AND secGroup_ref = $current_id
				AND child.menu_perant = $pmenu
sqlc;

				$rsc = mysqli_query($sql) or die(mysqli_error());
				while ($rowc = mysqli_fetch_array($rsc)){
					echo "&nbsp;&nbsp;&nbsp;" . $rowc["sub_pd"] . "<br>";
				}

			}

	?>
		</td>
	</tr>
	</table>

</td>
</tr>
</table>
</td></tr>
</table>
<?
	if (isset($_POST["cmd"]) && ($_POST["cmd"] > "")) {
		$cmd = explode("|", $_POST["cmd"]);
		switch ($cmd[0]) {
			case "new":
				$this->gridInsertRow($cmd[1], $cmd[2], $cmd[3]);
				break;
			case "del":
				$this->gridDeleteRow($cmd[1], $cmd[2], $cmd[3]);
				break;
		}
		echo '<script>';
		echo 'document.defaultFrm.action = "#'.$cmd[1].'";';
		echo 'document.defaultFrm.MOVETO.value = "stay";';
		echo 'document.defaultFrm.submit();';
		echo '</script>';
	}
?>
<input type='hidden' name='cmd' value=''>
<br>
