<?php 

require_once('contacts/globals.php');

class User {

  public $id, $username, $name, $surname, $office_phone, $cell_phone, $password, $email, $licence_key, $user_type, $organization;
  public $admin, $su;
  public $nrEmailsSent, $nrRecipients;
  public $listOfEmails;

  public function __construct() {
    $this->id          ="";
    $this->username    ="";
    $this->name        ="";
    $this->surname     ="";
    $this->office_phone="";
    $this->cell_phone  ="";
    $this->password    ="";
    $this->email       ="";
    $this->licence_key =null;
    $this->user_type   ="";
    $this->organization="";
    $this->admin=false;
    $this->su=false;
    $this->nrEmailsSent=0;
    $this->nrRecipients=0;
    $this->listOfEmails=null;
  }

  public function loadFromDB($row) {
		$this->id          =$row["id"];
	  $this->username    =$row["username"];
	  $this->name        =$row["name"];
	  $this->surname     =$row["surname"];
	  $this->office_phone=$row["office_phone"];
	  $this->cell_phone  =$row["cell_phone"];
	  $this->password    ="";
	  $this->email       =$row["email"];
	  $this->licence_key =$row["licence_key"];
	  $this->user_type   =$row["user_type"];
	  $this->organization=$row["organization"];
    if ($this->user_type=="su") $this->su=true;
    if ($this->user_type=="admin" || $this->su) $this->admin=true;
  }
  
  public function loadFromPOST($loadOrg=false) {
    if (isset($_POST["id"])) $this->id=$_POST["id"];
	  $this->username    =$_POST["username"];
	  $this->name        =$_POST["name"];
	  $this->surname     =$_POST["surname"];
	  $this->office_phone=$_POST["office_phone"];
	  $this->cell_phone  =$_POST["cell_phone"];
	  $this->password    =$_POST["password"];
	  $this->email       =$_POST["email"];
    if (isset($_POST["licence_key"])) $this->licence_key=$_POST["licence_key"];
	  $this->user_type   =$_POST["user_type"];
    if ($loadOrg) $this->organization=$_POST["organization"];
  }
  
  public function overwriteEmptyFields($user) {
    if ($this->id=="") $this->id=$user->id;
    if ($this->username=="") $this->username=$user->username;
    if ($this->name=="") $this->name=$user->name;
    if ($this->surname=="") $this->surname=$user->surname;
    if ($this->office_phone=="") $this->office_phone=$user->office_phone;
    if ($this->cell_phone=="") $this->cell_phone=$user->cell_phone;
    if ($this->email=="") $this->email=$user->email;
    if ($this->licence_key=="") $this->licence_key=$user->licence_key;
    if ($this->organization=="") $this->organization=$user->organization;
  }
		
  public function newPassword() {
		$this->password = "";
		srand ((double) microtime() * 1000000);
		for ($i=0; $i<MAX_PASSWORD_LENGTH; $i++) {
			$this->password .= chr(rand(97, 122));
		}
  }
  
  public function newLicence() {
		$this->licence_key = "";
		srand ((double) microtime() * 1000000);
		for ($i=0; $i<MAX_LICENCE_LENGTH; $i++) {
      $ix=rand(1,62);
      if ($ix<=10) $this->licence_key .= chr(47+$ix);      // 48=0
      else if ($ix<=36) $this->licence_key .= chr(54+$ix); // 65=A
      else $this->licence_key .= chr(60+$ix);              // 97=a
		}
  }

  public function getEmailFromName() {
    $result=$this->name.' '.$this->surname;
    if ($result=='') $result=EMAIL_FROM_NAME;
    return $result;
  }

  public function getEmailFromAddr() {
    $result=$this->email;
    if ($result=='') $result=EMAIL_FROM_ADDR;
    return $result;
  }
  
  public function getFullname() {
    $result=$this->name.' '.$this->surname;
    if ($result==' ') $result=$this->email;
    if ($result=='') $result=$this->id;
    return $result;
  }
  
}

?>
