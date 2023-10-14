<?php
App::import('Core', array('AppModel', 'Model'));

App::import('Behavior', 'OctoUpload.Uploadable');

function rrmdir($path)
{
  return is_file($path)?
    @unlink($path):
    array_map('rrmdir',glob($path.'/*'))==@rmdir($path) ;
}

class MockUploadableBehavior extends UploadableBehavior {
	protected function _isUploadedFile($filename) {
		return file_exists($filename);
	}

	protected function _uploadFile($originalFile, $destinationFile) {
		rename($originalFile, $destinationFile);
	}
}

App::import('Lib', 'Templates.AppTestCase');
class UploadableBehaviorTest extends AppTestCase {
	
/**
 * Autoload entrypoint for fixtures dependecy solver
 *
 * @var string
 * @access public
 */
	public $plugin = 'octo_upload';

/**
 * Test to run for the test case (e.g array('testFind', 'testView'))
 * If this attribute is not empty only the tests from the list will be executed
 *
 * @var array
 * @access protected
 */
	protected $_testsToRun = array();

/**
 * Method executed before each test
 *
 * @access public
 */
	function startTest($method) {
		parent::startTest($method);

		$this->Advark = ClassRegistry::init('Advark');
		$advarkFixture = new AdvarkFixture();
		$attachmentFixture = new AttachmentFixture();
		$this->record = array(
			'Advark' => $advarkFixture->records[0],
			'Attachment' => array(
				$attachmentFixture->records[0]
			)
		);

		mkdir('/tmp/cakeTest');
	}

/**
 * Method executed after each test
 *
 * @access public
 */
	function endTest() {
		unset($this->Advark);

		ClassRegistry::flush();

		rrmdir('/tmp/cakeTest');
	}

	function testSetup() {
		$this->Advark->Behaviors->attach('MockUploadable');

		$this->assertTrue(isset($this->Advark->Attachment));
		$this->assertIsA($this->Advark->Attachment, 'Attachment');
	}

	function testFind() {
		$this->Advark->Behaviors->attach('MockUploadable');

		$results = $this->Advark->find('all', array('recursive' => 1));
		$this->assertEqual($this->record['Attachment'], $results[0]['Attachment']);
	}

	function testValidSave() {
		$this->Advark->Behaviors->attach('MockUploadable', array('saveLocation' => '/tmp/cakeTest/'));

		$data = array(
			'Advark' => $this->record['Advark'],
			'Attachment' => array(
				array(
					'file' => array(
						'name' => 'file-1.txt',
						'tmp_name' => '/tmp/file-1.txt',
						'type' => 'plain/text',
						'size' => 8,
						'error' => 0
					),
					'description' => 'File 1'
				),
				array(
					'file' => array(
						'name' => 'file-2.txt',
						'tmp_name' => '/tmp/file-2.txt',
						'type' => 'plain/text',
						'size' => 8,
						'error' => 0
					),
					'description' => 'File 2'
				),
			)
		);
		unset($data['Advark']['id']);
		unset($data['Advark']['created']);
		unset($data['Advark']['modified']);

		$file1Contents = rand(0,10000);
		$file2Contents = rand(0,10000);
		file_put_contents('/tmp/file-1.txt', $file1Contents);
		file_put_contents('/tmp/file-2.txt', $file2Contents);

		$result = $this->Advark->save($data);
		$this->assertTrue($result);

		$advark = $this->Advark->find('first', array('order' => 'Advark.created DESC', 'recursive' => 1));

		$this->assertTrue(isset($advark['Attachment']));
		$this->assertTrue(count($advark['Attachment']) == 2);

		$this->assertTrue(!empty($advark['Attachment'][0]['saved_file']));
		$this->assertTrue(file_exists('/tmp/cakeTest/' . $advark['Attachment'][0]['saved_file']));

		$this->assertEqual(file_get_contents('/tmp/cakeTest/' . $advark['Attachment'][0]['saved_file']), $file1Contents);
	}
}