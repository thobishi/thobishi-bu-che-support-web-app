<?php

	// Store foreign key value to be added during import.
	$ind_id = $this->dbTableInfoArray["lkp_indicator"]->dbTableCurrentID;
	$this->formFields["imp_foreign1"]->fieldValue = $ind_id;
	$this->showField("imp_foreign1");
	
	$this->displayIndicatorHeader($ind_id);

	// Initialise a record in the batchUpload table.
	$this->formFields["dateStart"]->fieldValue = date('Y-m-d');
	$this->formFields["user_ref"]->fieldValue = $this->currentUserID;
	$this->formFields["document_upload_ref"]->fieldValue = "";

	$this->showField("dateStart");
	$this->showField("user_ref");
	$this->showField("document_upload_ref");

	$this->formFields["imp_foreign1"]->fieldValue = $ind_id;
	$this->showField("imp_foreign1");
	
	require_once ('./lib/class.importData.php');
	$imp = new importData("");

?>

<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td align="left">
	The list of items for a performance indicator may be imported from an Excel Spreadsheet instead of captured individually.
	<br><br>The Excel spreadsheet must be in a specific format:
	<ul>
		<li>The following data headings are required and are expected in record 1 in the spreadsheet:
		<?php $imp->setImportDefn("import_perf_ind_list");
			  $imp->printImportDefn("v_col_is_required");
		?>
		</li>
		<li>The list of items must start in row: <?php echo echo $imp->importStartRow; ?></li>
	</ul>

	<span class="specialb">Please note the following information concerning the import:</span>
	<br>Each time an import is run the data in the import file is <b>appended</b> to the data in the database.  Please do not 
	import the same data twice as duplicate records will result.  A facility to maintain (edit, delete and add items) the item 
	list for a particular year and indicator is available on the indicator summary page.

	<br><br>
	<span class="specialb">The import is a two stage process:</span>
	<br>
	<span class="specialb">Stage 1:</span>
	<ul>
		<li>The import file is validated according to the data requirements of the export and a report is displayed to the user.</li>
		<li>If the import file is not in the expected format then the import cannot continue.</li>
		<li>Data in the import file may have several rules that need to be adhered to in order to allow you to import the file.
		A report will display with a summary of the data that may be imported and data that will not be imported.</li>
	</ul>
	<span class="specialb">Stage 2:</span>
	<br><br>
	Once the user and the system are satisfied with the data in the import file the user may continue with the import.
	The data is imported into the system.
	</td>
</tr>
</table>
<br>