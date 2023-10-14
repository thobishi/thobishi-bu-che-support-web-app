<?php
	$site_proc_id = $this->dbTableInfoArray["inst_site_app_proceedings"]->dbTableCurrentID;
	$inst_id = $this->getValueFromTable("inst_site_app_proceedings","inst_site_app_proc_id", $site_proc_id, "institution_ref");
?>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
		<br>
		<?php echo $this->getSiteApplicationTableTop($site_proc_id, "sites"); ?>
	</td>
</tr>
<tr>
	<td class="specialh">
		<br>
		Institution notification:
		<br>
	</td>
</tr>
<tr>
	<td>
		Check the content of the email below which will be emailed to the HEQC-online institutional administrator 
		notifying them of the site visit details.  It is optional to email this letter.  
		In order to email the letter ensure the box under the <b>Send Email?</b> heading is checked.
		By default the box is checked if the letter has not been previously emailed.
		<br>
		<br>
	</td>
</tr>
<tr>
	<td>
		<?php
			$html = <<<HTML
				<table width="95%">
				<tr>
					<td><b>Designation</b></td>
					<td><b>Name</b></td>
					<td><b>Email</b></td>
					<td><b>Telephone</b></td>
					<td><b>Send Email?</b></td>
				</tr>
HTML;

			$contacts = $this->getInstitutionContacts($inst_id);
			foreach($contacts as $c){
				$cname = $c["lkp_title_desc"] . " " . $c["contact_name"] . " " . $c["contact_surname"];
				$html .= <<<HTML
					<tr>
						<td>{$c["contact_designation"]}</td>
						<td>{$cname}</td>
						<td>{$c["contact_email"]}</td>
						<td>{$c["contact_nr"]}</td>
						<td><input name="contact[]" type="Checkbox" value="{$c["contact_email"]}"></td>
					</tr>
HTML;
			}
		
			$html .= '</table>';
			echo $html;
		?>
	</td>
</tr>
<tr>
	<td>
		<br>
		The following email will be sent to the institution if the <b>Send Email?</b> box above is checked.
		<br />
		<span class="visi">The content below may be edited. This edited content will be emailed when you click on Continue.</span>
	</td>
</tr>
<tr>
	<td>
		<?php
			$this->formFields['institution_site_visit_notification']->fieldValue = $this->getTextContent("instSiteApplicPanel5", "Notification of site visit");
			$this->showField('institution_site_visit_notification');
		?>
	</td>
</tr>
<tr>
	<td>
		<b>Attachments that will be emailed with the above notification letter to the institution:</b><br>
		<?php
		
		$attach_arr = array();
		$doc_arr = $this->getSiteProcAttachments($site_proc_id);
		foreach($doc_arr as $doc_id => $title){
			$att = new octoDoc($doc_id);
			if ($att->isDoc()) {
				array_push($attach_arr, '<a href="'.$att->url().'" target="_blank">'.$att->getFilename().'</a>');
			}
		}

		$attachments = "";
		if (count($attach_arr) > 0){
			$attachments = implode('<br>', $attach_arr);
		}
		
		echo $attachments;
		?>
	</td>
</tr>
</table>
