<?php

class SynParse {
/////////////////////////////////////////////////////////////////////////////////////
// Variables
/////////////////////////////////////////////////////////////////////////////////////
	private $synheader;
	private $synarray;

	private $langheader;
	private $langseparator;
	
/////////////////////////////////////////////////////////////////////////////////////
// construct
/////////////////////////////////////////////////////////////////////////////////////
	public function __construct($wikitext, $langcode, $count) {
		$this->setLangParam($langcode);
		$this->extractTextLang($wikitext);
		$this->synarray = $this->extractSyn($count);
		$this->synarray = $this->stripTags();
	}
/////////////////////////////////////////////////////////////////////////////////////
// public methods
/////////////////////////////////////////////////////////////////////////////////////
	public function getSyn() {
		return $this->synarray;
	}
/////////////////////////////////////////////////////////////////////////////////////
// private methods
/////////////////////////////////////////////////////////////////////////////////////
// Extracts synonyms from wikitext
/////////////////////////////////////////////////////////////////////////////////////
	private function extractSyn($count) {
		$synstring = null;	
		$synpattern = "/$this->synheader.*?\n\n/us";
		$itempattern = "/\[\[.*?\]\]/u";
	
	// If pattern returns results, then extract synonyms
		if (preg_match_all($synpattern, $this->wikitext, $synmatch, PREG_PATTERN_ORDER) > 0) {
		// There may be more than one synonym section. Fuse them together as string.
			foreach ($synmatch[0] as $value) {
				$synstring .= $value;
			}
		// Match all double-bracketed words ([[...]])
			if (preg_match_all($itempattern, $synstring, $itemmatch, PREG_PATTERN_ORDER) > 0) {
				return array_slice($itemmatch[0], 0, $count);
			}
			else {
				die("No synonyms could be identified.");
			}
		}	
		else {
			die("No listed synonyms.");
		}
	}
/////////////////////////////////////////////////////////////////////////////////////
// Removes unnecessary string elements from results
/////////////////////////////////////////////////////////////////////////////////////
	private function stripTags() {
		$strippedarray = $this->synarray;
	// Remove first half of entries such as [[...:word]]
		$strippedarray = preg_replace("/\[\[.*?[|:]/u", "", $strippedarray);
	// Remove brackets [[
		$strippedarray = str_replace("[[", "", $strippedarray);
	// Remove brackets ]]
		$strippedarray = str_replace("]]", "", $strippedarray);

		return $strippedarray;
	}
/////////////////////////////////////////////////////////////////////////////////////
// Extracts text based on set language header and separator.
/////////////////////////////////////////////////////////////////////////////////////
	private function extractTextLang($wikitext) {
		include 'extracttextlang.php'; // Sets $this->wikitext
	}
/////////////////////////////////////////////////////////////////////////////////////
// Switch for language parameters.
/////////////////////////////////////////////////////////////////////////////////////
	private function setLangParam($langcode) {
		include './language.config.php';
	}
} // End of class
?>