<?php

/////////////////////////////////////////////////////////////////////////////////////
// This class is used to parse the wikitionary raw data in order to extract parts
// of speech for a given word.
// See the language.config.php file for setting language specific parameters.
/////////////////////////////////////////////////////////////////////////////////////

class PosParse {
/////////////////////////////////////////////////////////////////////////////////////
// Variables
/////////////////////////////////////////////////////////////////////////////////////
	private $count;				// part of speech, used to limit number of POS returned
	private $posmatchtype;		// set to either preg pattern or array with values
	private $posresults;		// extracted parts of speech for a given word
	private $posextrastring;	// bits of string that surrounds pos (must be constant, i.e. "=")
	
	private $langheader;		// string that identifies language in a wiktionary entry
	private $langseparator;		// string that separates language sections
	private $langcode;			// language code (e.g. en, fr, da, etc.)
	
// Preg pattern for pos. Set in switch function for whatever language you're working with.
	private $pospattern;
	
// Array for pos. Set in switch function for whatever language you're working with.
	private $posarray;
	
/////////////////////////////////////////////////////////////////////////////////////
// construct
/////////////////////////////////////////////////////////////////////////////////////
	public function __construct($wikitext, $langcode, $count) {
		$this->count = $count;
		$this->langcode = urlencode($langcode);
		$this->setLangParam($this->langcode);

		$this->extractTextLang($wikitext);
		
		$this->posresults = $this->extractPos();
	}
/////////////////////////////////////////////////////////////////////////////////////
// public methods; used to retrieve contents of variables
/////////////////////////////////////////////////////////////////////////////////////
	public function getLangHeader() {
		return $this->langheader;
	}	
	public function getPosResults() {
		return $this->posresults;
	}
	public function getWord() {
		return $this->word;
	}
/////////////////////////////////////////////////////////////////////////////////////
// private methods
/////////////////////////////////////////////////////////////////////////////////////
// Extracts text based on set language header and separator.
/////////////////////////////////////////////////////////////////////////////////////
	private function extractTextLang($wikitext) {
		include 'extracttextlang.php';
	}
/////////////////////////////////////////////////////////////////////////////////////
// Extracts every occurrence of a part of speech.
/////////////////////////////////////////////////////////////////////////////////////
	private function extractPos() {
		$tempposresults = array();
		
	// If the matches are in an array
		if ($this->posmatchtype == "array") {
			foreach ($this->posarray as $value) {
				if (strpos($this->wikitext, $value)) {
					$tempposresults[] = str_replace($this->posextrastring, "", $value);
				}
			}		
		}		
	// Else if the matches are part of a regular expression
		else if ($this->posmatchtype == "preg") {
			preg_match_all($this->pospattern, $this->wikitext, $matches);
			if (empty($matches[0]) !== true) {
				foreach ($matches[0] as $value) {
					$tempposresults[] = $value;
				}
			// Remove values based on the count provide by the user.	
				$tempposresults = array_slice($tempposresults, 0, $this->count);
			}
		}
	// Return results if array not empty.	
		if (empty($tempposresults) !==true) {
			return $tempposresults;
		}
		else {
			die("No POS found.");
		}
	}
/////////////////////////////////////////////////////////////////////////////////////
// Switch for language parameters.
/////////////////////////////////////////////////////////////////////////////////////
	private function setLangParam($langcode) {
		include './language.config.php';
	}
/////////////////////////////////////////////////////////////////////////////////////				
} // End of class