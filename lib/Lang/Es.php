<?php
namespace ybourque\Wikparser\lib\Lang;

class Es extends AbstractLang
{
    protected $langCode = "es";
    protected $langSeparator = "";
    protected $defHeader = "";
    protected $defTag = ";";
    protected $synHeader = "'''Sinónimo";
    protected $hyperHeader = "";
    protected $genderPattern = "(\s?(masculino|femenino)(\|es)?\}\}\s?===)";
    protected $posMatchType = "preg";
    protected $posPattern = "(===\s?\{\{\w*[\|\s])";
    protected $posArray = array();
    protected $posExtraString = "";
    protected $langHeader = '{{ES';
}
