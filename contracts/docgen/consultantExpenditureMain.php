<?php
$xml_head = <<<XMLHEAD
	<table width="100%" border="t,b">
	<tr>
					<td width="25%" bgcolor="5">Consultant type</td>
					<td width="20%" bgcolor="5">Consultant name</td>
					<td width="20%" bgcolor="5">Contract<br />Description</td>
					<td width="20%" bgcolor="5">Manager</td>
					<td width="15%" bgcolor="5">Start date</td>
					<td width="15%" bgcolor="5">Completion <br />date</td>
					<td width="15%" bgcolor="5">Budget (R)</td>
					<td width="15%" bgcolor="5">YTD <br />Expenses</td>
					<td width="15%" bgcolor="5">Contract<br />Status</td>
	</tr>
	</table>
XMLHEAD;

$xml_main .= <<<MAIN

	<table width="100%" border="t,b">

	<tr>
					<td width="25%">$d[type]</td>
					<td width="20%">$d[consultant]</td>
					<td width="20%">$d[description]</td>
					<td width="20%">$supstr</td>
					<td width="15%">$d[start_date]</td>
					<td width="15%">$d[end_date]</td>
					<td width="15%">$d[budget]</td>
					<td width="15%">$d[calcexp]</td>
					<td width="15%">$d[status]</td>
	</tr>

	</table>

MAIN;
?>