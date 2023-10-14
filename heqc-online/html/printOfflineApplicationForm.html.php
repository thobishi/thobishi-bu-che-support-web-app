<a name="application_form_admin_page"></a>
<table width="95%" border=0 align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>

<?php  $InstRef = $this->getValueFromTable("users", "user_id", $this->currentUserID, "institution_ref");
	$SQL = "SELECT * FROM HEInstitution WHERE HEI_id = ?";
    $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DB_DATABASE);
    if ($conn->connect_errno) {
        $this->error_email ("ERROR: $this->DBname", "$this->DBname database down\n\nMySQL: ".$conn->error () ,$this->DBname);
        printf("Error: %s\n".$conn->error);
        exit();
    }

    $sm = $conn->prepare($SQL);
    $sm->bind_param("s", $InstRef);
    $sm->execute();
    $RS = $sm->get_result();

	//$RS = mysqli_query ($conn, $SQL);
	while ($row = mysqli_fetch_array ($RS)) {
            $inst_priv_publ = $row["priv_publ"];
	}
?>
			<br>
			The links below allow you to download documentation for offline use. The documents enable you to determine the extent of the material that will be expected from you, with regards to:
			<ul><li>Applying for accreditation</li>
			<li>The institutional profile.</li>
			</ul>
			<span class="visi">
				Please note that you will not be entering information into the HEQC-online system if you use these offline forms to respond to criteria.
			</span>
			<br><br>
			If you use these documents to capture information, please note that you will need to "cut and paste" from the Word document into the online system, for the information to be saved online.
			<ul>
				<li>To <b>print the form</b> directly, click on the link, and click "Open". A Word document will open up - print as you would from Word.</li>
				<li>To <b>edit the form</b>, click on the link, and click "Save". Navigate to where you would like to save the document on your computer, and click "Save". You will be able to open the document up from your computer (as a normal Word document) and capture information into the tables.</li>
			</ul>


<?php 			$appl_form  = ($inst_priv_publ == "1") ? "offline_form_private" : "offline_form_public";
			$href_value = $this->getValueFromTable("settings", "s_key", $appl_form, "s_value");
?>


<br>
			
			<b>Until 31 December 2021</b><br>
			
			<img src="images/word.gif">
			<a href="<?php echo $href_value?>" target="_blank">

			<?php 			$appl_form  = ($inst_priv_publ == "1") ? "offline_form_instprofile_private" : "offline_form_instprofile_public";
			$href_value = $this->getValueFromTable("settings", "s_key", $appl_form, "s_value");
?>
			Download application form for offline use</a><br>
			<img src="images/word.gif">
			<a href="<?php echo $href_value?>" target="_blank">
			Download offline institutional profile overview</a>
			<br><br>




<b>From January 2022:</b><br>
			<img src="images/word.gif">
			<a href="documents/APPLICATION FORM FOR PROGRAMME ACCREDITATION.docx" target="_blank">
			Download application form for offline use </a><br>

			<img src="images/word.gif">
			<a href="<?php echo $href_value?>" target="_blank">
			Download offline institutional profile overview</a>

			<br><br>

			<hr>

			<br>
			<img src="images/acrobat.gif">
			<a href='<?php echo $this->getValueFromTable("settings", "s_key", "offline_form_criteria", "s_value");?>' target="_blank">Download Criteria for programme accreditation</a>
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




	</td>
</tr>
</table>

