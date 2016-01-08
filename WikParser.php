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

    /***********************************************************************************/
    /***********************************************************************************/
    // Language code for search, default english (en)
    /***********************************************************************************/
    // Number of results; default '100'
    /***********************************************************************************/
    // Set wikisource to local if not set. Values either 'local' or 'api'.
    /***********************************************************************************/
    /***********************************************************************************/
    public function getWordDefiniton($word,$query='def',$langCode='en',$count=100,$source='api'){
        $this->word = $word;
        $this->count = $count;
        $this->source = $source;
        $this->langParameters = $this->newLang($langCode);

        $wikitext = $this->getWikiText($this->langParameters, $this->source, $this->word);
        var_dump($wikitext);
        $parsed = [];
        foreach ((array) $query as $q) {
            $parsed[$q] = $this->parseQuery($q, $wikitext);
        }
        return $parsed;
    }

    private function newLang($langCode)
    {
        $class = 'ybourque\Wikparser\lib\Lang\\' . ucfirst(strtolower($langCode));
        return new $class();
    }

    private function parseQuery($query, $wikitext){
        $parsedDefinition = [];
        switch ($query) {
            /***********************************************************************************/
            // Include defparse class and create new object with 3 variables.
            /***********************************************************************************/
            case "def":
                $DefParse = new DefParse($this->langParameters);
                $parsedDefinition = $DefParse->getDef($wikitext, $this->count);
                break;
            /***********************************************************************************/
            // Include posparse class and create new object with 3 variables.
            /***********************************************************************************/
            case "pos":
                $posparse = new PosParse($this->langParameters);
                $parsedDefinition = $posparse->getPos($wikitext, $this->count);
                break;
            /***********************************************************************************/
            // Include synparse class and create new object with 3 variables.
            /***********************************************************************************/
            case "syn":
                $SynParse = new SynParse($this->langParameters);
                $parsedDefinition = $SynParse->getSyn($wikitext, $this->count);
                break;
            /***********************************************************************************/
            // Include hyperparse class and create new object with 3 variables. (Hypernyms)
            /***********************************************************************************/
            case "hyper":
                $HyperParse = new HyperParse($this->langParameters);
                $parsedDefinition = $HyperParse->getHyper($wikitext, $this->count);
                break;
            /***********************************************************************************/
            // Include genderparse class and create new object with 3 variables. (Gender)
            /***********************************************************************************/
            case "gender":
                $GenderParse = new GenderParse($this->langParameters);
                $parsedDefinition = $GenderParse->getGender($wikitext, $this->count);
                break;
            /***********************************************************************************/
            default:
                echo "You must specify a valid query type ('pos', 'def', 'syn', 'hyper', or 'gender').";
                break;
        }

        return $parsedDefinition;
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
