<?php
/* Attachment Test cases generated on: 2010-10-11 13:10:30 : 1286796510*/
App::import('Model', 'OctoUpload.Attachment');

App::import('Lib', 'Templates.AppTestCase');
class AttachmentTestCase extends AppTestCase {
/**
 * Autoload entrypoint for fixtures dependecy solver
 *
 * @var string
 * @access public
 */
	public $plugin = 'OctoUpload';

/**
 * Test to run for the test case (e.g array('testFind', 'testView'))
 * If this attribute is not empty only the tests from the list will be executed
 *
 * @var array
 * @access protected
 */
	protected $_testsToRun = array();

/**
 * Start Test callback
 *
 * @param string $method
 * @return void
 * @access public
 */
	public function startTest($method) {
		parent::startTest($method);
		$this->Attachment = AppMock::getTestModel('Attachment');
		$fixture = new AttachmentFixture();
		$this->record = array('Attachment' => $fixture->records[0]);
	}

/**
 * End Test callback
 *
 * @param string $method
 * @return void
 * @access public
 */
	public function endTest($method) {
		parent::endTest($method);
		unset($this->Attachment);
		ClassRegistry::flush();
	}

	
}
?>