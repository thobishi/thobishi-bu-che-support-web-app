<?php

$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body		= "manageUsersForm1";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Admin > User Management</span>";

// 20070618 (Diederik): selectAll func is in: manageUsersForm1.html.php
//$this->formOnSubmit = "selectAll();";
$this->formOnSubmit = "return selectAll();";

?>
