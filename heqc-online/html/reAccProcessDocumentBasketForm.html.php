<?php 
	$reaccred_id = $this->dbTableInfoArray["Institutions_application_reaccreditation"]->dbTableCurrentID;

	$this->formFields["reaccred_programme_ref"]->fieldValue = $reaccred_id;
	$this->showField("reaccred_programme_ref");
	$a_doc_id = $this->dbTableInfoArray["reaccred_document_process"]->dbTableCurrentID;

//add next button when document has been uploaded
//	if ($this->getValueFromTable("reaccred_document_process", "reaccred_document_id", $a_doc_id, "reaccred_document_title") != NULL) {
		$this->formActions["stay"]->actionMayShow = false;
//	} else {
//		$this->formActions["next"]->actionMayShow = false;
//	}
?>
<br>
<table border='0' width="95%" cellpadding="2" cellspacing="2" align="center">
	<tr>
		<td colspan="2">
			<?php echo $this->displayReaccredHeader($reaccred_id); ?>
		</td>
	</tr>
	<tr>
		<td colspan="2"><br><b>Please enter a title and description for your document and select the file for upload:</b></td>
	</tr>
	<tr>
		<td width="20%">Document Title</td>
		<td><?php $this->showField("reaccred_document_title") ?></td>
	</tr>
	<tr>
		<td width="20%">Comment/Note</td>
		<td>
		<?php $this->showField("reaccred_document_comment") ?>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><?php $this->makeLink("reaccred_document_ref") ?></td>
	</tr>
</table>
<br>

<script>
function uploadDocument(val){
	document.defaultFrm.CHANGE_TO_RECORD.value='reaccred_document_process|'+val;
}
</script>


