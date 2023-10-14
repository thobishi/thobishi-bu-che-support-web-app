<br>
<?php

	$typeSearch = readPOST("typeSearch",0);
	$nameSearch = readPOST("nameSearch","");
	$companySearch = readPOST("companySearch","");
	$statusSearch = readPOST("statusSearch",0);
	$ratingSearch = readPOST("ratingSearch","");
	$cheSupervisor = readPOST("cheSupervisor",0);

	// Managers are authorised to view consultants that are assigned to them.
	$isManager = $this->sec_partOfGroup(3);
	if ($isManager) {
		$cheSupervisor = $this->currentUserID;
	}

	$consult_whr = "";
	$contract_whr = "";
	$msg = "";
	$line = "";
	$join = "";
	$AND = "";

	$consult_whr_arr = array();
	$contract_whr_arr = array();

	if ($typeSearch > 0) array_push($consult_whr_arr,"type = $typeSearch");
	if ($nameSearch > '') array_push($consult_whr_arr,"name like '".$nameSearch."%' OR surname like '".$nameSearch."%'");
	if ($companySearch > '') array_push($consult_whr_arr,"company like '".$companySearch."%'");
	if ($cheSupervisor > 0){
		$join = "LEFT JOIN d_consultant_agreements ON d_consultant_agreements.consultant_ref = d_consultants.consultant_id ";
		array_push($consult_whr_arr,"che_supervisor_user_ref = $cheSupervisor");
	}
	if ($statusSearch > 0) array_push($contract_whr_arr,"d_consultants.status = $statusSearch");

	if (count($consult_whr_arr) > 0) $consult_whr = "WHERE " . implode(" AND ",$consult_whr_arr);
	if (count($contract_whr_arr) > 0 && count($consult_whr_arr) > 0)
	{
		$contract_whr = " AND " . implode(" AND ",$contract_whr_arr);
	}
	elseif(count($contract_whr_arr) > 0)
	{
		$contract_whr = " WHERE d_consultants.status = $statusSearch";
	}

	$consult_order = "ORDER BY type";

	//Query for Consultants Detail Reports

	$sql = <<< SQL
		SELECT d_consultants.*
		FROM d_consultants
		$join
		$consult_whr
		$contract_whr
		$consult_order
SQL;
echo "<br>sql: ".$sql;
 	$rs = $this->searchConsult ($sql);
	$msg = (count($rs)> 0)? mysqli_num_rows($rs): "";


	//Query for Consultants Expenditure Reports

	$main_ord = "type";

	if (count($contract_whr_arr) > 0 && count($consult_whr_arr) > 0)
	{
		$contract_whr = " AND c.status = $statusSearch";
	}
	elseif(count($contract_whr_arr) > 0)
	{
		$contract_whr = " WHERE c.status = $statusSearch";
	}
	$main_sql = <<<MAINSQL
				SELECT c.*, d.*
				FROM d_consultants as c
				LEFT JOIN d_consultant_agreements as d ON (consultant_ref = consultant_id)
				$consult_whr
				$contract_whr
				ORDER BY $main_ord
MAINSQL;
echo "<br>main_sql: ".$main_sql;

	$msgER = "";
	$rsER = $this->searchConsult ($main_sql);
	$msgER = (count($rsER)> 0)? mysqli_num_rows($rsER): "";

//Query for Contracts Expiring Reports
	$type = "";
	if($typeSearch != 0){
		$type = " AND c.type = $typeSearch";
	}

	$contract_whr = " AND c.status = 1";

	if (count($contract_whr_arr) > 0 )
	{
		$contract_whr = " AND c.status = $statusSearch";
	}
     if ($cheSupervisor > 0){
	 		$AND = " AND che_supervisor_user_ref = $cheSupervisor";
	}


	$curr_date = date('Y-m-d');
	$main_ord = "type";
	$sql_contract = <<<CONTRACTSQL
		SELECT c.consultant_id,
		IF (c.type=2, c.company, CONCAT(c.name, " ", c.surname)) AS consultant,
		a.description,
		c.email,
		c.type,
		a.start_date,
		a.end_date
		FROM d_consultant_agreements AS a
		LEFT JOIN d_consultants AS c
		ON consultant_ref=consultant_id
		WHERE end_date < DATE_ADD('$curr_date', INTERVAL 3 MONTH)
		$AND
		$contract_whr
	    $type
CONTRACTSQL;
echo "<br>sql_contract: ".$sql_contract;


	$msgCR = "";
	$rsCR = $this->searchConsult ($sql_contract);
	$msgCR = (count($rsCR)> 0)? mysqli_num_rows($rsCR): "";

