<a name="application_form_admin_page"></a>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>

<?php 
		// Only private institutions need to apply for re-accreditation
 		$InstRef = $this->getValueFromTable("users", "user_id", $this->currentUserID, "institution_ref");
		$inst_priv_publ = $this->getValueFromTable("HEInstitution", "HEI_id", $InstRef, "priv_publ");
		if ($inst_priv_publ == "1") { 
			$href_app = $this->getValueFromTable("settings", "s_key", "reaccred_form_private", "s_value");
			$href_inst = $this->getValueFromTable("settings", "s_key", "offline_form_instprofile_private", "s_value");
			$href_crit = $this->getValueFromTable("settings", "s_key", "offline_form_criteria", "s_value");
?>

			<br>
			The links below allow you to download documentation for offline use. The documents enable you to determine the extent of the material that will be expected from you, with regards to:
			<ul><li>Applying for reaccreditation</li>
			<li>The institutional profile.</li>
			</ul>
			<span class="visi">
				Please note that you will not be entering information into the HEQC-online system if you use these offline forms to respond to criteria.
			</span>
			<br><br>
			If you use these documents to capture information, please note that you will need to "cut and paste" from the Word document into the online system, for the information to be saved online.
			Please note that formatting (bold, fonts, tables etc) is not preserved.
			<ul>
				<li>To <b>print the form</b> directly, click on the link, and click "Open". A Word document will open up - print as you would from Word.</li>
				<li>To <b>edit the form</b>, click on the link, and click "Save". Navigate to where you would like to save the document on your computer, and click "Save". You will be able to open the document up from your computer (as a normal Word document) and capture information into the tables.</li>
			</ul>

			<img src="images/word.gif">
			<a href="<?php echo $href_app; ?>" target="_blank">
			Download application form for offline use</a>

			<br><br>

			<img src="images/word.gif">
			<a href="<?php echo $href_inst; ?>" target="_blank">
			Download offline institutional profile overview</a>

			<br><br>

			<hr>

			<br>
			<img src="images/acrobat.gif">
			<a href='<?php echo $href_crit; ?>' target="_blank">Download Criteria for programme reaccreditation</a>
			<i>(Requires Adobe Reader)</i>
			<br><br>

			<hr>

			<table border=0 width="100%" cellpadding=2 cellspacing=2 align="center">
				<tr align="right" valign="bottom"><td>
					<a href="http://www.adobe.com/products/acrobat/readstep2.html" target="_blank" class=>
						<img src="images/getacro.gif" border="0">
					</a>
					<br><br>
				</td></tr>
			</table>
		<?php }?>
	</td>
</tr>
</table>

