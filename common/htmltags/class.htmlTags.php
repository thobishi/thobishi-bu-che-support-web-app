<?php

class htmlTags implements Iterator {
	private $html;
	private $tagName;
	private $tags;

	public function __construct($html) {
		$this->html = $html;
	}

	public function getHTML () {
		return $this->html;
	}
	
	public function setTagName ($tagName) {
		$this->$tagName = $tagName;

		$regex = "/<\/?".$this->$tagName."((\s+\w+(\s*=\s*(?:\".*?\"|'.*?'|[^'\">\s]+))?)+\s*|\s*)\/?>/i";

		$matches = array();
		preg_match_all ($regex, $this->html, $matches, PREG_OFFSET_CAPTURE);

		$this->tags = $matches[0];
	}

	public function getArrtib ($attribute) {
		return $this->getAttributeVal($this->current(), $attribute);
	}

	public function replaceTag ($replacementTag) {
		$currentTag = $this->current();
		$spliceStart = $this->position();
		
		$oldLenght = strlen($currentTag);
		$newLenght = strlen($replacementTag);
		
		$this->html = substr_replace($this->html, $replacementTag, $spliceStart, $oldLenght);

		// update the new value of the tag
		$this->tags[$this->key()][0] = $replacementTag;

		$this->updateTagPosistions ($newLenght-$oldLenght);
	}
	
	public function setArrtib ($attribute, $value) {
		$currentTag = $this->current();
		$newTag = $this->replaceAttributeVal($currentTag, $attribute, $value);
		
		$this->replaceTag ($newTag);
	}

	public function stripJavascript () {
		$matches = array();
		$regex = "/(<script\b[^>]*>.*?<\/script>)/siu";
		while (	preg_match ($regex, $this->html, $matches, PREG_OFFSET_CAPTURE) ) {
			$script = $matches[0];
			$this->html = substr_replace($this->html, "", $script[1], strlen($script[0]));
		}
	}
	
	private function updateTagPosistions ($posMoved) {
		for($i=$this->key()+1; $i<count($this->tags); $i++) {
			$this->tags[$i][1] += $posMoved;
		}
	}
	
	private function getAttributeVal($tag, $attribute) {
		$matches = array();
		// This regular expression matches attribute="value" or
		// attribute='value' or attribute=value or attribute
		// It's also constructed so $matches[1][...] will be the
		// attribute names, and $matches[2][...] will be the
		// attribute values.
		preg_match_all('/(\w+)((\s*=\s*".*?")|(\s*=\s*\'.*?\')|(\s*=\s*\w+)|())/s', $tag, $matches, PREG_PATTERN_ORDER);

		for ($i = 0; $i < count($matches[1]); $i++) {
			if (strtolower($matches[1][$i]) == strtolower($attribute)) {
				// Gotta trim off whitespace, = and any quotes:
				$result = ltrim($matches[2][$i], " \n\r\t=");
				if ($result[0] == '"') { $result = trim($result, '"'); }
				else { $result = trim($result, "'"); }
				return $result;
			}
		}
		return false;
	}

	private function replaceAttributeVal($tag, $attribute, $newValue) {
		if ($newValue === null) {
			$pEQv = '';
		}
		else {
			// htmlspecialchars here to avoid potential cross-site-scripting attacks:
			$newValue = htmlspecialchars($newValue);
			$pEQv = $attribute.'="'.$newValue.'"';
		}

		// Same regex as getAttribute, but we wanna capture string offsets
		// so we can splice in the new attribute="value":
		preg_match_all('/(\w+)((\s*=\s*".*?")|(\s*=\s*\'.*?\')|(\s*=\s*\w+)|())/s', $tag, $matches, PREG_PATTERN_ORDER|PREG_OFFSET_CAPTURE);

		for ($i = 0; $i < count($matches[1]); $i++) {
			if (strtolower($matches[1][$i][0]) == strtolower($attribute)) {
				$spliceStart = $matches[0][$i][1];
				$spliceLength = strlen($matches[0][$i][0]);
				$result = substr_replace($tag, $pEQv, $spliceStart, $spliceLength);
				return $result;
			}
		}

		if (empty($pEQv)) { return $tag; }

		// No match: add attribute="newval" to $tag (before closing tag, if any):
		$closed = preg_match('!(.*?)((>|(/>))\s*)$!s', $tag, $matches);
		if ($closed) {
			return $matches[1] . " $pEQv" . $matches[2];
		}
		return "$tag $pEQv";
	}


/* --- Iterations start --- */

	public function rewind() {
		reset($this->tags);
	}

	public function current() {
		$var = current($this->tags);
		return (isset($var[0])?($var[0]):(false));
	}

	public function position() {
		$var = current($this->tags);
		return (isset($var[1])?($var[1]):(false));
	}

	public function key() {
		$var = key($this->tags);
		return $var;
	}

	public function next() {
		$var = next($this->tags);
		return ($var!==false)?((isset($var[0])?($var[0]):(false))):(false);
	}

	public function valid() {
		$var = $this->current() !== false;
		return $var;
	}
/* --- Iterations end --- */


}

?>
