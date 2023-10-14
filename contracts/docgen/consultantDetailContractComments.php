<?php

$xml_head = <<<COMMENTHEAD

    <p><font size="12" color="#454444"><b>Comments</b></font></p>

	<table width="100%" border="t,b,l,r">

	<tr>
		<td width="20%" bgcolor="5"><font size="10" color="#000000"><b>Comment date</b></font></td>
		<td width="80%" bgcolor="5"><font size="10" color="#000000"><b>Comment</b></font></td>
	</tr>

	</table>

COMMENTHEAD;

if ($numrec > 0){

 $xml .= <<<CONTRACTCOMMENT

	<table width="100%" border="t,b,l,r">

	<tr>
		<td width="20%"><font size="10" color="#000000">$d[comment_date]</font></td>
		<td width="80%"><font size="10" color="#000000">$d[comment]</font></td>
	</tr>

	</table>

CONTRACTCOMMENT;

 } else {

  $xml .= <<<CONTRACTCOMMENT

	<table width="100%" border="t,b,l,r">

	<tr>
		<td colspan="2"><font size="10" color="#000000">$noData</font></td>
	</tr>

	</table>

CONTRACTCOMMENT;


 }

?>