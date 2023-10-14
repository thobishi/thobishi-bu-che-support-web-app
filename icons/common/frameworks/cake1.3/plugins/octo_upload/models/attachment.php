<?php
class Attachment extends OctoUploadAppModel {
/**
 * Name
 *
 * @var string $name
 * @access public
 */
	public $name = 'Attachment';

/**
 * Validation parameters - initialized in constructor
 *
 * @var array
 * @access public
 */
	public $validate = array();

/**
 * Constructor
 *
 * @param mixed $id Model ID
 * @param string $table Table name
 * @param string $ds Datasource
 * @access public
 */
	public function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);
		$this->validate = array(
			'class' => array(
				'notempty' => array('rule' => array('notempty'), 'on' => 'create', 'required' => true, 'allowEmpty' => false, 'message' => __d('octo_upload', 'Please enter a Class', true))),
			'foreign_key' => array(
				'notempty' => array('rule' => array('notempty'), 'on' => 'create', 'required' => true, 'allowEmpty' => false, 'message' => __d('octo_upload', 'Please enter a Foreign Key', true))),
		);
	}

/**
 * Returns the record of a Complaint.
 *
 * @param string $id, complaint id.
 * @return array
 * @throws OutOfBoundsException If the element does not exists
 * @access public
 */
	public function view($id = null) {
		$attachment = $this->find('first', array(
			'conditions' => array(
				"{$this->alias}.{$this->primaryKey}" => $id)
		));

		if (empty($attachment)) {
			throw new OutOfBoundsException(__d('octo_upload', 'Invalid attachment', true));
		}

		if(!file_exists(APP . $attachment['Attachment']['saved_file'])) {
			throw new OutOfBoundsException(__d('octo_upload', 'Invalid attachment', true));
		}

		$attachment['Attachment']['file_details'] = pathinfo(APP . $attachment['Attachment']['saved_file']);
		$attachment['Attachment']['original_file'] = pathinfo($attachment['Attachment']['original_file']);

		return $attachment;
	}
}
