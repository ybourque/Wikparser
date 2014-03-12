<?php
/////////////////////////////////////////////////////////////////////////////////////
// Add a case for any additional languages and set variables according to the strings
// used by Wiktionary for the given language. See www.igrec.ca/projects/ for more
// info on how to do this.
/////////////////////////////////////////////////////////////////////////////////////

switch ($langcode) {
// English parameters	
	case "en":
		$this->langcode = "en";
		$this->langheader = "==English==";
		$this->langseparator = "----";
		$this->deftag = "# ";
		$this->synheader = "====Synonyms====";
		$this->hyperheader = "====Hypernyms====";
		$this->posextrastring = "=";
		$this->posmatchtype = "array";
		$this->posarray = array(
			"===Noun===", "===Verb===", "===Adjective===", "===Adverb===", "===Preposition===",
			"===Particle===", "===Pronouns===", "===Interjection===", "===Conjunction===",
			"===Article===");
		break;
// French parameters
	case "fr":
		$this->langcode = "fr";
		$this->langheader = "fr}} ==";
		$this->langseparator = "== {{=";
		$this->deftag = "# ";
		$this->hyperheader = "{{-syn-}}";
		$this->synheader = "{{-hyper-}}";
		$this->posmatchtype = "preg";
	// Old Regex. Wiktionary changed French POS. Updated here 8 Feb 2014.
//		$this->pospattern = "(\{\{\-[a-zA-ZéÉèÈàÀ]+\-[a-zA-ZéÉèÈàÀ]*\-?(\|num=\d)?\|fr(\|num=\d)?\}\})";
		$this->pospattern = "(\{\{\S\|[\d\w\s]+\|fr(\|num=[0-9])?\}\})u";
		break;
// Fill in the following settings for a language of your choice.
	case "":
		$this->langcode = "";
		$this->langheader = "";
		$this->langseparator = "";
		$this->deftag = "";
		$this->synheader = "";
		$this->hyperheader = "";
		$this->posextrastring = "";
		$this->posmatchtype = "";
		$this->pospattern = "";
		$this->posarray = array();
		break;		
// Default parameters (currently english)		
	default:
		$this->langcode = "en";
		$this->langheader = "==English==";
		$this->langseparator = "----";
		$this->deftag = "# ";
		$this->synheader = "====Synonyms====";
		$this->hyperheader = "====Hypernyms====";
		$this->posextrastring = "=";
		$this->posmatchtype = "array";
		$this->posarray = array(
			"===Noun===", "===Verb===", "===Adjective===", "===Adverb===", "===Preposition===",
			"===Particle===", "===Pronouns===", "===Interjection===", "===Conjunction===",
			"===Article===");
		break;
}

?>