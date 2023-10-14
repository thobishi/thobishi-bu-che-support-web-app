<br>
<div style = "margin-left: 50px; align:center;">
<table border='0' width="95%" cellpadding="2" cellspacing="2" align="center">
	<tr>
		<td colspan="2">
			<span class="loud">Manage Security Groups</span>
			<hr>
		</td>
	</tr>
	<tr>
		<td>
			Please select a CHE group to manage:
		</td>
	</tr>
	<tr>
		<td>
		<table class="saphireframe" width="100%" border=0  cellpadding="2" cellspacing="0">
			<tr class="doveblox">
				<td class="doveblox">Edit</td>
				<td class="doveblox" >Group Name</td>
				<td class="doveblox">Group type</td>
				<td class="doveblox">Group function</td>					
			</tr>		

<?php

			//2010-10-06 Robin: Omit the following groups because they are system development groups and not CHE groups and have been set to 'PRIVATE':
			//	1. 1: Administrator
			//	2. 5: Demo
			
			// Group type CHE may be edited and only CHE users must display.
			$SQL = "SELECT * FROM sec_Groups WHERE sec_group_type <> 'PRIVATE' ORDER BY sec_group_desc";
			$rs = mysqli_query($this->getDatabaseConnection(), $SQL);
			$html= '';
			if (mysqli_num_rows($rs) > 0){
				while ($row = mysqli_fetch_array($rs)){
					$getform = $this->scriptGetForm ("sec_Groups", $row['sec_group_id'], "next");
					$link1 = "<a href='" . $getform . "'><img src='images/ico_change.gif'></a>";
					$html .= '<tr>
								<td class="saphireframe">'. $link1 . '</td>
								<td class="saphireframe"> ' .$row['sec_group_desc']. ' </td>
								<td class="saphireframe"> ' .$row['sec_group_type']. ' </td>
								<td class="saphireframe"> ' .$row['sec_group_function']. ' </td>
							</tr>';

				}
			}
			echo $html;
/*			
?>
	<br>
	Please select a group to view:
	<br><br>
			<?php
			$SQL = "SELECT * FROM sec_Groups WHERE sec_group_type = 'VIEW'";
			$rs = mysqli_query($SQL);
			if (mysqli_num_rows($rs) > 0){
				while ($row = mysqli_fetch_array($rs)){
					$link1 = $this->scriptGetForm ('sec_Groups', $row[0], '_manageGroupsView');
?>
					<a href='<?php echo $link1; ?>'><?php echo $row[1]; ?></a><br>
<?php
				}
			}
			*/?>
		</table>	
		</td>
	</tr>
</table>
</div>
<br>


<input type='hidden' name='groupID'>

<script>
function setGroup(val){
	document.defaultFrm.groupID.value = val;
	document.defaultFrm.submit();
}
</script>


