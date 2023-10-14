<?php echo $finYear = '2008/2009'; ?>
<br>
<table width="99%" border=0 align="center" cellpadding="2" cellspacing="2">
	<tr><td>

		<table width="100%" border=0 align="center" cellpadding="2" cellspacing="2">
			<tr>
				<td width="80%">
					Financial data for <b><?php echo echo $finYear; ?></b> in the Project Register will be replaced by the latest financial data drawn from Pastel LedgerTransaction table.
				</td>
			</tr>
			<tr>
				<td width="80%">
					The following criteria are used when downloading data from Pastel:
					<UL>
					<li>ALL data for a defined list of Projects is extracted. <a href="<?echo 'javascript:goto(13);'?>">Edit</a></li>
					<li></A>ALL data for a set list of line item codes is extracted.<a href="<?php echo echo 'javascript:goto(14);'?>">Edit</a></li>
					</UL>
				</td>
			</tr>
			<tr align="center">
				<td>
					<input type="submit" class="btn" name="submitButton" value="Load" onClick="moveto('stay');">
				</td>
			</tr>

		</table>

		<br>

<?
		if (isset($_POST['submitButton']))
		{
			$archive_rows = $this->archiveLedgerTransactions($finYear);
			echo "<p><b>Number of records archived:</b> <i>".$archive_rows."</i>";
			$loaded_rows  = $this->loadLedgerTransactions($finYear);
			echo "<p><b>Number of records loaded:</b> ".$loaded_rows;
		} 
?>
	</td></tr>
</table>
<br>