<?php
namespace ybourque\Wikparser\lib\Lang;

class De extends AbstractLang
{
    protected $langCode = "de";
    protected $langSeparator = "({{Sprache|";
    protected $defHeader = "{{Bedeutungen}}";
    protected $defTag = ":";
    protected $synHeader = "{{Synonyme}}";
    protected $hyperHeader = "{{Oberbegriffe}}";
    protected $genderPattern = "(\{\{[mfn]\}\}\s===)";
    protected $posMatchType = "preg";
    protected $posPattern = "(\{\{Wortart\|\w+\|)";
    protected $posArray = array();
    protected $posExtraString = "{{Wortart|";
    protected $langHeader = 'Deutsch}})\s*==';
}
