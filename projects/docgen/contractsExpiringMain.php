<?php

 $xml_head = <<<DOCUMENTHEAD

 	<table width="100%" border="t,b">
 	<tr>
 		<td width="25%" bgcolor="5">Contract Description</td>
 		<td width="20%" bgcolor="5">Consultant Name</td>
 		<td width="18%" bgcolor="5">Type of Consultant</td>
 		<td width="18%" bgcolor="5">CHE Supervisor</td>
 		<td width="15%" bgcolor="5">Start Date</td>
 		<td width="15%" bgcolor="5">End Date</td>
 	</tr>
 	</table>

DOCUMENTHEAD;

 $xml_main .= <<<CONTRACTSDOCUMENT

 	<table width="100%" border="t,b">

 	<tr>
 		<td width="25%">$d[description]</td>
 		<td width="20%">$d[consultant]</td>
 		<td width="18%">$d[type_desc]</td>
 		<td width="18%">$d[che_supervisor]</td>
 		<td width="15%">$d[start_date]</td>
 		<td width="15%">$d[end_date]</td>
 	</tr>
 </table>


CONTRACTSDOCUMENT;
?>

