<?php

$fld_order = readPost('report_order','AccNumber');
$fld_order = ($fld_order > "") ? $fld_order : 'AccNumber';
$this->showField('report_order');

$fld_pastel_accno = readPost('report_pastel_accno');
$fld_search_descr = readPost('report_search_descr');

//$where = "AccNumber like '0215%' and upper(Description) not like '%PAYE%' and upper(Description) not like '%UIF%' and upper(Description) not like '%OPENING BALANCE%'";
//$where = " upper(Description) not like '%PAYE%' and upper(Description) not like '%UIF%' and upper(Description) not like '%OPENING BALANCE%'";

$whrArr = array("EType = 1 AND AccNumber not like '09%' AND AccNumber not like '01%' AND upper(Description) not like '%PAYE%' AND upper(Description) not like '%UIF%'");

if ($fld_pastel_accno > "") array_push($whrArr, " AccNumber like '%".$fld_pastel_accno."%'");
if ($fld_search_descr > "") array_push($whrArr, " Description like '%".$fld_search_descr."%'");

$where = implode(" AND ", $whrArr);

?>
<br>

<table border='0' width="95%" cellpadding="2" cellspacing="2" align="center">
	<tr>
		<td>
			<span class="loud">Reports: Pastel Account numbers and description</span>
			<hr>
		</td>
	</tr>
	<tr>
		<td align="center">
			Search pastel account number for: 
			<?php 
			$this->formFields["report_pastel_accno"]->fieldValue = $fld_pastel_accno;
			$this->showField("report_pastel_accno"); 
			?>
		</td>
	</tr>
	<tr>
		<td align="center">
			Search Description for: 

			<?php 
			$this->formFields["report_search_descr"]->fieldValue = $fld_search_descr;
			$this->showField("report_search_descr"); 
			?>
			<input type="Button" onclick="javascript:moveto('stay');" value="Search">
			<hr>
		</td>
	</tr>
	<tr>
		<td>
			<table border='0' width="95%" cellpadding="2" cellspacing="2" align="center">
<?php 
			//add up the number of active contracts they have? maybe link to list?
			$sql = <<< SQL
				SELECT AccNumber, Description, sum(Amount) as Amount
				FROM `pastel_ledger_transactions` 
				WHERE $where 
				GROUP BY AccNumber, Description
				ORDER BY AccNumber, Description
SQL;
// ORDER BY $fld_order
			$rs = mysqli_query($sql);
			$n_rec = mysqli_num_rows($rs);
			
			if ($n_rec > 0){
				$html =<<< HTML
					<tr><td colspan="4" align="right">Total: $n_rec</td></tr>
					<tr>
						<td class="oncolourcolumnheader"><a href="javascript:setReportOrder('AccNumber');">Pastel Account Number</a></td>
						<td class="oncolourcolumnheader"><a href="javascript:setReportOrder('Description');">Description</a></td>
						<td class="oncolourcolumnheader"><a href="javascript:setReportOrder('Description');">Amount</a></td>
						<td class="oncolourcolumnheader">Pastel extraction criteria <i><br>(Copy and paste to contract to link financial data)</i></td>
					</tr>
HTML;
			echo $html;
			while ($row = mysqli_fetch_array($rs)){
				$acc_no = $row["AccNumber"];
				$p_desc = $row["Description"];
				$p_amt = $row["Amount"];
				$p_ext = $acc_no . ":" . $p_desc;
				$html = <<< HTML
					<tr>
						<td class="oncolourcolumn">$acc_no</td>
						<td class="oncolourcolumn">$p_desc</td>
						<td class="oncolourcolumn">$p_amt</td>
						<td class="oncolourcolumn">$p_ext</td>
					</tr>
HTML;
				echo $html;
			}
		} else {
			echo "<tr><td align='center' class='oncolourcolumn'>- There are currently no pastel account numbers to report on -</td></tr>";
		}
?>
			</table>
		</td>
	</tr>
</table>
<br>