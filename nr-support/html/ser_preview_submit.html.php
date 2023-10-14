<div class="accordion" id="accordion2">
	<div class="accordion-group">
		<div class="accordion-heading">
		  <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne">
			<h3>Preview and information</h3>
		  </a>
		</div>
		<div id="collapseOne" class="accordion-body collapse in">
			<div class="accordion-inner">
				<?php
					$prog_id = $this->dbTableInfoArray["nr_programmes"]->dbTableCurrentID;
					$settings = $this->getStringWorkFlowSettings($this->workFlow_settings);
					$formsToValidate = array(
									array("ser","Self Evaluation report"),
									array("ser_data","Data tables"),
									array("ser_profile","Profile of programme"),
									array("ser_contact","Contact details of Head of programme"),
									array("ser_contact_head","Contact person"),					
								);
					$formsToPreview = array("ser","ser_profile","ser_contact","ser_contact_head","ser_data");

					function doOutPutBuffer ($buffer) {
						$h = fopen ("/tmp/nr_mis_output.html", "w+");
						$search_array = array("/\<script\>.*\<\/script\>/sU", "/(\<a.*[^>]href=.*(?:openFileWin|changeCMD|winContentText.*).*\>)(.*)(\<\/a\>)/U");
						$replace_array = array("", "\\2");
						$html = $buffer;
						$html = preg_replace ($search_array, $replace_array, $buffer);
						fwrite($h, $html);
						return $html;
					}

					ob_start("doOutPutBuffer");
					foreach ($formsToPreview as $form) {
						$app = new NRonline (1);
						$app->parseWorkFlowString($settings);
						$app->template = $form;
						$app->view = 1;
						$app->formStatus = FLD_STATUS_DISABLED;
						$app->readTemplate();
						$app->createHTML($app->body);
						unset ($app);
					}

					ob_end_flush();
				?>
			</div>
		</div>
	</div>
	<div class="accordion-group">
		<div class="accordion-heading">
		  <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo">
			<h3>Preview and validated information</h3>
		  </a>
		</div>
		<div id="collapseTwo" class="accordion-body collapse">
			<div class="accordion-inner">
			<?php						
				echo '<table class="table table-hover">';					
				foreach($formsToValidate as $form){
					echo '<thead>';
					echo '<tr>';
						echo '<th colspan = "3">'.$form[1].'</th>';
					echo '</tr>';
					echo '</thead>';
					$this->validateFields("$form[0]");
					if (isset($child["$form[0]"]) && $child["$form[0]"] > ""){
						$this->validateFieldsperChild($site_title,$child["$form[0]"],"application_ref",$app_id);
					}
				}
				echo '</table>'
				
				// if(){
					// $this->createAction ("Submit to CHE ", "Submit to CHE", "submit", "", "ico_next.gif");
				// }
			?>   
			</div>

		</div>
	</div>
</div>
<div class="back-to-top">[<a href="#">Back to Top</a>]</div>
<script>
	// label to take user back to the validation page
	// document.defaultFrm.VALIDATION.value = '_gotoValidationApplication_v2';
	
</script>
