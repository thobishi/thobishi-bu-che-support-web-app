<br>
<table width="99%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr><td>
<?php 
	$dateFrom   = (isset($_POST['dateFrom']) && $_POST['dateFrom'] != "") ? $_POST['dateFrom'] : '0';
	$dateTo     = (isset($_POST['dateTo']) && $_POST['dateTo'] != "") ? $_POST['dateTo'] : '0'; 
	$searchText = (isset($_POST['searchText']) && $_POST['searchText'] != "") ? $_POST['searchText'] : "";
	$searchFor = (isset($_POST['searchFor']) && $_POST['searchFor'] != "") ? $_POST['searchFor'] : "";
	$institution = (isset($_POST['institution']) && $_POST['institution'] != "") ? $_POST['institution'] : "";
	$mode_delivery = (isset($_POST['mode_delivery']) && $_POST['mode_delivery'] != "") ? $_POST['mode_delivery'] : "";
?> 

<table width="100%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td colspan="4"><span class="loud">Applications submitted to the HEQC via the HEQC-online system:</span></td>
</tr>

<tr>
	<td colspan='4'>
	<br>
	To search for:
	<ul>
		<li>applications submitted to CHE <b>from a certain date</b>, enter the date in the "From:" date field.</li>
		<li>all applications submitted <b>up until a certain date</b>, enter the date in the "To:" date field.</li>
		<li>applications submitted in a certain <b>date range</b>, fill in both the "From" or "To" submission date fields.</li>
		<li>a <b>specific HEQC reference number</b>, enter either all or part of the reference number in the relevant field. In this way, you are able to search for all applications submitted by a specific institution - by entering the reference code of the institution (e.g. PR064).</li>
		<li>a <b>specific institution</b>, select the insitution from the drop down list.</li>
		<li><b>processed or unprocessed applications</b>, select your preference from the drop-down box.</li>
		<li><b>ALL applications</b> submitted through the HEQC-online system, click "Search" without entering anything into any of the fields.</li>
	</ul>
	Please note that the applications are sorted according to the date of submission.
	<br><br>
	<hr><br></td>
</tr>

<tr align="right">
	<td width="25%">
		Select submission date -
	</td>
	<td width="10%">From:</td>
	<td align="left"> <?php $this->showField('dateFrom');	?></td>
</tr>
<tr align="right">
	<td>&nbsp;</td>
	<td>To: </td>
	<td align="left"><?php $this->showField('dateTo');	?></td>
</tr>


<tr align="right">
	<td colspan="2">Search for HEQC reference number:	</td>
	<td align="left"><?php $this->showField("searchText"); ?></td>
</tr>

<tr align="right">
	<td colspan="2">Search by institution:	</td>
	<td align="left"><?php $this->showField("institution"); ?></td>
</tr>

<tr align="right">
	<td colspan="2">Search for</td>
	<td align="left"><?php $this->showField("searchFor"); ?> applications</td>
</tr>
<tr align="right">
	<td colspan="2">Search by mode of delivery:	</td>
	<td align="left"><?php $this->showField("mode_delivery"); ?></td>
</tr>
<tr>
	<td align="right" colspan="2"><br></td>
	<td>
		<input type="submit" class="btn" name="submitButton" value="Search" onClick="javascript:moveto('_report_submittedApplications');">
	</td>
</tr>
</table>
<br>

<?php 

ini_set('memory_limit','-1');
if (isset($_POST['submitButton'])){
//if (isset($_POST['dateFrom'])){

	$this->reportSubmittedApplications($searchText,$dateFrom,$dateTo, $searchFor, "", "", $institution, "", $mode_delivery);

} // end if (isset($_POST['submitButton'])
?>
<br>
</td></tr>
</table>
