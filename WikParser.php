<?php
namespace quuuit\Wikparser;
use quuuit\Wikparser\lib\DefParse;
use quuuit\Wikparser\lib\WikiExtract;

class WikParser{
    public $parsedDefinition='';
    public function getWordDefiniton($word,$query='def',$langCode='en',$count=100,$source='api'){
        $this->word = $word;
        $this->count = $count;
        $this->source = $source;
        switch ($langCode) {
            // English parameters
            case "en":
                $this->langParameters = array(
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
                $this->langParameters = array(
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
                $this->langParameters = array(
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
                $this->langParameters = array(
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
                $this->langParameters = array(
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
                $this->langParameters = array(
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
        return $this->parseQuery($query);
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
                    $wikitext = $this->get_wiki_text($this->langParameters, $this->source, $this->word);
                    if(isset($DefParse)){
                        $parsedDefinition = $DefParse->getDef($wikitext, $this->count);
                    }
                }
                break;
            /***********************************************************************************/
            // Include posparse class and create new object with 3 variables.
            /***********************************************************************************/
            case "pos":
                include 'lib/class.posparse.php';
                if(isset($this->langParameters) and isset($this->word) and isset($this->source) and isset($this->count)){
                    $posparse = new PosParse($this->langParameters);
                    $wikitext = $this->get_wiki_text($this->langParameters, $this->source, $this->word);
                    if(isset($DefParse)){
                        $parsedDefinition = $posparse->get_pos($wikitext, $this->count);
                    }
                }
                break;
            /***********************************************************************************/
            // Include synparse class and create new object with 3 variables.
            /***********************************************************************************/
            case "syn":
                include 'lib/class.synparse.php';
                if(isset($this->langParameters) and isset($this->word) and isset($this->source) and isset($this->count)){
                    $SynParse = new SynParse($this->langParameters);
                    $wikitext = $this->get_wiki_text($this->langParameters, $this->source, $this->word);
                    if(isset($DefParse)){
                        $parsedDefinition = $SynParse->get_syn($wikitext, $this->count);
                    }
                }
                break;

            /***********************************************************************************/
            // Include hyperparse class and create new object with 3 variables. (Hypernyms)
            /***********************************************************************************/
            case "hyper":
                include 'lib/class.hyperparse.php';
                if(isset($this->langParameters) and isset($this->word) and isset($this->source) and isset($this->count)){
                    $HyperParse = new HyperParse($this->langParameters);
                    $wikitext = $this->get_wiki_text($this->langParameters, $this->source, $this->word);
                    if(isset($DefParse)){
                        $parsedDefinition = $HyperParse->get_hyper($wikitext, $this->count);
                    }
                }
                break;
            /***********************************************************************************/
            // Include genderparse class and create new object with 3 variables. (Gender)
            /***********************************************************************************/
            case "gender":
                include 'lib/class.genderparse.php';
                if(isset($this->langParameters) and isset($this->word) and isset($this->source) and isset($this->count)){
                    $GenderParse = new GenderParse($this->langParameters);
                    $wikitext = $this->get_wiki_text($this->langParameters, $this->source, $this->word);
                    if(isset($DefParse)){
                        $parsedDefinition = $GenderParse->get_gender($wikitext, $this->count);
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
    public function get_wiki_text($langParameters, $wikiSource, $word) {
        $WikiExtract = new WikiExtract($langParameters, $wikiSource);
        return $WikiExtract->get_wikitext($word);
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
