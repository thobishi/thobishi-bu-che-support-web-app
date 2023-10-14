<?php

require_once('/var/www/html/common/_systems/heqc-online.php');

//file_put_contents('php://stderr', print_r("class.octoGenDocGen OCTODOCGEN_URL : ".OCTODOCGEN_URL, TRUE));
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
		$f = fopen('/tmp/'.$this->xml.".txt","wb") or die("Unable to open file!");
		//$f=fopen('/tmp/'.$this->xml.".txt","wb");
		fwrite($f,$content);
		fclose($f);
	}

	public function generateDoc () {
		if (!$this->mayGen) return false;
ob_start();
ob_end_clean();
		set_time_limit(0);

		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: public");
		header('Content-Type: text/html; charset=utf-8');
		header("Content-Description: File Transfer");
		header("Content-Type: application/octet-stream");
		header("Content-Disposition: attachment; filename=\"".$this->xml.".rtf\"");

		$parm = base64_decode($this->parameters);
		$file = OCTODOCGEN_URL.$this->xml.".php?".$parm;
		$xml_template = join("",file($file));
		
		
		//echo $xml_template; 
		file_put_contents('php://stderr', print_r("xml_template: \n".$xml_template."\n", TRUE));

		// Robin 25/7/2007 For development purposes to see xml generated.  Take out once report works nicely.
		//$this->write_file($xml_template);
       $xml=$this->cleanStr($xml_template);
		// creating class object specifying the driver type - "RTF"
		    $xml_temp = str_replace('&nbsp;', '', $xml);
			$xml_temp =str_replace('•', '', $xml);
$this->write_file($xml_temp);
		     //$xml_temp =str_replace('•', '', $xml_template); - commented by Robin.  Change $xml_template to $xml_temp to change the one with &nbsp; changed.
		  //  $xml_temp = str_replace('â€œ', '', $xml_template);
		  //  $xml_temp = str_replace('â€', '', $xml_template);
		   // $xml_temp = str_replace('â', '', $xml_template);
		   
		   	$xml_tmp = mb_convert_encoding($xml_temp, 'UTF-8', 'UTF-8');

		    
             $xml = new nDOCGEN($xml_temp,"RTF"); 
                 
		//$xml = $xml_template;

	//	file_put_contents('php://stderr', print_r("xml \n".$xml, TRUE));
	
			
		//	echo $xml_template;    
		echo $xml->get_result_file();

		return true;
	}
	
	
	function cleanStr($value){
    $value = str_replace('&nbsp;', '', $value);
    $value = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $value);
    return $value;
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

		file_put_contents('php://stderr', print_r("\nfile : ".$file, TRUE));
		
		$xml_template = join("",file($file));

		//file_put_contents('php://stderr', print_r("\nxml_template : ".$xml_template, TRUE));
		// Robin 25/7/2007 For development purposes to see xml generated.  Take out once report works nicely.
		$this->write_file($xml_template);
        //file_put_contents('php://stderr', print_r("\nT.H.E F.I.L.E H.A.S B.E.E.N W.R.I.T.T.E.N T.O D.I.S.K", TRUE));
		//file_put_contents('php://stderr', print_r("\nxml_template : ".$xml_template, TRUE));
		// creating class object specifying the driver type - "RTF"
		$xml = new nDOCGEN($xml_template, "RTF");
		
		
		
		
		//file_put_contents('php://stderr', print_r("\nxml-get_result_file : ".$xml->get_result_file(), TRUE));
		
		$fp = fopen($filename,"w");
		fwrite($fp, $xml->get_result_file());
		//fwrite($fp, $xml);
		fclose($fp);

		return true;
	}
	
	function new_xml_parser($file) {
		global $doc_wrap;
		global $parser_file;
		$xml_parser = xml_parser_create('UTF-8');
		xml_parser_set_option($xml_parser, XML_OPTION_TARGET_ENCODING, "UTF-8");
		xml_parser_set_option($xml_parser, XML_OPTION_CASE_FOLDING, 0);
		xml_parser_set_option($xml_parser,XML_OPTION_SKIP_WHITE,1);
		xml_set_element_handler($xml_parser, "startElement", "endElement");
		xml_set_character_data_handler($xml_parser, "characterData");
		xml_set_processing_instruction_handler($xml_parser, "PIHandler");
		xml_set_unparsed_entity_decl_handler($xml_parser, "test_ent");
		xml_set_default_handler($xml_parser, "defaultHandler");
		xml_set_external_entity_ref_handler($xml_parser, "externalEntityRefHandler");
	 
		if ($file == "") { return false; }
		if (!is_array($parser_file)) { settype($parser_file, "array"); }
		$parser_file[$xml_parser] = $file;
		return $xml_parser;
	} // end of functi
	
	
	
	
	
	
	public function url ($desc, $path="") {
		if (!$this->xml) return false;

		$token = octoToken::create($this->xml."_".$this->parameters, "Document");
		$parm = base64_encode( $this->parameters );
		echo '<a href="'.$path.'document.php?r='.$this->xml.'&p='.$parm.'&token='.$token.'" target="_blank">'.$desc.'</a>';
	}

	public function checkToken ($token) {
		$parm = base64_decode($this->parameters);
		file_put_contents('php://stderr', print_r("base64 : ".$parm, TRUE));
		$this->mayGen= false;

		if ($token == octoToken::create($this->xml."_".$parm, "Document")) {
			file_put_contents('php://stderr', print_r("token : ".$token, TRUE));
			$this->mayGen = true;
		}

		return $this->mayGen;

	}

}
?>
