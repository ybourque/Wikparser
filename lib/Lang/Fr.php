<?php
namespace ybourque\Wikparser\lib\Lang;

class Fr extends AbstractLang
{
    protected $langCode = "fr";
    protected $langSeparator = "== {{=";
    protected $defHeader = "";
    protected $defTag = "# ";
    protected $synHeader = "==== {{S|synonymes}} ====";
    protected $hyperHeader = "==== {{S|hyperonymes}} ====";
    protected $genderPattern = "(\{\{([mf]|mf)\??\}\})";
    protected $posMatchType = "preg";
    protected $posPattern = "(\{\{\S\|[\d\w\s]+\|fr(\|num=[0-9])?\}\})u";
    protected $posArray = array();
    protected $posExtraString = "{{S|";
    protected $langHeader = 'fr}}\s*==';
}
