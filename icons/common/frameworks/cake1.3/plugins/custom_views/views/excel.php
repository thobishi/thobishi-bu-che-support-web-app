<?php
class ExcelView extends View {
	public $PhpExcel = null;
	private $options = array(
		'graphTemplate' => false,
		'filename' => 'output',
		'version' => '2007'
	);

	public function setOption($key, $value) {
		$this->options[$key] = $value;
	}	
	
	public function render($action = null, $layout = null, $file = null)
	{
		ini_set('memory_limit', '2048M');
		set_time_limit(0);

		App::import('Vendor', 'PhpExcel', array('file' => 'PHPExcel' . DS . 'PHPExcel' . DS . 'IOFactory.php'));

		$this->PhpExcel = new PHPExcel();

		$renderedTemplate = parent::render($action, $layout, $file);

		$this->output = '';

		if($this->options['graphTemplate']) {
			$this->graphOutput();
		}
		else {
			$this->standardOutput();
		}
	}

	private function graphOutput() {
		try {
			$tmpLocation = '/tmp/' . time() . rand(0, time()) . '/';

			$graphFile = APP . $this->options['graphTemplate'];

			if(is_readable($graphFile)) {
				$this->unzip($graphFile, $tmpLocation . 'template/');
			}
			else {
				throw new Exception('Could not read template.');
			}

			$objWriter = new PHPExcel_Writer_Excel2007($this->PhpExcel);
			$objWriter->save($tmpLocation . 'source.xlsx');
			$unzippedSource = $this->unzip($tmpLocation . 'source.xlsx', $tmpLocation . 'source/');

			$this->copyFiles($tmpLocation);

			if($this->zip($tmpLocation . 'final.xlsx', $tmpLocation . 'template/')) {
				$file = $tmpLocation . 'final.xlsx';
				header('Content-Description: File Transfer');
				header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				header('Content-Disposition: attachment;filename="'.$this->options['filename'].'.xlsx"');
				header('Content-Transfer-Encoding: binary');
				header('Cache-Control: max-age=0');
				header('Content-Length: ' . filesize($file));
				ob_clean();
				flush();
				readfile($file);
			}
		}
		catch (Exception $e) {
			die($e->getMessage());
		}

		$this->cleanUp($tmpLocation);
	}

	private function cleanUp($dir) {
		$files = scandir($dir);
		array_shift($files);		// remove '.' from array
		array_shift($files);		// remove '..' from array

		foreach($files as $file) {
			$file = $dir . '/' . $file;
			if(is_dir($file)) {
				$this->cleanUp($file);
				rmdir($file);
			}
			else {
				unlink($file);
			}
		}
		rmdir($dir);
	}

	private function listdir($dir='.') {
		if(!is_dir($dir)) {
			return false;
		}

		$dir = rtrim($dir, '/');
		$files = array();
		$this->listdiraux($dir, $files);

		return $files;
	}

	private function listdiraux($dir, &$files) {
		$handle = opendir($dir);
		while(($file = readdir($handle)) !== false) {
			if($file == '.' || $file == '..') {
				continue;
			}
			$filepath = $dir == '.' ? $file : $dir . '/' . $file;

			if(is_link($filepath)){
				continue;
			}

			if(is_file($filepath)) {
				$files[] = $filepath;
			}
			else if(is_dir($filepath)) {
				$this->listdiraux($filepath, $files);
			}
		}
		closedir($handle);
	}

	private function zip ($filename, $location) {
		$files = $this->listdir($location);
		sort($files, SORT_LOCALE_STRING);

		$zip = new ZipArchive();
		if($zip->open($filename, ZIPARCHIVE::OVERWRITE) !== true) {
			throw new Exception("Could not created zip file $filename");
		}
		//add the files
		foreach($files as $file) {
			$zip->addFile($file, str_replace($location, '', $file));
		}

		$zip->close();

		return file_exists($filename);
	}

	private function copyFiles($location) {
		$dest = $location . 'template/xl/worksheets/sheet1.xml';
		$source = $location . 'source/xl/worksheets/sheet1.xml';

		if(!copy($source, $dest)){
			throw new Exception("failed to copy $file...");
		}

		$dest = $location . 'template/xl/sharedStrings.xml';
		$source = $location . 'source/xl/sharedStrings.xml';

		if(!copy($source, $dest)){
			throw new Exception("failed to copy $file...");
		}
	}

	private function unzip($fileName, $location) {
		$zip = new ZipArchive;

		$res = $zip->open($fileName);
		if($res === true) {
			$zip->extractTo($location);
			$zip->close();
			return;
		}
		else{
			throw new Exception('Could not unzip template. Error code: ' . $res);
		}
	}

	private function standardOutput() {
		if($this->options['version'] == '2007')
		{
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

			$objWriter = PHPExcel_IOFactory::createWriter($this->PhpExcel, 'Excel2007');
			$extension = 'xlsx';
		}
		else
		{
			header('Content-Type: application/vnd.ms-excel');

			$objWriter = PHPExcel_IOFactory::createWriter($this->PhpExcel, 'Excel5');
			$extension = 'xls';
		}

		header('Content-Disposition: attachment;filename="'.$this->options['filename'].'.'.$extension.'"');
		header('Cache-Control: max-age=0');

		$objWriter->setPreCalculateFormulas(false);
		$objWriter->save('php://output');
	}
}
