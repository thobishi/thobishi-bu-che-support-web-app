<?php 
	$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;

	$this->formFields["application_ref"]->fieldValue = $app_id;
	$this->showField("application_ref");
	$a_doc_id = $this->dbTableInfoArray["ia_documents"]->dbTableCurrentID;

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
			<?php $this->displayApplicationForOutcomes($app_id); ?>
		</td>
	</tr>
	<tr>
		<td colspan="2"><br><b>Please enter a title and description for your document and select the file for upload:</b></td>
	</tr>
	<tr>
		<td width="20%">Document Title</td>
		<td><?php $this->showField("document_title") ?></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><?php $this->makeLink("application_doc") ?></td>
	</tr>
</table>
<br>


