<?php

class Session {

  public $user;
  public $email;

  public function __construct() {
    $this->user=null;
    $this->email=null;
  }

  public function isLoggedIn() {
    if ($this->user!=null && $this->user->id>0) return true;
    return false;
  }

  public function logout() {
    $this->user=null;
    $this->email=null;
  }

  public function getUserOrg() {
    if ($this->user!=null) return $this->user->organization;
    return null;
  }

  public function getUserId() {
    return $this->user->id;
  }
  
  public function getUserCode() {
    return "U".$this->user->id;
  }

  public function isAdmin() {
    return $this->user->admin;
  }
  
  public function isSuperUser() {
    return $this->user->su;
  }

  public static function getSession() {
    session_start();
    $result=null;
    if (isset($_SESSION["session"])) $result=$_SESSION["session"];
    if ($result==null) {
      $result=new Session();
      $_SESSION["session"]=&$result;
    }
    return $result;
  }
  
  public static function getSessionRW() {
    $result=self::getSession();
    if (!$result->isLoggedIn()) {
      echo "<script>window.top.location='http://".$_SERVER['HTTP_HOST']."/index.php'</script>";
      die('Not logged in');
    }
    return $result;
  }
  
  public static function getSessionRO() {
    $result=self::getSession();
    session_write_close();
    if (!$result->isLoggedIn()) {
      echo "<script>window.top.location='http://".$_SERVER['HTTP_HOST']."/index.php'</script>";
      die('Not logged in');
    }
    return $result;
  }

}

?>
