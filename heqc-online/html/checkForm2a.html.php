<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2"><tr><td>
<?php $this->showInstitutionTableTop ()?>
<?php 
$this->showField("documentation");
$this->showField("changeTo");

$app_id = $this->dbTableInfoArray["Institutions_application"]->dbTableCurrentID;
$app_version = $this->getValueFromTable("Institutions_application", "application_id", $app_id, "app_version");

$checkBoxArr = array();

	$checkBoxArr = explode("|", $this->getFieldValue("documentation"));
	if (count($checkBoxArr) > 0) {
		foreach ($checkBoxArr AS $keys=>$values) {
			$this->formFields["DOCRADIO_".$values]->fieldOptions = "CHECKED";
		}
	}
?>
<br><br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td colspan="2"><b>The following supporting documentation needs to have been received by the HEQC:</b></td>
</tr>
<tr>
	<td>Please check that:<ul>
	<?phpif ($app_version == "1") { ?><li>You have received all the documentation and tick it off in appropriate box</li><?php} ?>
	<li>All documentation is filed in registry</li></ul></td>
</tr>
<tr>
	<td>If the documentation you have received is incomplete, click <a href="javascript:changeField(1);moveto('339');">here</a></td>
</tr>
<tr>
	<td>If you have not received any of the documentation yet, click <a href="javascript:changeField(2);moveto('334');">here</a></td>
</tr>
<tr>
	<td>&nbsp;</td>
</tr>

<?php 
	if ($app_version == "1") {
?>
<tr>
	<td><table border=1 cellpadding="2" cellspacing="0"><tr>
		<td align="center" class="oncolourb">All documentation</td>
		<td align="center" class="oncolourb">Selected documentation</td>
		<td class="oncolourb">Reasons supplied by institution for not submitting documentation</td>
		</tr>

<?php 
	$all_docs = $no_docs = $docs = $doc_url = array();
	$all_docs = $this->returnApplicationDocs ($docs, $no_docs, $doc_url);
	$inst_id = $this->getValueFromTable("HEInstitution", "HEI_id", $this->getValueFromTable("Institutions_application", "application_id", $app_id, "institution_id"), "priv_publ");
	foreach ($all_docs AS $key=>$value) {
		if ((substr($value, (strpos($value, "|")+1), strlen($value)) == $inst_id) || (substr($value, (strpos($value, "|")+1), strlen($value)) == 3)) {
			if (!(($this->getValueFromTable("Institutions_application", "application_id", $app_id, "NQF_ref") < 3) && (substr($key, 0, 1) == 9))) {
				echo '<tr>';
				echo '<td valign="top">';
				echo substr($value, 0, strpos($value, "|"));
				echo '</td>';
				$wrap = "nowrap";
				if ((substr(key($docs), 9, strlen(key($docs)))) == $key) {
					if (strlen(current($docs)) > 100) $wrap = "";
					echo '<td '.$wrap.' valign="top">';
					$this->showField(key($docs));
					echo '&nbsp;'.current($docs);
					if (key($doc_url) == $key) {
						echo '&nbsp;-&nbsp;';
						$doc = new octoDoc (current($doc_url));

						if ($url = $doc->url()) {
							echo '<a href="'.$url.'" target="_blank" title="'.$doc->getFilename().'">Open document</a>';
						}else{
							echo current($doc_url);
						}
						next($doc_url);
					}
					echo '</td>';
					next($docs);
				}else {
					echo '<td>&nbsp;</td>';
				}
				if (key($no_docs) == $key) {
					echo '<td valign="top">';
					echo current($no_docs);
					echo '</td>';
					next($no_docs);
				}else {
					echo '<td>&nbsp;</td>';
				}
				echo '</tr>';
			}
		}
	}
?>
		</table>
	</td>
</tr>

<?php 
	}

	if ($app_version == "2" or $app_version == "3" or $app_version == "4") {

		$html =<<<DISPLAY
			<tr class="onblueb">
				<td>Document description</td>
				<td>Download document</td>
				<td>Required by CHE?</td>
			</tr>
DISPLAY;

		//
		//If the field is a _doc, it is a document.
		//If the field has fieldValidationName = doc, it is required
		//
		//

		switch ($app_version){
		case 2:
			$SQL =<<<sql
			SELECT *
			FROM template_field
			WHERE fieldName LIKE '%_doc'
			AND template_name LIKE '%_v2'
			ORDER BY fieldOrder
sql;
		case 3:
		case 4:
			$SQL =<<<sql3
			SELECT *
			FROM template_field
			WHERE fieldName LIKE '%_doc'
			AND ((template_name LIKE '%_v2' OR template_name LIKE '%_v3') AND template_name != 'accForm1_v2')
			ORDER BY fieldOrder
sql3;
		}

                $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
                if ($conn->connect_errno) {
                    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
                    printf("Error: %s\n".$conn->error);
                    exit();
                }
		$rs = mysqli_query($conn, $SQL);

		if (mysqli_num_rows($rs) > 0) {
			while ($row = mysqli_fetch_array($rs)) {
				$docField = $row['fieldName'];
				if (stripos($row['template_name'], "_2_v2") > 0)
				{
					//if template is a child:
					$docValue = $this->getValueFromTable("ia_criteria_per_site", "application_ref", $app_id, $docField);
					$currentTableId = $this->getValueFromTable("ia_criteria_per_site", "application_ref", $app_id, "ia_criteria_per_site_id");
				}
				else
				{
					$docValue = $this->getValueFromTable("Institutions_application", "application_id", $app_id, $docField);
				}

				$docObj = new octoDoc($docValue);
				$docURL = $docObj->url();
				$docDownload = $docObj->getFilename();
				$docName = ($row['fieldDisplayName']) ? $row['fieldDisplayName'] : $row['fieldTitle'];
				$condition = $row["fieldValidationCondition"];
				$evalRes = "";

				$checkValidated = ($row['fieldValidationName'] == "doc") ? "true" : "false";

				// Validate field only if its condition is satisfied.
				if ($condition > ""){
					$evalStr = "return (($condition)?(true):(false));";
					$this->mis_eval_pre(__LINE__, __FILE__);
					$evalRes = eval($evalStr);
					$this->mis_eval_post($evalStr);
				}
				if ($condition == "") {
					if ($row['fieldValidationName'] == "doc") {
						//no condition, but document is required by all
						$evalRes = true;
					} else {
						$evalRes = false;
					}
				}

				$required = (($checkValidated == true) && ($evalRes)) ? "<img src='images/check_mark.gif'>" : "";

				$okToDisplay = "false";

				if ($condition)	{
					//if condition is met, display it
					$okToDisplay = ($evalRes) ? true : false;
				} else {
					//if no document to display, don't show a row
					$okToDisplay = ($docDownload) ? true : false;
				}

				if ($okToDisplay) {
					$html .=<<< DISPLAY
						<tr class="onblue">
							<td>$docName</td>
							<td><a href="$docURL" target="_blank">$docDownload</a></td>
							<td>$required</td>
						</tr>
DISPLAY;
				}
			}
		}

		echo $html;
	}
?>

<tr>
	<td>&nbsp;</td>
</tr>

</table>

</td></tr></table>


<script>
	function changeField(val) {
		document.defaultFrm.FLD_changeTo.value = val;
	}
</script>
