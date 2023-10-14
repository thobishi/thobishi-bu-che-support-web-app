<?php 
	$curr_date = date('Y-m-d');
?>

<br>
<table border='0' width="95%" cellpadding="2" cellspacing="2" align="center">
	<tr>
		<td>
			<span class="loud">Reports: Contracts Expiring</span>
			<hr>
			Contracts expiring within the next 3 months:
		</td>
	</tr>
	<tr>
		<td>
			<table border='0' width="95%" cellpadding="2" cellspacing="2" align="center">
<?php 
		$sql = <<< SQL
			SELECT c.consultant_id,
			IF (c.type=2, c.company, CONCAT(c.name, " ", c.surname)) AS consultant,
			a.description,
			c.email,
			c.type,
			a.start_date,
			a.end_date
			FROM d_consultant_agreements AS a
			LEFT JOIN d_consultants AS c
			ON consultant_ref=consultant_id
			WHERE end_date < DATE_ADD('$curr_date', INTERVAL 3 MONTH)
			AND a.status != 2
SQL;
		$rs = mysqli_query($sql);
		if (mysqli_num_rows($rs) > 0){
			$html =<<< HTML
				<tr>
					<td class="oncolourcolumnheader">Contract description</td>
					<td class="oncolourcolumnheader">Consultant name</td>
					<td class="oncolourcolumnheader">Type of consultant</td>
					<td class="oncolourcolumnheader">Start date</td>
					<td class="oncolourcolumnheader">End date</td>
				</tr>
HTML;
			echo $html;
			while ($row = mysqli_fetch_array($rs)){
				$con_id = $row["consultant_id"];
				$name = $row["consultant"];
				$desc = $row["description"];
				$email = $row["email"];
				$type = $this->getValueFromTable("lkp_consultant_type", "lkp_consultant_type_id", $row["type"], "lkp_consultant_type_desc");
				$start_date = $row["start_date"];
				$end_date = $row["end_date"];
				$html = <<< HTML
					<tr>
						<td class="oncolourcolumn">$desc</td>
						<td class="oncolourcolumn">$name</td>
						<td class="oncolourcolumn">$type</td>
						<td class="oncolourcolumn">$start_date</td>
						<td class="oncolourcolumn">$end_date</td>
					</tr>
HTML;
				echo $html;
			}
		} else {
			echo "<tr><td align='center' class='oncolourcolumn'>- No contracts are due to expire within the next 3 months-</td></tr>";
		}
?>
			</table>
		</td>
	</tr>
</table>
<br>