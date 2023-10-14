<?php
  
require_once('contacts/globals.php');

class FieldList {

  static public $unsubIx=9;
  static public $disclaimIx=11;

  static public $fields=array(

    array( "show"=>"Y", "tag"=>"fullname"   , "description"=>"Full Name"         , "default"=>"Client"         , "replaceWith"=>"getFullName"       ),
    array( "show"=>"Y", "tag"=>"name"       , "description"=>"Name"              , "default"=>"Client"         , "replaceWith"=>"getName"           ),
    array( "show"=>"Y", "tag"=>"surname"    , "description"=>"Surname"           , "default"=>"Client"         , "replaceWith"=>"getSurname"        ),
    array( "show"=>"Y", "tag"=>"email"      , "description"=>"Email"             , "default"=>"(Email)"        , "replaceWith"=>"getEmail"          ),
    array( "show"=>"Y", "tag"=>"phone"      , "description"=>"Phone"             , "default"=>"(Phone number)" , "replaceWith"=>"getPhone"          ),
    array( "show"=>"Y", "tag"=>"fax"        , "description"=>"Fax"               , "default"=>"(Fax number)"   , "replaceWith"=>"getFax"            ),
    array( "show"=>"Y", "tag"=>"mobile"     , "description"=>"Mobile"            , "default"=>"(Mobile number)", "replaceWith"=>"getMobile"         ),
    array( "show"=>"Y", "tag"=>"company"    , "description"=>"Company"           , "default"=>"(Company)"      , "replaceWith"=>"getCompany"        ),
    array( "show"=>"Y", "tag"=>"jobtitle"   , "description"=>"Job title"         , "default"=>"(Job title)"    , "replaceWith"=>"getJobTitle"       ),
    array( "show"=>"Y", "tag"=>"unsubscribe", "description"=>"Unsubscribe"       , "default"=>"Click here to unsubscribe", "replaceWith"=>"getUnsubscribe" ),
    array( "show"=>"Y", "tag"=>"updateinfo" , "description"=>"Update information", "default"=>"Click here to update your personal information", "replaceWith"=>"getUpdateInfo" ),
    array( "show"=>"N", "tag"=>"disclaimer" , "description"=>"Disclaimer", "default"=>"Disclaimer/complaints", "replaceWith"=>"getDisclaimerInfo"  )

  );

  static public function getFullName($person, $batchId, $type) {
    return $person->name.' '.$person->surname;
  }

  static public function getName($person, $batchId, $type)     { return $person->name;      }
  static public function getSurname($person, $batchId, $type)  { return $person->surname;   }
  static public function getEmail($person, $batchId, $type)    { return $person->email;     }
  static public function getPhone($person, $batchId, $type)    { return $person->phone;     }
  static public function getFax($person, $batchId, $type)      { return $person->fax;       }
  static public function getMobile($person, $batchId, $type)   { return $person->mobile;    }
  static public function getCompany($person, $batchId, $type)  { return $person->company;   }
  static public function getJobTitle($person, $batchId, $type) { return $person->job_title; }

  static public function getUnsubscribe($person, $batchId, $type) {
    return ""; // Use screen message and then default message
  }
  
  static public function generateUnsubscribeURL($msg, $person, $batchId, $type) {
    if ($msg=='NOT') return "";
    return "<a href='".BASEURL."unsubscribe.php?email=".$person->email."&batchId=".$batchId."&token=".
            md5($person->id."-".$person->email."-".$batchId."-".MD5_PASSWORD)."'>".trim($msg)."</a>";
  }
  
  static public function generateDefaultUnsubscribeURL($person, $batchId, $type) {
    return FieldList::generateUnsubscribeURL(FieldList::$fields[FieldList::$unsubIx]["default"], $person, $batchId, $type);
  }

  static public function generateDefaultDisclaimerURL($person, $batchId, $type) {
    return FieldList::generateDisclaimerURL(FieldList::$fields[FieldList::$disclaimIx]["default"], $person, $batchId, $type);
  }
  
  static public function getUpdateInfo($person, $batchId, $type) {
    return ""; // Use screen and then default message
  }
  
  static public function generateUpdateInfoURL($msg, $person, $batchId, $type) {
    return "<a href='".BASEURL."updateInfo.php?email=".$person->email."&token=".md5($person->id."-".$person->email."-".MD5_PASSWORD)."'>".trim($msg)."</a>";
  }

  static public function getDisclaimerInfo() {
    return ""; // Use screen and then default message
  }
  
  static public function generateDisclaimerURL($msg, $person, $batchId, $type) {
    return "<a href='".BASEURL."disclaimer.php?email=".$person->email."'>".trim($msg)."</a>";
  }

}

?>