//Query for Review Consultants Reports
	$type = "";
		if($typeSearch != 0){
			$type = " AND c.type = $typeSearch";
	}

	$contract_whr = "1";
	if (count($contract_whr_arr) > 0 )
	{
		$contract_whr =  $statusSearch;
	}



	$main_ord = " ORDER BY consultant_ref";

	$review_sql = <<<REVIEWSQL
		SELECT c.*,
		IF (c.type=2, c.company, CONCAT(c.name, " ", c.surname)) AS consultant,
		count(d_consultant_agreements.consultant_ref) AS total_agreements,
		d_consultant_agreements.status
		FROM d_consultants AS c,
		d_consultant_agreements
		WHERE consultant_id = consultant_ref
		$AND
		AND d_consultant_agreements.status = $contract_whr
		$type
		GROUP BY consultant_ref
		$main_ord

REVIEWSQL;
echo "<br>review_sql: ".$review_sql;


	$msgRR = "";
	$rsRR = $this->searchConsult ($review_sql);
	$msgRR = (count($rsRR)> 0)? mysqli_num_rows($rsRR): "";

	//Query for comments

	$type = "";
			if($typeSearch != 0){
			 $type = " AND c.type = $typeSearch";
		}

		$contract_whr = "1";
		if (count($contract_whr_arr) > 0 )
		{
			$contract_whr =  $statusSearch;
		}


	 $main_ord = " ORDER BY consultant_ref";

$comments_sql =<<<COMMENTS
		SELECT c.*,
		IF (c.type=2, c.company, CONCAT(c.name, " ", c.surname)) AS consultant,
		owners_comments.CHEcomment, owners_comments.deliverydate_deadlines, owners_comments.meeting_requirements,
		owners_comments.quality_work, owners_comments.comment_date,
		count(d_consultant_agreements.consultant_ref) AS total_agreements,
		CONCAT(users.name, " " , users.surname," ", users.email," ", users.contact_nr) AS supervisor,
		c.type,
		d_consultant_agreements.status
		FROM d_consultants AS c,
		d_consultant_agreements
		LEFT JOIN owners_comments ON owners_comments.agreement_ref = d_consultant_agreements.agreement_id
		LEFT JOIN users ON owners_comments.user_ref = users.user_id
		WHERE consultant_id = consultant_ref
		$AND
		AND d_consultant_agreements.status = $contract_whr
		$type
		GROUP BY consultant_ref
		$main_ord
COMMENTS;
echo "<br>comments_sql: ".$comments_sql;


?>

<table border='0' width="95%" cellpadding="2" cellspacing="2" align="center">
	<tr>
		<td>
			<span class="loud">Reports: Downloadable Reports</span>
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

	<td class="specialb" align="right" colspan="2">Consultant type</td>
	<td>
		<?php 
		$this->formFields["typeSearch"]->fieldValue = $typeSearch;
		$this->showField("typeSearch");
		?>
	</td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td class="specialb" align="right">Consultant name or surname</td>
	<td>
		<?php 
			$this->formFields["nameSearch"]->fieldValue = $nameSearch;
			$this->showField("nameSearch");
		?>
	</td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td class="specialb" align="right">Company name</td>
	<td>
		<?php 
			$this->formFields["companySearch"]->fieldValue = $companySearch;
			$this->showField("companySearch");
		?>
	</td>
</tr>
<?php echo 
if (!$isManager){
?>
<tr>
	<td>&nbsp;</td>
	<td class="specialb" align="right">Supervisor</td>
	<td>
		<?php 
			$this->formFields["cheSupervisor"]->fieldValue = $cheSupervisor;
			$this->showField("cheSupervisor");
		?>
	</td>
</tr>
<?php 
}
?>
<tr>
	<td>&nbsp;</td>
	<td class="specialb" align="right">Status of contract</td>
	<td>
		<?php 
			$this->formFields["statusSearch"]->fieldValue = $statusSearch;
			$this->showField("statusSearch");
		?>
	</td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td><input type="submit" class="btn" name="submitButton" value="Search" onClick="javascript:moveto('stay');"></td>
</tr>
</table>
<br>

