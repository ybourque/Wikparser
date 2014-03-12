<?php

/////////////////////////////////////////////////////////////////////////////////////
// This class is used to parse the wikitionary raw data in order to extract hypernyms
// for a given word. Hypernyms are infrequently included in Wiktionary entries.
// See the language.config.php file for setting language specific parameters.
/////////////////////////////////////////////////////////////////////////////////////

class HyperParse {
/////////////////////////////////////////////////////////////////////////////////////
// Variables
/////////////////////////////////////////////////////////////////////////////////////
	private $hyperheader;
	private $hyperarray;

	private $langheader;
	private $langseparator;
	
/////////////////////////////////////////////////////////////////////////////////////
// construct
/////////////////////////////////////////////////////////////////////////////////////
	public function __construct($wikitext, $langcode, $count) {
		$this->setLangParam($langcode);
		$this->extractTextLang($wikitext);
		$this->hyperarray = $this->extractHyper($count);
		$this->hyperarray = $this->stripTags();
	}
/////////////////////////////////////////////////////////////////////////////////////
// public methods
/////////////////////////////////////////////////////////////////////////////////////
	public function getHyper() {
		return $this->hyperarray;
	}
/////////////////////////////////////////////////////////////////////////////////////
// private methods
/////////////////////////////////////////////////////////////////////////////////////
// Extracts hypernyms from wikitext
/////////////////////////////////////////////////////////////////////////////////////
	private function extractHyper($count) {
		$hyperstring = null;	
		$hyperpattern = "/$this->hyperheader.*?\n\n/us";
		$itempattern = "/\[\[.*?\]\]/u";
	
	// If pattern returns results, then extract hypernyms
		if (preg_match_all($hyperpattern, $this->wikitext, $hypermatch, PREG_PATTERN_ORDER) > 0) {
		// There may be more than one hypernym section. Fuse them together as string.
			foreach ($hypermatch[0] as $value) {
				$hyperstring .= $value;
			}
		// Match all double-bracketed words ([[...]])
			if (preg_match_all($itempattern, $hyperstring, $itemmatch, PREG_PATTERN_ORDER) > 0) {
				return array_slice($itemmatch[0], 0, $count);
			}
			else {
				die("No hypernyms could be identified.");
			}
		}	
		else {
			die("No listed hypernyms.");
		}
	}
/////////////////////////////////////////////////////////////////////////////////////
// Removes unnecessary string elements from results
/////////////////////////////////////////////////////////////////////////////////////
	private function stripTags() {
		$strippedarray = $this->hyperarray;
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