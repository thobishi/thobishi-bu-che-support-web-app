<?php
	$inst_id = $this->dbTableInfoArray["institutional_profile"]->dbTableCurrentID;
//	echo $inst_id;
?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>

<br>
<tr align="center">
<td align="center">
<b  >      GENERAL POLICIES </b>
</td>
</tr>
<tr align="center">
<td align="center">

</td>
</tr>
<br>
<br>
<table width="95%" border=0 cellpadding="2" cellspacing="2">

<tr>
	<td width="30%" align="right"><b>1.	Policy on Institutional Policies</b></td>
	<td class="oncolour">
		<br>
		&nbsp;<?php $this->makelink("policy_institutional_doc");?>
	</td>
</tr>


<tr>
	<td width="30%" align="right"><b>2.	Quality assurance policy incl. measures applied under emergency situations, e.g. COVID-19 restrictions</b></td>
	<td class="oncolour">
		<br>
		&nbsp;<?php $this->makelink("quality_policy_doc");?>
	</td>
</tr>

<tr>
	<td width="30%" align="right"><b>3.	Language Policy</b></td>
	<td class="oncolour">
		<br>
		&nbsp;<?php $this->makelink("language_policy_doc");?>
	</td>
</tr>

<tr>
	<td width="30%" align="right"><b>4.	Policy for distance provisioning</b></td>
	<td class="oncolour">
		<br>
		&nbsp;<?php $this->makelink("policy_distance_doc");?>
	</td>
</tr>


<tr>
	<td width="30%" align="right"><b>5.	Plagiarism policy</b></td>
	<td class="oncolour">
		<br>
		&nbsp;<?php $this->makelink("plagiarism_policy_doc");?>
	</td>
</tr>

</td>
</tr>
</table>