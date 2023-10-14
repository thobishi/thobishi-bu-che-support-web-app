<?php

$xml_head = <<<DOCUMENTHEAD

    <p><font size="12" color="#454444"><b>Performance Rating</b></font></p>

     <table width="100%" border="t,b,l,r">
 	<tr>
 	    <td width="11%" bgcolor="5">Date</td>
 	    <td width="23%" bgcolor="5">Rater</td>
 	    <td width="14%" bgcolor="5">Milestones/Deadlines</td>
	    <td width="17%" bgcolor="5">Meeting the Requirements</td>
 	    <td width="15%" bgcolor="5">Quality of Work</td>
 	    <td width="20%" bgcolor="5">Comments</td>
  	</tr>
       </table>

DOCUMENTHEAD;

if ($numrec > 0){

 $xml .= <<<PERFORMANCEDOCUMENT

 	<table width="100%" border="t,b,l,r">

 	<tr>
	   <td width="11%">$d[date]</td>
	   <td width="23%">$d[user]</td>
	   <td width="14%">$d[deliverydate_deadlines]</td>
	   <td width="17%">$d[meeting_requirements]</td>
	   <td width="15%">$d[quality_work]</td>
	   <td width="20%">$d[CHEcomment]</td>
 	</tr>
 </table>

PERFORMANCEDOCUMENT;

 } else {

  $xml .= <<<PERFORMANCECOMMENT

	<table width="100%" border="t,b,l,r">

	<tr>
		<td colspan="6"><font size="10" color="#000000">$noData</font></td>
	</tr>

	</table>

PERFORMANCECOMMENT;

}

 ?>