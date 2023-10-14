<?php


   	$xml_head = <<<DOCUMENTHEAD

 	<table width="100%" border="t,b,l,r">
 	<tr>
 	    <td width="20%" bgcolor="5">Contract Description</td>
 		<td width="25%" bgcolor="5">Consultant Name</td>
 		<td width="20%" bgcolor="5">Company</td>
 		<td width="20%" bgcolor="5">Type of Consultant</td>
 		<td width="28%" bgcolor="5">CHE Supervisor</td>
 		<td width="20%" bgcolor="5">Delivery Date Deadlines</td>
 	</tr>
 	</table>

DOCUMENTHEAD;

 $xml_main .= <<<COMMENTDOCUMENT

 	<table width="100%" border="t,b,l,r">

 	<tr>
 	   	<td width="20%">$d[description]</td>
 		<td width="25%">$d[consultant]</td>
 		<td width="20%">$d[company]</td>
 		<td width="20%">$d[type_desc]</td>
 		<td width="28%">$d[supervisor]</td>
 		<td width="20%">$d[deliverydate_deadlines]</td>
 	</tr>
 </table>

COMMENTDOCUMENT;

?>