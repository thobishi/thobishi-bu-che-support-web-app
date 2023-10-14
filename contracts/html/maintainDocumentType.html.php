<br>
<br>
<table border='0' width="95%" cellpadding="2" cellspacing="2" align="center">
	<tr>
		<td colspan="3">
			<span class="loud">Maintain document type</span>
			<hr>
		</td>
	</tr>
	<tr>
		<td>
			Click on <span class="specialb">Edit</span> to edit the document type. 
			<br><br>Click on "Add a new type" from the <b>Actions menu</b> to add a new type:
			<br><br>
			<table border='0' width="95%" cellpadding="2" cellspacing="2" align="center">
				<tr>
					<td class="oncolourcolumnheader">Edit</td>
					<td class="oncolourcolumnheader">Document Type</td>
				</tr>
<?php
				$SQL = "SELECT * FROM lkp_document_type WHERE 1 ORDER BY lkp_document_type_desc";
				$rs = mysqli_query($SQL);
				if (mysqli_num_rows($rs) > 0){
					while ($row = mysqli_fetch_array($rs)){
					$doc_type_id = $row["lkp_document_type_id"];
					$doc_type = $row["lkp_document_type_desc"];
					$text = <<< TEXT
						<tr class="oncolourcolumn">
							<td>
								<a href='javascript:setDocType("$doc_type_id");moveto("next");'>Edit</a>
							</td>
							<td>$doc_type</td>
						</tr>
TEXT;
						echo $text;

					}

				}
?>
		</table>
	</td>
</tr>
</table>

<br><br>

<script>
function setDocType(val){
	document.defaultFrm.CHANGE_TO_RECORD.value='lkp_document_type|'+val;
}
</script>



