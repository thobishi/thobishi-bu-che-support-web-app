<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td align="left">
<?php
	$ind_id = readPOST('imp_foreign1');
	$this->formFields["imp_foreign1"]->fieldValue = $ind_id;
	$this->showField("imp_foreign1");
	
	$this->displayIndicatorHeader($ind_id);

	require_once ('./lib/class.importData.php');

	$this->makeImport("document_upload_ref");

	$doc_id = $this->formFields["document_upload_ref"]->fieldValue;

	if ($doc_id > 0){

		$imp = new importData(OCTODOC_DIR.$doc_id.".xls");

		$rc = $imp->setImportDefn("import_perf_ind_list");
		if ($rc == true){

			$imp->foreign_key["detail_lkp_indicator_ref"] = $ind_id;
		
			$rc1 = $imp->importFile();
			$imp->displayImportFileReport();

			if ($rc1 == true){
				$imp->importFileData("yes");
				$imp->displayImportFileDataReport();
			}

		}

	}

	?>
	</td>
</tr>
</table>
