<?php

/**
 * class handeling all the reports
 *
 * this is a generic class that handles all the reports generated under the reports menu item
 * @author Reyno vd Hoven
*/
class reports extends handleDocs {
	/**
	 * default constructor
	 *
	 * This empty constructer can be used when a normal page wants to use some of the functions of this class.
	 * @author Reyno van der Hoven
	*/
	function reports(){
	}

	function AC1(){
		$total = 0;
		$ret = "The following table is a summary of the number of applications per institution that will be discussed at the next AC Meeting.<br><br>";
		$ret .= "<table border='0' cellpadding='2' cellspacing='2'>";
		$ret .= "<tr>";
		$ret .= "<td class='oncolourb'>Institution</td>";
		$ret .= "<td class='oncolourb'>Number of applications</td>";
		$ret .= "</tr>";

		$SQL  = "SELECT i.HEI_name,count(*) as total ";
		$SQL .= "FROM Institutions_application AS a ";
		$SQL .= "LEFT JOIN HEInstitution as i  ";
		$SQL .= "ON HEI_id = a.institution_id  ";
		$SQL .= "WHERE submission_date > '1970-01-01'  ";
		$SQL .= "AND (a.CHE_reference_code > '')  ";
		$SQL .= "AND a.institution_id NOT IN (1, 2) ";
		$SQL .= "AND a.AC_desision = '' ";
		$SQL .= "GROUP BY i.HEI_name";

			$rs = mysqli_query($SQL);
			if (mysqli_num_rows($rs) > 0){
				while ($row = mysqli_fetch_array($rs)){
					$ret .= "<tr  class='onblue'>";
					$ret .= "<td>".$row[0]."</td>";
					$ret .= "<td align='center'>".$row[1]."</td>";
					$ret .= "</tr>";
					$total += $row[1];

				}
			}
		$ret .= "<tr class='onblueb'><td>TOTAL</td><td align='center'>";
		$ret .= $total."</td></tr>";
		$ret .= "</table>";

		return $ret;
	}

	function AC2(){
		$ret = "The following table is a list of all the programmes that will be discussed at the next AC Meeting, with the % they scored in the paper evaluation.<br><br>";
		$ret .= "<table border='1' cellpadding='2' cellspacing='2'>";
		$ret .= "<tr>";
		$ret .= "<td class='oncolourb'>Institution</td>";
		$ret .= "<td class='oncolourb'>Programme</td>";
		$ret .= "<td class='oncolourb'>Score %</td>";
		$ret .= "</tr>";
			$SQL = "SELECT HEI_name as Institution,program_name,application_comp_all FROM Institutions_application,application_summery_comments,HEInstitution WHERE application_status=1 AND HEI_id=institution_id AND  application_ref=application_id ORDER BY Institution,application_comp_all ";


			$rs = mysqli_query($SQL);
			if (mysqli_num_rows($rs) > 0){
				while ($row = mysqli_fetch_array($rs)){
					$ret .= "<tr>";
					$ret .= "<td>".$row["Institution"]."</td>";
					$ret .= "<td>".$row["program_name"]."</td>";
					$ret .= "<td align='center'>".$row["application_comp_all"]."</td>";
					$ret .= "</tr>";

				}
			}
		$ret .= "</table>";
		return $ret;
	}

