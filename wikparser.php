<?php
////////////////////////////////////////////////////////////////////////////////////
// Wikitionary Text Parser 0.2c
// Author: Yves Bourque
// Go to http://www.igrec.ca/projects/ for full instructions.
////////////////////////////////////////////////////////////////////////////////////
	header('Content-type: text/html; charset=utf-8');

////////////////////////////////////////////////////////////////////////////////////
// Set variables. Valid values as follows:
// *word: any string
// *query: 'pos', 'def', 'syn', 'hyper'
// lang: 'en', 'fr' -- other code as set in the config file
// count: any number > 0
// source: 'local' for local wiki mysql database, 'api' for Wiktionary's api (slow)
////////////////////////////////////////////////////////////////////////////////////

// search word
		@$word = $_GET['word'] or die("Error: You must specify a search string.");
////////////////////////////////////////////////////////////////////////////////////

// query type; 'pos' for part of speech, 'def' for definition. Validated in case
// below.
		@$query = $_GET['query'] or die("You must specify a query type ('pos','def', 'syn' or 'hyper').");
////////////////////////////////////////////////////////////////////////////////////

// Language code for search, default english (en)
	if (isset($_GET['lang'])) {
		$langcode = $_GET['lang'];
	}
	else {
		$langcode = "en";
	}
////////////////////////////////////////////////////////////////////////////////////
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
////////////////////////////////////////////////////////////////////////////////////
// Set wikisource to local if not set. Values either 'local' or 'api'.	
	if (isset($_GET['source'])) {
		if ($_GET['source'] == 'api' || $_GET['source'] == 'local') {
			$wikisource = $_GET['source'];
		}
		else {
			$wikisource = 'api';
		}
	}
	else {
		$wikisource = 'api';
	}
////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////
// Include wikiextract class and create new object with 3 variables. Returns the
// contents of the wiktionary entry.
////////////////////////////////////////////////////////////////////////////////////
	include 'classes/class.wikiextract.php';
	$WikiExtract = new WikiExtract($word, $wikisource, $langcode);
	$wikitext = $WikiExtract->getWikiText();
////////////////////////////////////////////////////////////////////////////////////
	switch ($query) {
	////////////////////////////////////////////////////////////////////////////////////
	// Include defparse class and create new object with 3 variables.
	////////////////////////////////////////////////////////////////////////////////////
		case "def":
			include 'classes/class.defparse.php';
			$DefParse = new DefParse($wikitext, $langcode, $count);
			$defarray = $DefParse->getDef();
			
			printResults($defarray);
			break;
	////////////////////////////////////////////////////////////////////////////////////
	// Include posparse class and create new object with 4 variables.
	////////////////////////////////////////////////////////////////////////////////////
		case "pos":
			include 'classes/class.posparse.php';
			$posparse = new PosParse($wikitext, $langcode, $count);
			$posarray = $posparse->getPosResults();
			
			printResults($posarray);
			break;
	////////////////////////////////////////////////////////////////////////////////////
	// Include synparse class and create new object with 3 variables.
	////////////////////////////////////////////////////////////////////////////////////		
		case "syn":
			include 'classes/class.synparse.php';
			$SynParse = new SynParse($wikitext, $langcode, $count);
			$synarray = $SynParse->getSyn();

			printResults($synarray);
			break;
	////////////////////////////////////////////////////////////////////////////////////
	// Include hyperparse class and create new object with 3 variables. (Hypernyms)
	////////////////////////////////////////////////////////////////////////////////////	
		case "hyper":
			include 'classes/class.hyperparse.php';
			$HyperParse = new HyperParse($wikitext, $langcode, $count);
			$hyperarray = $HyperParse->getHyper();

			printResults($hyperarray);
			break;
			
		default:
			echo "You must specify a valid query type ('pos', 'def' or 'syn').";
			break;
	}		
////////////////////////////////////////////////////////////////////////////////////
	function printResults($array) {
		$resultseparator = " | ";
		$printresults = null;
		foreach ($array as $value) {
			$printresults .= $value.$resultseparator;
		}
		echo rtrim($printresults, $resultseparator);
	}
?>