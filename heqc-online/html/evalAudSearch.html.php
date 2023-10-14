<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td colspan="6"><span class="loud">Search for Evaluators or Auditors:</span></td>
</tr>
<tr>
	<td colspan="6">&nbsp;</td>
</tr>
<tr>
	<td align="right"><b>Available:</b>&nbsp;</td>
	<td class="oncolour"><?php $this->showField("active") ?></td>
	<td align="right"><b>Full/Part Time:</b>&nbsp;</td>
	<td class="oncolour"><?php $this->showField("Full_part") ?></td>	
	<td align="right"><b>Province:</b>&nbsp;</td>
	<td class="oncolour"><?php $this->showField("Province") ?></td>	
</tr>
<tr>
	<td align="right"><b>Gender:</b>&nbsp;</td>
	<td class="oncolour"><?php $this->showField("Gender") ?></td>
	<td align="right"><b>Race:</b>&nbsp;</td>
	<td class="oncolour"><?php $this->showField("Race") ?></td>
	<td align="right"><b>Disability:</b>&nbsp;</td>
	<td class="oncolour"><?php $this->showField("Disability") ?></td>		
</tr>
<tr>
	<?php /*?><td align="right"><b>A-Rated:</b>&nbsp;</td>
	<td class="oncolour"><?php $this->showField("A_rated") ?></td>
	<td align="right"><b>Sector:</b>&nbsp;</td>
	<td class="oncolour"><?php $this->showField("Eval_sector_ref") ?></td>
	<td align="right"><b>Organisation Type:</b>&nbsp;</td>
	<td class="oncolour"><?php $this->showField("Organisation_type_ref") ?></td><?php */?>
</tr>
<tr>
	<td colspan="6">&nbsp;</td>
</tr>
<tr>
	<td align="right"><b>Highest Qualification:</b>&nbsp;</td>
	<td class="oncolour"><?php $this->showField("qualifications_ref") ?></td>
	<td align="right"><b>Teaching Experience:</b>&nbsp;</td>
	<td class="oncolour"><?php $this->showField("Teaching_experience") ?></td>
	<td align="right"><b>Research Experience:</b>&nbsp;</td>
	<td class="oncolour"><?php $this->showField("Research_expereince") ?></td>
</tr>
<tr>
	<td colspan="6">&nbsp;</td>
</tr>
<tr>
	<td colspan="2" align="right"><b>Search for:</b></td>
	<td colspan="4" align="left" class="oncolour">
		<?php $this->showField("National_Review_Evaluator") ?>&nbsp;<b>National Review Evaluator</b>&nbsp;&nbsp;&nbsp;&nbsp;
		<?php $this->showField("Auditor") ?>&nbsp;<b>Auditor</b>&nbsp;&nbsp;&nbsp;&nbsp;
		<?php $this->showField("Evaluator") ?>&nbsp;<b>Evaluator</b>&nbsp;
		<?php $this->showField("Institutional_reviewer") ?>&nbsp;<b>Institutional Reviewer</b>&nbsp;
	</td>
</tr>
<tr>
	<td colspan="2" align="right"><b>Institution:</b>&nbsp;</td>
	<td class="oncolour" colspan="4"><?php $this->showField("employer_ref") ?></td>
</tr>
<?php /*?>
<tr>
	<td colspan="2" align="right"><b>Institution Type:</b>&nbsp;</td>
	<td class="oncolour" colspan="4"><?php $this->showField("Employer_type_ref") ?></td>
</tr>
<tr>
	<td colspan="2" align="right"><b>Historical Status:</b>&nbsp;</td>
	<td class="oncolour" colspan="4"><?php $this->showField("historical_status_ref") ?></td>
</tr>
<tr>
	<td colspan="2" align="right"><b>Merge Status:</b>&nbsp;</td>
	<td class="oncolour" colspan="4"><?php $this->showField("merged_status_ref") ?></td>
</tr>
<tr>
<td colspan="2" align="right"><b>ETQA:</b>&nbsp;</td>
	<td class="oncolour" colspan="4"><?php $this->showField("ETQA_ref") ?></td>
</tr>
<?php */?>
<tr>
	<td colspan="2" align="right"><b>Main CESM classification:</b>&nbsp;</td>
	<td class="oncolour" colspan="4">
	<?php	$this->showField("CESM_code1") ?>
	</td>
</tr>
<tr>
	<td colspan="2" align="right"><b>Sub CESM classification:</b>&nbsp;</td>
	<td class="oncolour" colspan="4">
	<?php	$this->showField("CESM_code2") ?>
	</td>
</tr>
<tr>
	<td colspan="6">&nbsp;</td>
</tr>
<tr>
	<td colspan="2" align="right"><b>Search:</b> (by Name)</td>
	<td class="oncolour" colspan="4"><?php $this->showField("searchText") ?></td>
</tr>
<tr>
	<td colspan="2" align="right"><b>Search:</b> (by Job Title)</td>
	<td class="oncolour" colspan="4"><?php $this->showField("searchText1") ?></td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
	<td colspan="4">
	<input type="button" class="btn" value="Search" onClick="moveto('stay');">
	</td>
</tr>
<tr>
	<td colspan="6">&nbsp;</td>
</tr>
</table>
<?php 
if (isset($_POST["searchText"])){
	$search = new evalSearch();
	$iframeText = "";

	$SQL = $search->buildSQL ("Persnr, Names, Surname, Work_Number, E_mail", $_POST);;

	if ($rs = mysqli_query($this->getDatabaseConnection(), $SQL)) {
	    $iframeText .= "<table width='95%' align='center'>\n";
		if (mysqli_num_rows($rs) > 0){
			$iframeText .= "<tr><td colspan=\"3\"><b>Search Results for: <br></b>". implode('<br>',$search->searchCrit) ."</td><td align=\"right\"><a href=\"docgen/xls_evaluatorContactDetails.php?".$search->getUrlQuery()."\" target\"_blank\">Download report in Excel</a>&nbsp;<b>Total: ".mysqli_num_rows($rs)."</b></td></tr>";
			$iframeText .= "<tr><td colspan=\"4\">&nbsp;</td>";
			$iframeText .= "<tr><td><b>INFO</b></td><td><b>NAME</b></td><td><b>TEL NO</b></td><td><b>EMAIL:</b></td></tr>\n";
		    while ($row = mysqli_fetch_array($rs)) {
				$iframeText .= "<tr onmouseover='this.bgColor=\"#EAEFF5\"' onmouseout='this.bgColor=\"#FFFFFF\"'><td valign='top'><a href='javascript:showInfo(".$row["Persnr"].");'><img border='0' src='images/info.png'></a></td>\n";
				$iframeText .= "<td valign='top'><a href='javascript:showInfo(".$row["Persnr"].");'>" .$row["Surname"]. ", " .$row["Names"] . "</a></td>\n";
				$iframeText .= "<td valign='top'>". $row["Work_Number"] ."</td>\n";
				$iframeText .= "<td valign='top'>".$row["E_mail"]."</td></tr>\n";
			}
		}else {
			$iframeText .= "<tr><td colspan='2' align='center'><b>No results found!</b></td></tr>\n";
		}
	    $iframeText .= "</table>\n";
	}
	echo $iframeText;
}
?>

