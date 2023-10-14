<?php
/**
 * Short description for highlight.php
 *
 * Some minor modifications to allow it to work with php4.
 *
 * PHP version 5
 *
 * Copyright (c) 2008, CHris Schiflett
 *
 * @copyright     Copyright (c) 2007, Chris Schiflett
 * @link          http://shiflett.org/blog/oct/formatting-and-highlighting-php-code-listings
 * @package       mi
 * @subpackage    mi.vendors
 * @since         v 1.0
 * @license       tbd
 */

/*
 * Default CSS to follow:
body {
	margin: 2em;
	padding: 0;
	border: 0;
	font: 1em verdana, helvetica, sans-serif;
	color: #000;
	background: #fff;
	text-align: center;
}
ol.code {
	 width: 90%;
	 margin: 0 5%;
	 padding: 0;
	 font-size: 0.75em;
	 line-height: 1.8em;
	 overflow: hidden;
	 color: #939399;
	 text-align: left;
	 list-style-position: inside;
	 border: 1px solid #d3d3d0;
}
ol.code li {
	 float: left;
	 clear: both;
	 width: 99%;
	 white-space: nowrap;
	 margin: 0;
	 padding: 0 0 0 1%;
	 background: #fff;
}
ol.code li.even { background: #f3f3f0; }
ol.code li code {
	 font: 1.2em courier, monospace;
	 color: #c30;
	 white-space: pre;
	 padding-left: 0.5em;
}
.code .comment { color: #939399; }
.code .default { color: #44c; }
.code .keyword { color: #373; }
.code .string { color: #c30; }
 */

/**
 * highlight class
 *
 * @package       mi
 * @subpackage    mi.vendors
 */
class highlight {

/**
 * highlight method
 *
 * @access public
 * @return void
 */
	public function highlight() {
		$this->__construct();
	}

/**
 * construct method
 *
 * @access private
 * @return void
 */
	public function __construct() {
		ini_set('highlight.comment', 'comment');
		ini_set('highlight.default', 'default');
		ini_set('highlight.keyword', 'keyword');
		ini_set('highlight.string', 'string');
		ini_set('highlight.html', 'html');
	}

/**
 * process method
 *
 * @param string $code
 * @access public
 * @return void
 */
	public function process($code= "") {
		$code= highlight_string($code, TRUE);
		/* Clean Up */
		if (phpversion() >= 5) {
			$code= substr($code, 33, -15);
			$code= str_replace('<span style="color: ', '<span class="', $code);
		} else {
			$code= substr($code, 25, -15);
			$code= str_replace('<font color=', '<span class=', $code);
			$code= str_replace('</font>', '</span>', $code);
		}
		$code= str_replace('&nbsp;', ' ', $code);
		$code= str_replace('&amp;', '&#38;', $code);
		$code= str_replace('<br />', "\n", $code);
		$code= trim($code);
		/* Normalize Newlines */
		$code= str_replace("\r", "\n", $code);
		$code= preg_replace("!\n\n+!", "\n", $code);
		$lines= explode("\n", $code);
		/* Previous Style */
		$previous= FALSE;
		/* Output Listing */
		$return= "  <ol class=\"code\">\n";
		foreach ($lines as $key => $line) {
			if (substr($line, 0, 7) == '</span>') {
				$previous= FALSE;
				$line= substr($line, 7);
			}
			if (empty ($line)) {
				$line= '&#160;';
			}
			if ($previous) {
				$line= "<span class=\"$previous\">" . $line;
			}
			/* Set Previous Style */
			if (strpos($line, '<span') !== FALSE) {
				switch (substr($line, strrpos($line, '<span') + 13, 1)) {
					case 'c' :
						$previous= 'comment';
						break;
					case 'd' :
						$previous= 'default';
						break;
					case 'k' :
						$previous= 'keyword';
						break;
					case 's' :
						$previous= 'string';
						break;
				}
			}
			/* Unset Previous Style Unless Span Continues */
			if (substr($line, -7) == '</span>') {
				$previous= FALSE;
			}
			elseif ($previous) {
				$line .= '</span>';
			}
			if ($key % 2) {
				$return .= "    <li class=\"even\"><code>$line</code></li>\n";
			} else {
				$return .= "    <li><code>$line</code></li>\n";
			}
		}
		$return .= "  </ol>\n";
		return $return;
	}
}
?>