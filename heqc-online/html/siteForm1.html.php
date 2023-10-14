<table width=95% border=0 align="center" cellpadding="2" cellspacing="2">
<tr><td>
<br>
<b>INSTITUTION INFORMATION:</b>
<br><br>
<table width=75% border=0 align="center" cellpadding="2" cellspacing="2">
<?php 
	$ins_id = $this->dbTableInfoArray["HEInstitution"]->dbTableCurrentID;
	$SQL = "SELECT * FROM Sites WHERE HEI_ref=? AND active=1 ORDER BY Site_name";

	$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
	if ($conn->connect_errno) {
	    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
	    printf("Error: %s\n".$conn->error);
	    exit();
	}

	$sm = $conn->prepare($SQL);
	$sm->bind_param("s", $ins_id);
	$sm->execute();
	$RS = $sm->get_result();


	//$RS = mysqli_query($SQL);
?>
<tr>
	<td valign="top"><b>Additional Sites of Delivery:</b></td>
</tr><tr>
<?php 
	while ($row = mysqli_fetch_array($rs)) {
?>
		<td class="oncolour">&nbsp;<a href="javascript:document.defaultFrm.CHANGE_TO_RECORD.value='Sites|<?php echo $row["sites_id"]?>';moveto(166);"><?php echo $row["Site_name"]?>, <?php echo $row["Street_adr1"]?></a></td></tr>
<?php 
	}
?>
</table>
<table width="95%" border=0 cellpadding="2" cellspacing="2">
<tr>
	<td align="center"><input type="button" class="btn" name="add" value="Add Additional Sites for Delivery" onClick="javascript:document.defaultFrm.CHANGE_TO_RECORD.value='Sites|NEW';moveto(166)"></td>
</tr></table>


<br><br>
</td></tr></table>
