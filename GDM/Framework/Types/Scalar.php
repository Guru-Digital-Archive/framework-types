<?php

namespace GDM\Framework\Types;

abstract class Scalar extends Type implements Interfaces\ConvertibleInterface {

    protected $orignalValue;
    protected $returnValue;

    public function __toString() {
        return $this->toString();
    }

    /**
     * @return boolean
     */
    public function toBoolean() {
        return (bool) $this->get();
    }

    /**
     * @return number
     */
    public function toInteger() {
        return (integer) $this->get();
    }

    /**
     * @return number
     */
    public function toDouble() {
        return (double) $this->get();
    }

    /**
     * @return string
     */
    public function toString() {
        return (string) $this->get();
    }

    /**
     * @return array
     */
    public function toArray() {
        return (array) $this->get();
    }

}
