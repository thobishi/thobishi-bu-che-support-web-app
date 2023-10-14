<?php 
	$groupName = $this->getValueFromTable("sec_Groups", "sec_group_id", $_POST["groupID"], "sec_group_desc");
?>
<script type="text/javascript">
function selectAll() {

	sLength = document.defaultFrm.elements['members[]'].length;
	for (i=0; i<sLength; i++) {
		document.defaultFrm.elements['members[]'].options[i].selected = true;
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
<br>
<input type='hidden' name='groupID' value="<?php echo $_POST["groupID"]?>">
<table border="0" cellpadding="2" cellspacing="2" align="center" width="95%">
	<tr>
		<td colspan="2">
			<span class="loud">Manage Security Groups > <?php echo $groupName?></span>
			<hr>
		</td>
	</tr>
	<tr>
	<td valign="top" width="20%" class="oncolourcolumnheader">Group name:</td>
<?php 
$SQL = "SELECT * FROM sec_Groups WHERE sec_group_id=".$_POST["groupID"];
$rs = mysqli_query($SQL);
if (mysqli_num_rows($rs) > 0){
	while ($row = mysqli_fetch_array($rs)){
		echo "<td class='oncolourcolumn'><input type='text' value='".$row[1]."' name='groupName'</td>";

	}

}
?>
	</tr>
	<tr>
		<td valign="top" class="oncolourcolumnheader">Group members:</td>
		<td class="oncolourcolumn">
			<table border="0">
			<tr>
				<td>
					<strong>Selected</strong><br>
<?php 
					$SQL = "SELECT user_id,name,surname FROM sec_UserGroups,users WHERE sec_user_ref=user_id and sec_group_ref=".$_POST["groupID"]." ORDER BY surname";
					//2008-05-05 Rebecca - we have to give the tmpArray a value of '' else it will give an error when there is nothing in the tmpArray
					$tmpArray = array('\'\'');
					$rs = mysqli_query($SQL);
?>
					<select name="members[]" multiple size="10">
<?php 
						while ($row = mysqli_fetch_array($rs)){
							array_push($tmpArray,$row["user_id"])
?>
							<option value="<?php echo $row["user_id"]?>"><?php echo $row["name"]?> <?php echo $row["surname"]?></option>
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
					//2008-05-05 Rebecca - removed the additional ''s from the string - was giving it a value of "NOT IN ('1, 2, 3...')" and failing
					$SQL = "SELECT user_id,name,surname FROM users WHERE active = 1 AND user_id not in(".implode(',',$tmpArray).") ORDER BY name";
					$rs = mysqli_query($SQL);
?>
					<select name="Allmembers" multiple size="10">
<?php 
						while ($row = mysqli_fetch_array($rs)){
?>
							<option value="<?php echo $row["user_id"]?>"><?php echo $row["name"]?> <?php echo $row["surname"]?></option>
<?php 
						}
?>
					</select>

				</td>
			</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td valign="top" class='oncolourcolumnheader'>Group processes:</td>
		<td class='oncolourcolumn'>
<?php 
			$SQL = "SELECT processes_id,processes_desc,menu_perant FROM processes,lnk_SecGroup_process WHERE process_ref=processes_id and secGroup_ref=".$_POST["groupID"]." ORDER BY processes_desc";
			$rs = mysqli_query($SQL);
			if (mysqli_num_rows($rs) > 0){
				$children = array();
				$parents = array();
				while ($row = mysqli_fetch_array($rs)){
					if($row["menu_perant"] == 0){
						$parents[$row["processes_id"]] = $row["processes_desc"];
					}
					else{
						$children[$row["menu_perant"]][] = $row["processes_desc"];
					}
				}
				foreach($parents as $parentKey => $parent){
					
					foreach($children as $childKey => $child){
						if($parentKey == $childKey ){
							if($parent == "Edit" && $groupName == "Overview"){
								echo '<b>View</b><br>';
							}else{
								echo '<b>'. $parent .'</b><br>';
							}
							foreach($child as $subitem){
								echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$subitem .'<br>';
							}
						}
					}
				}
			}
	?>
		</td>
	</tr>
</table>
