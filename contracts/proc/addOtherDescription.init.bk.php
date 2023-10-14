<?php 
	$doc_id = $this->dbTableInfoArray["d_agreement_docs"]->dbTableCurrentID;

	$newdesc = $this->getValueFromTable("d_agreement_docs", "agreement_doc_id", $doc_id , "new_document_type");

	if ($newdesc > ""){

			$SQL = "SELECT lkp_document_type_id FROM `lkp_document_type` WHERE lkp_document_type_desc ='" .$newdesc. "'";
			$RS = mysqli_query($SQL);
			$num_rows = mysqli_num_rows($RS);
			if ($num_rows == 0){
				$SQL = "INSERT INTO `lkp_document_type` (lkp_document_type_desc) VALUES ('". $newdesc ."')";
				$RS = mysqli_query($SQL);
				$id = mysqli_insert_id();
				if ($id > ""){
					$SQL = "UPDATE d_agreement_docs SET new_document_type = '', document_type_ref = " . $id . " WHERE agreement_doc_id = ". $doc_id;
					if (! mysqli_query ($SQL) ) {
						$this->writeLogInfo(10, "SQL-addOtherDescription-init", $SQL."  --> ".mysqli_error(), true);
					}
				}
			}

	}
?>