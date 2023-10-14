<?php

// BUG line
// if (empty($run_in_script_mode) || !$run_in_script_mode) session_register("ses_userid");

class Security {
	var $userTable, $userIDfield, $usernameField, $passwordField;
	var $internal_ip_array;
	var $userInterface;
	var $noLogonMessage;

	public function __construct() {
		$this->readSettings ();
	}

	function readSettings () {
		Settings::set('userInterface', 1);
		Settings::set('securityLevel', 1);
		//the following line changed from "login" to "welcome"
		$this->tmp_notLoggedIn = "welcome";
		$this->tmp_noAccess = "noAccess";
		$this->noLogonMessage = "";

		$this->userTable     = SEC_USER_TABLE;
		$this->userIDfield   = SEC_USER_KEY;
		$this->usernameField = SEC_USER_NAME;
		$this->passwordField = SEC_USER_PWD;
		$this->internal_ip_array = array(SEC_USER_INTERNAL_IP);

		$this->getUserId();
	}

	function verifySecurity () {
//		Robin 27/3/2008: Uncomment and load to live to debug users getting unexpectedly logged off or duplicate errors on their 
//		institutional profile.
//		$str = print_r($this,true);
//		$this->AuditLog->writeLogInfo(15, "DEBUG", $str);
		$mayGo = false;
		
		switch (Settings::get('securityLevel')) {
			case 0: 	// anybody may
				$mayGo = true;
				break;
			case 1:		// must be logged in
				if(!$this->checkLogin ()) {
					Settings::set('template', $this->tmp_notLoggedIn);
				}
				break;
			case 2:		// must be part if proces
				if(! ( $this->checkLogin () AND $this->checkAccess () ) ) {
					Settings::set('template', $this->tmp_noAccess);
				}
				break;
		}
		return ($mayGo);
	}

	function getUserID () {
		if (isset ($_SESSION["ses_userid"]) ) {
			if ($_SESSION["ses_userid"] > 0) {
				Settings::set('currentUserID', $_SESSION["ses_userid"]);
			}
		}
	}

