<?php
/***********************************************************************************/
// This class is used to parse the wikitionary raw data in order to extract synonyms
// for a given word. Synonyms are not systematically included in Wiktionary entries.
// See the language.config.php file for setting language specific parameters.
/***********************************************************************************/

class SynParse {
/***********************************************************************************/
// Variables
/***********************************************************************************/
	private $langCode;		// language code (e.g. en, fr, da, etc.)
	private $synHeader; 	// Synonyms header, set in config file
	
/***********************************************************************************/
// construct
/***********************************************************************************/
	public function __construct($langParameters) {
		if (empty($langParameters['synHeader'])) {
			die("ERROR: Synonym parameter is not set for this language in language.config.php.");
		}
		else {
			$this->langCode = $langParameters['langCode'];
			$this->synHeader = $langParameters['synHeader'];
		}
	}
/***********************************************************************************/
// public methods
/***********************************************************************************/
	public function get_syn($wikitext, $count) {
		$synArray = $this->extract_syn($wikitext, $count);
		return $this->strip_tags($synArray);
	}
/***********************************************************************************/
// private methods
/***********************************************************************************/
// Extracts synonyms from wikitext
/***********************************************************************************/
	private function extract_syn($wikitext, $count) {
		$synString = null;
		$synPattern = "/$this->synHeader.*?\n\n/us";
		$itemPattern = "/\[\[.*?\]\]/u";
	
	// If pattern returns results, then extract synonyms
		if (preg_match_all($synPattern, $wikitext, $synMatch, PREG_PATTERN_ORDER) > 0) {
		// There may be more than one synonym section. Fuse them together as string.
			foreach ($synMatch[0] as $value) {
				$synString .= $value;
			}
		// Match all double-bracketed words ([[...]])
			if (preg_match_all($itemPattern, $synString, $itemMatch, PREG_PATTERN_ORDER) > 0) {
				return array_slice($itemMatch[0], 0, $count);
			}
			else {
				die("No synonyms could be identified.");
			}
		}	
		else {
			die("No listed synonyms.");
		}
	}
/***********************************************************************************/
// Removes unnecessary string elements from results
/***********************************************************************************/
	private function strip_tags($synArray) {
		$strippedArray = $synArray;
	// Remove first half of entries such as [[...:word]]
		$strippedArray = preg_replace("/\[\[.*?[|:]/u", "", $strippedArray);
	// Remove brackets [[
		$strippedArray = str_replace("[[", "", $strippedArray);
	// Remove brackets ]]
		$strippedArray = str_replace("]]", "", $strippedArray);

		return $strippedArray;
	}
/***********************************************************************************/
} // End of class
?>