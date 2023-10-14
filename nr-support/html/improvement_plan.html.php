<h3>Upload Improvement Plan for <em><?php echo $this->getInstitutionInfo('hei_name'); ?></em></h3>
<?php
    echo $this->element('filters/' . Settings::get('template'), $_POST);
    $details = $this->getInstProgressDetails($_POST, Settings::get('template'));
    $nr_programme_id = $details[0]['id'];
    /*print_r('Doing some super cool stuff with Deena.....................................');
    print_r($details);*/
    // if (0) 
    // Deenanath - Logic to mail CHE ADMINISTRATOR
    if ( isset($_POST['FLD_improvement_plan_approval_letter']) ||
    isset($_POST['FLD_improvement_plan_additional_doc1']) ||
    isset($_POST['FLD_improvement_plan_additional_doc2']) ||
    isset($_POST['FLD_improvement_plan_additional_doc3']))
   {
    //print_r('Doing some super cool stuff with Deena.....................................');
    $this->db->setValueInTable("nr_programmes", "id", $nr_programme_id, "date_submitted", date('Y-m-d'));
     //$fieldSts = $this->saveImprovementPlanAdditionalDocs($nr_programme_id, $_POST['FLD_improvement_plan_approval_letter'], 'improvement_plan_approval_letter');
    /* $fieldSts = $this->saveImprovementPlanAdditionalDocs($nr_programme_id, $_POST['FLD_improvement_plan_additional_doc1'], 'improvement_plan_additional_doc1');
     $fieldSts = $this->saveImprovementPlanAdditionalDocs($nr_programme_id, $_POST['FLD_improvement_plan_additional_doc2'], 'improvement_plan_additional_doc2');
     $fieldSts = $this->saveImprovementPlanAdditionalDocs($nr_programme_id, $_POST['FLD_improvement_plan_additional_doc3'], 'improvement_plan_additional_doc3');
    echo 'inner loop test dos';
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
        $subject = "Improvement Plan Uploaded";
        $message = <<<msg
          Dear ADMINISTRATOR,
            Please login and check submitted improvement plan document for $heiName
msg;
        $this->Email->misMailByGroup(3, $subject, $message, implode(",",$cc));
        $this->clearWorkflowSettings();*/
    }
    if(!empty($details)){
?>
      <table class="table table-hover table-bordered table-striped">
      <tr>
      <td class="fieldsetData">
        <fieldset><legend>Approval Letter:</legend>
          <?php
            $this->makeLink("improvement_plan_approval_letter", "Approval Letter"); //, "nr_programmes", "id", $nr_programme_id);
          ?>
        </fieldset>
      </td>
      </tr>
      <tr>
      <td class="fieldsetData">
        <fieldset><legend>Upload your improvement plan:</legend>
          <h3>Document1</h3>
          <?php
            $this->makeLink("improvement_plan_additional_doc1", "Additional Document1"); // , "nr_programmes", "id", $nr_programme_id);
          ?>
        </fieldset>
      </td>
      </tr>
      <tr>
      <td class="fieldsetData">
        <h3>Document2</h3>
          <?php
            $this->makeLink("improvement_plan_additional_doc2", "Additional Document2"); // , "nr_programmes", "id", $nr_programme_id);
          ?>
      </td>
      </tr>
      <tr>
      <td class="fieldsetData">
        <h3>Document3</h3>
          <?php
            $this->makeLink("improvement_plan_additional_doc3", "Additional Document3"); // , "nr_programmes", "id", $nr_programme_id);
          ?>
      </td>
      </tr>
        </table>
		<a href="javascript:goto(2)" class="btn"> Submit </a>
<?php
    }else{
        echo 'No results found';
    }
?>