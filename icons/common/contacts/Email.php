<?php

class Email {

  public $id, $toIdList, $fromUserId, $fromAliasId, $dateSent, $noOfRcpt, $subject, $body, $attach1, $attach2, $attach3;
  public $file1, $file2, $file3;

  public function __construct() {
    $this->id         ="";
    $this->toIdList   ="";
    $this->fromUserId ="";
    $this->fromAliasId="";
    $this->dateSent   =date("Y-m-d H:i:s");
    $this->noOfRcpt   =0;
    $this->subject    ="";
    $this->body       ="";
    $this->attach1    ="";
    $this->file1      =null;
    $this->attach2    ="";
    $this->file2      =null;
    $this->attach3    ="";
    $this->file3      =null;
  }
  
  public function loadFromPOST() {
    $this->toIdList   =$_POST["toIdList"];
    $this->fromUserId =$_POST["fromUserId"];
    $this->fromAliasId=$_POST["fromAliasId"];
    $this->subject    =$_POST["subject"];
    $this->body       =$_POST["body"];
    $this->attach1    =$_FILES["attach1"]["name"];
    $this->file1      =$_FILES["attach1"];
    $this->attach2    =$_FILES["attach2"]["name"];
    $this->file2      =$_FILES["attach2"];
    $this->attach3    =$_FILES["attach3"]["name"];
    $this->file3      =$_FILES["attach3"];
  }
  
  public function loadFromDB($row) {
    $this->id         =$row["id"];
    $this->toIdList   =$row["toIdList"];
    $this->fromUserId =$row["fromUserId"];
    $this->fromAliasId=$row["fromAliasId"];
    $this->dateSent   =$row["dateSent"];
    $this->noOfRcpt   =$row["noOfRcpt"];
    $this->subject    =$row["subject"];
    $this->body       =$row["body"];
    $this->attach1    =$row["attach1"];
    $this->attach2    =$row["attach2"];
    $this->attach3    =$row["attach3"];
  }
  
  public function checkIfMostRecentDuplicate($email) {
    if (substr($this->dateSent,0,10)==substr($email->dateSent,0,10) && $this->subject==$email->subject && $this->toIdList==$email->toIdList) return true;
    return false;
  }

}
