<?php
namespace ybourque\Wikparser\lib;
/***********************************************************************************/
// This class is used to extract all definitions for a word.
// See the language.config.php file for setting language specific parameters.
/***********************************************************************************/

class DefParse {
/***********************************************************************************/
// Variables
/***********************************************************************************/
	private $sectionPattern; 	// definitions header, set in config file
	private $defTag;		// definitions tag, set in config file

/***********************************************************************************/
// construct
/***********************************************************************************/
	public function __construct($langParameters) {
		if (empty($langParameters['defHeader']) && empty($langParameters['defTag'])) {
			die("ERROR: Definition parameters are not set for this language in language.config.php.");
		}

		// Find all matches for header + text until double newline.
		if ($langParameters['defHeader']) {
			// use an explicit definition header string
			$this->sectionPattern = '/('.preg_quote($this->defHeader).'.*?\n\n)/su';
		} elseif ($langParameters['posPattern']) {
			// some languages, such as french, have definitions after the part-of-speech
			$this->sectionPattern = "/(" . $langParameters['posPattern'] . '.*?\n\n)/su';
		}

		$this->defTag = $langParameters['defTag'];
		$this->tagPattern = "/\n".str_replace(" ", "\s", preg_quote($this->defTag)).".*/u";
	}

/***********************************************************************************/
// public methods
/***********************************************************************************/
	public function getDef($wikitext, $count) {
		$defArray = $this->extractDef($wikitext, $count);
		return $this->stripTags($defArray);
	}
/***********************************************************************************/
// private methods
/***********************************************************************************/
// Extracts all definitions by splitting at new lines and matching for definition
// tags set in paramaters.
/***********************************************************************************/
	private function extractDef($wikitext, $count) {
		if ($this->sectionPattern) {
			return $this->extractDefSection($wikitext, $count);
		}

		// no section pattern, examine entire wikitext
		preg_match_all($this->tagPattern, $wikitext, $matches);
		if ($matches) {
			return array_slice($matches[0], 0, $count);
		} else {
			return array();
		}
	}

	private function extractDefSection($wikitext, $count)
	{
		preg_match_all($this->sectionPattern, $wikitext, $sectionMatches);
		if (! $sectionMatches) {
			return array();
		}

		$defArray = array();
		foreach ($sectionMatches[0] as $value) {
			// Find all matches for deftag + text until newline.
			preg_match_all($this->tagPattern, $value, $tagMatches);
			if ($tagMatches) {
				foreach ($tagMatches[0] as $value) {
					$defArray[] = $value;
				}
			}
		}
		return array_slice($defArray, 0, $count);
	}

/***********************************************************************************/
// Strips tags used for additional info and links to other words.
/***********************************************************************************/
	private function stripTags($defArray) {
	// Strip anything enclosed between {{ }}
		$strippedArray = preg_replace('(\{\{.*?\}\})', "", $defArray);
	// Remove 1st half of [[word|Word]] strings.
		$strippedArray = preg_replace('(\[\[[^\]]*?\|)u', "", $strippedArray);
	// Remove brackets [[
		$strippedArray = str_replace("[[", "", $strippedArray);
	// Remove brackets ]]
		$strippedArray = str_replace("]]", "", $strippedArray);
	// Remove definition identifier
		$strippedArray = str_replace($this->defTag, "", $strippedArray);

		return $strippedArray;
	}
/***********************************************************************************/
}
