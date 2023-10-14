<?php



$xml_main .= <<<MAIN
	<page/>

	<table width="100%" border="0">
		<tr>
			<td><font size="12" color="#454444"><b>$d[consultant] ($d[status])</b></font></td>
		</tr>
	</table>

	<table width="100%" border="1">
	<tr>
		<td width="25%">Type of consultant</td>
		<td width="35%"><font size="10" color="#000000"><b>$d[type]</b></font></td>
		<td width="20%">Race</td>
		<td width="20%"><font size="10" color="#000000"><b>$d[race]</b></font></td>
	</tr>
	<tr>
		<td>Company</td>
		<td><font size="10" color="#000000"><b>$d[company]</b></font></td>
		<td>Gender</td>
		<td><font size="10" color="#000000"><b>$d[gender]</b></font></td>
	</tr>
	<tr>
		<td>Email</td>
		<td><font size="10" color="#000000"><b>$d[email]</b></font></td>
		<td>Telephone no</td>
		<td><font size="10" color="#000000"><b>$d[contact_nr]</b></font></td>
	</tr>
	<tr>
		<td>Postal address</td>
		<td colspan="3"><font size="10" color="#000000"><b>$d[postal_address]</b></font></td>
	</tr>
 </table>

 <br/>

<table width="100%" border="0">
	<tr>
		<td><font size="12" color="#454444"><b>Contract: $d[description] ($d[status])</b></font></td>
	</tr>
	</table>


	<table width="100%" border="1">
	<tr>
	    <td>Contract ID Number</td>
		<td colspan="3"><font size="10" color="#000000"><b>$d[idnumber]</b></font></td>
	</tr>
	<tr>
		<td width="25%">Start date</td>
		<td width="25%"><font size="10" color="#000000"><b>$d[start_date]</b></font></td>
		<td width="25%">End date</td>
		<td width="25%"><font size="10" color="#000000"><b>$d[end_date]</b></font></td>
	</tr>
	<tr>
		<td>Manager Details</td>
		<td colspan="3"><font size="10" color="#000000"><b>$supstr</b></font></td>
	</tr>
	<tr>
		<td>Service Delivery</td>
		<td colspan="3"><font size="10" color="#000000"><b>$d[l_service_delivery]</b></font></td>
	</tr>
	<tr>
		<td>Fees</td>
		<td colspan="3"><font size="10" color="#000000"><b>$d[payment_rate]</b></font></td>
	</tr>
	<tr>
		<td>Duration</td>
		<td colspan="3"><font size="10" color="#000000"><b>$d[duration]</b></font></td>
	</tr>
	<tr>
		<td>Budget (R)</td>
		<td colspan="3"><font size="10" color="#000000"><b>$d[budget]</b></font></td>
	</tr>
 </table>

MAIN;
?>