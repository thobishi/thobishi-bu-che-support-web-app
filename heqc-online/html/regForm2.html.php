<?php
	$user_reg_id = $this->dbTableInfoArray["user_registration"]->dbTableCurrentID;
	$val_msg = "";
	$user_id = 0;
	$register = true;
	$this->formActions["next"]->actionMayShow = false;
	$new_inst_name = readPost('FLD_institution_name');
//echo "*" . $new_inst_name . "*";
	// Identify if this user exists in HEQC-Online
	$user_reg_email = $this->getValueFromTable("user_registration", "user_reg_id",$user_reg_id, "email");

//echo "user_reg_id: ". $user_reg_id;
//echo "user_reg_email". $user_reg_email;

	$sql = <<<USER
		SELECT * 
		FROM users
		WHERE email = ?
USER;
        $conn = $this->getDatabaseConnection();
        $sm = $conn->prepare($sql);
        $sm->bind_param("s", $user_reg_email);
        $sm->execute();
        
        $rs = $sm->get_result();
	//$rs = mysqli_query($conn, $sql);
	$n = mysqli_num_rows($rs);
//echo "n: ". $n;

	if ($n == 1){  //Validate status of current user.  More than one user cannot be returned because of a unique index on email address;
		$row = mysqli_fetch_array($rs);

		$user_id = $row["user_id"];
		$active = $row["active"];
		$val_msg = "You are already a user on the HEQC-Online System.";

		if ($active == 0){
			$val_msg .= <<<MSG
				<ul>
				<li>Your user status is inactive which means that you will not be able to logon to the system.</li>
				<li>Please contact CHE and request that your login is activated.</li>
				</ul>
MSG;
		}

		if ($active == 1){
			$val_msg .= <<<MSG
				<ul>
				<li>Your user status is active.</li> 
				<li>You will be able to logon to the system.</li>
				<li>If you have forgotten your password then please use the forgot password facility to acquire a new password.</li>
				</ul>
MSG;
		}


		$register = false;
	}

	$inst_id = readPost("FLD_institution_ref");

	// Validate the institutional administrator: Is there already one?
	if ($inst_id > 0){

		$admarr = $this->getInstitutionAdministrator(0,$inst_id);

		if ($admarr[0] > 0) {

			$adm_user_id = $admarr[0];

			if ($user_id == $adm_user_id){
					$val_msg .= "You are already the institutional administrator.";
			} 
			else {
				$adm_name = $this->getUserName($adm_user_id);
				$val_msg .= <<<MSG
					<p>$adm_name is the HEQC-Online administrator for your institution.
					<br>
					<ul>
						<li>Contact him/her for any maintenance on institutional users.</li>
					</ul>
					<p>
					If the HEQC-Online administrator for your institution has changed then contact CHE to request the change.
					</p>
MSG;
			}
			$register = false; //false

		}
	} else {

		if ($new_inst_name > ''){
			/* Validate that an institution with this name does not already exist. */
			$sql = <<<SQL
				SELECT HEI_name FROM HEInstitution WHERE TRIM(UCASE(HEI_name)) = TRIM(UCASE(?))
SQL;
                        $sm = $conn->prepare($sql);
                        $sm->bind_param("s", $new_inst_name);
                        $sm->execute();
        
                        $rs = $sm->get_result();
			//$rs = mysqli_query($this->getDatabaseConnection(), $sql);
			$n = mysqli_num_rows($rs);
			if ($n > 0){				
				$row = mysqli_fetch_array($rs);
				$val_msg .= <<<MSG
					<br>You are trying to register as the administrator for a new institution (specified using other): <i>$new_inst_name</i>.
					<br><br>
					However an institution with the same name: <b>$row[HEI_name]</b> has already been registered.  Please click on previous in the actions menu 
					and rather select this institution from the list of institutions.
					<p>
					If you need the HEQC-Online administrator for your institution to change then contact CHE to request the change.
					</p>
MSG;
				$register = false;
			}
		}
	}
?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
	<br>
	<span class="specialh">Online registration for higher education providers</span>
	<br>
	</td>
</tr>
<tr>
	<td>
	<?php echo $val_msg; ?>
	</td>
</tr>
<?php if ($register === true ){ 

			$this->formActions["next"]->actionMayShow = true;


?>
<tr>
<td>
	<br>
	<br>
	Please upload your completed <span class="special">
	HEQC Online User - Institutional Application form and then submit your application for approval</span>
	<br>
	<br>
	<table width="90%" border="0" align="center" cellpadding="2" cellspacing="2">
		<tr>
			<td align="left"><b>Upload completed HEQC Online User - Institutional Application form*</b></td>
			<td class="oncolour"><?php echo $this->makeLink("registration_doc")?></td>
		</tr>
	</table>
	<br>
	<br>
	</td>
</tr>
<tr>
	<td colspan="2"><b><i>* Indicates required fields</i></b>
	</td>
</tr>
<?php } ?>
</table>
