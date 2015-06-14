<?php
/***********************************************************************************/
// Gets all raw text for a given word via either Wiktionary's API or a local MySQL
// copy (defined in conc.php). Accepts 3 variables:
// $word = string
// $langCode = string (2-letter language code)
// $wikiSource = string (either 'api' or 'local' for local MySQL).
/***********************************************************************************/

class WikiExtract {
/***********************************************************************************/
// Variables
/***********************************************************************************/
	private $wikiSource; // api or local
	private $langCode; // 2-letter language code
	private $langHeader; // language header from config file
	private $langSeparator; // language separator string from config file
	
/***********************************************************************************/
// construct
/***********************************************************************************/
	public function __construct($langParameters, $wikiSource) {
		$this->wikiSource = $wikiSource;
		$this->langCode = $langParameters['langCode'];
		$this->langHeader = $langParameters['langHeader'];
		$this->langSeparator = $langParameters['langSeparator'];
	}
	
/***********************************************************************************/
// public methods
/***********************************************************************************/
	public function get_wikitext($word) {
		if ($this->wikiSource == 'local') {
			$wikitext = $this->sql_fetch_data($word);
		}
		else if ($this->wikiSource == 'api') {
			$wikitext = $this->get_wikitext_from_wiktionary($word);
		}
		
		return $this->lang_extract($wikitext);
	}
/***********************************************************************************/
// private methods
/***********************************************************************************/

/***********************************************************************************/
// Retrieves raw data via Wiktionary's API.
/***********************************************************************************/
	private function get_wikitext_from_wiktionary($word) {
		$word = urlencode($word);
	
	// Must be supplied, otherwise IP will be banned
		$userAgent = "Wikitionary Text Parser 0.3 (http://www.igrec.ca/projects)";
	// Paramaters passed to the Wik API, including search word.	
		$params = '?action=parse&prop=wikitext&page='.$word.'&format=xml';
		
		$ch = curl_init('https://'.$this->langCode.'.wiktionary.org/w/api.php'.$params);
		curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch,CURLOPT_ENCODING , "gzip");
		
		$wikiAPIResult = curl_exec($ch);
		curl_close($ch);

		if (strpos($wikiAPIResult, "error code=\"missingtitle\"") !== false) {
			die("ERROR: The Wiktionary API did not return a page for that word.");
		}
		else {
			// Strip Wiktionary XML tags
			$wikiAPIResult = preg_replace('(\<\?xml.*\<wikitext xml\:space\=\"preserve\"\>)s', "", $wikiAPIResult);
			$wikiAPIResult = preg_replace('(\<\/wikitext\>\<\/parse\>\<\/api\>)s', "", $wikiAPIResult);
			return $wikiAPIResult;
		}
	}		
/***********************************************************************************/
// Retrieves raw data via a local MySQL copy of Wiktionary (defined in conc.php).
/***********************************************************************************/
	private function sql_fetch_data($word) {
		
		include 'conc.php';
		$word = mysqli_real_escape_string($conn, $word);
	
	// 3 tables are used page->revision->text	
		$query = "SELECT t.old_text FROM text t ";
		$query .= "JOIN revision r ON r.rev_text_id = t.old_id ";
		$query .= "JOIN page p ON r.rev_page = p.page_id ";
		$query .= "WHERE p.page_title = '$word' AND p.page_namespace = 0";
		
		if (!$queryResult = $conn->query($query)) {
			die("Error: Couldn't query word.");
		}
		
		if ($queryResult->num_rows > 0) {
			while ($row = $queryResult->fetch_assoc()){
				$wikitext = $row['old_text'];
			}
			return $wikitext;
		}
		else {
			die("No such word found.");
		}				
	}
/***********************************************************************************/
// Extracts content for specified language from raw wiktionary data. Some entries
// contain text for other languages.
/***********************************************************************************/
	private function lang_extract($wikitext){
		$bln = false;
		
		if ($this->langSeparator !== "") {
			$languages = explode($this->langSeparator, $wikitext);
		
			foreach ($languages as $value) {
				if (strpos($value, $this->langHeader) !== false) {
					$wikitext = $value;
					$bln = true;
				}
			}
			if ($bln !== true) {
				die("No such word for specified language.");
			}
			else {
				return $wikitext;
			}
		}
		else {
			return $wikitext;
		}
	}
}

?>