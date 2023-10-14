<?php
class UploadableBehavior extends ModelBehavior {
	protected $_settings = array();

	private $__defaults = array();

	public function setup(&$Model, $options = array()) {
		$this->__defaults = array(
			'saveLocation' => WWW_ROOT . 'files' . DS . Inflector::tableize($Model->alias) . DS,
			'maxFiles' => 0
		);

		$this->_settings[$Model->alias] = array_merge($this->__defaults, $options);

		if($this->_settings[$Model->alias]['maxFiles'] == 1) {
			$bindType = 'hasOne';
		}
		else {
			$bindType = 'hasMany';
		}
		
		$Model->bindModel(array(
			$bindType => array(
				'Attachment' => array(
					'className' => 'OctoUpload.Attachment',
					'foreignKey' => 'foreign_key',
					'conditions' => array(
						'Attachment.class' => $Model->name
					),
					'dependant' => true
				)
			)
		), false);
	}

	public function afterSave(&$Model, $created) {
		if(isset($Model->data['Attachment'])) {
			if(!is_writable($this->_settings[$Model->alias]['saveLocation'])) {
				throw new Exception(sprintf(__('The location for saving %s attachments is not writable. Please contact the system administrator.', true), Inflector::humanize($Model->alias)));
			}
			
			extract($this->_settings[$Model->alias]);

			if(!isset($Model->data['Attachment'][0])) {
				$attachments = array($Model->data['Attachment']);
			}
			else {
				$attachments = $Model->data['Attachment'];
			}

			if($maxFiles == 1){
				$attachments = array($Model->data['Attachment'][0]);
			}

			unset($attachments['removed']);
			
			foreach($attachments as $attachment) {
				if(!empty($attachment['file']['tmp_name']) && $this->_isUploadedFile($attachment['file']['tmp_name'])) {
					$file = $attachment['file'];
					unset($attachment['file']);

					$attachment['class'] = $Model->name;
					$attachment['foreign_key'] = $Model->id;

					$Model->Attachment->create();
					
					if($Model->Attachment->save($attachment)) {
						$fileInfo = pathinfo($file['name']);
						$savedName = $Model->Attachment->id . '.' . $fileInfo['extension'];

						$this->_uploadFile($file['tmp_name'], $saveLocation . $savedName);

						$fileData = array(
							'Attachment' => array(
								'id' => $Model->Attachment->id,
								'original_file' => $file['name'],
								'saved_file' => str_replace(APP, '', $saveLocation) .  $savedName
							)
						);

						$Model->Attachment->save($fileData);
					}
				}
				elseif(isset($attachment['description']) && isset($attachment['id'])) {
					$Model->Attachment->save($attachment);
				}
			}
		}
	}

	protected function _isUploadedFile($filename) {
		return is_uploaded_file($filename);
	}

	protected function _uploadFile($originalFile, $destinationFile) {
		move_uploaded_file($originalFile, $destinationFile);
	}
}
