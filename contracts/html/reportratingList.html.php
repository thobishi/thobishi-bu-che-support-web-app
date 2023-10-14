<br>
<?php
	
	$deliverySearch = readPOST("deliverySearch","");
	$meetingSearch = readPOST("meetingSearch","");
	$qualitySearch = readPOST("qualitySearch","");


	$whr = "";

	$whr_arr = array();


	if ($deliverySearch > "0") array_push($whr_arr,"oc.deliverydate_deadlines = '".$deliverySearch."'");
    if ($meetingSearch > "0") array_push($whr_arr,"oc.meeting_requirements = '".$meetingSearch."'");
	if ($qualitySearch > "0") array_push($whr_arr,"oc.quality_work = '".$qualitySearch."'");

	if (count($whr_arr)>0) $whr ="WHERE"." ". implode (' AND ', $whr_arr);





	$main_sql = <<<MAINSQL
		 SELECT oc.deliverydate_deadlines,oc.meeting_requirements,
		 oc.quality_work, c.type,
		 CONCAT(c.name," ",c.surname) as consultant,
		 c.company,
		 CONCAT(us.name, " " , us.surname," ", us.email," ", us.contact_nr) AS supervisor,
		 ca.description
		 FROM owners_comments oc
		 LEFT JOIN d_consultant_agreements ca ON oc.agreement_ref = ca.agreement_id
		 LEFT JOIN d_consultants c ON ca.consultant_ref = c.consultant_id
		 LEFT JOIN users us ON oc.user_ref = us.user_id
		 $whr
	     ORDER BY ca.description
MAINSQL;




?>

<table border='0' width="95%" cellpadding="2" cellspacing="2" align="center">
	<tr>
		<td>
			<span class="loud">Reports: List of Ratings</span>
			<hr>
		</td>
	</tr>
</table>

<br>
<table border='0' width="95%" cellpadding="2" cellspacing="2" align="center">
<tr>
	<td align="center" colspan="3"><b>Refine your search to get relevant reports.</b></td>
</tr>

<tr>

	<td class="specialb" align="right" colspan="2">Delivery Deadlines</td>
	<td>
		<?php 
		$this->formFields["deliverySearch"]->fieldValue = $deliverySearch;
		$this->showField("deliverySearch");
		?>
	</td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td class="specialb" align="right">Meeting Requirements</td>
	<td>
		<?php 
			$this->formFields["meetingSearch"]->fieldValue = $meetingSearch;
			$this->showField("meetingSearch");
		?>
	</td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td class="specialb" align="right">Quality Work</td>
	<td>
		<?php 
			$this->formFields["qualitySearch"]->fieldValue = $qualitySearch;
			$this->showField("qualitySearch");
		?>
	</td>
</tr>

<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td><input type="submit" class="btn" name="submitButton" value="Create Report" onClick="javascript:moveto('stay');"></td>
	<td><input class="btn" type="button" name="Clear" value="Clear Fields" onclick="clearFields()"></td>
</tr>
</table>
<br>

<table border='0' width="80%" cellpadding="2" cellspacing="2" align="center">


	<tr>
		<td class="specialb">
			<?php 

					$doc = new octoDocGen ("reviewPerformanceRatingsMake","deliverySearch=$deliverySearch&meetingSearch=$meetingSearch&qualitySearch=$qualitySearch");
					$doc->url ("Performance Rating Report");

			?>
		</td>
	</tr>
	<tr>
			<td class="speciali">
				Detail report on performance ratings and comments of contracts with the Council for Higher Education.
			</td>
	</tr>


</table>
<br>
<script>
	function clearFields(){
		document.defaultFrm.deliverySearch.options[0].selected=true;
		document.defaultFrm.meetingSearch.options[0].selected=true;
		document.defaultFrm.qualitySearch.options[0].selected=true;		
	}
</script>



