<?php
namespace ybourque\Wikparser;
use ybourque\Wikparser\lib\DefParse;
use ybourque\Wikparser\lib\GenderParse;
use ybourque\Wikparser\lib\HyperParse;
use ybourque\Wikparser\lib\PosParse;
use ybourque\Wikparser\lib\SynParse;
use ybourque\Wikparser\lib\WikiExtract;

class WikParser{
    public $parsedDefinition='';

    /** @var string */
    protected $langHeader;

    /**
     * Set the language header.
     * This is the header (usually a second-level header, i.e. H2 in HTML) under
     * the words that are to be queried.
     * For example, English is '==\w*English\w*=='.
     * @param string $langHeader
     */
    public function setLangHeader($langHeader) {
        $this->langHeader = $langHeader;
    }

    /**
     * Get the language header regex used for $langCode language.
     * For example, for 'en' (English) this is '==\s*English\s*=='.
     * @param string $langCode The Wiktionary langage code (usually the same as ISO-639).
     * @return string The regular expression used to determine the langage header.
     */
    public function getLangHeader($langCode = null) {
        if ($this->langHeader) {
            return $this->langHeader;
        }
        switch ($langCode) {
            case 'fr':
                return 'fr}}\s*==';
            case 'es':
                return '{{ES';
            case 'de':
                return 'Deutsch}})\s*==';
            case 'en':
            default:
                return '==\s*English\s*==';
        }
    }

    public function getWordDefiniton($word,$query='def',$langCode='en',$count=100,$source='api'){
        $this->word = $word;
        $this->count = $count;
        $this->source = $source;
        $this->langParameters = $this->newLang($langCode);
        return $this->parseQuery($query);
    }

    private function newLang($langCode)
    {
        $class = 'ybourque\Wikparser\lib\Lang\\' . ucfirst(strtolower($langCode));
        return new $class();
    }

    /***********************************************************************************/
    /***********************************************************************************/
// Language code for search, default english (en)
    /***********************************************************************************/
// Number of results; default '100'
    /***********************************************************************************/
// Set wikisource to local if not set. Values either 'local' or 'api'.
    /***********************************************************************************/
    /***********************************************************************************/
    public function parseQuery($query){
        switch ($query) {
            /***********************************************************************************/
            // Include defparse class and create new object with 3 variables.
            /***********************************************************************************/
            case "def":
                if(isset($this->langParameters) and isset($this->word) and isset($this->source) and isset($this->count)){
                    $DefParse = new DefParse($this->langParameters);
                    $wikitext = $this->getWikiText($this->langParameters, $this->source, $this->word);
                    if(isset($DefParse)){
                        $parsedDefinition = $DefParse->getDef($wikitext, $this->count);
                    }
                }
                break;
            /***********************************************************************************/
            // Include posparse class and create new object with 3 variables.
            /***********************************************************************************/
            case "pos":
                if(isset($this->langParameters) and isset($this->word) and isset($this->source) and isset($this->count)){
                    $posparse = new PosParse($this->langParameters);
                    $wikitext = $this->getWikiText($this->langParameters, $this->source, $this->word);
                    if(isset($DefParse)){
                        $parsedDefinition = $posparse->getPos($wikitext, $this->count);
                    }
                }
                break;
            /***********************************************************************************/
            // Include synparse class and create new object with 3 variables.
            /***********************************************************************************/
            case "syn":
                if(isset($this->langParameters) and isset($this->word) and isset($this->source) and isset($this->count)){
                    $SynParse = new SynParse($this->langParameters);
                    $wikitext = $this->getWikiText($this->langParameters, $this->source, $this->word);
                    if(isset($DefParse)){
                        $parsedDefinition = $SynParse->getSyn($wikitext, $this->count);
                    }
                }
                break;

            /***********************************************************************************/
            // Include hyperparse class and create new object with 3 variables. (Hypernyms)
            /***********************************************************************************/
            case "hyper":
                if(isset($this->langParameters) and isset($this->word) and isset($this->source) and isset($this->count)){
                    $HyperParse = new HyperParse($this->langParameters);
                    $wikitext = $this->getWikiText($this->langParameters, $this->source, $this->word);
                    if(isset($DefParse)){
                        $parsedDefinition = $HyperParse->getHyper($wikitext, $this->count);
                    }
                }
                break;
            /***********************************************************************************/
            // Include genderparse class and create new object with 3 variables. (Gender)
            /***********************************************************************************/
            case "gender":
                if(isset($this->langParameters) and isset($this->word) and isset($this->source) and isset($this->count)){
                    $GenderParse = new GenderParse($this->langParameters);
                    $wikitext = $this->getWikiText($this->langParameters, $this->source, $this->word);
                    if(isset($DefParse)){
                        $parsedDefinition = $GenderParse->getGender($wikitext, $this->count);
                    }
                }
                break;
            /***********************************************************************************/
            default:
                echo "You must specify a valid query type ('pos', 'def', 'syn', 'hyper', or 'gender').";
                break;
        }
        if(isset($parsedDefinition)){
            return $parsedDefinition;
        }else{
            return [];
        }
    }

    /***********************************************************************************/
// Include wikiextract class and create new object with 2 variables. Returns the
// contents of the wiktionary entry for a given word.
    /***********************************************************************************/
    public function getWikiText($langParameters, $wikiSource, $word) {
        $WikiExtract = new WikiExtract($langParameters, $wikiSource);
        return $WikiExtract->getWikiText($word);
    }

    /***********************************************************************************/
// Prints contents of array.
    /***********************************************************************************/
    public function printResults($array) {
        $resultseparator = " | ";
        $printresults = null;
        foreach ($array as $value) {
            $printresults .= $value.$resultseparator;
        }
        echo rtrim($printresults, $resultseparator);
    }
}
?>
