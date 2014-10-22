<?php
/***********************************************************************************/
// Add a case for any additional languages and set variables according to the strings
// used by Wiktionary for the given language. See www.igrec.ca/projects/ for more
// info on how to do this.
// Basics:
/*case "2-letter language code":
		$this->langCode = 2-letter language code
		$this->langHeader = string that begins section for a given language
		$this->langSeparator = string that indicates a new language section
		$this->defHeader = header for definitions section; may not be present
		$this->defTag = string that begins a definition
		$this->synHeader = header for synonyms
		$this->hyperHeader = header for hypernyms
		$this->genderPattern = regex for gender
		$this->posExtraString = extra string around POS that can be stripped
		$this->posPattern = regex pattern for POS
		$this->posArray = array(all possible values for POS); leave empty if using preg
		$this->posMatchType = 'preg' for regular expressions; 'array' for array */
/***********************************************************************************/

switch ($this->langCode) {
// English parameters	
	case "en":
		$this->langCode = "en";
		$this->langHeader = "==English==";
		$this->langSeparator = "----";
		$this->defHeader = "";
		$this->defTag = "# ";
		$this->synHeader = "====Synonyms====";
		$this->hyperHeader = "====Hypernyms====";
		$this->genderPattern = "";
		$this->posMatchType = "array";
		$this->posArray = array(
			"===Noun===", "===Verb===", "===Adjective===", "===Adverb===", "===Preposition===",
			"===Particle===", "===Pronouns===", "===Interjection===", "===Conjunction===",
			"===Article===");
		$this->posExtraString = "=";
		break;
// French parameters
	case "fr":
		$this->langCode = "fr";
		$this->langHeader = "fr}} ==";
		$this->langSeparator = "== {{=";
		$this->defHeader = "";
		$this->defTag = "# ";
		$this->synHeader = "==== {{S|synonymes}} ====";
		$this->hyperHeader = "==== {{S|hyperonymes}} ====";
		$this->genderPattern = "(\{\{([mf]|mf)\??\}\})";
		$this->posMatchType = "preg";
		$this->posPattern = "(\{\{\S\|[\d\w\s]+\|fr(\|num=[0-9])?\}\})u";
		$this->posArray = array();
		$this->posExtraString = "{{S|";
		break;
// Spanish parameters
	case "es":
		$this->langCode = "es";
		$this->langHeader = "{{ES";
		$this->langSeparator = "";
		$this->defHeader = "";
		$this->defTag = ";";
		$this->synHeader = "'''Sinónimo";
		$this->hyperHeader = "";
		$this->genderPattern = "(\s?(masculino|femenino)(\|es)?\}\}\s?===)";
		$this->posMatchType = "preg";
		$this->posPattern = "(===\s?\{\{\w*[\|\s])u";
		$this->posArray = array();
		$this->posExtraString = "";
		break;
// German parameters
	case "de":
		$this->langCode = "de";
		$this->langHeader = "Deutsch}}) ==";
		$this->langSeparator = "({{Sprache|";
		$this->defHeader = "{{Bedeutungen}}";
		$this->defTag = ":";
		$this->synHeader = "{{Synonyme}}";
		$this->hyperHeader = "{{Oberbegriffe}}";
		$this->genderPattern = "(\{\{[mfn]\}\}\s===)";
		$this->posMatchType = "preg";
		$this->posPattern = "(\{\{Wortart\|\w+\|)";
		$this->posArray = array();
		$this->posExtraString = "{{Wortart|";
		break;
// Fill in the following settings for a language of your choice.
	case "":
		$this->langCode = "";		// string
		$this->langHeader = "";		// string
		$this->langSeparator = "";	// string
		$this->defHeader = "";		// string
		$this->defTag = "";			// string
		$this->synHeader = "";		// string
		$this->hyperHeader = "";	// string
		$this->genderPattern = "";	// regular expression
		$this->posExtraString = "";	// string
		$this->posMatchType = "";	// 'preg' or 'array'
		$this->posPattern = "";		// regular expression
		$this->posArray = array();	// array
		break;		
// Default parameters (currently english)		
	default:
		$this->langCode = "en";
		$this->langHeader = "==English==";
		$this->langSeparator = "----";
		$this->defHeader = "";
		$this->defTag = "# ";
		$this->synHeader = "====Synonyms====";
		$this->hyperHeader = "====Hypernyms====";
		$this->genderPattern = "";
		$this->posExtraString = "=";
		$this->posMatchType = "array";
		$this->posArray = array(
			"===Noun===", "===Verb===", "===Adjective===", "===Adverb===", "===Preposition===",
			"===Particle===", "===Pronouns===", "===Interjection===", "===Conjunction===",
			"===Article===");
		break;
}
?>