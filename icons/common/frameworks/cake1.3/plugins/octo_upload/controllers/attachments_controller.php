<?php
class AttachmentsController extends OctoUploadAppController {
	private $__mimeTypes = array(
		'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
		'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
	);

	protected function _setupAuth() {
		foreach(class_parents($this) as $parent){
			if(method_exists($parent,'_setupAuth')){
				parent::_setupAuth();
				break;
			}
		}

		if(isset($this->Auth)) {
			$this->Auth->allow('download');
		}
	}

	public function download($id = null, $originalFile = null) {
		try {
			Configure::write('debug', 0);
			$attachment = $this->Attachment->view($id);
	
			$this->view = 'Media';

			$params = array(
				'id' => $attachment['Attachment']['file_details']['basename'],
				'name' => $attachment['Attachment']['original_file']['filename'],
				'extension' => $attachment['Attachment']['file_details']['extension'],
				'mimeType' => $this->__mimeTypes,
				'path' => $attachment['Attachment']['file_details']['dirname'] . DS,
				'download' => true
			);

			$this->set($params);
		}
		catch(OutOfBoundsException $e) {
			$this->cakeError('error404', array('url' => $originalFile));
		}
	}
}