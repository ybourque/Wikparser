<?php
/***********************************************************************************/
// Wikitionary Text Parser 0.3a
// Author: Yves Bourque
// Go to http://www.igrec.ca/projects/ for full instructions.
/***********************************************************************************/
	header('Content-type: text/html; charset=utf-8');

/***********************************************************************************/
// Set variables. Valid values as follows:
// *word: any string
// *query: 'pos', 'def', 'syn', 'hyper', 'gender'
// lang: 'en', 'fr', 'es', 'de' -- other codes as set in the config file
// count: any number > 0
// source: 'local' for local wiki mysql database, 'api' for Wiktionary's api (slow)
/***********************************************************************************/

// search word
		@$word = $_GET['word']
		or die("ERROR: You must specify a search string. Append ?word=WORD
		to the end of the URL.");
/***********************************************************************************/

// query type; 'pos' for part of speech, 'def' for definition. Validated in case
// below.
		@$query = $_GET['query']
		or die("ERROR: You must specify a query type. Append &query=QUERY TYPE 
		('pos','def', 'syn' or 'hyper') to the end of the URL.");
/***********************************************************************************/
// Language code for search, default english (en)
	if (isset($_GET['lang'])) {
		$langCode = $_GET['lang'];
	}
	else {
		$langCode = "en";
	}
/***********************************************************************************/
// Number of results; default '100'
	if (isset($_GET['count'])) {
		$count = $_GET['count'];
		$count = intval($count);
		if ($count < 1 || $count > 100) {
			$count = 100;
		}
	}
	else {
		$count = 100;
	}
/***********************************************************************************/
// Set wikisource to local if not set. Values either 'local' or 'api'.	
	if (isset($_GET['source'])) {
		if ($_GET['source'] == 'api' || $_GET['source'] == 'local') {
			$wikiSource = $_GET['source'];
		}
		else {
			$wikiSource = 'api';
		}
	}
	else {
		$wikiSource = 'api';
	}
/***********************************************************************************/
// Create $langParameters variable using the values defined in config file.
	include './language.config.php';
/***********************************************************************************/

	switch ($query) {
	/***********************************************************************************/
	// Include defparse class and create new object with 3 variables.
	/***********************************************************************************/
		case "def":
			include 'classes/class.defparse.php';
			$DefParse = new DefParse($langParameters);
			$wikitext = get_wiki_text($langParameters, $wikiSource, $word);
			$defArray = $DefParse->getDef($wikitext, $count);
			
			printResults($defArray);
			break;
	/***********************************************************************************/
	// Include posparse class and create new object with 3 variables.
	/***********************************************************************************/
		case "pos":
			include 'classes/class.posparse.php';
			$posparse = new PosParse($langParameters);
			$wikitext = get_wiki_text($langParameters, $wikiSource, $word);
			$posArray = $posparse->get_pos($wikitext, $count);
			
			printResults($posArray);
			break;
	/***********************************************************************************/
	// Include synparse class and create new object with 3 variables.
	/***********************************************************************************/		
		case "syn":
			include 'classes/class.synparse.php';
			$SynParse = new SynParse($langParameters);
			$wikitext = get_wiki_text($langParameters, $wikiSource, $word);
			$synArray = $SynParse->get_syn($wikitext, $count);

			printResults($synArray);
			break;
	/***********************************************************************************/
	// Include hyperparse class and create new object with 3 variables. (Hypernyms)
	/***********************************************************************************/	
		case "hyper":
			include 'classes/class.hyperparse.php';
			$HyperParse = new HyperParse($langParameters);
			$wikitext = get_wiki_text($langParameters, $wikiSource, $word);
			$hyperArray = $HyperParse->get_hyper($wikitext, $count);

			printResults($hyperArray);
			break;
	/***********************************************************************************/
	// Include genderparse class and create new object with 3 variables. (Gender)
	/***********************************************************************************/	
		case "gender":
			include 'classes/class.genderparse.php';
			$GenderParse = new GenderParse($langParameters);
			$wikitext = get_wiki_text($langParameters, $wikiSource, $word);
			$genderArray = $GenderParse->get_gender($wikitext, $count);

			printResults($genderArray);
			break;		
	/***********************************************************************************/	
		default:
			echo "You must specify a valid query type ('pos', 'def', 'syn', 'hyper', or 'gender').";
			break;
	}
/***********************************************************************************/
// Include wikiextract class and create new object with 2 variables. Returns the
// contents of the wiktionary entry for a given word.
/***********************************************************************************/
	function get_wiki_text($langParameters, $wikiSource, $word) {
		include 'classes/class.wikiextract.php';
		$WikiExtract = new WikiExtract($langParameters, $wikiSource);
		return $WikiExtract->get_wikitext($word);	
	}
/***********************************************************************************/
// Prints contents of array.
/***********************************************************************************/
	function printResults($array) {
		$resultseparator = " | ";
		$printresults = null;
		foreach ($array as $value) {
			$printresults .= $value.$resultseparator;
		}
		echo rtrim($printresults, $resultseparator);
	}
?>