	function checkLogin () {
		$mayGo = false;

		if (isset ($_SESSION["ses_userid"]) ) {
			if ($_SESSION["ses_userid"] > 0) {
				Settings::set('currentUserID', $_SESSION["ses_userid"]);
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
    // echo $user . ' - ' .$pass;
    // print_r(compact('user', 'pass'));
		if ($pass > "") {
			$SQL = "SELECT * FROM ".$this->userTable." WHERE UPPER(".$this->usernameField.") = UPPER(:user) AND ".$this->passwordField . " = PASSWORD(:pass)";
			$rs = $this->db->query($SQL, compact('user', 'pass'));
      // print_r($rs);
			if ($row = $rs->fetch()) {
        // echo 'row found';exit;
				if ($row["active"] > 0) {
					Settings::set('currentUserID', $row[$this->userIDfield]);
					$this->setUserSession();
					$this->updateUserLastLoginDate(Settings::get('currentUserID'));
				} else {
					$this->noLogonMessage = '<span class="expiry">Access to the system is not granted. <a href="#infoModal" class="showHide" data-target="loginError" data-title="Possible reasons for login failure">Possible reasons</a></span><div id="err" class="loginError"><ul><li>You have not registered a user profile as yet, please apply for a login name.</li><li>You have entered an incorrect username or password, please try again.</li><li>You have applied for a profile, but your application has not been processed yet.</li></ul></div><div id="infoModal" class="modal hide fade"><div class="modal-header"><button type="button" class="closeLogin" data-dismiss="modal" aria-hidden="true">&times;</button><h3></h3></div><div class="modal-body"></div><div class="modal-footer"><a href="#" data-dismiss="modal" class="btn btn-primary">Ok</a></div></div>';
					return 0;
				}
			}
		} else {
			$this->noLogonMessage = "Please enter a username and password.";
			return 0;
		}
		if (!(Settings::get('currentUserID') > 0)) {
			$this->noLogonMessage = "The username/password entered is not valid.";
		}
	}

	/*
	* Louwtjie:
	* 2005-01-25
	*Function to show when last a user logged in.
	*/
	function updateUserLastLoginDate($id=0) {
		$this->db->query("UPDATE `users` SET login_number=(login_number+1), last_login_date=:loginDate WHERE user_id=:userId", array(
			'loginDate' => date("Y-m-d"),
			'userId' => $id
		));
	}

	function setUserSession () {
		if (Settings::get('currentUserID') > 0) {
			// session_register werk nie hier nie, dus array.
			$_SESSION["ses_userid"] = Settings::get('currentUserID');
		}
	}

	function destroyUserSession () {
		unset($_SESSION["ses_userid"]);
		Settings::set('currentUserID', 0);
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
		if ( Settings::isIsset('currentUserID') && (Settings::get('currentUserID')>0) ) {
			$ret = true;
		}
		return ($ret);
	}

	function sec_userInGroup ($group, $user=0) {
		if ($user == 0) {  // if no user is spesified, use the active user.
			$user = Settings::get('currentUserID');
		}

		$SQL = "SELECT count(*) as count FROM sec_UserGroups, sec_Groups ".
					 "WHERE sec_user_ref = :user ".
						 "AND sec_group_ref = sec_group_id ".
						 "AND sec_group_desc = :group";
		$rs = $this->db->query($SQL, compact('user', 'group'));
		if ( $row = $rs->fetch() ) {
			if ($row['count'] > 0) {
				return true;
			}
		}
		
		return false;
	}

	function sec_internal_user () {
		$internal = false;
		if ($this->sec_loggedIn () && $this->sec_userInGroup ("CHE")) {
			$internal = true;
		}
		return ($internal);
	}
	
	/*
		Get secGroup name
	*/
	
	function getSecGroupName($groupid){
		$groupName = array();
		
		$SQL = "SELECT sec_group_desc FROM sec_Groups WHERE sec_group_id = :groupid;";
		$rs = $this->db->query($SQL, compact('groupid'));
		
		while($row = $rs->fetch()){
			$groupName['template'] = strtolower(str_replace(' ', '_', $row[0]));
			$groupName['name'] = $row[0];
		}

		return $groupName;
	}
	function getMultipleGroupsName($groupidArr){
		// $this->pr($groupidArr);
		$groupArr = array();
		$placeHolders = implode(', ', array_fill(0, count($groupidArr), '?'));

		$SQL = "SELECT DISTINCT(sec_group_desc) FROM sec_Groups WHERE sec_group_id IN($placeHolders);";
		$rs = $this->db->query($SQL, $groupidArr);
		while($row = $rs->fetch()){
			array_push($groupArr, $row['sec_group_desc']);
		}
		return $groupArr;
	}
	/*	Diederik
			Created: 2004/04/07
			Check if the active user is part of a group
	*/
	function sec_partOfGroup ($groupid, $userId ="") {
		$ret = false;

		if ($groupid==0) {
			$ret = true;
		} else {
			$id = ($userId > "") ? $userId : Settings::get('currentUserID');
			$SQL = "SELECT * FROM sec_UserGroups ".
				"WHERE (sec_user_ref = ". $id .") ".
				"  AND (sec_group_ref = :groupid)";
			$rs = $this->db->query($SQL,compact('groupid'));
			if ($rs->rowCount() > 0) {
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
		$S = "SELECT distinct sec_group_ref FROM sec_UserGroups WHERE sec_user_ref = :userId";

		$r = $this->db->query($S, array('userId' => Settings::get('currentUserID')));
		while ($rrow = $r->fetch()){
			array_push($ret,$rrow[0]);
		}

		return $ret;
	}

	/* 2004-05-07
	   Diederik
	   Function to current logged on user info.
	*/

	function getCurrentUserInfo ($field="name",$uuser="") {
		$user = Settings::get('currentUserID');
		if ($uuser > ""){
			$user = $uuser;
		}
		$ret = "";

		$SQL = "SELECT * FROM `users` WHERE user_id=".$user;
		$RS = $this->db->query($SQL);
		if ($row = $RS->fetch()) {
			$ret = $row[$field];
		}

		return ($ret);
	}

	// 20080229: Diederik, check if this is pwd of the current user and is it the admin
	function isAdminPassword ($pwd) {
		if ($pwd > "") {
			$SQL = "SELECT * FROM users WHERE user_id = ".Settings::get('currentUserID')." AND ".$this->passwordField." = PASSWORD('".$pwd."')";
			$rs = mysqli_query($SQL);
			if (mysqli_num_rows($rs)==1 AND $this->checkIfAdmin($this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID, Settings::get('currentUserID'))) {
				return (true);
			}
		}
		return (false);
	}
// end of class
}
