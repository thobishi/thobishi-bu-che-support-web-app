<?php

$xml_head = <<<DOCUMENTHEAD

     <p><font size="12" color="#454444"><b>Documents</b></font></p>

	<table width="100%" border="t,b,l,r">

	<tr>
		<td width="15%" bgcolor="5"><font size="10" color="#000000"><b>Date</b></font></td>
		<td width="25%" bgcolor="5"><font size="10" color="#000000"><b>Type</b></font></td>
		<td width="30%" bgcolor="5"><font size="10" color="#000000"><b>Title</b></font></td>
		<td width="30%" bgcolor="5"><font size="10" color="#000000"><b>Name</b></font></td>
	</tr>

	</table>

DOCUMENTHEAD;

if ($numrec > 0){

$xml .= <<<CONTRACTDOCUMENT

	<table width="100%" border="t,b,l,r">

	<tr>
		<td width="15%"><font size="10" color="#000000">$d[date]</font></td>
		<td width="25%"><font size="10" color="#000000">$d[document_type]</font></td>
		<td width="30%"><font size="10" color="#000000">$d[document_title]</font></td>
		<td width="30%"><font size="10" color="#000000">$d[document_name]</font></td>
	</tr>

	</table>

CONTRACTDOCUMENT;

} else {

  $xml .= <<<CONTRACTCOMMENT

	<table width="100%" border="t,b,l,r">

		<tr>
			<td colspan="4"><font size="10" color="#000000">$noData</font></td>
		</tr>

	</table>

CONTRACTCOMMENT;


 }

?>