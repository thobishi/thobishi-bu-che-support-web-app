<br>
<br>
<input type='hidden' name='groupID' value="<?php echo $_POST["groupID"]?>">
<table border='0'>
<tr>
	<td>&nbsp;</td>
	<td>
		<table border='0'>
		<tr class="doveblox">
			<td valign="top" class="doveblox"><strong>Group name:</strong></td>
<?php
			$SQL = "SELECT * FROM sec_Groups WHERE sec_group_id=?";
			$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
            if ($conn->connect_errno) {
                $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
                printf("Error: %s\n".$conn->error);
                exit();
            }

			$sm = $conn->prepare($SQL);
			$sm->bind_param("s", $_POST["groupID"]);
			$sm->execute();
			$rs = $sm->get_result();

			//$rs = mysqli_query($conn, $SQL);
			if (mysqli_num_rows($rs) > 0){
				while ($row = mysqli_fetch_array($rs)){
					echo "<td><input type='text' value='".$row[1]."' name='groupName'</td>";
				}
			}
?>
		</tr>
		<tr class="doveblox">
			<td valign="top" class="doveblox" ><strong>Group members:</strong> </td>
			<td class="doveblox">
				<table border="0">
				<tr>
					<td>
						<strong>Selected</strong><br>
						<select name="members[]" multiple size="10">
<?php
							$SQL = "SELECT user_id,name,surname, email FROM sec_UserGroups,users WHERE sec_user_ref=user_id and sec_group_ref=? ORDER BY name";
							$tmpArray = array();

							$sm = $conn->prepare($SQL);
							$sm->bind_param("s", $_POST["groupID"]);
							$sm->execute();
							$rs = $sm->get_result();

							//$rs = mysqli_query($conn, $SQL);
							if (mysqli_num_rows($rs) > 0){
								while ($row = mysqli_fetch_array($rs)){
								array_push($tmpArray,$row["user_id"])
?>
								<option value="<?php echo $row["user_id"]?>">
									<?php echo $row["name"]?> <?php echo $row["surname"]?> <?php echo " (" . $row["email"]. ")" ?>
								</option>
								
<?php
								}
							}
							else{						
?>
								<option value="">No results	</option>
<?php
							}
?>
						</select>
					</td>
					<td>
						<input onclick="javascript:removeMembers(document.defaultFrm.elements['Allmembers'],document.defaultFrm.elements['members[]']);" type="Button" value=">>"><br>
						<input onclick="javascript:addMembers(document.defaultFrm.elements['Allmembers'],document.defaultFrm.elements['members[]']);" type="Button" value="<<">
					</td>
					<td>
						<strong>Available<br></strong>
<?php
						$SQL = "SELECT user_id,name,surname, email FROM users WHERE user_id not in('".implode(',',$tmpArray)."') ORDER BY name";
						$rs = mysqli_query($conn, $SQL);
						if (mysqli_num_rows($rs) > 0){
?>
							<select name="Allmembers" multiple size="10">
<?php
							while ($row = mysqli_fetch_array($rs)){
?>
								<option value="<?php echo $row["user_id"]?>"><?php echo $row["name"]?> <?php echo $row["surname"]?> <?php echo " (" . $row["email"]. ")" ?></option>
<?php
							}
?>
							</select>
<?php
						}
						else{	
?>
					<select name="Allmembers" multiple size="10">
						<option value="">No results	</option>
					</select>
<?php
						}
?>
					</td>
				</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td valign="top" class="doveblox"><strong>Group processes:</strong> </td>
			<td class="doveblox">
				<table border="0">
				<tr>
					<td>
						<strong>Selected</strong><br>
						<select name="processes[]" multiple size="10">
<?php
						$SQL = "SELECT processes_id,processes_desc FROM processes,lnk_SecGroup_process WHERE process_ref=processes_id and secGroup_ref=? ORDER BY processes_desc";

						$sm = $conn->prepare($SQL);
						$sm->bind_param("s", $_POST["groupID"]);
						$sm->execute();
						$rs = $sm->get_result();

						//$rs = mysqli_query($conn, $SQL);
						$tmpArray = array();
						if (mysqli_num_rows($rs) > 0){
							while ($row = mysqli_fetch_array($rs)){
								array_push($tmpArray,$row["processes_id"])
?>
								<option value="<?php echo $row["processes_id"]?>"><?php echo $row["processes_desc"]?></option>
<?php
							}
						}
?>
						</select>
					</td>
					<td>
						<input onclick="javascript:removeMembers(document.defaultFrm.elements['Allprocesses'],document.defaultFrm.elements['processes[]']);" type="Button" value=">>"><br>
						<input onclick="javascript:addMembers(document.defaultFrm.elements['Allprocesses'],document.defaultFrm.elements['processes[]']);" type="Button" value="<<">
					</td>
					<td>
						<strong>Available<br></strong>
<?php
						$SQL = "SELECT processes_id,processes_desc FROM processes WHERE processes_id not in('".implode(',',$tmpArray)."') and menu_is_item='Yes' ORDER BY processes_desc";
						$rs = mysqli_query($conn, $SQL);
						if (mysqli_num_rows($rs) > 0){
?>
							<select name="Allprocesses" multiple size="10">
<?php
							while ($row = mysqli_fetch_array($rs)){
?>
								<option value="<?php echo $row["processes_id"]?>"><?php echo $row["processes_desc"]?></option>
<?php
							}
?>
							</select>
<?php
						}
?>
					</td>
				</tr>
				</table>
			</td>
		</tr>
		</table>
	</td>
</tr>
</table>

<script>
function selectAll() {

	sLength = document.defaultFrm.elements['members[]'].length;
	for (i=0; i<sLength; i++) {
		document.defaultFrm.elements['members[]'].options[i].selected = true;
	}
	sLength = document.defaultFrm.elements['processes[]'].length;
	for (i=0; i<sLength; i++) {
		document.defaultFrm.elements['processes[]'].options[i].selected = true;
	}

	return true;
}

function addMembers(obj,obj2) {
	sLen = obj.length;
	for ( i=0; i<sLen; i++){
		if (obj.options[i].selected == true ) {
			obj2Len = obj2.length;
			obj2.options[obj2Len]= new Option(obj.options[i].text, obj.options[i].value);
		}
	}
	for ( i=(sLen-1); i>=0; i--) {
		if (obj.options[i].selected == true ) {
			obj.options[i] = null;
		}
	}
}
function removeMembers(obj,obj2) {
	sLen = obj2.length;
	for ( i=0; i<sLen ; i++){
		if (obj2.options[i].selected == true ) {
			objLen = obj.length;
			obj.options[objLen]= new Option(obj2.options[i].text, obj2.options[i].value);
		}
	}
	for ( i = (sLen -1); i>=0; i--){
		if (obj2.options[i].selected == true ) {
			obj2.options[i] = null;
		}
	}
}


</script>