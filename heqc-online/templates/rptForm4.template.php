<?php

$this->title		= "CHE Accreditation";
$this->bodyHeader	= "formHead";
$this->body		= "rptForm4";
$this->bodyFooter	= "formFoot";
$this->NavigationBar	= "<span class=pathdesc>Evaluators information > Report</span>";

$this->setFormDBinfo("Eval_Auditors", "Persnr");

$this->createField("Persnr", "TEXT");

?>
