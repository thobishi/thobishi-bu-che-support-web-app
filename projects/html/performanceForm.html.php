<table width="95%" cellpadding=2 cellspacing=2 border=0 align="center">
<tr><td>
<br>
Please edit the performance indicator fields below and save
<br>
<br>

<table border='0'>
<tr>
<td>&nbsp;</td>
<td>

	<table border='0'>
	<tr>
		<td  valign="top" style="font:bold">Performance Indicator short title:</td>
		<td class="oncolourb"><?php echo echo $this->showField("indicator_type"); ?></td>
	</tr>
	<tr>
			<td  valign="top" style="font:bold">Performance Indicator description:</td>
			<td class="oncolourb"><?php echo echo $this->showField("indicator_desc"); ?></td>
	</tr>
	<tr>
		<td  valign="top" style="font:bold">Order in which to list:</td>
		<td class="oncolourb">
			<table>
				<tr><td style="font:italic">This order is used to determine the order that the indicators must display in all reports.  
				Intervals of 10 are used to allow new indicators to be slotted into the existing order.</td></tr>
				<tr><td><?	$this->showField("indicator_order");	?></td></tr>
			</table>
	</tr>
	<tr>
			<td  valign="top" style="font:bold">Status</td>
			<td class="oncolourb">
				<table>
					<tr><td style="font:italic">A status of active indicates that this indicator must appear in the performance indicator data capture interface and reports.
					If a performance indicator is no longer necessary then it may be made redundant by setting it to disabled.</td></tr>
					<tr><td class="oncolourb"><?php echo echo $this->showField("indicator_active_ref"); ?></td></tr>
				</table>
			</td>
	</tr>
	</table>

</td>
</tr>
</table>
<br>
</td></tr>
</table>
