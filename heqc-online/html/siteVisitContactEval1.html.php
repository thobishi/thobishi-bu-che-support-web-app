<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<?php $this->showInstitutionTableTop()?>
<br><br>
<table width="85%" border=0 align="center" cellpadding="2" cellspacing="2"><tr>
	<td><?php $this->showField("phone_comments");?></td>
</tr></table>

<?php $this->showField("date");?>
<?php $this->showField("eval_ref");?>
<?php $this->showField("siteVisit_ref");?>
<br><br>
<?php 
	if (isset($_POST["contact_eval"]) && ($_POST["contact_eval"])) {
		$this->showEvalPhoneComments("siteVisit_contact_eval", "siteVisit_ref", "eval_ref", $this->dbTableInfoArray["siteVisit"]->dbTableCurrentID, $_POST["contact_eval"], "date", "phone_comments");
	}
?>
<br><br>
</td></tr></table>
