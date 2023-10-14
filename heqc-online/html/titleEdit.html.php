<br>
<?php
	$this->formFields["search_progname"]->fieldValue = readPost('search_progname');
	$this->formFields["search_institution"]->fieldValue = readPost('search_institution');
	$this->formFields["search_HEQCref"]->fieldValue = readPost('search_HEQCref');
	$this->formFields["report_ind"]->fieldValue = readPost('report_ind');

	$this->showField("search_progname");
	$this->showField("search_institution");
	$this->showField("search_HEQCref");
	$this->showField("report_ind");

	$app_id = readPost("data");

	if ($app_id > 0){
		$this->getApplicationInfoTableTop($app_id);
		
		$date_changed = date("Y-m-d");
		$user = $this->getValueFromTable("users","user_id", $this->currentUserID,"name");
		$this->formFields["application_ref"]->fieldValue = $app_id;
		$this->formFields["user_ref"]->fieldValue = $this->currentUserID;
		$this->formFields["date_changed"]->fieldValue = $date_changed;
		$this->formFields["old_title"]->fieldValue = $this->getValueFromTable("Institutions_application","application_id", $app_id,"program_name");
		$this->showField("application_ref");
		$this->showField("user_ref");
		$this->showField("date_changed");
		$this->showField("old_title");
?>
		<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
		<tr>
			<td class="loud">Change programme name for above application:</td>
		</tr>
		<tr>
			<td>
				Please complete the following fields and click Save in the actions menu.
			</td>
		</tr>
		<tr>
			<br />
			<td>
				<table width="95%" border="0" align="center" cellpadding="2" cellspacing="2">
				<tr>
					<td class="visi" colspan="2">
					It will be recorded that <?php echo $user; ?> changed the programme name on <?php echo $date_changed; ?>.
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>
						<?php	
							$prev_title_list = $this->getApplicationTitleHistory($app_id); 
							if ($prev_title_list > ''){
								echo "<b>List of previous titles</b>";
								echo $prev_title_list;
							}
						?>
					</td>
				</tr>
				<tr>
					<td>New programme name</td><td><?php $this->showField('new_title'); ?></td>
				</tr>
				<tr>
					<td>Reason for changing the programme name</td><td><?php $this->showField('reason'); ?></td>
				</tr>		
				<tr>
					<td>Document as evidence for the programme name change</td><td><?php $this->makeLink('reason_doc'); ?></td>
				</tr>	
				</table>
			</td>
		</tr>		
		</table>
<?php
	} else {
		echo "The programme name can't be changed for this application.  Please contact HEQC-online support to assist with the problem.";
	}
?>