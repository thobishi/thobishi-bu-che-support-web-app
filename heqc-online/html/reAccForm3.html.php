<?php 
	$progID = $this->dbTableInfoArray["Institutions_application_reaccreditation"]->dbTableCurrentID;
?>

<input type='hidden' name='cmd' value=''>
<input type='hidden' name='id' value=''>
<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
	<tr>
		<td colspan="2">
			<?php echo $this->displayReaccredHeader($progID); ?>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="loud">2.2 Programme details<hr></td>
	</tr>
	<tr>
		<td valign="top"><br/>
		<table width="95%" align="left" cellpadding="2" cellspacing="2" border="0">
		<?php

		if (isset($_POST["cmd"]) && ($_POST["cmd"] > "")) {
			$cmd = explode("|", $_POST["cmd"]);
			switch ($cmd[0]) {
				case "new":
					$this->gridInsertRow($cmd[1], $cmd[2], $cmd[3]);
					break;
				case "del":
					$this->gridDeleteRow($cmd[1], $cmd[2], $cmd[3]);
					break;
			}
			echo '<script>';
			echo 'document.defaultFrm.action = "#'.$cmd[1].'";';
			echo 'document.defaultFrm.MOVETO.value = "stay";';
			echo 'document.defaultFrm.submit();';
			echo '</script>';
		}

			$dFields = array();

			array_push($dFields, "type__text|size__10|name__site_delivery");
			array_push($dFields, "type__text|size__10|name__contact_mode");
			array_push($dFields, "type__text|size__10|name__full_parttime");
			array_push($dFields, "type__text|size__10|name__duration_programme");
			array_push($dFields, "type__text|size__10|name__headcount_enrolment");
			array_push($dFields, "type__text|size__10|name__diplomates_graduates");

			$hFields = array();
			array_push($hFields,"Year");
			array_push($hFields,"Site(s) of delivery");
			array_push($hFields,"Contact (C)/ Distance (D)");
			array_push($hFields,"Full-time (F)/ Part-time (P)");
			array_push($hFields,"Normal duration of the programme");
			array_push($hFields,"Headcount enrolment");
			array_push($hFields,"Number of diplomates/graduates");

			$this->gridShow("reaccred_programme_details", "reaccred_programme_details_id", "reaccred_programme_ref__".$progID, $dFields, $hFields, "lkp_year", "lkp_year_desc", "lkp_year_desc", "programme_year",1,40,5,FALSE,""," lkp_year_desc BETWEEN 2012 AND 2016");

		?>
		</table>
	</td>
</tr>

<!--<tr>
	<td colspan="2" class="loud">2.2.1 Provide details of the programme as applicable.<td>
</tr>
<tr>
</tr>-->
</table>
<br>
