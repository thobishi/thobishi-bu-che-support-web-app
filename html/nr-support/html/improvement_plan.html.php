<h3>Upload Improvement Plan for <em><?php echo $this->getInstitutionInfo('hei_name'); ?></em></h3>
<?php
    echo $this->element('filters/' . Settings::get('template'), $_POST);
    $details = $this->getInstProgressDetails($_POST, Settings::get('template'));
    if(!empty($details)){
?>
        <table class="table table-hover table-bordered table-striped">
      <tr>
      <td class="fieldsetData">
        <fieldset><legend>Upload your improvement plan:</legend>
          <?php
            $this->makeLink("improvement_plan_doc", "Improvement plan document", "nr_programmes", "id", $nr_programme_id);
          ?>
        </fieldset>
      </td>
      </tr>
        </table>
<?php
    }else{
        echo 'No results found';
    }
?>