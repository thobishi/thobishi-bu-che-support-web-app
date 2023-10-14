<br>
<br>
<div style = "margin-left: 50px; align:center;">
<table width="80%" border=0 align="left" cellpadding="2" cellspacing="2">
	<tr>
		<td>Please select a group to manage by clicking on the edit link:</td>			
	</tr>
	<tr>	
		<td >
		<table class="saphireframe" width="100%" border=0  cellpadding="2" cellspacing="0">
			<tr class="doveblox">
				<td class="doveblox">Edit</td>
				<td class="doveblox" >Group Name</td>
				<td class="doveblox">Group type</td>
				<td class="doveblox">Group function</td>					
			</tr>

<?php 
$html= '';
$SQL = "SELECT * FROM sec_Groups WHERE 1 ORDER BY sec_group_desc";
$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();
}
$rs = mysqli_query($conn, $SQL);
if (mysqli_num_rows($rs) > 0){
	while ($row = mysqli_fetch_array($rs)){
		$editLink = "<a href='javascript:setGroup(\"".$row['sec_group_id']."\")'><img src='images/ico_change.gif'></a>";
		
		$html .= '<tr>
					<td class="saphireframe">'. $editLink . '</td>
					<td class="saphireframe"> ' .$row['sec_group_desc']. ' </td>
					<td class="saphireframe"> ' .$row['sec_group_type']. ' </td>
					<td class="saphireframe"> ' .$row['sec_group_function']. ' </td>
				</tr>';
		
	
	}

}
echo $html;
?>			
		</table>
		</td>
	</tr>	
	
</table>
</div>
<input type='hidden' name='groupID'>
<script>
function setGroup(val){
	document.defaultFrm.groupID.value = val;
	document.defaultFrm.submit();
}
</script>
