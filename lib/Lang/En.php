<?php
namespace ybourque\Wikparser\lib\Lang;

class En extends AbstractLang
{
    protected $langCode = "en";
    protected $langSeparator = "----";
    protected $defHeader = "";
    protected $defTag = "# ";
    protected $synHeader = "====Synonyms====";
    protected $hyperHeader = "====Hypernyms====";
    protected $genderPattern = "";
    protected $posMatchType = "array";
    protected $posPattern = "";
    protected $posArray = array(
        '===Noun===',
        '===Verb===',
        '===Adjective===',
        '===Adverb===',
        '===Preposition===',
        '===Particle===',
        '===Pronouns===',
        '===Interjection===',
        '===Conjunction===',
        '===Article==='
    );
    protected $posExtraString = "=";
    protected $langHeader = '==\s*English\s*==';
}