	function AC3(){
		$ret = "The following table is a summary of the Number of programmes submitted by Type of HEI and Science Domain.<br><br>";
		$ret .= "<table border='1' cellpadding='2' cellspacing='2'>";
		$ret .= "<tr>";
		$ret .= "<td class='oncolourb'>Type of HEI</td>";
		$ret .= "<td class='oncolourb'>Science Domain</td>";
		$ret .= "<td class='oncolourb'>Number</td>";
		$ret .= "</tr>";
			$SQL = "SELECT lnk_priv_publ.lnk_priv_publ_desc,SpecialisationCESM_code1.Description,count(*) FROM lnk_priv_publ,Institutions_application,SpecialisationCESM_code1,HEInstitution WHERE application_status=1 AND SpecialisationCESM_code1.CESM_code1=Institutions_application.CESM_code1 AND HEI_id=institution_id AND priv_publ=lnk_priv_publ.lnk_priv_publ_id GROUP BY lnk_priv_publ.lnk_priv_publ_id,Institutions_application.CESM_code1";
			$rs = mysqli_query($SQL);
			if (mysqli_num_rows($rs) > 0){
				while ($row = mysqli_fetch_array($rs)){
					$ret .= "<tr>";
					$ret .= "<td>".$row[0]."</td>";
					$ret .= "<td>".$row[1]."</td>";
					$ret .= "<td align='center'>".$row[2]."</td>";
					$ret .= "</tr>";

				}
			}
		$ret .= "</table>";
		return $ret;
	}

		function AC4(){
			$total = 0;
//applications that have been submitted, but do not have an outcome.
//check for cancelled and withdrawn processes
			$ret = "The following table is a list of the applications that will be discussed at the next AC Meeting.<br><br>";
			$ret .= "<table border='0' cellpadding='2' cellspacing='2' width='100%' align='center'>";
			$ret .= "<tr class='onblueb'>";
			$ret .= "<td width='60%' align='center'>Insitution</td>";
			$ret .= "<td width='60%' align='center'>Programme Name</td>";
			$ret .= "<td align='center'>CHE Reference Number</td>";
			$ret .= "</tr>";

/*
			$SQL  = "SELECT i.HEI_name, a.*";
			$SQL .= "FROM Institutions_application as a, HEInstitution as i ";
			$SQL .= "WHERE a.application_status >= 0 ";
			$SQL .= "AND i.HEI_id=a.institution_id ";
			$SQL .= "AND i.HEI_id NOT IN (1,2) ";
			$SQL .= "AND a.CHE_reference_code != '' ";
			$SQL .= "AND a.submission_date > '1970-01-01' ";
			$SQL .= "AND a.AC_desision = '' ";
*/

		$SQL  = "SELECT i.HEI_name, a.* ";
		$SQL .= "FROM Institutions_application AS a ";
		$SQL .= "LEFT JOIN HEInstitution as i  ";
		$SQL .= "ON HEI_id = a.institution_id  ";
		$SQL .= "WHERE submission_date > '1970-01-01'  ";
		$SQL .= "AND (a.CHE_reference_code > '')  ";
		$SQL .= "AND a.institution_id NOT IN (1, 2) ";
		$SQL .= "AND a.AC_desision = '' ";


				$rs = mysqli_query($SQL);
				$total = mysqli_num_rows($rs);
				if ($total > 0){
					while ($row = mysqli_fetch_array($rs)){
						$ret .= "<tr  class='onblue'>";
						$ret .= "<td valign='top'>".$row['HEI_name']."</td>";
						$ret .= "<td valign='top'>".$row['program_name']."</td>";
						$ret .= "<td valign='top'>".$row['CHE_reference_code']."</td>";
						$ret .= "</tr>";

					}
				}
			$ret .= "<tr class='onblueb'><td colspan='3' align='right'>TOTAL:&nbsp;&nbsp;&nbsp;".$total."</td></tr>";
			$ret .= "</table>";

			return $ret;
	}

