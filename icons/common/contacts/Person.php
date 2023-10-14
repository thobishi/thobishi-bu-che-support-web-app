<?php 

class Person {

  public $id, $name, $surname, $email, $phone, $fax, $mobile, $company, $job_title, $postal_addr, $postal_code, $physical_addr, $physical_code;
  public $email2, $email3, $keywords;

  public function __construct() {
    $this->id="";
    $this->name="";
    $this->surname="";
    $this->email="";
    $this->phone="";
    $this->fax="";
    $this->mobile="";
    $this->company="";
    $this->job_title="";
    $this->postal_addr="";
    $this->postal_code="";
    $this->physical_addr="";
    $this->physical_code="";
    $this->email2="";
    $this->email3="";
    $this->keywords="";
  }

  public function overwriteEmptyFields($person) {
    if ($this->id=="") $this->id=$person->id;
    if ($this->name=="") $this->name=$person->name;
    if ($this->surname=="") $this->surname=$person->surname;
    if ($this->email=="") $this->email=$person->email;
    if ($this->phone=="") $this->phone=$person->phone;
    if ($this->fax=="") $this->fax=$person->fax;
    if ($this->mobile=="") $this->mobile=$person->mobile;
    if ($this->company=="") $this->company=$person->company;
    if ($this->job_title=="") $this->job_title=$person->job_title;
    if ($this->postal_addr=="") $this->postal_addr=$person->postal_addr;
    if ($this->postal_code=="") $this->postal_code=$person->postal_code;
    if ($this->physical_addr=="") $this->physical_addr=$person->physical_addr;
    if ($this->physical_code=="") $this->physical_code=$person->physical_code;
    if ($this->email2=="") $this->email2=$person->email2;
    if ($this->email3=="") $this->email3=$person->email3;
    if ($this->keywords=="") $this->keywords=$person->keywords;
  }

  public function loadFromPOST() {
    if (isset($_POST["id"])) $this->id=$_POST["id"];
	  $this->name         =$_POST["name"];
	  $this->surname      =$_POST["surname"];
	  $this->email        =$_POST["email"];
    $this->phone        =$_POST["phone"];
    $this->fax          =$_POST["fax"];
    $this->mobile       =$_POST["mobile"];
    $this->company      =$_POST["company"];
    $this->job_title    =$_POST["job_title"];
    $this->postal_addr  =$_POST["postal_addr"];
    $this->postal_code  =$_POST["postal_code"];
    $this->physical_addr=$_POST["physical_addr"];
    $this->physical_code=$_POST["physical_code"];
    $this->email2       =$_POST["email2"];
    $this->email3       =$_POST["email3"];
    $this->keywords     =$_POST["keywords"];
  }

  public function loadFromDB($row) {
		$this->id           =$row["id"];
	  $this->name         =$row["name"];
	  $this->surname      =$row["surname"];
	  $this->email        =$row["email"];
    $this->phone        =$row["phone"];
    $this->fax          =$row["fax"];
    $this->mobile       =$row["mobile"];
    $this->company      =$row["company"];
    $this->job_title    =$row["job_title"];
    $this->postal_addr  =$row["postal_addr"];
    $this->postal_code  =$row["postal_code"];
    $this->physical_addr=$row["physical_addr"];
    $this->physical_code=$row["physical_code"];
    $this->email2       =$row["email2"];
    $this->email3       =$row["email3"];
    $this->keywords     =$row["keywords"];
  }
	
  public function getFullname() {
    $result=$this->name.' '.$this->surname;
    if ($result==' ') $result=$this->email;
    if ($result=='') $result=$this->id;
    return $result;
  }

  public static function makeDisplayName($name1, $name2, $sep=" ") {
    $retName='-No information-';
    if ($name1>"" && $name2>"") $retName=$name1.$sep.$name2." ";
    else if ($name2>"") $retName=$name2." ";
    else if ($name1>"") $retName=$name1." ";
    return $retName;
  }

}

?>