<table border='0' width="80%" cellpadding="2" cellspacing="2" align="center">

	<tr>
		<td>
			<?php 
				if (($typeSearch  == 0 && $nameSearch == "" && $companySearch == "" && $statusSearch == 0 && $cheSupervisor == 0 ) || count($rs) == 0)
				{
					$line = "";
				}
				else
				{
					$line = "<b>(".$msg." consultant/s)</b>";
				}
			?>
		</td>
    </tr>
	<tr>
		<td class="specialb">
			<?php 

					$doc = new octoDocGen ("consultantDetailMake","typeSearch=$typeSearch&nameSearch=$nameSearch&companySearch=$companySearch&statusSearch=$statusSearch&cheSupervisor=$cheSupervisor");
					$doc->url ("Consultants Detail Report");
					echo "&nbsp;&nbsp;&nbsp;".$line;
			?>
		</td>
	</tr>
	<tr>
		<td class="speciali">
			Detail report on each consultant and his contracts with the Council for Higher Education.
		</td>
	</tr>
	<tr>
			<td class="speciali">&nbsp;</td>
	</tr>
	<tr>
			<td>
				<?php 
					if (($typeSearch  == 0 && $nameSearch == "" && $companySearch == "" && $statusSearch == 0  && $cheSupervisor == 0) || count($rsER) == 0)
					{
						$line = "";
					}
					else
					{
						$line = "<b>(".$msgER." contract/s)</b>";
					}
				?>
			</td>
    </tr>
	<tr>
		<td class="specialb">
			<?php 

					$doc = new octoDocGen ("consultantExpenditureMake","typeSearch=$typeSearch&nameSearch=$nameSearch&companySearch=$companySearch&statusSearch=$statusSearch&cheSupervisor=$cheSupervisor");
					$doc->url ("Consultants Expenditure Report");
					echo "&nbsp;&nbsp;&nbsp;".$line;
			?>
		</td>
	</tr>
	<tr>
		<td class="speciali">
			Expenditure report on each consultant and his contracts with the Council for Higher Education.
		</td>
	</tr>
</table>

<br />

<table border='0' width="80%" cellpadding="2" cellspacing="2" align="center">
    <tr>
		<td>
			<?php 
				if (($typeSearch  == 0 && $nameSearch == "" && $companySearch == "" && $statusSearch == 0  && $cheSupervisor == 0) || count($rs) == 0)
				{
					$line = "";
				}
				else
				{
					$line = "<b>(".$msgCR." contract/s)</b>";
				}
			?>
			</td>
    </tr>
	<tr>
		<td class="specialb">
			<?php 
					$doc = new octoDocGen ("contractsExpiringMake","typeSearch=$typeSearch&nameSearch=$nameSearch&companySearch=$companySearch&statusSearch=$statusSearch&cheSupervisor=$cheSupervisor");
					$doc->url ("Contracts Expiring Report");
					echo "&nbsp;&nbsp;&nbsp;".$line;
			?>
		</td>
	</tr>
	<tr>
		<td class="speciali">
			Expiring contracts report on each consultant and their end date for contracts with the Council for Higher Education.
		</td>
	</tr>
	<tr>
			<td class="speciali">&nbsp;</td>
	</tr>
	<tr>
		<td>
			<?php 
				if (($typeSearch  == 0 && $nameSearch == "" && $companySearch == "" && $statusSearch == 0 && $cheSupervisor == 0) || count($rsRR) == 0)
				{
					$line = "";
				}
				else
				{
				    $line = "<b>(".$msgRR." consultant/s)</b>";
				}
			?>
			</td>
    </tr>
	<tr>
		<td class="specialb">
			<?php 
					$doc = new octoDocGen ("reviewConsultantsMake","typeSearch=$typeSearch&nameSearch=$nameSearch&companySearch=$companySearch&statusSearch=$statusSearch&cheSupervisor=$cheSupervisor");
					$doc->url ("Review Consultants Report");
					echo "&nbsp;&nbsp;&nbsp;".$line;
			?>
		</td>
	</tr>
	<tr>
		<td class="speciali">
			Review list of consultants and their current contracts report with the Council for Higher Education.
		</td>
	</tr>
	<tr>
		<td class="speciali">&nbsp;</td>
	</tr>
	<tr>
		<td class="specialb">
			<?php 
					$doc = new octoDocGen ("reviewRatingsMake","typeSearch=$typeSearch&nameSearch=$nameSearch&companySearch=$companySearch&statusSearch=$statusSearch&cheSupervisor=$cheSupervisor");
					$doc->url ("Review comments and ratings Report");
			?>
		</td>
	</tr>
	<tr>
		<td class="speciali">
			Review list of comments and the rating of contracts report with the Council for Higher Education.
		</td>
	</tr>

</table>
<br>

