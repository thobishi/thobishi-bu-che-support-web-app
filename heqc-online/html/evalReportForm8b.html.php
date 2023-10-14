<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<?php $this->showInstitutionTableTop();?>
<br><br>
<table width="75%" border=0  cellpadding="2" cellspacing="2"><tr>
	<td>
<?php 
	$this->showField("application_ref");
	$this->showField("institution_ref");
	$this->showField("eval_ref");
?>
<b>Please indicate if and why you think a site visit is neccesary:</b> <?php $this->showField("recommend");?>
	</td>
</tr><tr>
	<td><?php $this->showField("sitevisit_reason");?></td>
</tr></table>
<br><br>
</td></tr></table>