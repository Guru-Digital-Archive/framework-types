<?php

namespace GDM\Framework\Types;

class ArrayWrapper extends Scalar implements \ArrayAccess, \Countable, \IteratorAggregate
{

    /**
     * Trimes each element of this array
     *
     * @return \GDM\Framework\Types\ArrayWrapper
     */
    public function trim()
    {
        array_walk($this->returnValue, function(&$val) {
            $val = trim($val);
        });

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