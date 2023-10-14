<a name="application_form_question4"></a>
<br>
<?php 

	$current_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	if ($this->view != 1) {	$this->getApplicationInfoTableTopForHEI_sites($current_id); }

	$this->displayRelevantButtons($current_id, $this->currentUserID);
?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<b>9. POSTGRADUATE POLICIES, PROCEDURES AND REGULATIONS: (Criterion 9)
</b>
<br>
<br>

<fieldset>
<legend>Minimum standards</legend>
<?php echo $this->getTextContent("accForm19_v2", "minimumStandards"); ?>
</fieldset>
<br>
<br>
Is this a post-graduate programme? 	<?php $this->showField("is_postgraduate_ref"); ?>
<br>
<br>
<?php $displayStyle = $this->displayifConditionMetInstitutions_applications($current_id, 'is_postgraduate_ref', '2'); ?>
<div id="is_postgrad" style="display:<?php echo $displayStyle?>">
<?php

echo $this->buildSiteCriteriaEditforApplication($current_id,'9');

?>
<hr>
</div>
<br><br>

</td>
</tr>
</table>

