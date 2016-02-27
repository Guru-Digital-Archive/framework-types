<?php

namespace GDM\Framework\Types;

class ArrayWrapper extends Scalar implements \ArrayAccess, \Countable, \IteratorAggregate
{

    /**
     * Strip whitespace (or other characters) from the beginning and end of each in the list string
     * see http://php.net/manual/en/function.trim.php
     * @param string $character_mask [optional] <p>
     * Optionally, the stripped characters can also be specified using
     * the <i>character_mask</i> parameter.
     * Simply list all characters that you want to be stripped. With
     * .. you can specify a range of characters.
     * </p>
     * @return \GDM\Framework\Types\ArrayWrapper  The ArrayWrapper will all items trimmed.
     */
    public function trim($character_mask = " \t\n\r\0\x0B")
    {
        array_walk($this->returnValue, function(&$val) use($character_mask) {
            $val = trim($character_mask);
        });

        return $this;
    }

    /**
     * Filters the ArrayWrapper items using a callback function
     * see http://php.net/manual/en/function.array-filter.php
     * @param callable $callback [optional] <p>
     * The callback function to use
     * </p>
     * <p>
     * If no <i>callback</i> is supplied, all entries of
     * <i>array</i> equal to <b>FALSE</b> (see
     * converting to
     * boolean) will be removed.
     * </p>
     * @param int $flag [optional] <p>
     * Flag determining what arguments are sent to <i>callback</i>:
     * <b>ARRAY_FILTER_USE_KEY</b> - pass key as the only argument
     * to <i>callback</i> instead of the value
     * @return \GDM\Framework\Types\ArrayWrapper
     */
    public function filter(callable $callback = null, $flag = 0)
    {
        if ($callback && $flag) {
            $this->returnValue = array_filter($this->returnValue, $callback, $flag);
        } else if ($callback) {
            $this->returnValue = array_filter($this->returnValue, $callback);
        } else {
            $this->returnValue = array_filter($this->returnValue);
        }
        return $this;
    }

    protected function clear()
    {
        $this->orignalValue = $this->returnValue  = [];
        return $this;
    }

    /**
     * Get the resulting string
     * @return string
     */
    public function get()
    {
        return $this->returnValue;
    }

    function fromObject($value)
    {
        $this->clear();
        $this->orignalValue = $this->returnValue  = [$value];
        return $this;
    }

    function fromBool($value)
    {
        $this->clear();
        $this->orignalValue = $this->returnValue  = [$value];
        return $this;
    }

    function fromInt($value)
    {
        $this->clear();
        $this->orignalValue = $this->returnValue  = [$value];
        return $this;
    }

    function fromDouble($value)
    {
        $this->clear();
        $this->orignalValue = $this->returnValue  = [$value];
        return $this;
    }

    function fromString($value)
    {
        $this->clear();
        $this->orignalValue = $this->returnValue  = [$value];
        return $this;
    }

    function fromDelimitedString($value, $seperator = ",")
    {
        $this->clear();
        $this->orignalValue = $this->returnValue  = explode($seperator, $value);
        return $this;
    }

    public function fromArray($value)
    {
        $this->clear();
        $this->orignalValue = $this->returnValue  = $value;
        return $this;
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->returnValue[] = $value;
        } else {
            $this->returnValue[$offset] = $value;
        }
    }

    public function offsetExists($offset)
    {
        return isset($this->returnValue[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->returnValue[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->returnValue[$offset]) ? $this->returnValue[$offset] : null;
    }

    public function count()
    {
        return count($this->returnValue);
    }

    public function length()
    {
        return count($this);
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->returnValue);
    }

    public function toInts()
    {
        return array_map(function($value) {
            return (int) trim($value);
        }, $this->returnValue);
    }
}