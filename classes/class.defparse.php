<?php
/***********************************************************************************/
// This class is used to extract all definitions for a word.
// See the language.config.php file for setting language specific parameters.
/***********************************************************************************/

class DefParse {
/***********************************************************************************/
// Variables
/***********************************************************************************/
	private $langCode;		// language code (e.g. en, fr, da, etc.)
	private $defHeader; 	// definitions header, set in config file
	private $defTag;		// definitions tag, set in config file
	
/***********************************************************************************/
// construct
/***********************************************************************************/
	public function __construct($langParameters) {
		if (empty($langParameters['defHeader']) && empty($langParameters['defTag'])) {
			die("ERROR: Definition parameters are not set for this language in language.config.php.");
		}
		else {
			$this->langCode = $langParameters['langCode'];
			$this->defHeader = $langParameters['defHeader'];
			$this->defTag = $langParameters['defTag'];
		}
	}
/***********************************************************************************/
// public methods
/***********************************************************************************/
	public function getDef($wikitext, $count) {
		$defArray = $this->extract_def($wikitext, $count);
		return $this->strip_tags($defArray);
	}
/***********************************************************************************/
// private methods
/***********************************************************************************/
// Extracts all definitions by splitting at new lines and matching for definition
// tags set in paramaters.
/***********************************************************************************/
	private function extract_def($wikitext, $count) {
		$defArray = array();
		if (!empty($this->defHeader)) {
			$sectionPattern = "(".preg_quote($this->defHeader).".*?\n\n)s";
		// Find all matches for header + text until double newline.	
			preg_match_all($sectionPattern, $wikitext, $sectionMatches);
			if ($sectionMatches) {
				$defPattern = "/\n".str_replace(" ", "\s", preg_quote($this->defTag)).".*/";
				foreach ($sectionMatches[0] as $value) {
				// Find all matches for deftag + text until newline.	
					preg_match_all($defPattern, $value, $defMatches);
					if ($defMatches) {
						foreach ($defMatches[0] as $value) {
							$defArray[] = $value;
						}
					}
				}
				return array_slice($defArray, 0, $count);
			}
			else {
				die("No definitions section found.");
			}
		}
		else {		
			$defPattern = "/\n".str_replace(" ", "\s", preg_quote($this->defTag)).".*/";
			preg_match_all($defPattern, $wikitext, $matches);
			if ($matches) {
				return array_slice($matches[0], 0, $count);
			}
		}
	}
/***********************************************************************************/
// Strips tags used for additional info and links to other words.
/***********************************************************************************/
	private function strip_tags($defArray) {
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
} // End of class.
?>