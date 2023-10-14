<?php 

require_once('contacts/globals.php');

class Organization {

  public $id, $name, $expire;

  public function __construct() {
    $this->id  ="";
    $this->name="";
  }

  public function loadFromDB($row) {
		$this->id    =$row["id"];
	  $this->name  =$row["organization"];
	  $this->expire=$row["expire"];
  }

  public function expireWarning() {
    if ($this->expire==null) return '';
    $result='';
    $now=time();
    $exp=strtotime($this->expire);
    if ($now-60*60*24*1>$exp) $result='Your licence expired on '.date('Y-m-d', $exp);
    else if ($now+60*60*24*EXPIRE_WARN_DAYS>$exp) $result='Your licence will expire on '.date('Y-m-d', $exp);
    return $result;
  }
  
}

?>
