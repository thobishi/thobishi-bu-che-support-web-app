<?php

if (!defined('OCTODOCGEN_URL')) die ('ERROR: system can not read the DocGen path');
// we use SYS_PATH for the relative path

class octoDocGen {
	private $xml, $parameters;
	private $mayGen;

	function __construct($xml, $parameters) {
		$this->mayGen = false;
		$this->xml = $xml;
		$this->parameters = $parameters;
	}

	private function write_file($content){
		$f=fopen('/tmp/'.$this->xml.".txt","wb");
		fwrite($f,$content);
		fclose($f);
	}

	public function generateDoc () {
		if (!$this->mayGen) return false;

		set_time_limit(0);

		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: public");
		header("Content-Description: File Transfer");
		header("Content-Type: application/octet-stream");
		header("Content-Disposition: attachment; filename=\"".$this->xml.".rtf\"");

		$parm = base64_decode($this->parameters);
		$file = OCTODOCGEN_URL.$this->xml.".php?".$parm;
		$xml_template = join("",file($file));

		// Robin 25/7/2007 For development purposes to see xml generated.  Take out once report works nicely.
		$this->write_file($xml_template);

		// creating class object specifying the driver type - "RTF"
		$xml = new nDOCGEN($xml_template,"RTF");

		echo $xml->get_result_file();

		return true;
	}

	public function saveDoc ($filename) {
		//if (!$this->mayGen) return false;

		set_time_limit(0);

		//header("Pragma: public");
		//header("Expires: 0");
		//header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		//header("Cache-Control: public");
		//header("Content-Description: File Transfer");
		//header("Content-Type: application/octet-stream");
		//header("Content-Disposition: attachment; filename=\"".$this->xml.".rtf\"");

		//$parm = base64_decode($this->parameters);
		$parm = $this->parameters;
		$file = OCTODOCGEN_URL.$this->xml.".php?".$parm;

		$xml_template = join("",file($file));

		// Robin 25/7/2007 For development purposes to see xml generated.  Take out once report works nicely.
		//$this->write_file($xml_template);

		// creating class object specifying the driver type - "RTF"
		$xml = new nDOCGEN($xml_template,"RTF");
		
		$fp = fopen($filename,"w");
		fwrite($fp, $xml->get_result_file());
		fclose($fp);

		return true;
	}
	
	public function url ($desc, $path="") {
		if (!$this->xml) return false;

		$token = octoToken::create($this->xml."_".$this->parameters, "Document");
		$parm = base64_encode( $this->parameters );
		echo '<a href="'.ROOT_URL.'document.php?r='.$this->xml.'&p='.$parm.'&token='.$token.'" target="_blank">'.$desc.'</a>';
	}

	public function checkToken ($token) {
		$parm = base64_decode($this->parameters);

		$this->mayGen= false;

		if ($token == octoToken::create($this->xml."_".$parm, "Document")) {
			$this->mayGen = true;
		}

		return $this->mayGen;

	}

}
?>
