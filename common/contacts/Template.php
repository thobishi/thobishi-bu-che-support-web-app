<?php 

class Template {

  public $id, $name, $html;

  public function __construct() {
    $this->id="";
    $this->name="";
    $this->html="";
  }

  public function loadFromDB($row) {
		$this->id  =$row["template_id"];
	  $this->name=$row["template_name"];
	  $this->html=$row["template_html"];
  }

  public function loadFromPOST() {
		$this->id  =$_POST["template_id"];
	  $this->name=$_POST["template_name"];
	  $this->html=$_POST["template_html"];
  }
		
}

?>
