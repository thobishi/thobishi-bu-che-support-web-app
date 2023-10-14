<?php
App::import('Vendor', 'tcPDF', array('file' => 'tcpdf'.DS.'tcpdf.php'));

class CustomPDF extends TCPDF {
	private $__options = array();

	public function setOptions($options) {
		$this->__options = $options;
	}

    public function Header() {
		if($this->__options['backgroundImage'] !== false) {
			// Full background image
			$bMargin = $this->getBreakMargin();
			$auto_page_break = $this->AutoPageBreak;
			$this->SetAutoPageBreak(false, 0);
			$img_file = WWW_ROOT.'img/' . $this->__options['backgroundImage'];
			$this->Image($img_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
			// restore auto-page-break status
			$this->SetAutoPageBreak($auto_page_break, $bMargin);
		}
    }

	public function Footer() {
		// Position at 15 mm from bottom
		$footerText = ($this->__options['footerMSG']) ? $this->__options['footerMSG'] : 'Generated: '.date('Y-m-d');
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(50, 10, $footerText, 0, false, 'L', 0, '', 0, false, 'T', 'M');
        $this->Cell(50, 10, 'Page '.$this->getAliasNumPage(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
	}
}

class PdfView extends View {
	private $__options = array(
		'backgroundImage' => false,
		'hasHeader' => false,
		'hasFooter' => false,
		'orientation' => false,
		'footerMSG' => false
	);

	function render($action = null, $layout = null, $file = null)
	{
		ini_set('memory_limit', '1024M');
		set_time_limit(0);

		if($this->getVar('options')) {
			$this->__options = array_merge($this->__options, $this->getVar('options'));
		}

		// create new PDF document
		$orientation = ($this->__options['orientation']) ? $this->__options['orientation'] : PDF_PAGE_ORIENTATION;
		$this->Pdf = new CustomPDF($orientation, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', true);

		$renderedTemplate = parent::render($action, $layout, $file);
		$this->output = '';

		$this->Pdf->setFontSubsetting(false);

		$this->Pdf->setOptions($this->__options);

		// set document information
		$this->Pdf->SetCreator('Octoplus');
		$this->Pdf->SetAuthor('Octoplus');
		$this->Pdf->SetTitle('');
		$this->Pdf->SetSubject('');
		$this->Pdf->SetKeywords('');

		$this->Pdf->SetFont('helvetica');
		$this->Pdf->setPrintHeader($this->__options['hasHeader']);
		$this->Pdf->setPrintFooter($this->__options['hasFooter']);

		//set margins
		$this->Pdf->SetMargins(10, 15, 10);

		//set auto page breaks
		$this->Pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		//set image scale factor
		$this->Pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		$this->Pdf->AddPage();

		$this->Pdf->writeHTML($renderedTemplate, true, 0, true, 0);

		$renderedTemplate = '';
		//$this->Pdf->lastPage();

		$this->output = $this->Pdf->Output($this->getVar('filename') . '.pdf', 'D');

		return false;
	}
}