<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
<td>
	<?php
	$this->showInstitutionTableTop ();
	$id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
	$this->formFields['application_status']->fieldValue = 4;
	$this->showField('application_status');

	?>

<?php
	 $current_user_id = $this->currentUserID;
	 $grp = 42;

?>
<br><br>
<table>
<tr>
	<td>
	Once  you have completed your part, send it to the next person that needs to be involved in the process. To do this, select an email address from the list below:<br><br>
	<b>Colleague: </b>
<?php
	$dd = $this->makeDropdownOfGroupUsers($grp);
	echo $dd;
?>
<br><br>
	</td>
</tr>
<tr>
	<td>
		<?php $grp_name = $this->getValueFromTable("sec_Groups","sec_group_id",$grp,"sec_group_desc"); ?>
		<span class="visi">Note: The users in the above list belong to the <?php echo $grp_name; ?> group</span>
	</td>
</tr>
</table>

	<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
	<tr>
		<td>
		<br>
		Please provide your reasons for not approving the evaluator reports on this application. Please add any further comments
		or instructions to the Project Admininstrator to assist with resolving the problem.
		</td>
	</tr>
	<tr>
		<td>
		When you click on <b>Send to Project Administrator</b>, this application will be returned to the Project Administrator
		with your comments and instructions.  You will not have access to approve this application until the Project Administrator
		marks it ready for approval it and sends it back to you.
		</td>
	</tr>
	<tr>
		<td>
		<br>
		<?php $this->showField('manager_evalreport_comment'); ?>
		</td>
	</tr>
	</table>
</td>
</tr>
</table>

