<?php 
	$reaccred_id = $this->dbTableInfoArray["Institutions_application_reaccreditation"]->dbTableCurrentID;
?>
<br>
<table border='0' width="95%" cellpadding="2" cellspacing="2" align="center">
	<tr>
		<td colspan="2">
			<?php echo $this->displayReaccredHeader($reaccred_id); ?>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="loud">Reaccreditation process documents<br><hr></td>
	</tr>
	<tr>
		<td colspan="2">Please upload any documentation pertaining to this application.  The following required documents should be uploaded:
				<ul>
					<li>Checklisted report</li>
					<li>Evaluators reports</li>
					<li>Directorate recommendation</li>
					<li>AC meeting minutes</li>
					<li>Letters and outcome</li>
					<li>Representation</li>
					<li>Site visit report</li>
				</ul>
		</td>
	</tr>
	<tr>
		<td class="oncolourcolumn">
			<table border='0' width="95%" cellpadding="2" cellspacing="2" align="center">
				<tr>
					<td class="oncoloursoft" align="right" colspan="5">
						<?phpif ($this->view != 1){?>
						<a href="javascript:reaccredDocProcess('NEW');moveto('_label_reaccProcessDocForm');">>> Add new document</a>
						<?php }?>
					</td>
				</tr>
				<tr>
					<td class="oncoloursoft">Document Title</td>
					<td class="oncoloursoft">Comment</td>
					<td class="oncoloursoft">Date added</td>
					<td class="oncoloursoft">Edit/Update</td>
				</tr>
<?php 
			$SQL = "SELECT * FROM reaccred_document_process WHERE reaccred_programme_ref=?";

			$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
			if ($conn->connect_errno) {
			    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
			    printf("Error: %s\n".$conn->error);
			    exit();
			}

			$sm = $conn->prepare($SQL);
			$sm->bind_param("s", $reaccred_id);
			$sm->execute();
			$rs = $sm->get_result();


			//$rs = mysqli_query($SQL);

			$docHtml = "";
			if (mysqli_num_rows($rs) > 0) {
				while ($row = mysqli_fetch_array($rs)) {
					$title = $row["reaccred_document_title"];
					$doc_ref = $row["reaccred_document_ref"];
					$dateUpdated  = $this->getValueFromTable("documents", "document_id", $doc_ref , "last_update_date");
					$document = new octoDoc($doc_ref);
					$docLink	 = "<a href='".$document->url()."' target='_blank'>".$title."</a>";
					$doc_id = $row["reaccred_document_id"];
					$link = "Edit";
					if ($this->view != 1){
						$link = '<a href="javascript:reaccredDocProcess('.$doc_id.');moveto(\'_label_reaccProcessDocForm\');">Edit</a>';
					}
					$docHtml .=<<< TEXT
						<tr>
							<td class="ongreycolumn">$docLink</td>
							<td class="ongreycolumn">$row[reaccred_document_comment]</td>
							<td class="ongreycolumn">$dateUpdated</td>
							<td class="ongreycolumn">$link</td>
						</tr>
TEXT;
				}
				echo $docHtml;
			} else {
				echo "<tr><td> - No documents have been added -</td></tr>";
			}
?>
			</table>
		</td>
	</tr>
</table>
<br>

<script>
function reaccredDocProcess(val){
	document.defaultFrm.CHANGE_TO_RECORD.value='reaccred_document_process|'+val;
}
</script>
