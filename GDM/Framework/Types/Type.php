<?php

namespace GDM\Framework\Types;

/**
 *
 * @author Corey Sewell <corey@gurudigital.nz>
 */
abstract class Type
{

    /**
     *
     * @param mixed $value [Optional]
     *
     * @throws \UnexpectedValueException
     * @codeCoverageIgnore
     * @return static
     */
    static function create($value = null)
    {
        return new static($value);
    }

    /**
     * @param mixed $value [Optional]
     *
     * @throws \UnexpectedValueException
     * @codeCoverageIgnore
     * @api
     */
    public function __construct($value = null)
    {
        $this->set($value);
    }

    /**
     * @param mixed $value
     *
     * @throws \UnexpectedValueException
     * @api
     * @return static
     */
    final public function set($value)
    {
        $isValid = false;

        if ($value === null) {
            $this->clear();
            $isValid = true;
        } elseif (is_object($value)) {
            $isValid = $this->fromObject($value);
        } elseif (is_bool($value)) {
            $isValid = $this->fromBool($value);
        } elseif (is_int($value)) {
            $isValid = $this->fromInt($value);
        } elseif (is_double($value)) {
            $isValid = $this->fromDouble($value);
        } elseif (is_string($value)) {
            $isValid = $this->fromString($value);
        } elseif (is_array($value)) {
            $isValid = $this->fromArray($value);
        } elseif (is_resource($value)) {
            $isValid = $this->fromResource($value);
        }

        if (!$isValid) {
            throw new \UnexpectedValueException(sprintf(
                'The value of type "%s" could not be converted to "%s".', gettype($value), get_called_class()
            ));
        }
        return $this;
    }

    /**
     * @param string $value
     *
     * @return boolean
     * @codeCoverageIgnore
     * @api
     */
    protected function fromString($value)
    {
        return false;
    }

    /**
     * @param object $value
     *
     * @return boolean
     * @codeCoverageIgnore
     * @api
     */
    protected function fromObject($value)
    {
        return false;
    }

    /**
     * @param boolean $value
     *
     * @return boolean
     * @codeCoverageIgnore
     * @api
     */
    protected function fromBool($value)
    {
        return false;
    }

    /**
     * @param integer $value
     *
     * @return boolean
     * @codeCoverageIgnore
     * @api
     */
    protected function fromInt($value)
    {
        return false;
    }

    /**
     * @param double $value
     *
     * @return boolean
     * @codeCoverageIgnore
     * @api
     */
    protected function fromDouble($value)
    {
        return false;
    }

    /**
     * @param array $value
     *
     * @return boolean
     * @codeCoverageIgnore
     * @api
     */
    protected function fromArray($value)
    {
        return false;
    }

    /**
     * @param resource $value
     *
     * @return boolean
     * @codeCoverageIgnore
     * @api
     */
    protected function fromResource($value)
    {
        return false;
    }

    /**
     * Returns a php native representation
     *
     * @return mixed
     * @codeCoverageIgnore
     * @api
     */
    abstract public function get();

    /**
     * Clear the value.
     * @codeCoverageIgnore
     * @api
     */
    abstract protected function clear();
}