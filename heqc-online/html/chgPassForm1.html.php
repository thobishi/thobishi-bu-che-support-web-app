<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<br><br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr>
	<td width="30%" align="right"><b>New Password:</b></td>
	<td width="70%"><?php $this->showField("password")?></td>
</tr><tr>
	<td width="30%" align="right"><b>Confirm Password:</b></td>
	<td width="70%"><?php $this->showField("password_confirm") ?></td>
</tr><tr>
	<td></td>
	<td><input type="button" class="btn" value="Change Password" onClick="checkPass(document.defaultFrm.password, document.defaultFrm.password_confirm);"></td>
</tr></table>
<br><br>
</td></tr></table>

<?php


	if (isset($_POST["password"]) && ($_POST["password"] > "")) {
             $pwd = $_POST['password'];

            if( strlen($pwd) < 8 ) {
             $error .= "Password too short!
            ";
                 }

          if( strlen($pwd) > 20 ) {
            $error .= "Password too long!
                 ";
                    }

                if( strlen($pwd) < 8 ) {
                 $error .= "Password too short!
                  ";
                         }

                 if( !preg_match("#[0-9]+#", $pwd) ) {
                  $error .= "Password must include at least one number!
                      ";
                 }

                 if( !preg_match("#[a-z]+#", $pwd) ) {
                     $error .= "Password must include at least one letter!
                   ";
                   }

            if( !preg_match("#[A-Z]+#", $pwd) ) {
               $error .= "Password must include at least one CAPS!
                ";
                     }

           if( !preg_match("#\W+#", $pwd) ) {
            $error .= "Password must include at least one symbol!
                ";
                    }

          if($error){
         echo "Password validation failure(your choise is weak): $error";
      } 


else { 
    $pass = $_POST["password"];

$SQL = 'UPDATE users SET password = PASSWORD2(?) WHERE user_id = ?';

//$SQL = "UPDATE `users` " . "SET password=PASSWORD2('$pass')" . "WHERE user_id =" .$this->currentUserID;
		
		$conn = $this->getDatabaseConnection();
		$sm = $conn->prepare($SQL);
		$sm->bind_param("ss", $pass, $this->currentUserID);
		$sm->execute();
		$rs = $sm->get_result();
		
	//	$rs = mysqli_query($conn, $SQL);
		echo '<script>goto('.__HOMEPAGE.');</script>';
      //  print_r ($SQL);
        file_put_contents('php://stderr', print_r("URL : ".$SQL, TRUE));

}

echo $currentUserID;
		
	}

	echo $currentUserID;
?>


