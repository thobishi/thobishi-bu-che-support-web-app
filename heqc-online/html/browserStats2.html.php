<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<br><br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr>
	<td>
<?php 
		if (isset($_POST["appCodeName"]) && ($_POST["appCodeName"] > "")) {
			$messageCookie = ($_POST["cookieEnabled"] == "true")?(""):("The system indicates that your cookies are disabled. Please enable them in order to use the system successfully.");
			$messageJava = ($_POST["javaEnabled"] == "true")?(""):("The system indicates that your javascript are disabled. Please enable it in order to use the system successfully.");
?>
			<b>Your browser reports the following information:</b><br>
			<b><?php echo $messageCookie;?></b><br>
			<b><?php echo $messageJava;?></b><br>
			<table><tr>
				<td>appCodeName:</td>
				<td><?php echo $_POST["appCodeName"];?></td>
			</tr><tr>
				<td>appMinorVersion:</td>
				<td><?php echo $_POST["appMinorVersion"];?></td>
			</tr><tr>
				<td>appName:</td>
				<td><?php echo $_POST["appName"];?></td>
			</tr><tr>
				<td>cookieEnabled:</td>
				<td><?php echo $_POST["cookieEnabled"];?></td>
			</tr><tr>
				<td>cpuClass:</td>
				<td><?php echo $_POST["cpuClass"];?></td>
			</tr><tr>
				<td>onLine:</td>
				<td><?php echo $_POST["onLine"];?></td>
			</tr><tr>
				<td>platform:</td>
				<td><?php echo $_POST["platform"];?></td>
			</tr><tr>
				<td>systemLanguage:</td>
				<td><?php echo $_POST["systemLanguage"];?></td>
			</tr><tr>
				<td>userAgent:</td>
				<td><?php echo $_POST["userAgent"];?></td>
			</tr><tr>
				<td>userLanguage:</td>
				<td><?php echo $_POST["userLanguage"];?></td>
			</tr><tr>
				<td>javaEnabled:</td>
				<td><?php echo $_POST["javaEnabled"];?></td>
			</tr><tr>
				<td>taintEnabled:</td>
				<td><?php echo $_POST["taintEnabled"];?></td>
			</tr><tr>
				<td>mimeTypes:</td>
				<td><?php 
							if ((count($_POST["mimeTypes"]) > 0) && ($_POST["mimeTypes"][0] > "")) {
								foreach ($_POST["mimeTypes"] AS $mime) {
									echo $mime."<br>";
								}
							}else {
								echo "None";
							}
						?></td>
			</tr><tr>
				<td>plugins:</td>
				<td><?php 
							if ((count($_POST["plugins"]) > 0) && ($_POST["plugins"][0] > "")) {
								foreach ($_POST["plugins"] AS $mime) {
									echo $mime."<br>";
								}
							}else {
								echo "None";
							}
						?></td>
			</tr></table>
<?php 
			$message = "";
			$user = (isset($this->currentUserID) && ($this->currentUserID > 0))?($this->currentUserID):("");
			if ($user > "") {
				$user = "(USER: ".$this->getValueFromTable("users", "user_id", $user, "email").")";
			}
			$message .= "The IP number, ".$_SERVER["REMOTE_ADDR"]." ".$user.", has the following browser settings:\n";
			$message .= "appCodeName:	".$_POST["appCodeName"]."\n";
			$message .= "appMinorVersion:	".$_POST["appMinorVersion"]."\n";
			$message .= "appName:	".$_POST["appName"]."\n";
			$message .= "cookieEnabled:	".$_POST["cookieEnabled"]."\n";
			$message .= "cpuClass:	".$_POST["cpuClass"]."\n";
			$message .= "onLine:	".$_POST["onLine"]."\n";
			$message .= "platform:	".$_POST["platform"]."\n";
			$message .= "systemLanguage:	".$_POST["systemLanguage"]."\n";
			$message .= "userAgent:	".$_POST["userAgent"]."\n";
			$message .= "userLanguage: ".$_POST["userLanguage"]."\n";
			$message .= "javaEnabled:	".$_POST["javaEnabled"]."\n";
			$message .= "taintEnabled:	".$_POST["taintEnabled"]."\n";
			$message .= "mimeTypes:	";
						if ((count($_POST["mimeTypes"]) > 0) && ($_POST["mimeTypes"][0] > "")) {
							foreach ($_POST["mimeTypes"] AS $mime) {
								$message .= $mime."\n";
							}
						}else {
							$message .= "None\n";
						}
			$message .= "plug-ins:	";
						if ((count($_POST["plugins"]) > 0) && ($_POST["plugins"][0] > "")) {
							foreach ($_POST["plugins"] AS $mime) {
								$message .= $mime."\n";
							}
						}else {
							$message .= "None\n";
						}
			
			
			$this->misMailByName ("heqc@octoplus.co.za", "Browser Info", $message);
		}else {
			echo "The system was unsuccessful in retrieving any browser information.";
		}
?>
	</td>
</tr></table>
<br><br>
</td></tr></table>