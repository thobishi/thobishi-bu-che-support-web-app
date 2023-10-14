<?php
/**
 * Short description for highlight.php
 *
 * Long description for highlight.php
 *
 * PHP version 5
 *
 * Copyright (c) 2008, Andy Dawson
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) 2008, Andy Dawson
 * @link          www.ad7six.com
 * @package       mi
 * @subpackage    mi.views.helpers
 * @since         v 1.0
 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/**
 * HighlightHelper class
 *
 * @uses          AppHelper
 * @package       mi
 * @subpackage    mi.views.helpers
 */
class HighlightHelper extends AppHelper {

/**
 * name variable
 *
 * @var string
 * @access public
 */
	public $name = 'Highlight';

/**
 * auto variable
 *
 * @var bool
 * @access public
 */
	public $auto = true;

/**
 * construct function
 *
 * @param mixed $one
 * @param mixed $two
 * @param mixed $three
 * @access private
 * @return void
 */
	public function __construct($one = null, $two = null, $three = null) {
		parent::__construct($one, $two, $three);
		App::import('Vendor', 'Mi.Highlight');
		$this->highlight = new highlight();
	}

/**
 * afterRender function
 *
 * @access public
 * @return void
 */
	public function afterRender() {
		if ($this->auto) {
			$text = @ ob_get_clean();
			ob_start();
			echo $this->auto($text);
		}
	}

/**
 * auto function
 *
 * @param mixed $text
 * @access public
 * @return void
 */
	public function auto($text) {
		$this->auto = false; // avoid double processing
		preg_match_all('/(<pre>)([\\s\\S]*?)(<\\/pre>)/i',  $text, $result, PREG_PATTERN_ORDER);
		if (!empty($result['0'])) {
			$count = count($result['0']);
			for($i = 0; $i < $count; $i++) {
				$result['2'][$i] = str_replace('<', '&lt;', $result['2'][$i]); // ensure escaping
				$highlighted = '<pre class="code">' . $result['2'][$i] . '</pre>';

				$stripStart = false;
				if (strpos($result['2'][$i], '&lt;?') === false) {
					$stripStart = true;
					// add 2 dummy lines so that the odd-even is in sync when removed
					$result['2'][$i] = '<?php ' . "\r\n"  . '$x=$y;' . "\r\n" . $result['2'][$i];
				}
				$highlighted .= $this->process(html_entity_decode($result['2'][$i]));

				if ($stripStart) {
					$highlighted = str_replace('<li><code><span class="default">&lt;?php </span></code></li>', '', $highlighted);
					$highlighted = str_replace('<li class="even"><code><span class="default">$x</span><span class="keyword">=</span><span class="default">$y</span><span class="keyword">;</span></code></li>', '', $highlighted);
				}
				$text = str_replace($result[0][$i], $highlighted, $text);
			}
		}
		// Prevent cache poisoning
		$text = str_replace('<?php ', '&lt;?', $text);
		return $text;
	}

/**
 * process function
 *
 * @param mixed $text
 * @access public
 * @return void
 */
	public function process($text) {
		return $this->highlight->process($text);
	}
}