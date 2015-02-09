<?php
/***********************************************************************************/
// This class strips common tags from Wiktionary's raw data.
// Accepts an array.
/***********************************************************************************/

class StripTags {
/***********************************************************************************/
// Variables
/***********************************************************************************/
	
/***********************************************************************************/
// construct
/***********************************************************************************/
	public function __construct() {
	}
/***********************************************************************************/
// public methods
/***********************************************************************************/
	public function strip_tags($array, $langCode) {
		$tags_array = array("[", "]", "{", "}", "=");
		foreach ($tags_array as $tag) {
			$array = str_replace($tag, "", $array);
		}
		foreach ($array as $string) {
			$string = trim($string);
		
		// Trims language code from end of string if preceded by | (e.g. Spanish gender)
			$strippedString = preg_replace("/\|$langCode$/", "", $string);
		// Remove trailing Wiktionary API XML tags (when closing tags occur immediately
		// after requested info)
			$finalArray[] = preg_replace('(\<\/\w*\>)', "", $strippedString);
		}
		return $finalArray;
	}
/***********************************************************************************/
} // End of class
?>