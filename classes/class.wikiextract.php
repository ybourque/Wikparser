<?php

class WikiExtract {
/////////////////////////////////////////////////////////////////////////////////////
// Variables
// These are the records and tables found in the wiktionary database when dumped and
// loaded into a mysql database using the Wikimedia mwdumper.
/////////////////////////////////////////////////////////////////////////////////////		
	private $page_id_record = "page_id";
	private $page_title_record = "page_title";
	private $page_namespace_record = "page_namespace";
	private $page_table = "page";
	
	private $rev_text_id_record = "rev_text_id";
	private $rev_page_record = "rev_page";
	private $revision_table = "revision";
	
	private $old_text_record = "old_text";
	private $old_id_record = "old_id";
	private $text_table = "text";
	
	private $word; 		// Search word passed from user input
	private $wikitext;	// Query results to be parsed
	private $langcode;	// Language code (en, fr, etc.)
	
/////////////////////////////////////////////////////////////////////////////////////
// construct
/////////////////////////////////////////////////////////////////////////////////////	
	public function __construct($word, $wikisource, $langcode) {
		$this->langcode = $langcode;
		if ($wikisource == 'local') {
			$this->connectToSql();
			$this->wikitext = $this->sqlFetchData($word);
		}
		else if ($wikisource == 'api') {
			$this->wikitext = $this->getWikiTextFromWiktionary($word);
		}
	}
/////////////////////////////////////////////////////////////////////////////////////
// public methods
/////////////////////////////////////////////////////////////////////////////////////
	public function getWikiText () {
		return $this->wikitext;
	}
/////////////////////////////////////////////////////////////////////////////////////
// private methods
/////////////////////////////////////////////////////////////////////////////////////
	private function connectToSql() {
		include 'conc.php';
	}
/////////////////////////////////////////////////////////////////////////////////////
// Retrieves raw data via Wiktionary's API.
/////////////////////////////////////////////////////////////////////////////////////
	private function getWikiTextFromWiktionary($word) {
		$this->word = urlencode($word);
	
	// Must be supplied, otherwise IP will be banned
		$useragent = "Wikitionary Text Parser 0.2 (http://www.igrec.ca/projects)";
	// Paramaters passed to the Wik API, including search word.	
		$params = '?action=parse&prop=wikitext&page='.$this->word.'&format=xml';
		
		$ch = curl_init('http://'.$this->langcode.'.wiktionary.org/w/api.php'.$params);
		curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch,CURLOPT_ENCODING , "gzip");
		
		$wikiapiresult = curl_exec($ch);
		curl_close($ch);

		if (strpos($wikiapiresult, "error code=\"missingtitle\"") !== false) {
			die("No such word.");
		}
		else {
			return $wikiapiresult;
		}
	}		
/////////////////////////////////////////////////////////////////////////////////////	
	private function sqlFetchData($word) {
		$word = mysql_real_escape_string($word);
		
	// Fetch page_id based on word.
		$pageIdQuery = "SELECT $this->page_id_record FROM $this->page_table 
			WHERE $this->page_title_record = '$word' AND $this->page_namespace_record = 0";

		$pageIdResult = mysql_query($pageIdQuery)
			or die(mysql_error("No page ID found."));

		$row = mysql_fetch_array($pageIdResult);
		$pageId = $row[$this->page_id_record];

	// Fetch revision id based on page_id.	
		$revTextIdQuery = "SELECT $this->rev_text_id_record FROM $this->revision_table 
			WHERE $this->rev_page_record = '$pageId'";

		$revTextIdResult = mysql_query($revTextIdQuery)
			or die(mysql_error("No Revision Text ID found."));

		$row2 = mysql_fetch_array($revTextIdResult);
		$revTextId = $row2[$this->rev_text_id_record];

	// Fetch word text based on revision id.
		$oldTextQuery = "SELECT $this->old_text_record FROM $this->text_table 
			WHERE $this->old_id_record = '$revTextId'";

		$oldTextResult = mysql_query($oldTextQuery)
			or die(mysql_error("No Old Text found."));

		$row3 = mysql_fetch_array($oldTextResult);
		$wikitext = $row3[$this->old_text_record] or die("No such word.");
		
		return $wikitext;
	}
/////////////////////////////////////////////////////////////////////////////////////
}

?>