<br>
<table width="99%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr><td>
<?php 

	$ref_no = (isset($_POST['searchText']) && $_POST['searchText'] != "") ? $_POST['searchText'] : "";
	$institution = (isset($_POST['institution']) && $_POST['institution'] != "") ? $_POST['institution'] : "";
	$reacc_ind = (isset($_POST['reacc_ind']) && $_POST['reacc_ind'] != "") ? $_POST['reacc_ind'] : "";

?>
<table width="100%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td colspan="3"><span class="loud">Application Audit Trail:</span></td>
</tr>

<tr>
	<td colspan='3'>
	<br>
	This report allows for detailed tracking of an application. <br>
	Please note that audit trail data has been collected from 8 December 2006.  You will ONLY be able to track the full history of applications that were submitted from this date on. Processes which look like they have originated from the HEQC (e.g. with only one entry, and this entry is an HEQC employee) were processed before December 2006, and processing continued only afterwards.
	<br>
	<ul>
	 	<li>To view the audit trail for all applications from a particular institution, select it from the drop down box and click "Search".</li>
	 	<li>To view the audit trail for re-accreditation applications check the re-accreditation box.</li>
	 </ul>
	</td>
</tr>

<tr align="right">
	<td width="25%">Search for HEQC reference number:	</td>
	<td align="left" width="15%">&nbsp;<?php $this->showField("searchText"); ?>
		&nbsp;&nbsp;<b>Check to indicate re-accreditation</b>&nbsp;&nbsp;<?php $this->showField("reacc_ind");?></td>
</tr>
<tr align="right">
	<td>Search by institution:	</td>
	<td align="left"><?php $this->showField("institution"); ?></td>
	<td align="left"><input type="submit" class="btn" name="submitButton" value="Search" onClick="moveto('stay');"></td>
</tr>

</table>
<br>
<hr>
<br>

<?php 

if (isset($_POST['submitButton']) || $ref_no > "" || ($institution != "" && $institution > 0) )
//need javascript to check that ONLY one button (not 0 or 2) is selected
{
	$this->reportAuditTrail($ref_no, $institution, $reacc_ind);
}
?>
<br>
</td></tr>
</table>