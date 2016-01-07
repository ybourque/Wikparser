<?php
namespace ybourque\Wikparser\lib\Lang;

use ArrayAccess;

abstract class AbstractLang implements ArrayAccess
{
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
