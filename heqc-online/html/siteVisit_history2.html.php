<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<?php $this->showInstitutionTableTop ()?>
<br>
<?php 
	if ((isset($_POST["application_ref"]) && ($_POST["application_ref"] > 0)) && (($_POST["site_ref"]) && ($_POST["site_ref"] > 0))) {
		echo $this->genSiteVisitReport ($_POST["application_ref"], $_POST["site_ref"]);
	}
?>

<br><br>
</td></tr></table>
