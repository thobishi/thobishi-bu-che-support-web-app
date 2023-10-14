<?php
	$inst_id = $this->dbTableInfoArray["institutional_profile"]->dbTableCurrentID;
//	echo $inst_id;
?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<br>
<b>DOCUMENTS TO UPLOAD:</b>
<br><br>

<br><br>
<table width="95%" border=0 cellpadding="2" cellspacing="2">

<tr>
	<td width="30%" align="right"><b>1. Learning Management System and Management Information System</b></td>
	<td class="oncolour">
		<br>
		&nbsp;<?php $this->makelink("learning_management_doc");?>
	</td>
</tr>


<tr>
	<td width="30%" align="right"><b>2. Licenses for Information Technology software</b></td>
	<td class="oncolour">
		<br>
		&nbsp;<?php $this->makelink("license_doc");?>
	</td>
</tr>

<tr>
	<td width="30%" align="right"><b>3.	Library Collection development policy</b></td>
	<td class="oncolour">
		<br>
		&nbsp;<?php $this->makelink("library_collection_doc");?>
	</td>
</tr>

<tr>
	<td width="30%" align="right"><b>4.	E-library policy</b></td>
	<td class="oncolour">
		<br>
		&nbsp;<?php $this->makelink("elibrary_policy_doc");?>
	</td>
</tr>


<tr>
	<td width="30%" align="right"><b>5.	Curriculum vitae of the librarian</b></td>
	<td class="oncolour">
		<br>
		&nbsp;<?php $this->makelink("curriculum_vitae_doc");?>
	</td>
</tr>

<tr>
	<td width="30%" align="right"><b>6.	Library database agreements</b></td>
	<td class="oncolour">
		<br>
		&nbsp;<?php $this->makelink("library_database_doc");?>
	</td>
</tr>

<tr>
	<td width="30%" align="right"><b>7.	Licences for library databases</b></td>
	<td class="oncolour">
		<br>
		&nbsp;<?php $this->makelink("license_library_doc");?>
	</td>
</tr>

</td>
</tr>
</table>