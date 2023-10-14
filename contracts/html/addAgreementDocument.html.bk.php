<?php 
	$agreement_ref = $this->dbTableInfoArray["d_consultant_agreements"]->dbTableCurrentID;
	$cons_id = $this->getValueFromTable("d_consultant_agreements", "agreement_id", $agreement_ref, "consultant_ref");

	$this->formFields["agreement_ref"]->fieldValue = $agreement_ref;
	$this->showField("agreement_ref");
	$a_doc_id = $this->dbTableInfoArray["d_agreement_docs"]->dbTableCurrentID;

//add next button when document has been uploaded
	if ($this->getValueFromTable("d_agreement_docs", "agreement_doc_id", $a_doc_id, "document_title") != NULL) {
		$this->formActions["stay"]->actionMayShow = false;
	} else {
		$this->formActions["next"]->actionMayShow = false;
	}
?>
<br>
<table border='0' width="95%" cellpadding="2" cellspacing="2" align="center">
	<tr>
		<td colspan="2">
			<?php echo echo $this->displayContractHeader($cons_id,$agreement_ref,"Documents"); ?>
			<hr>
		</td>
	</tr>
	<tr>
		<td width="20%">Document Title</td>
		<td><?php echo $this->showField("document_title") ?></td>
	</tr>
	<tr>
		<td width="20%">Type of document</td>
		<td>
		<?php echo $this->showField("document_type_ref") ?>
		&nbsp;Other:<?php echo $this->showField("other_document"); 
		 $this->showField("new_document_type");?>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><?php echo $this->makeLink("agreement_doc") ?></td>
	</tr>
</table>
<br>

<script>
function uploadDocument(val){
	document.defaultFrm.CHANGE_TO_RECORD.value='d_agreement_docs|'+val;
}
</script>


