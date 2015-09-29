<?php
/***********************************************************************************/
// This class is used to parse the wikitionary raw data in order to extract gender
// for a given word.
// See the language.config.php file for setting language specific parameters.
/***********************************************************************************/

class GenderParse {
/***********************************************************************************/
// Variables
/***********************************************************************************/
	private $langCode;			// language code (e.g. en, fr, da, etc.)
	private $genderPattern;		// Gender regex pattern, set in config file
	
/***********************************************************************************/
// construct
/***********************************************************************************/
	public function __construct($langParameters) {
		if (empty($langParameters['genderPattern'])) {
			die("ERROR: Gender parameters are not set for this language in language.config.php.");
		}
		else {
			$this->langCode = $langParameters['langCode'];
			$this->genderPattern = $langParameters['genderPattern'];
		}
	}
/***********************************************************************************/
// public methods; used to retrieve contents of variables
/***********************************************************************************/	
	public function get_gender($wikitext, $count) {
		$genderArray = $this->extract_gender($wikitext, $count);
		
		include "./classes/class.strip.tags.php";
		$stripTagsObject = new StripTags();
		
		return $stripTagsObject->strip_tags($genderArray, $this->langCode);
	}
/***********************************************************************************/
// private methods
/***********************************************************************************/
// Extracts every occurrence of gender.
/***********************************************************************************/
	private function extract_gender($wikitext, $count) {
		$tempGenderResults = array();
		
		if ($this->genderPattern != "") {
			
			preg_match_all($this->genderPattern, $wikitext, $matches);
			
			if (empty($matches[0]) !== true) {
				foreach ($matches[0] as $value) {
					$tempGenderResults[] = $value;
				}
			// Remove values based on the count provide by the user.	
				$tempGenderResults = array_slice($tempGenderResults, 0, $count);
			}
		}
		else {
			die("No gender pattern specified for this language.");
		}
		
	// Return results if array not empty.	
		if (empty($tempGenderResults) !==true) {
			return array_unique($tempGenderResults);
		}
		else {
			die("No gender found.");
		}
	}
/***********************************************************************************/
} // End of class