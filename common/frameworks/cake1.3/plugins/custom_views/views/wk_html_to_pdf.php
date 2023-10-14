<?php
App::import('Vendor', 'wkhtmltopdf');

class WkHtmlToPdfView extends View {
	private $options = array(
		'backgroundImage' => null,
		'headerElement' => null,
		'footer' => array(
			'font-size' => 8,
			'spacing' => 5
		),
		'orientation' => 'Portrait'
	);

	function render($action = null, $layout = null, $file = null)
	{
		if($this->getVar('options')) {
			$this->options = Set::merge($this->options, $this->getVar('options'));
		}

		// create new PDF document

		$this->Pdf = new Wkhtmltopdf(array(
			'path' => '/tmp/',
			'orientation' => $this->options['orientation']
		));
		$renderedTemplate = parent::render($action, $layout, $file);
		$this->output = '';
		
		if($this->getVar('options')) {
			$this->options = Set::merge($this->options, $this->getVar('options'));
		}		

		$this->Pdf->setHtml($renderedTemplate);
		$this->Pdf->setTitle($this->getVar('title_for_layout'));

		if($this->options['footer']) {
			$this->Pdf->setFooter($this->options['footer']);
		}
		$this->Pdf->output(Wkhtmltopdf::MODE_DOWNLOAD,$this->getVar('filename') . '.pdf');

		return false;
	}
}