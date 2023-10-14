<?php

$xml_head = <<<DOCUMENTHEAD
  <page />
 	<table width="100%" border="t,b">
 	<tr>
 		<td width="25%" bgcolor="5">Consultant Name</td>
 		<td width="20%" bgcolor="5">Company</td>
 		<td width="25%" bgcolor="5">Type of Consultant</td>
 		<td width="30%" bgcolor="5">Current Contracts</td>
  	</tr>
 	</table>

DOCUMENTHEAD;

 $xml_main .= <<<CONSULTANTDOCUMENT

 	<table width="100%" border="t,b">

 	<tr>
 		<td width="25%">$d[consultant]</td>
 		<td width="20%">$d[company]</td>
 		<td width="25%">$d[type_desc]</td>
 		<td align="center" width="30%">$d[total_agreements]</td>
 	</tr>
 </table>

CONSULTANTDOCUMENT;

?>