<h3>Upload National Standard Alignment Report for <em><?php echo $this->getInstitutionInfo('hei_name'); ?></em></h3>
<?php


echo $this->dbTableInfoArray["nr_programmes"]->dbTableKeyField;
    echo $this->element('filters/' . Settings::get('template'), $_POST);
    $details = $this->getInstProgressDetails($_POST, Settings::get('template'));

	$detailsReal = array(); // Initialize the new array

foreach ($details as $row) {
    if ($row['nr_programme_name'] === 'Bachelor of Social Work') {
        $detailsReal[] = $row; // Add the matching row to the new array
    }
}

// Access the desired value(s) in the new array
if (!empty($detailsReal)) {
    foreach ($detailsReal as $row) {
        echo $row['che_reference_code'] . "\n";
    }
} else {
    echo "No matching row found.";
}

//var_dump($this);
echo $this->dbTableInfoArray["nr_programmes"]->dbTableKeyField;

    $nr_programme_id = $detailsReal[0]['id'];
	$_SESSION["ses_keyVal"] = $nr_programme_id;
	
	// Set dbTableCurrentID to the value of $nr_programme_id
	$this->dbTableInfoArray["nr_programmes"]->dbTableCurrentID = $nr_programme_id;

	$prog_id = $this->dbTableInfoArray["nr_programmes"]->dbTableCurrentID;
echo $prog_id;
echo "current id";
echo $this->dbTableInfoArray["nr_programmes"]->dbTableCurrentID ;
echo "assignedid";
echo $nr_programme_id;



$link1 = $this->scriptGetForm('nr_programmes', $prog_id, '_national_standard_alignemnt_report_profi');

$link4 = $this->scriptGetForm('nr_programmes', $prog_id, '_label_ser_data_llb');
$fieldsComplete = $this->getStatusOfSection('nr_programmes', $prog_id, array('ser_profile'));




    /*print_r('Doing some super cool stuff with Deena.....................................');
    print_r($details);*/
    // if (0) 
    // Deenanath - Logic to mail CHE ADMINISTRATOR
    if ( isset($_POST['uploadser_national_standard_alignemnt_report_doc_1']) ||
    isset($_POST['FLD_uploadser_national_standard_alignemnt_report_doc_1']) ||
    isset($_POST['FLD_uploadser_national_standard_alignemnt_report_doc_2']) ||
    isset($_POST['FLD_uploadser_national_standard_alignemnt_report_doc_3']))
   {
    //print_r('Doing some super cool stuff with Deena.....................................');
    $this->db->setValueInTable("nr_programmes", "id", $nr_programme_id, "date_submitted", date('Y-m-d'));
     /*//$fieldSts = $this->saveImprovementPlanAdditionalDocs($nr_programme_id, $_POST['FLD_improvement_plan_approval_letter'], 'improvement_plan_approval_letter');
     $fieldSts = $this->saveImprovementPlanAdditionalDocs($nr_programme_id, $_POST['FLD_uploadser_national_standard_alignemnt_report_doc_1'], 'improvement_plan_additional_doc1');
     $fieldSts = $this->saveImprovementPlanAdditionalDocs($nr_programme_id, $_POST['FLD_uploadser_national_standard_alignemnt_report_doc_2'], 'improvement_plan_additional_doc2');
     $fieldSts = $this->saveImprovementPlanAdditionalDocs($nr_programme_id, $_POST['FLD_uploadser_national_standard_alignemnt_report_doc_3'], 'improvement_plan_additional_doc3');
    echo 'inner loop test dos';*/
    //TODO: check for dynamic name
   /*     $secGroupRef = 3;
        $SQL = "SELECT email FROM users u
                INNER JOIN sec_usergroups g ON g.sec_user_ref = u.user_id
                WHERE g.sec_group_ref=:secGroupRef";
        $rs = $this->db->query($SQL, compact('secGroupRef'));
        while ($row = $rs->fetch()) {
          $cc[] = $row['email'];
        }*/
       /* $cc[] = 'deena.mishra@nouveausoft.co.in';
        $cc[] = 'bohlalenkoana007@gmail.com';
        $cc[] = 'bohlale@esoftwaresolutions.co.za';
        $heiName = $this->getInstitutionInfo('hei_name');
        $subject = "NSAR Uploaded";
        $message = <<<msg
          Dear ADMINISTRATOR,
            Please login and check submitted National Standard Alignment document for $heiName
msg;
        $this->Email->misMailByGroup(3, $subject, $message, implode(",",$cc));
        $this->clearWorkflowSettings();*/
    }
    if(!empty($details)){
?>
      <table class="table table-hover table-bordered table-striped">
      <tr>
      <td class="fieldsetData">
        <fieldset><legend>1.	Download the National Standard Alignment Report (NSAR) template and complete</legend>
        <a class="col-md-2" target="_blank" href="html_documents/BSW Standards.pdf"><img src="images/DOC.png" alt="DOC" />&nbsp;BSW Standards</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br/><br/>
        <a class="col-md-2" target="_blank" href="html_documents/BSW_NSAR_template.docx"><img src="images/DOC.png" alt="DOC" />&nbsp;BSW NSAR Template</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br/><br/>
</fieldset>
      </td>
      </tr>
      <tr>
      <td class="fieldsetData">
        <fieldset><legend>2.	Provide profile data for Qualification</legend>
          <p>Inactive for now</p>
        </fieldset>
      </td>
      </tr>
      <tr>
      <td class="fieldsetData">
        <fieldset><legend>3.	Upload your NSAR and sign off the VERIFICATION  page:</legend>
          <?php
            $this->makeLink("uploadser_national_standard_alignemnt_report_NSAR_Sign_Off", "NSAR And Sign-Off", "nr_programmes", "id", $nr_programme_id);
          ?>
        </fieldset>
      </td>
      </tr>

      <tr>
      <td class="fieldsetData">
        <fieldset><legend>Supporting documents </legend>
          <h3>Document 1</h3>
          <?php
    
//echo "hello"+nr_programmes;
            $this->makeLink("uploadser_national_standard_alignemnt_report_doc_1", "National Standard Alignment Report Document 1", "$nr_programmes", "", $nr_programme_id);
    
?>
        </fieldset>
      </td>
      </tr>
      <tr>
      <td class="fieldsetData">
        <h3>Document 2</h3>
          <?php
            $this->makeLink("uploadser_national_standard_alignemnt_report_doc_2", "National Standard Alignment Report Document 2" , "nr_programmes", "id", $prog_id);
          ?>
      </td>
      </tr>
      <tr>
      <td class="fieldsetData">
        <h3>Document 3</h3>
          <?php
            //makeLink($field,$text="", $table="", $keyFLD="", $keyVal="",$DateUploadedField="")
            $this->makeLink("uploadser_national_standard_alignemnt_report_doc_3", "National Standard Alignment Report Document 3", "nr_programmes", "id", $prog_id);
          ?>
      </td>
      </tr>
        </table>
		<!-- <a href="javascript:goto(2)" class="btn"> Submit </a> -->
<?php
    }else{
        echo 'No results found';
    }
?>