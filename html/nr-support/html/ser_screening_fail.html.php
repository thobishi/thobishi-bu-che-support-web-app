<h3>Screening</h3>
<?php
	$prog_id = $this->dbTableInfoArray["nr_programmes"]->dbTableCurrentID;
	$adminArr = $this->getProgrammeAdministrator($prog_id);
	$admInfoArr = $this->db->customArrRequest("email, contact_nr","users","","user_id = $adminArr[0]");

	$currentUserID = Settings::get('currentUserID');

	echo '<div class="row-fluid">';
	$this->displayProgrammeInfo();
	echo '<div class="clear"></div>';
	echo '</div>';
	
	echo '<div class= "alert alert-block alert-error alert-large">';
	echo "You have indicated that the SER has not satisfied all screening criteria. The SER must be returned to the institution. The following email may be emailed to the institution. The screening report will be attached. Please follow up with the institution to ensure that they have received it.  Click on '<strong>Next</strong>' to send this application back to the institution";
	echo '</div>';
	echo '<span>';
	echo "'<em>'Uncheck the box if you want to bypass sending email to the user.'</em>'";
	echo '</span>';
	
	$files = "";
	$doc_id = $this->db->getValueFromTable("screening","programme_ref",$prog_id,"checklist_report_doc");
	if ($doc_id > ""){
		$doc_url = $this->db->getValueFromTable("documents", "document_id", $doc_id,"document_url");
		$doc_name = $this->db->getValueFromTable("documents", "document_id", $doc_id,"document_name");
		$files = array();
		array_push($files,array(OCTODOC_DIR.$doc_url,$doc_name));
	}	
	// $this->pr($files);
	
	$chk_admin = '<input type="Checkbox" name="id_admin[]" value="'.$adminArr[0].'" checked>';
	$filesArray = '<input type="hidden" name="files" value="'.htmlentities(json_encode($files)).'">';
	echo $filesArray;
?>	
	
	<table class="table table-bordered">
		<thead>
			<tr>
			  <th>Administrator name</th>
			  <th>Email</th>
			  <th>Telephone no.</th>
			  <th>Send email?</th>			  
			</tr>
		  </thead>
		  <tbody>
			<tr>
			  <td><?php echo $this->getUserFullName($adminArr[0]); ?></td>
			  <td><?php echo $admInfoArr[0]; ?></td>
			  <td><?php echo $admInfoArr[1]; ?></td>
			  <td><?php echo $chk_admin; ?></td>
			</tr>
		  </tbody>	
	</table>
<?
	echo '<div class="src_return_email">';
	$this->showfield('return_to_institution_email');
	echo '</div>';
	
	$this->view = 1;


	echo '<em>Attachment: </em>';
	$this->makeLink("checklist_report_doc", "Checklist report");

	
	
	
	
?>