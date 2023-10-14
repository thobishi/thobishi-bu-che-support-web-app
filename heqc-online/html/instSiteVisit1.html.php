<?php
	$site_visit_id = $this->dbTableInfoArray["inst_site_visit"]->dbTableCurrentID;
	$site_app_proc_id = $this->dbTableInfoArray["inst_site_app_proceedings"]->dbTableCurrentID;
	$inst_id = $this->getValueFromTable("inst_site_app_proceedings", "inst_site_app_proc_id",$site_app_proc_id, "institution_ref");
	$html = "";
?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
		<br>
		<?php echo $this->getSiteVisitTableTop($site_visit_id); ?>
	</td>
</tr>
<tr>
	<td class="specialh">
		<br>
		Enter site visit details:
		<br>
	</td>
</tr>
</table>
<br>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>Date for site visit to take place
	</td>
	<td>
		<?php $this->showField("final_date_visit"); ?>
	</td>
</tr>
<tr>
	<td>
		Upload initiation document for the site visit<br>
		<span class="specialsi">(The initiation document is the application form for a new or additional site of delivery or a letter to the institution indicating that a site visits needs to take place.)</span>
		<br>
	</td>
	<td>
		<?php $this->makeLink("initiation_doc"); ?>
	</td>
</tr>
<?php
	
?>
<tr>
	<td>
	</td>
</tr>
<tr>
	<td>
		Please indicate the reason/s that the site visit is being scheduled
	</td>
	<td>

		<table align="center" border="1">
		<tr>
			<td valign="top">New institution</td>
			<td valign="top">New site of delivery</td>
			<td valign="top">New mode of delivery</td>
			<td valign="top">Extension of offerings to postgraduate</td>
			<td valign="top">Public complaint</td>
			<td valign="top">Accreditation application deferred</td>
			<td valign="top">Accreditation application condition</td>

		</tr><tr>
			<td valign="top"><?php $this->showField("new_institution")?></td>
			<td valign="top"><?php $this->showField("new_site_delivery")?></td>
			<td valign="top"><?php $this->showField("new_mode_delivery")?></td>
			<td valign="top"><?php $this->showField("ext_off_postgrad")?></td>
			<td valign="top"><?php $this->showField("public_complaint")?></td>
			<td valign="top"><?php $this->showField("accr_app_deferred")?></td>
			<td valign="top"><?php $this->showField("accr_app_condition")?></td>
		</tr>
		</table>
	</td>
</tr>
<tr>
	<td>
		On approval of this site must the applications associated with this site visit be:
		<ul>
			<li>Re-located: Applications will be moved from the sites they're currently on to this site</li>
			<li>Added to site: Applications will be added to this site in addition to the other sites offered.</li>
		</ul>
	</td>
	<td>
		<?php $this->showField("lkp_relocate_ref"); ?>
	</td>
</tr>
<tr>
	<td colspan="2"><span class="visi">Note: Sites and programmes offered will be displayed to the user for approval before the system adds or removes programmes from a site.</span>
</td>
</tr>
<tr>
	<td colspan="2">
<?php
		//if some applications have already been assigned to this meeting, display them as checked
		
		$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
		if ($conn->connect_errno) {
		    $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
		    printf("Error: %s\n".$conn->error);
		    exit();
		}
		
		$dSQL = <<<APPS
			SELECT CHE_reference_code, program_name, inst_site_visit_progs.*
			FROM Institutions_application, inst_site_visit_progs
			WHERE Institutions_application.application_id = inst_site_visit_progs.application_ref
			AND site_visit_ref = $site_visit_id
APPS;
		

		//$sm = $conn->prepare($SQL);
		//$sm->bind_param("s", $site_visit_id);
		//$sm->execute();
		//$dRs = $sm->get_result();


		$dRs = mysqli_query($conn, $dSQL);
		$apps_tot = mysqli_num_rows($dRs);
		$this->formFields["appTotal"]->fieldValue = $apps_tot;
		$html = <<<HTML
			<br>
				The following applications have been assigned to this site visit. 
				<br>
				<span class="specialsi">(To <b>remove</b> an application from this site visit, check the relevant application and click Save)</span>
			<br><br>
			<table cellspacing=2 cellspacing=2 border=0 width='95%' align='center'>
			<tr class='oncolourb'>
				<td>Remove from site visit</td>
				<td>HEQC reference number</td>
				<td>Programme name</td>
			</tr>
HTML;
		
		$this->showField("appTotal");

		if ($apps_tot > 0){
			while ($dRow = mysqli_fetch_array($dRs)) {
				$html .= <<<HTML
					<tr class='onblue'>
						<td><input name='removeApplic[]' value="$dRow[inst_site_visit_progs_id]" type='Checkbox'></td>
						<td>$dRow[CHE_reference_code]</td>
						<td>$dRow[program_name]</td>
					</tr>
HTML;
			}
		} else {
			$html .= <<<HTML
				<tr class='onblue' align='center'>
					<td colspan='3'>
						No applications have been assigned to this site visit.
					</td>
				<tr>
HTML;
		}
			
		$html .= "</table>";

		$html .= <<<HTML
			<br><hr><br>
			The institution has the following applications. 
			To assign an application to this site visit, please check the box next to the relevant application and click Save in the Actions menu.
			<br><br>
HTML;

		//select all programmes that may be assigned to a site visit. They must have an outcome.
		$SQL = <<<READY
			SELECT application_id, CHE_reference_code, program_name
			FROM Institutions_application
			LEFT JOIN inst_site_visit_progs ON Institutions_application.application_id = inst_site_visit_progs.application_ref AND inst_site_visit_progs.site_visit_ref = $site_visit_id
			WHERE institution_id = $inst_id
			AND (Institutions_application.AC_desision > 0 OR  application_id IN (SELECT application_ref FROM ia_proceedings WHERE heqc_board_decision_ref > 0))
			AND inst_site_visit_progs.application_ref IS NULL
READY;

		//$sm = $conn->prepare($SQL);
		//$sm->bind_param("s", $inst_id);
		//$sm->execute();
		//$rs = $sm->get_result();


		$rs = mysqli_query($conn, $SQL);

		$html .= <<<HTML
			<table cellspacing="2" cellspacing="2" border="0" width="95%" align="center"
			<tr class="oncolourb">
				<td>Add to site visit</td>
				<td>HEQC reference number</td>
				<td>Programme name</td>
			</tr>
HTML;

		if (mysqli_num_rows($rs) > 0) {

				while ($row = mysqli_fetch_array($rs)) {
					$html .= <<<HTML
					<tr class="onblue">
						<td><input name='addApplic[]' value="$row[application_id]" type='Checkbox'></td>
						<td>$row[CHE_reference_code]</a></td>
						<td>$row[program_name]</td>
					</tr>
HTML;
				}
		}
		else {
			$html .= <<<HTML
				<tr class='onblue' align='center'>
					<td colspan='3'>
						No available applications. It may be that the applications weren't captured online or they do not have an outcome.
					</td>
				<tr>
HTML;
		}
		$html .= "</table>";
		echo $html;
?>
	</td>
</tr>
</table>