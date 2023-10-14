<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<?php $this->showInstitutionTableTop ()?>
<br><br>
<b><?php echo $info_str?></b>
<br><br>
<?php 
	if ($type > "") {
		$this->showEmailAsHTML("checkForm30", $type);
	}
?>
</td></tr></table>
