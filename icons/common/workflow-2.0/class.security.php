<?php

// BUG line
// if (empty($run_in_script_mode) || !$run_in_script_mode) session_register("ses_userid");

class security extends dbFunctions {
	var $userTable, $userIDfield, $usernameField, $passwordField;
	var $securityLevel, $currentUserID;
	var $internal_ip_array;
	var $userInterface;
	var $noLogonMessage;

	function __construct () {
		$this->dbConnect ();
		$this->readSettings ();

	}
	
	function security () {
            self::__construct();
	}

	function readSettings () {
		$this->userInterface = 1;
		$this->securityLevel = 1;
		//the following line changed from "login" to "welcome"
		$this->tmp_notLoggedIn = "welcome";
		$this->tmp_noAccess = "noAccess";
		$this->noLogonMessage = "";

		$this->userTable     = SEC_USER_TABLE;
		$this->userIDfield   = SEC_USER_KEY;
		$this->usernameField = SEC_USER_NAME;
		$this->passwordField = SEC_USER_PWD;
		$this->internal_ip_array = array(SEC_USER_INTERNAL_IP);

		$this->getUserId ();
	}

	function verifySecurity () {
//		Robin 27/3/2008: Uncomment and load to live to debug users getting unexpectedly logged off or duplicate errors on their 
//		institutional profile.
//		$str = print_r($this,true);
//		$this->writeLogInfo(15, "DEBUG", $str);
		$mayGo = false;
		
		switch ($this->securityLevel) {
			case 0: 	// anybody may
				$mayGo = true;
				break;
			case 1:		// must be logged in
				if(!$this->checkLogin ()) {
					$this->template = $this->tmp_notLoggedIn;
				}
				break;
			case 2:		// must be part if proces
				if(! ( $this->checkLogin () AND $this->checkAccess () ) ) {
					$this->template = $this->tmp_noAccess;
				}
				break;
		}
		return ($mayGo);
	}

	function getUserID () {
		if (isset ($_SESSION["ses_userid"]) ) {
			if ($_SESSION["ses_userid"] > 0) {
				$this->currentUserID = $_SESSION["ses_userid"];
			}
		}
	}

	function checkLogin () {
		$mayGo = false;

		if (isset ($_SESSION["ses_userid"]) ) {
			if ($_SESSION["ses_userid"] > 0) {
				$this->currentUserID = $_SESSION["ses_userid"];
				$mayGo = true;
			}
		}

		return ($mayGo);
	}

	function checkAccess () {
		$mayGo = false;
		return ($mayGo);
	}


	function userLogin ($user, $pass) {
		if ($pass > "") {
			$SQL = "SELECT * FROM ".$this->userTable." WHERE ".$this->usernameField." = UPPER(?) AND ".$this->passwordField." = PASSWORD(?)";
			$conn = $this->getDatabaseConnection();
                        $sm = $conn->prepare($SQL);
                        $sm->bind_param("ss", $user, $pass);
                        $sm->execute();
                        $rs = $sm->get_result();
			//$rs = mysqli_query($SQL);
			if ($row = mysqli_fetch_array ($rs)) {
				if ($row["active"] > 0) {
					$this->currentUserID = $row[$this->userIDfield];
					$this->setUserSession ();
					$this->updateUserLastLoginDate($this->currentUserID);
				}else{
					$this->noLogonMessage = "<span class=\"expiry\">Access to the system is not granted.</span> <a href='javascript:showHide(document.all.err);'>Possible reasons</a><br><div id=\"err\" style=\"display:none\"><table align='center'><tr><td align='left'><span class=\"special\"><ul><li type='1'>You have not registered a user profile as yet, please apply for a login name.</li><li type='1'>You have entered an incorrect username or password, please try again.</li><li type='1'>You have applied for a profile, but your application has not been processed yet.</li></ul></span></td></tr></table></div>";
					return 0;
				}
			}
		}else {
			$this->noLogonMessage = "Please enter a username and password.";
			return 0;
		}
		if (!($this->currentUserID > 0)) {
			$this->noLogonMessage = "The username/password entered is not reflected in the system.";
		}
	}

	/*
	* Louwtjie:
	* 2005-01-25
	*Function to show when last a user logged in.
	*/
	function updateUserLastLoginDate($id=0) {
                $d = date("Y-m-d");
		$SQL = "UPDATE `users` SET login_number=(login_number+1), last_login_date=? WHERE user_id=?";
		$conn = $this->getDatabaseConnection();
                $sm = $conn->prepare($SQL);
                $sm->bind_param("ss", $d, $id);
                $sm->execute();
                $rs = $sm->get_result();
	}

	function setUserSession () {
		if ($this->currentUserID > 0) {
			// session_register werk nie hier nie, dus array.
						$_SESSION["ses_userid"] = $this->currentUserID;
		}
	}

	function destroyUserSession () {
		unset($_SESSION["ses_userid"]);
		$this->currentUserID = 0;
	}

