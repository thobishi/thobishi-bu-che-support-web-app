<?php
	$html_ref = "<ul>";

	// Get reference documents from settings
	$sql = "SELECT * FROM settings
		WHERE s_key like 'ref_0%'";
        $conn = $this->getDatabaseConnection();
        $stmt = $conn->prepare($sql);

        $stmt->execute();

        $rs = $stmt->get_result();
	//$rs = mysqli_query($sql);
	$n = mysqli_num_rows($rs);

	if ($n == 0) $html_ref .= "<li>No reference documents are currently available</li>";

	while ($row = mysqli_fetch_array($rs)){
		$doc = $this->download($row['s_value'], "", $row['s_description'], "desc");
		$html_ref .= '<li>' . $doc . "</li>";
	}

		$html_ref .= "</ul>";
?>
<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td class="loud">List of Accreditation Reference documents</td>
</tr>
<tr>
	<td>
	<br>The following reference documents are available to assist with the accreditation process of programmes at CHE.</td>
</tr>
<tr>
	<td>
		<?php echo $html_ref; ?>
	</td>
</tr>
</table>

