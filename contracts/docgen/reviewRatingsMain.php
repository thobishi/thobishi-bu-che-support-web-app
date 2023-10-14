<?php

$xml_head = <<<DOCUMENTHEAD

 	<table width="100%" border="t,b,l,r">
 	<tr>

 		<td width="20%" bgcolor="5">Contract Description</td>
 		<td width="24%" bgcolor="5">Consultant</td>
 		<td width="30%" bgcolor="5">Manager</td>
 		<td width="11%" bgcolor="5">Date</td>
 		<td width="14%" bgcolor="5">Milestones/Deadlines</td>
		<td width="17%" bgcolor="5">Meeting the Requirements</td>
 		<td width="15%" bgcolor="5">Quality of Work</td>
 		<td width="28%" bgcolor="5">Comments</td>

  	</tr>
 	</table>

DOCUMENTHEAD;

 $xml_main .= <<<COMMENTDOCUMENT

 	<table width="100%" border="t,b,l,r">

 	<tr>

 		<td width="20%">$d[description]</td>
 		<td width="24%">$d[consultant] <b>$d[company]</b></td>
 		<td width="30%">$supstr</td>
 		<td width="11%">$d[date]</td>
 		<td width="14%">$d[deliverydate_deadlines]</td>
		<td width="17%">$d[meeting_requirements]</td>
 		<td width="15%">$d[quality_work]</td>
 		<td width="28%">$d[CHEcomment]</td>
 	</tr>
 </table>

COMMENTDOCUMENT;

?>