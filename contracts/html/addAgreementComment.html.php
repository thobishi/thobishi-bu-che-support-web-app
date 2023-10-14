<?php 
	$comment_id = $this->dbTableInfoArray["d_agreement_comments"]->dbTableCurrentID;
	$agreement_ref = $this->dbTableInfoArray["d_consultant_agreements"]->dbTableCurrentID;
	$cons_id = $this->getValueFromTable("d_consultant_agreements", "agreement_id", $agreement_ref, "consultant_ref");
	$isAdministrator = $this->sec_partOfGroup(1);
	
	$this->formFields["agreement_ref"]->fieldValue = $agreement_ref;
	$this->showField("agreement_ref");

?>
<br>
<table border='0' width="95%" cellpadding="2" cellspacing="2" align="center">
	<tr>
		<td colspan="2">
			<?php echo echo $this->displayContractHeader($cons_id,$agreement_ref,"Comments"); ?>
			<hr>
		</td>
	</tr>
	<tr>
		<td width="10%" valign="top">Name of commenter:</td>
		<td><?php echo $this->showField("commenter_name") ?></td>
	</tr>
	<tr>
		<td width="10%" valign="top">Date:</td>
		<td><?php echo $this->showField("comment_date") ?></td>
	</tr>
	<tr>
		<td width="10%" valign="top">Your comment:</td>
		<td><?php echo $this->showField("comment") ?></td>
	</tr>
	
	<?php if ($isAdministrator){?>
	<tr>
		<td width="10%" valign="top" colspan="2">Click on <a href="javascript:delComment('<?php echo $comment_id;?>','')">Delete this comment</a> to delete the above comment record.</td>
	</tr>
	<?php 
	}
	
		// Record who captured the comment.  Ignore previously captured comments.
	if ($this->formFields["comment_date"]->fieldValue == '') {
		$this->formFields["user_ref"]->fieldValue = $this->currentUserID;
		$this->showField("user_ref");
	}
	?>
</table>
<br>

