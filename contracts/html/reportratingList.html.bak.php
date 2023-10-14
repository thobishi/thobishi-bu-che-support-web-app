<br>
<?php

	$typeSearch = readPOST("typeSearch",0);
	$companySearch = readPOST("companySearch","");
	$deliverySearch = readPOST("deliverySearch","");
	$meetingSearch = readPOST("meetingSearch","");
	$qualitySearch = readPOST("qualitySearch","");
	$cheOwner = readPOST("cheOwner","");

	$consult_whr = "";
	$contract_whr = "";
	$join ="";
	$AND ="";
	$delivery = "";
	$quality = "";
	$meeting = "";
	$delivery_join ="";

	$consult_whr_arr = array();
	$contract_whr_arr = array();

	if ($deliverySearch > "0") array_push($consult_whr_arr,"deliverydate_deadlines like '".$deliverySearch."%'");
    if ($meetingSearch > "0") array_push($consult_whr_arr,"meeting_requirements like '".$meetingSearch."%'");
	if ($qualitySearch > "0") array_push($consult_whr_arr,"quality_work like '".$qualitySearch."%'");




   //Query for Delivery deadline


  	 $delivery = "";
	 	if($deliverySearch >''){
	 		$delivery = " AND oc.deliverydate_deadlines like '".$deliverySearch."%'";
	  }

	$main_sql = <<<MAINSQL
		 SELECT oc.deliverydate_deadlines, c.type,
		 CONCAT(c.name," ",c.surname) as consultant,
		 c.company,
		 CONCAT(us.name, " " , us.surname," ", us.email," ", us.contact_nr) AS supervisor,
		 ca.description
		 FROM owners_comments oc
		 LEFT JOIN d_consultant_agreements ca ON oc.agreement_ref = ca.agreement_id
		 LEFT JOIN d_consultants c ON ca.consultant_ref = c.consultant_id
		 LEFT JOIN users us ON oc.user_ref = us.user_id
	     WHERE oc.meeting_requirements != "0"
	     $delivery
	     ORDER BY ca.description
MAINSQL;
echo ($main_sql);

	// Query for Meeting Requirements
	if ($meetingSearch > ''){
        $meeting = "AND oc.meeting_requirements like '%".$meetingSearch."%'";
     }

	$meeting_sql = <<<MEETINGSQL
			 SELECT oc.meeting_requirements, c.type,
			 c.name,c.surname,
			 CONCAT(us.name, " " , us.surname," ", us.email," ", us.contact_nr) AS supervisor,
			 c.company, ca.description
			 FROM owners_comments oc
			 LEFT JOIN d_consultant_agreements ca ON oc.agreement_ref = ca.agreement_id
		     LEFT JOIN d_consultants c ON ca.consultant_ref = c.consultant_id
		     LEFT JOIN users us ON oc.user_ref = us.user_id
		     WHERE oc.meeting_requirements != "0"
				   $meeting
		     ORDER BY ca.description
MEETINGSQL;


	// Query for Quality Requirements
	if ($qualitySearch > ''){
	        $quality = "AND oc.quality_work like '%".$qualitySearch."%'";
	     }

		$quality_sql = <<<QUALITYSQL
			 SELECT oc.quality_work, c.type,
			 c.name,c.surname,
			 CONCAT(us.name, " " , us.surname," ", us.email," ", us.contact_nr) AS supervisor,
			 c.company, ca.description
			 FROM owners_comments oc
			 LEFT JOIN d_consultant_agreements ca ON oc.agreement_ref = ca.agreement_id
			 LEFT JOIN d_consultants c ON ca.consultant_ref = c.consultant_id
			 LEFT JOIN users us ON oc.user_ref = us.user_id
			 WHERE oc.quality_work != "0"
			       $quality
			 ORDER BY ca.description
QUALITYSQL;


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
</tr>
</table>
<br>

<table border='0' width="80%" cellpadding="2" cellspacing="2" align="center">


	<tr>
		<td class="specialb">
			<?php 

					$doc = new octoDocGen ("listRatingsMake","deliverySearch=$deliverySearch&meetingSearch=$meetingSearch&qualitySearch=$qualitySearch");
					$doc->url ("Delivery Date Deadline Report");

			?>
		</td>
	</tr>
	<tr>
		<td class="speciali">
			Review list of delivery date deadlines rating/s on contracts with the Council for Higher Education.
		</td>
	</tr>
	<tr>
			<td class="speciali">&nbsp;</td>
	</tr>
	<tr>
		<td class="specialb">
			<?php 
					$doc = new octoDocGen ("meetingRatingsMake","deliverySearch=$deliverySearch&meetingSearch=$meetingSearch&qualitySearch=$qualitySearch");
					$doc->url ("Meeting Requirements Report");


			?>
		</td>
	</tr>
	<tr>
		<td class="speciali">
			Review list of meeting requirements rating/s on contracts with the Council for Higher Education.
		</td>
	</tr>
	<tr>
				<td class="speciali">&nbsp;</td>
	</tr>

		<tr>
			<td class="specialb">
				<?php 
						$doc = new octoDocGen ("qualityRatingsMake","deliverySearch=$deliverySearch&meetingSearch=$meetingSearch&qualitySearch=$qualitySearch");
						$doc->url ("Quality Work Report");

				?>
			</td>
		</tr>
		<tr>
			<td class="speciali">
				Review list of quality work rating/s on contracts with the Council for Higher Education.
			</td>
		</tr>
		<tr>
			<td class="speciali">&nbsp;</td>
	 </tr>

</table>
<br>




