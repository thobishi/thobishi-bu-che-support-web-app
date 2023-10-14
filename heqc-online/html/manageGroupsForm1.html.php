<?php 
	$groupID = $this->dbTableInfoArray["sec_Groups"]->dbTableCurrentID;

	//$groupID = readPost('groupID');
	$groupName = $this->getValueFromTable("sec_Groups", "sec_group_id", $groupID, "sec_group_desc");
?>
<br>
<input type='hidden' name='groupID' value="<?php echo $groupID?>">
	<table border='0' cellpadding="2" cellspacing="2" align="center" width="95%">
	<tr>
		<td colspan="2">
			<span class="loud">Manage Security Groups > <?php echo $groupName?></span>
			<hr>
		</td>
	</tr>
	<tr class="doveblox">
	<td valign="top" width="20%" class="doveblox">Group name:</td>
<?php 
$SQL = "SELECT * FROM sec_Groups WHERE sec_group_id=".$groupID;
$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
if ($conn->connect_errno) {
    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
    printf("Error: %s\n".$conn->error);
    exit();
}

$rs = mysqli_query($conn, $SQL);
if (mysqli_num_rows($rs) > 0){
	$row = mysqli_fetch_array($rs);
	echo "<td class='oncolourcolumn'><input type='text' value='".$row[1]."' name='groupName'></td>";
}
?>
	</tr>
	<tr>
	<td valign="top" class="doveblox">Group members:</td>
	<td class='doveblox'>
		<table border="0"><tr><td><strong>Selected</strong><br>
<?php 
// Only display active users.  Inactive users cannot login and therefore belong to no group.
$SQL = <<<SQLUSER
	SELECT user_id,
		name,
		surname, email
	FROM sec_UserGroups s, users u
	WHERE s.sec_user_ref = u.user_id 
	AND s.sec_group_ref= '$groupID' 
	AND u.active = 1
	ORDER BY name
SQLUSER;
//2008-05-05 Rebecca - we have to give the tmpArray a value of '' else it will give an error when there is nothing in the tmpArray
$tmpArray = array('\'\'');

$rs = mysqli_query($conn, $SQL);
?>
	<select name="members[]" multiple size="10">
<?php 
	while ($row = mysqli_fetch_array($rs)){
	array_push($tmpArray,$row["user_id"])
?>
	<option value="<?php echo $row["user_id"]?>"><?php echo $row["name"]?> <?php echo $row["surname"]?> <?php echo " (" . $row["email"]. ")" ?></option>
<?php 
	}
?>
	</select>
	</td><td>
	<input onclick="javascript:removeMembers(document.defaultFrm.elements['Allmembers'],document.defaultFrm.elements['members[]']);" type="Button" value=">>"><br>
	<input onclick="javascript:addMembers(document.defaultFrm.elements['Allmembers'],document.defaultFrm.elements['members[]']);" type="Button" value="<<">
</td>
	<td><strong>Available<br></strong>
<?php 
//2008-05-05 Rebecca - removed the additional ''s from the string - was giving it a value of "NOT IN ('1, 2, 3...')" and failing
$SQL = <<<USERAVAIL
	SELECT user_id, 
		name,
		surname, email
	FROM users 
	WHERE active = 1 
	AND user_id NOT IN(".implode(',',$tmpArray).") 
	AND institution_ref = 2
	ORDER BY name
USERAVAIL;
$rs = mysqli_query($conn, $SQL);
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

		</td>
		</tr>
		</table>

	<tr>
	<td valign="top" class='doveblox'>Group processes:</td>
	<td class='doveblox'>
<?php 
			$SQL = <<<PROCSQL
				SELECT p.processes_id, p.processes_desc as mainmenu, c.processes_id, c.processes_desc as submenu, c.menu_perant FROM processes p, processes c,lnk_SecGroup_process l 
				WHERE 
				c.menu_perant = p.processes_id
				AND l.process_ref = c.processes_id 
				AND l.secGroup_ref =  '$groupID'
				AND c.menu_perant < 0
				ORDER BY p.processes_desc
PROCSQL;
			$rs = mysqli_query($conn, $SQL);
			if (mysqli_num_rows($rs) > 0){
				$prev = '';
				while ($row = mysqli_fetch_array($rs)){
					if ($row['mainmenu'] != $prev){
						echo "<b>".$row["mainmenu"]."</b><br>" ;
					} 
					echo "&nbsp;&nbsp;&nbsp;" . $row["submenu"] . "<br>";
					$prev = $row['mainmenu'];
				}
			} else {
				echo 'No processes have been linked for this group<br>';
			}
	?>
	</td>
	</tr>
	</table>

<br>
<script type="text/javascript">
function selectAll() {
	sLength = document.defaultFrm.elements['members[]'].length;
	for (i=0; i<sLength; i++) {
		document.defaultFrm.elements['members[]'].options[i].selected = true;
	}
	/*
	//commented out 05/05/08 Rebecca - no processes element defined
	sLength = document.defaultFrm.elements['processes[]'].length;
	for (i=0; i<sLength; i++) {
		document.defaultFrm.elements['processes[]'].options[i].selected = true;
	}
	*/
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