<?php
$rept_date = date('d M Y');

$xml_cover = <<<COVER

<table border="0" width="100%">

	<tr>
		<td>
		<img src="docgen/images/header.jpg" width="190" height="33" wrap="no" align="left" border="0" left="-2" top="-2" anchor="INCELL" />
		</td>
	</tr>

	<tr>
		<td>
			<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
			<p align="center"/><font size="24" color="#000000" align="center">Projects Register</font>
			<p align="center"/><br /><font size="26" color="#000000" align="center"><b>Performance Indicator Report</b></font>
			<p align="center"/><br /><br /><font size="16" color="#000000" align="center"><i>Generated on $rept_date</i></font>
			<br /><br />
			<p align="center"/><br /><br /><font size="20" color="#000000" align="center">Council on Higher Education</font>
			<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
		</td>
	</tr>

	<tr>
		<td valign="bottom"><img src="docgen/images/footer.jpg" width="190" height="5" wrap="no" border="0" left="-2" top="1" anchor="INCELL" /></td>
	</tr>

</table>

COVER;
?>