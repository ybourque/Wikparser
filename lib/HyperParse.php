<?php
namespace ybourque\Wikparser\lib;

/***********************************************************************************/
// This class is used to parse the wikitionary raw data in order to extract hypernyms
// for a given word. Hypernyms are infrequent in Wiktionary entries.
// See the language.config.php file for setting language specific parameters.
/***********************************************************************************/

class HyperParse {
/***********************************************************************************/
// Variables
/***********************************************************************************/
	private $langCode;			// language code (e.g. en, fr, da, etc.)
	private $hyperHeader;		// Hypernyms header, set in config file

/***********************************************************************************/
// construct
/***********************************************************************************/
	public function __construct($langParameters) {
		if (empty($langParameters['hyperHeader'])) {
			die("ERROR: Hypernym parameter is not set for this language in language.config.php.");
		}
		else {
			$this->langCode = $langParameters['langCode'];
			$this->hyperHeader = $langParameters['hyperHeader'];
		}
	}
/***********************************************************************************/
// public methods
/***********************************************************************************/
	public function getHyper($wikitext, $count) {
		$hyperArray = $this->extractHyper($wikitext, $count);
		return $this->stripTags($hyperArray);
	}
/***********************************************************************************/
// private methods
/***********************************************************************************/
// Extracts hypernyms from wikitext
/***********************************************************************************/
	private function extractHyper($wikitext, $count) {
		$hyperString = null;
		$hyperPattern = "/$this->hyperHeader.*?\n\n/us";
		$itemPattern = "/\[\[.*?\]\]/u";

	// If pattern returns results, then extract hypernyms
		if (preg_match_all($hyperPattern, $wikitext, $hyperMatch, PREG_PATTERN_ORDER) > 0) {
		// There may be more than one hypernym section. Fuse them together as string.
			foreach ($hyperMatch[0] as $value) {
				$hyperString .= $value;
			}
		// Match all double-bracketed words ([[...]])
			if (preg_match_all($itemPattern, $hyperString, $itemMatch, PREG_PATTERN_ORDER) > 0) {
				return array_slice($itemMatch[0], 0, $count);
			}
			else {
				return array();
			}
		}
		else {
			return array();
		}
	}
/***********************************************************************************/
// Removes unnecessary string elements from results
/***********************************************************************************/
	private function stripTags($hyperArray) {
		$strippedArray = $hyperArray;
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