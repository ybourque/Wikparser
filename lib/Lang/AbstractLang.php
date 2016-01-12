<?php
namespace ybourque\Wikparser\lib\Lang;

use ArrayAccess;

abstract class AbstractLang implements ArrayAccess
{
    protected $langCode;
    protected $langSeparator;
    protected $defHeader;
    protected $defTag;
    protected $synHeader;
    protected $hyperHeader;
    protected $genderPattern;
    protected $posMatchType;
    protected $posPattern;
    protected $posArray = array();
    protected $posExtraString;
    protected $langHeader;

    public function offsetExists($key)
    {
        return property_exists($this, $key);
    }

    public function offsetGet($key)
    {
        return $this->$key;
    }

    public function offsetSet($key, $val)
    {
        $this->$key = $val;
    }

    public function offsetUnset($key)
    {
        $this->$key = null;
    }
}
