<br>
<br>
<table border='0' width="95%" cellpadding="2" cellspacing="2" align="center">
	<tr>
		<td colspan="3">
			<span class="loud">Manage Users</span>
			<hr>
		</td>
	</tr>
<tr>
	<td>
		Please select a user to manage, or select "Add a new user" from the <b>Actions menu.</b>:
		<br><br>
		<table border='0' width="95%" cellpadding="2" cellspacing="2" align="center">
		<tr>
			<td class="oncolourcolumnheader">Name</td>
			<td class="oncolourcolumnheader">Email</td>
			<td class="oncolourcolumnheader">Registration Date</td>
			<td class="oncolourcolumnheader">Access to system</td>
		</tr>
<?php
				$SQL = "SELECT * FROM users WHERE 1 ORDER BY surname,name";
				$rs = mysqli_query($SQL);
				if (mysqli_num_rows($rs) > 0){
					while ($row = mysqli_fetch_array($rs)){
					$user_id = $row["user_id"];
					$name = $row["surname"].", ".$row["name"];
					$email = $row["email"];
					$reg_date = $row["registration_date"];
					$status = $this->getValueFromTable("lkp_active", "lkp_active_id", $row["active"], "lkp_active_desc");
					$text = <<< TEXT
						<tr class="oncolourcolumn">
							<td>
								<a href='javascript:setUser("$user_id");moveto("next");'>$name</a>
							</td>
							<td>$email</td>
							<td>$reg_date</td>
							<td>$status</td>
						</tr>
TEXT;
						echo $text;

					}

				}
?>
		</table>
	</td>
</tr>
</table>

<br><br>

<script>
function setUser(val){
	document.defaultFrm.CHANGE_TO_RECORD.value='users|'+val;
}
</script>



