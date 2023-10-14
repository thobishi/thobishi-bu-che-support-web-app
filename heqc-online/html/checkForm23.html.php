<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<?php $this->showInstitutionTableTop ()?>
<br>
<table width="100%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>The application has been checked for completion. Now you need to establish whether the programme conforms to current policy. Use the template below to do the check. This same template will be incorporated into your summary of the programme for the Accreditation Committee meeting.</td>
</tr>
</table>
<table cellpadding="2" cellspacing="2" border="1" width="90%">
<th colspan="3">COMPLIANCE WITH APPROPRIATE REGULATIONS:</th>
<?php 
$this->showField("application_ref");

$headingArray = array();
array_push($headingArray,"REGULATION");
array_push($headingArray,"");
array_push($headingArray,"COMMENTS (include in the comments what actions have been taken in case of lack/incorrect information)");

$refDispArray = array();
array_push($refDispArray,"lkp_screening_regulation_desc");

$dispFields = array();
array_push($dispFields,"yes_no");
array_push($dispFields,"comments_text");

$this->makeGRID("lkp_screening_regulation",$refDispArray,"lkp_screening_regulation_id","1","screening_compliance","screening_compliance_id","regulation_ref","screening_ref",$this->dbTableInfoArray["screening"]->dbTableCurrentID,$dispFields,$headingArray, "", "", 70, 5);
?></table>
</td></tr></table>
