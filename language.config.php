<?php
/***********************************************************************************/
// Add a case for any additional languages and set variables according to the strings
// used by Wiktionary for the given language. See www.igrec.ca/projects/ for more
// info on how to do this.
// Basics:
/*case "2-letter language code":
		langCode = 2-letter language code
		langHeader = string that begins section for a given language
		angSeparator = string that indicates a new language section
		defHeader = header for definitions section; may not be present
		defTag = string that begins a definition
		synHeader = header for synonyms
		hyperHeader = header for hypernyms
		genderPattern = regex for gender
		posExtraString = extra string around POS that can be stripped
		posPattern = regex pattern for POS
		posArray = array(all possible values for POS); leave empty if using preg
		posMatchType = 'preg' for regular expressions; 'array' for array */
/***********************************************************************************/

switch ($langCode) {
// English parameters	
	case "en":
		$langParameters = array(
			"langCode" => "en",
			"langHeader" => "==English==",
			"langSeparator" => "----",
			"defHeader" => "",
			"defTag" => "# ",
			"synHeader" => "====Synonyms====",
			"hyperHeader" => "====Hypernyms====",
			"genderPattern" => "",
			"posMatchType" => "array",
			"posPattern" => "",
			"posArray" => array(
			'===Noun===', '===Verb===', '===Adjective===', '===Adverb===', '===Preposition===',
			'===Particle===', '===Pronouns===', '===Interjection===', '===Conjunction===',
			'===Article==='),
			"posExtraString" => "=",
		);
		break;	
// French parameters
	case "fr":
		$langParameters = array(
			"langCode" => "fr",
			"langHeader" => "fr}} ==",
			"langSeparator" => "== {{=",
			"defHeader" => "",
			"defTag" => "# ",
			"synHeader" => "==== {{S|synonymes}} ====",
			"hyperHeader" => "==== {{S|hyperonymes}} ====",
			"genderPattern" => "(\{\{([mf]|mf)\??\}\})",
			"posMatchType" => "preg",
			"posPattern" => "(\{\{\S\|[\d\w\s]+\|fr(\|num=[0-9])?\}\})u",
			"posArray" => array(),
			"posExtraString" => "{{S|",
		);
		break;	
// Spanish parameters
	case "es":
			$langParameters = array(
			"langCode" => "es",
			"langHeader" => "{{ES",
			"langSeparator" => "",
			"defHeader" => "",
			"defTag" => ";",
			"synHeader" => "'''Sinónimo",
			"hyperHeader" => "",
			"genderPattern" => "(\s?(masculino|femenino)(\|es)?\}\}\s?===)",
			"posMatchType" => "preg",
			"posPattern" => "(===\s?\{\{\w*[\|\s])u",
			"posArray" => array(),
			"posExtraString" => "",
		);
		break;	
// German parameters
	case "de":
		$langParameters = array(
			"langCode" => "de",
			"langHeader" => "Deutsch}}) ==",
			"langSeparator" => "({{Sprache|",
			"defHeader" => "{{Bedeutungen}}",
			"defTag" => ":",
			"synHeader" => "{{Synonyme}}",
			"hyperHeader" => "{{Oberbegriffe}}",
			"genderPattern" => "(\{\{[mfn]\}\}\s===)",
			"posMatchType" => "preg",
			"posPattern" => "(\{\{Wortart\|\w+\|)",
			"posArray" => array(),
			"posExtraString" => "{{Wortart|",
		);
		break;
// Fill in the following settings for a language of your choice.
	case "":
		$langParameters = array(
			"langCode" => "",		// string
			"langHeader" => "",		// string
			"langSeparator" => "",	// string
			"defHeader" => "",		// string
			"defTag" => "",			// string
			"synHeader" => "",		// string
			"hyperHeader" => "",	// string
			"genderPattern" => "",	// regex
			"posMatchType" => "",	// 'preg' or 'array'
			"posPattern" => "",		// regex
			"posArray" => "",		// array
			"posExtraString" => "",	// string
		);
		break;		
// Default parameters (currently english)		
	default:
		$langParameters = array(
			"langCode" => "en",
			"langHeader" => "==English==",
			"langSeparator" => "----",
			"defHeader" => "",
			"defTag" => "# ",
			"synHeader" => "====Synonyms====",
			"hyperHeader" => "====Hypernyms====",
			"genderPattern" => "",
			"posMatchType" => "array",
			"posPattern" => "",
			"posArray" => array(
			'===Noun===', '===Verb===', '===Adjective===', '===Adverb===', '===Preposition===',
			'===Particle===', '===Pronouns===', '===Interjection===', '===Conjunction===',
			'===Article==='),
			"posExtraString" => "=",
		);
		break;
}
?>