	function generalReport($category, $status){

		$perThis = "";
		$statusStr = ($status == "accredited") ? "with AC meeting decisions" : "";
		$leftjoin = "";
		$additionalFROM = "";
		$additionalWHERE = "";

		switch ($category)
		{
			case "HEI" :
				$perThis = "institution";
				$select = "i.HEI_name, ";
				$leftjoin = "LEFT JOIN HEInstitution as i ON HEI_id = a.institution_id ";
				$groupby = "i.HEI_name";
				break;
			case "CESM" :
				$perThis = "CESM category";
				$select = "s.Description, a.*, ";
				$additionalFROM = ", HEInstitution AS i, SpecialisationCESM_code1 AS s ";
				$additionalWHERE  = "AND i.HEI_id = a.institution_id ";
				$additionalWHERE .= "AND s.CESM_code1 = a.CESM_code1 ";
				$groupby = "s.Description";
				break;
			case "NQF" :
				$perThis = "NQF level";
				$select = "l.NQF_level, ";
				$additionalFROM = ", NQF_level AS l ";
				$leftjoin = "LEFT JOIN HEInstitution as i ON HEI_id = a.institution_id ";
				$additionalWHERE  = "AND l.NQF_id = a.NQF_ref ";
				$groupby = "l.NQF_level";
				break;
		}

		$total = 0;
		$prov_total = 0;
		$cond_total = 0;
		$not_total = 0;
		$def_total = 0;
		$ret = "The following table is a summary of the number of applications ".$statusStr." (per ".$perThis.").<br><br>";
		$ret .= "<table border='0' cellpadding='2' cellspacing='2'>";
		$ret .= "<tr>";
		$ret .= "<td class='oncolourb'>".ucwords($perThis)."</td>";
		$ret .= "<td class='oncolourb'>Number of applications</td>";
		if ($status == "accredited")
		{
			$ret .= "<td class='oncolourb'>Provisionally Accredited</td>";
			$ret .= "<td class='oncolourb'>Accredited with Conditions</td>";
			$ret .= "<td class='oncolourb'>Not Accredited</td>";
			$ret .= "<td class='oncolourb'>Deferred</td>";
		}
		$ret .= "</tr>";

		$SQL  = "SELECT ".$select."count(*) as total ";

		if ($status == "accredited")
		{
			$SQL .= ", sum(IF(a.AC_desision='1', 1, 0)) as provisional, ";
			$SQL .= "sum(IF(a.AC_desision='2', 1, 0)) as conditional, ";
			$SQL .= "sum(IF(a.AC_desision='3', 1, 0)) as not_accredited, ";
			$SQL .= "sum(IF(a.AC_desision='4', 1, 0)) as deferred ";
		}

		$SQL .= "FROM Institutions_application AS a ";
		$SQL .= $leftjoin;
		$SQL .= $additionalFROM;
		$SQL .= "WHERE submission_date > '1970-01-01'  ";
		//$SQL .= "WHERE a.application_status >=0 ";
//do we need above?
		$SQL .= "AND (a.CHE_reference_code > '')  ";
		$SQL .= "AND a.institution_id NOT IN (1, 2) ";
		$SQL .= $additionalWHERE;
		$SQL .= ($status == "accredited") ? "AND a.AC_desision != '' " : "";
		$SQL .= "GROUP BY ".$groupby;

			$rs = mysqli_query($SQL);
			if (mysqli_num_rows($rs) > 0){
				while ($row = mysqli_fetch_array($rs)){
					$ret .= "<tr  class='onblue'>";
					$ret .= "<td>".$row[0]."</td>";
					$ret .= "<td align='center' class='oncolourb'>".$row['total']."</td>";
					if ($status == "accredited")
					{
						$ret .= "<td align='center'>".$row['provisional']."</td>";
						$ret .= "<td align='center'>".$row['conditional']."</td>";
						$ret .= "<td align='center'>".$row['not_accredited']."</td>";
						$ret .= "<td align='center'>".$row['deferred']."</td>";
						$prov_total += $row['provisional'];
						$cond_total += $row['conditional'];
						$not_total += $row['not_accredited'];
						$def_total += $row['deferred'];
					}
					$ret .= "</tr>";
					$total += $row['total'];
				}
			}
		$ret .= "<tr class='onblueb'><td>TOTAL</td>";
		$ret .= "<td align='center'>".$total."</td>";
		if ($status == "accredited")
		{
			$ret .= "<td align='center'>".$prov_total."</td>";
			$ret .= "<td align='center'>".$cond_total."</td>";
			$ret .= "<td align='center'>".$not_total."</td>";
			$ret .= "<td align='center'>".$def_total."</td>";
		}
		$ret .= "</tr></table>";

		return $ret;
	}


}
?>
