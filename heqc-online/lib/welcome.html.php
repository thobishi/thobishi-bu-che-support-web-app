<div id="login_block" class="login-block">
	<?php if (!empty($this->noLogonMessage)) { ?>
		<div class="alert alert-small">
			<?php echo $this->noLogonMessage; ?>
		</div>
	<?php } ?>
	<div class="inputs">
		<?php $this->showField("oct_username"); ?>
		<?php $this->showField("oct_passwd"); ?>
		<input type="submit" class="btn btn-success" name="login" value="Login">
		<a href="?goto=9">Forgot your password?</a>
	</div>
</div>

<div class="welcome-page">
	<div id="contact_block" class="contact-block">
		<h2>Contact</h2>
		<p>
			For any queries please contact
			<br />
			Ms Fundiswa (Fundi) Kanise at the CHE:
			<br /><br />
			+27 (0)12 349 3913
			<br /><br />
			nr-online@che.ac.za
		</p>
	</div>
	<div id="page_content" class="page-content">
		<h2>Welcome to CHE National Reviews Online!</h2>  
		<p>The purpose of this website is two-fold: </p>
		<h1 class="yellow-background">1. Self-evaluation Report Guidance</h1>

		<p>
			Download the latest information, guidelines and templates to complete your<br>
			Self-evaluation report as part of the preparation for your site visit:
		</p>
<!-- Documents used for BSW 
	 DO NOT DELETE AND KEEP DOCUMENTS FOR HISTORICAL REASONS
		<table class="table file-list">
			<tr>
				<td  class="homeListCol"><img src="html_images/PDF.png" alt="link_image"/></td><td><a href="html_documents/National_Review_Manual.pdf" target="_blank">National Review Manual for the Re-accreditation of Programmes, 2012</a></td>
			</tr>
			<tr>
				<td><img src="html_images/PDF.png" alt="link_image"/></td><td><a href="html_documents/CHE_accreditation_criteria_Nov2004.pdf" target="_blank">Criteria for Programme Accreditation, 2004</a></td>
			</tr>
			<tr>
				<td><img src="html_images/PDF.png" alt="link_image"/></td><td><a href="html_documents/CHE_Accreditation_Criteria_for_the_Review_of_BSW_2012.pdf" target="_blank">CHE Accreditation criteria adapted for the Review of Undergraduate Programmes, 2013</a></td>
			</tr>
			<tr>
				<td><img src="html_images/DOC.png" alt="link_image"/></td><td><a href="html_documents/SER_template.docx"  target="_blank">Self-evaluation Report Template, 2013</a></td>
			</tr>
		</table>
-->
<!-- Documents for LLB -->
		<table class="table file-list">
			<tr>
				<td  class="homeListCol"><img src="html_images/PDF.png" alt="link_image"/></td><td><a href="html_documents/FRW_National Review_2015.pdf" target="_blank">National Review Framework</a></td>
			</tr>
			<tr>
				<td  class="homeListCol"><img src="html_images/PDF.png" alt="link_image"/></td><td><a href="html_documents/FRW_Qualification Standards Development_2015.pdf" target="_blank">Framework for Qualification Standards in Higher Education</a></td>
			</tr>
			<tr>
				<td  class="homeListCol"><img src="html_images/PDF.png" alt="link_image"/></td><td><a href="html_documents/LLB/National_Reviews_ Manual_20150915.pdf" target="_blank">National Review Manual for the Re-accreditation of Programmes, 2015</a></td>
			</tr>
			<tr>
				<td><img src="html_images/PDF.png" alt="link_image"/></td><td><a href="html_documents/CHE_accreditation_criteria_Nov2004.pdf" target="_blank">Criteria for Programme Accreditation, 2004</a></td>
			</tr>
			<tr>
				<td><img src="html_images/PDF.png" alt="link_image"/></td><td><a href="html_documents/LLB/Standards for Bachelor of Laws_ LLB final version_20150921.pdf" target="_blank">Qualification Standard for Bachelor of Laws(LLB), 2015</a></td>
			</tr>
			<tr>
				<td><img src="html_images/DOC.png" alt="link_image"/></td><td><a href="html_documents/LLB/LLB_SER_template_August_2015.docx"  target="_blank">Self-evaluation Report Template, 2013</a></td>
			</tr>
		</table>
		<h1 class="red-background">2. Self-evaluation Report Submission</h1>

		<p>
			To submit your SER you will log into the system from this website using login details that will be provided to you.
		</p>
		<p>
			Please note that the due date for submitting your SER is <strong>16 May 2016</strong>.
		</p>
	</div>
</div>
</div></div></div>
</form>
</div>

<script>
$(document).ready(function(){
	$("form.login input")
		.each(function(){
			if (this.value) {
				$(this).prev().hide();
			}
		})
		.focus(function(){
			$(this).prev().hide();
		})
		.blur(function(){
			if (!this.value) {
				$(this).prev().show();
			}
		});
});	
</script>
