<?php
	$addlink = $this->scriptGetForm ('institutional_profile_sites', 'NEW', '_startEditAdditionalSites');
	$blink = '<input type="button" class="btn" name="add" value="Add Additional Sites of Delivery" onClick=' . "'" . $addlink . "'>";
?>
<table width="95%" border="0" align="center" cellpadding="2" cellspacing="2">
<tr>
	<td>
		<br>
		<span class="specialb">Additional Sites of Delivery:</span>
		<br>
		<br>
		Please capture any additional sites of delivery here when applying to CHE for new or additional sites of delivery.
		Note: You may delete sites of delivery up until the site application has been approved or any applications have been specified as being offered on the sites.
		<br>
		<table width="95%" border="1" align="center" cellpadding="2" cellspacing="2">
		<?php
			echo $content;
		?>
		<tr>
			<td colspan="2">
				<?php echo $blink; ?>
			</td>
		</tr></table>
		<br><br>
	</td>
</tr>
</table>
