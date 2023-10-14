<?php

$xml_c1 .= <<<CHILD1
  <br />
  <br />

	<table width="100%" border="0">
	<tr>
		<td><font size="12" color="#454444"><b>Contract: $d[description] ($d[status])</b></font></td>
	</tr>
	</table>


	<table width="100%" border="1">

	<tr>
		<td width="25%">Start date</td>
		<td width="25%"><font size="10" color="#000000"><b>$d[start_date]</b></font></td>
		<td width="25%" bgcolor="5">End date</td>
		<td width="25%"><font size="10" color="#000000"><b>$d[end_date]</b></font></td>
	</tr>
	<tr>
		<td>Supervisor Details</td>
		<td colspan="3"><font size="10" color="#000000"><b>$supstr</b></font></td>
	</tr>
	<tr>
		<td>Service Delivery</td>
		<td colspan="3"><font size="10" color="#000000"><b>$d[l_service_delivery]</b></font></td>
	</tr>
	<tr>
		<td>Payment Rate</td>
		<td colspan="3"><font size="10" color="#000000"><b>$d[payment_rate]</b></font></td>
	</tr>
	<tr>
		<td>Duration</td>
		<td colspan="3"><font size="10" color="#000000"><b>$d[duration]</b></font></td>
	</tr>
	<tr>
		<td>Budget</td>
		<td colspan="3"><font size="10" color="#000000"><b>$d[budget]</b></font></td>
	</tr>
 </table>

CHILD1;



?>