	function makePassword($length,$strength=0) {
		$vowels = 'aeiouy';
  		$consonants = 'bdghjlmnpqrstvwxz';
  		if ($strength & 1) {
    		$consonants .= 'BDGHJLMNPQRSTVWXZ';
		}
		if ($strength & 2) {
			$vowels .= "AEIOUY";
		}
		if ($strength & 4) {
			$consonants .= '0123456789';
		}
		if ($strength & 8) {
			$consonants .= '@#$%^';
		}
		$password = '';
		$alt = time() % 2;
		srand(time());
		for ($i = 0; $i < $length; $i++) {
			if ($alt == 1) {
				$password .= $consonants[(rand() % strlen($consonants))];
				$alt = 0;
			} else {
				$password .= $vowels[(rand() % strlen($vowels))];
				$alt = 1;
			}
		}
		return $password;
	}

	function sec_internal_ip () {
		$ret = false;

		foreach ($this->internal_ip_array as $ip) {
			if (! strncmp ($ip, $_SERVER["REMOTE_ADDR"], strlen ($ip)) ) {
				$ret = true;
			}
		}

		return ($ret);
	}

	function sec_loggedIn () {
		$ret = false;
		if ( isset($this->currentUserID) && ($this->currentUserID>0) ) {
			$ret = true;
		}
		return ($ret);
	}

	function sec_userInGroup ($group, $user=0) {
		$ret = false;
		if ($user == 0) {  // if no user is spesified, use the active user.
			$user = $this->currentUserID;
		}

		$SQL = "SELECT * FROM sec_UserGroups, sec_Groups ".
					 "WHERE sec_user_ref = ? ".
						 "AND sec_group_ref = sec_group_id ".
						 "AND sec_group_desc = ?";
						 
                $conn = $this->getDatabaseConnection();
                $sm = $conn->prepare($SQL);
                $sm->bind_param("ss", $user, $group);
                $sm->execute();
                $rs = $sm->get_result();
                        
		//$rs = mysqli_query ($SQL);
		if ( $row = mysqli_fetch_array ($rs) ) {
			$ret = true;
		}
		return ($ret);
	}

	function sec_internal_user () {
		$internal = false;
		if ($this->sec_loggedIn () && $this->sec_userInGroup ("CHE")) {
			$internal = true;
		}
		return ($internal);
	}

	/*	Diederik
			Created: 2004/04/07
			Check if the active user is part of a group
	*/
	function sec_partOfGroup ($groupid) {
		$ret = false;

		if ($groupid==0) {
			$ret = true;
		} else {
			$SQL = "SELECT * FROM sec_UserGroups ".
				"WHERE (sec_user_ref = ?) ".
				"  AND (sec_group_ref = ?)";
				
                        $conn = $this->getDatabaseConnection();
                        $sm = $conn->prepare($SQL);
                        $sm->bind_param("ss", $this->currentUserID, $group);
                        $sm->execute();
                        $rs = $sm->get_result();
                        
			//$rs = mysqli_query ($SQL);
			if (mysqli_num_rows($rs) > 0) {
				$ret = true;
			}
		}

		return ($ret);
	}


	/*
	Reyno
	Created: 2004/05/05
	Returns an array with all the groups you belong to.
	*/
	function sec_inGroups() {
		$ret = array();
		array_push($ret,0);
		$S = "SELECT distinct sec_group_ref FROM sec_UserGroups WHERE sec_user_ref = ?";

		$conn = $this->getDatabaseConnection();
                $sm = $conn->prepare($S);
                $sm->bind_param("s", $this->currentUserID);
                $sm->execute();
                $r = $sm->get_result();
                        
		//$r = mysqli_query($S);
		if (mysqli_num_rows($r) > 0) {
			while ($rrow = mysqli_fetch_array($r)){
				array_push($ret,$rrow[0]);
			}
		}
		return $ret;
	}

	/* 2004-05-07
	   Diederik
	   Function to current logged on user info.
	*/

	function getCurrentUserInfo ($field="name",$uuser="") {
		$user = $this->currentUserID;
		if ($uuser > ""){
			$user = $uuser;
		}
		$ret = "";

		$SQL = "SELECT * FROM `users` WHERE user_id=?";
		$conn = $this->getDatabaseConnection();
                $sm = $conn->prepare($SQL);
                $sm->bind_param("s", $user);
                $sm->execute();
                $RS = $sm->get_result();
                        
		//$RS = mysqli_query($SQL);
		if ($row = mysqli_fetch_array($RS)) {
			$ret = $row[$field];
		}

		return ($ret);
	}

	// 20080229: Diederik, check if this is pwd of the current user and is it the admin
	function isAdminPassword ($pwd) {
		if ($pwd > "") {
			$SQL = "SELECT * FROM users WHERE user_id = ? AND ".$this->passwordField." = PASSWORD(?)";
			$conn = $this->getDatabaseConnection();
                        $sm = $conn->prepare($SQL);
                        $sm->bind_param("ss", $this->currentUserID, $pwd);
                        $sm->execute();
                        $rs = $sm->get_result();
                        
			//$rs = mysqli_query($SQL);
			if (mysqli_num_rows($rs)==1 AND $this->checkIfAdmin($this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, $this->currentUserID)) {
				return (true);
			}
		}
		return (false);
	}


// end of class
}

?>
