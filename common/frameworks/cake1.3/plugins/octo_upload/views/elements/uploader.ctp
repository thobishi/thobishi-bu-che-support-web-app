<?php
	$default = array(
		'maxFiles' => 0,
		'hideDescription' => false,
		'fileLabel' => __d('octo_upload', 'File', true),
		'descriptionLabel' => __d('octo_upload', 'File description', true),
		'Model' => null
	);
	
	$options = array_merge($default, $options);
	
	extract($options);
?>

<?php
	echo '<div id="octoUploaderFiles">';
		if(isset($this->data['Attachment']) && $maxFiles != 1 && count($this->data['Attachment']) > 0) {
			$numberUploads = count($this->data['Attachment']);
		}
		else {
			if(isset($this->data['Attachment']['id'])) {
				$this->data['Attachment'][0] = $this->data['Attachment'];
			}
			$numberUploads = 1;
		}
	
		for($upload = 0;$upload < $numberUploads; $upload++) {
			echo '<div class="octoUploaderFileBlock">';
				echo $this->Form->button(__d('octo_upload', 'Remove file', true), array('class' => 'octoUploadDelete', 'type' => 'button', 'style' => 'float:right;'.($numberUploads == 1 ? 'display:none' : '')));

				if(!empty($this->data['Attachment'][$upload]['original_file'])) {
					$after = '<span class="octoUploaderCurrentFile"><b>'.__d('octo_upload', 'Existing file:', true) . ' </b>';
					$after .= $this->Html->link(
						$this->data['Attachment'][$upload]['original_file'],
						array('controller' => 'attachments', 'action' => 'download', 'plugin' => 'octo_upload', 'admin' => false, $this->data['Attachment'][$upload]['id'], $this->data['Attachment'][$upload]['original_file'])
					);
					$after .= '<br>'.__d('octo_upload', 'You can upload a new file if you wish to replace the existing one', true).'</span>';

					echo $this->Form->input('Attachment.'.$upload.'.id', array('class'=> 'octoUploaderId', 'value' => $this->data['Attachment'][$upload]['id']));
				}
				else {
					$after = '';
				}

				echo $this->Form->input('Attachment.'.$upload.'.file', array(
					'type' => 'file',
					'label' => $fileLabel,
					'after' => $after
				));
				if(!$hideDescription) {
					echo $this->Form->input('Attachment.'.$upload.'.description', array('label' => $descriptionLabel, 'value' => (isset($this->data['Attachment'][$upload]['description']) ? $this->data['Attachment'][$upload]['description'] : '')));
				}
			echo '</div>';
		}

	echo '</div>';

	if($maxFiles != 1) {
		echo $this->Form->button(__d('octo_upload', 'Add another file', true), array('id' => 'octoUploaderAdd', 'type' => 'button'));
	}
?>

<?php if($maxFiles != 1) { ?>
	<span id="octoUploaderMaxFiles" style="display:none;"><?php echo $maxFiles; ?></span>

	<?php
		echo $this->Form->input('Attachment.removed', array('type' => 'hidden', 'id' => 'octoUploadRemoved'));
	?>

	<script type="text/javascript">
	$(function() {
		var $octoUploaderTemplate = $('div.octoUploaderFileBlock:first')
			.clone()
			.hide()
			.find('.octoUploaderCurrentFile')
				.remove()
			.end()
			.find('.octoUploaderId')
				.remove()
			.end();
			
		var maxFiles = parseInt($('#octoUploaderMaxFiles').text());

		var numberUploads = $('.octoUploaderFileBlock').length;

		$('#octoUploaderAdd').click(function(e) {
			e.preventDefault();
			
			if($('.octoUploaderFileBlock').length <= maxFiles || maxFiles == 0) {
				var $appender = $octoUploaderTemplate.clone();
				var $inputs = $appender.find(':input:not(button)');

				$inputs.each(function() {
					var $this = $(this);
					var $label = $this.prev('label');
					
					$this
						.attr('id', $this.attr('id').replace(/[0-9]+/, numberUploads))
						.attr('name', $this.attr('name').replace(/[0-9]+/, numberUploads));

					$label
						.attr('for', $label.attr('for').replace(/[0-9]+/, numberUploads));
				});

				$appender.appendTo('#octoUploaderFiles').fadeIn('fast', function() {});
				
				if($('.octoUploaderFileBlock').length > 1) {
					$('.octoUploadDelete').fadeIn('fast');
				}

				numberUploads++;
			}
			else {
				$(this).attr('disabled', true);
			}
		});

		$('#octoUploaderFiles').delegate('.octoUploadDelete', 'click', function(e) {
			e.preventDefault();

			if($('.octoUploaderFileBlock').length > 1) {
				var $parent = $(this).closest('div.octoUploaderFileBlock');

				var $idField = $parent.find('.octoUploaderId');

				if($idField.length > 0) {
					$('#octoUploadRemoved').val($('#octoUploadRemoved').val() + ';' + $idField.val());
				}

				$parent.fadeOut('fast', function() {
					$(this).remove()
					if($('.octoUploaderFileBlock').length <= 1) {
						$('.octoUploadDelete').fadeOut('fast');
					}
				});
			}
		});
	});
	</script>
<?php } ?>
