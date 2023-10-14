<br>
<table border='0' width="95%" cellpadding="2" cellspacing="2" align="center">
	<tr>
		<td>
			<span class="loud">Reports: Review Consultants</span>
			<hr>
			Review list of consultants:
		</td>
	</tr>
	<tr>
		<td>
			<table border='0' width="95%" cellpadding="2" cellspacing="2" align="center">
<?php 
		//add up the number of active contracts they have? maybe link to list?
		$sql = <<< SQL
			SELECT c.*,
			IF (c.type=2, c.company, CONCAT(c.name, " ", c.surname)) AS consultant,
			count(d_consultant_agreements.consultant_ref) AS total,
			d_consultant_agreements.status
			FROM d_consultants AS c,
			d_consultant_agreements
			WHERE consultant_id = consultant_ref
			AND d_consultant_agreements.status != 2
			GROUP BY consultant_ref
SQL;
		$rs = mysqli_query($sql);
		if (mysqli_num_rows($rs) > 0){
			$html =<<< HTML
				<tr>
					<td class="oncolourcolumnheader">Consultant name</td>
					<td class="oncolourcolumnheader">Type of consultant</td>
					<td class="oncolourcolumnheader">Current contracts</td>
				</tr>
HTML;
			echo $html;
			while ($row = mysqli_fetch_array($rs)){
				$con_id = $row["consultant_id"];
				$name = $row["consultant"];
				$email = $row["email"];
				$total_agreements = $row["total"];
				$type = $this->getValueFromTable("lkp_consultant_type", "lkp_consultant_type_id", $row["type"], "lkp_consultant_type_desc");
				$html = <<< HTML
					<tr>
						<td class="oncolourcolumn">$name</td>
						<td class="oncolourcolumn">$type</td>
						<td class="oncolourcolumn">$total_agreements</td>
					</tr>
HTML;
				echo $html;
			}
		} else {
			echo "<tr><td align='center' class='oncolourcolumn'>- There are currently no contracts to report on -</td></tr>";
		}
?>
			</table>
		</td>
	</tr>
</table>
<br>