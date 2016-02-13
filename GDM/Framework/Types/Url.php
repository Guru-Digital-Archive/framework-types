<?php

namespace GDM\Framework\Types;

/**
 * Helper class for manipulatring URLs
 * ----
 * @author Corey Sewell <corey@gurudigital.nz>
 */
class Url extends Scalar implements \ArrayAccess
{
    public $scheme       = null;
    public $host         = null;
    public $port         = null;
    public $user         = null;
    public $pass         = null;
    public $pathSegments = [];
    public $parameters   = [];
    public $fragment     = null;
    private $serverVars  = null;

    /**
     * Parses the current returnValue and fills this objects properties
     *
     * @return $this
     */
    private function parse()
    {
        $this->parameters   = [];
        $this->pathSegments = [];
        $parsedUrl          = parse_url($this->returnValue);
        foreach ($parsedUrl as $key => $value) {
            if ($key == 'query') {
                parse_str($value, $this->parameters);
            } elseif ($key == 'path') {
                $this->pathSegments = ($value) ? explode('/', ltrim($value, '/')) : [];
            } else {
                $this->{$key} = $value;
            }
        }
        return $this;
    }

    /**
     * Magic method, used by access inaccessible properties.
     * Returns the value of the query string parameter named in <i>$key</i>
     *
     *
     * @param string $key The key of the parameter value to return
     * @return mixed The value of <i>$key</i> or null if its not set
     */
    public function __get($key)
    {
        return isset($this->parameters[$key]) ? $this->parameters[$key] : null;
    }

    /**
     * Magic method, used by setting inaccessible properties.
     *
     * Sets the value of the query string parameter named in <i>$key</i>
     *
     * @param string $key The key of the parameter value to set
     * @param string $value The value to set for the parameter
     */
    public function __set($key, $value)
    {
        if (is_bool($value)) {
            $value = $value ? 'true' : 'false';
        } else if (is_array($value) || is_object($value)) {
            $value = serialize($value);
        }
        $this->parameters[$key] = $value;
    }

    /**
     * Magic method, used by calling isset() or empty() on inaccessible properties.
     *
     * Determine if a query parameter is set and is not NULL.
     *
     * @param string $key The key of the parameter value to test
     * @return bool true if property exists and has value other than null, false otherwise.
     */
    public function __isset($key)
    {
        return isset($this->parameters[$key]);
    }

    /**
     * Magic method, used by calling unset() on inaccessible properties.
     *
     * Removes a query parameter from the url
     *
     * @param string $key The key of the parameter remove
     *
     */
    public function __unset($key)
    {
        if (isset($this->parameters[$key])) {
            unset($this->parameters[$key]);
        }
    }

    /**
     * Checks if this url object is equal to another.
     * @param string $str2 <p>
     * The string to compare against
     * </p>
     * @param bool $caseSensitive [optional] <p>
     * If true the string comparision is case sensitive
     * </p>
     * </p>
     * @param bool $includeQuery [optional] <p>
     * If true the query string is ignored from the string comparision
     * </p>
     * @param bool $ignoreSpecialChars [optional] <p>
     * If true the special characters are ignored from the string comparision
     * </p>
     * @return int &lt; 0 if <i>str1</i> is less than
     * <i>str2</i>; &gt; 0 if <i>str1</i>
     * is greater than <i>str2</i>, and 0 if they are
     * equal.
     */
    public function compareTo($url, $caseSensitive = true, $includeQuery = false, $ignoreSpecialChars = false)
    {
        /* @var $urlObj Url */
        $urlObj  = $url instanceof Url ? $url : self::create($url)->decodePath();
        /* @var $urlObj2 Url */
        $urlObj2 = clone $this;
        $urlObj2->decodePath();
        if ($ignoreSpecialChars) {
            $urlObj->tidy();
            $urlObj2->tidy();
        }
        if (!$includeQuery) {
            $urlObj->parameters  = [];
            $urlObj2->parameters = [];
        }
        return $caseSensitive ? strcmp($urlObj, $urlObj2) : strcasecmp($urlObj, $urlObj2);
    }

    /**
     * Calls rawurldecode on each path segment, leaving the seperator intact
     *
     * @link http://php.net/manual/en/function.rawurldecode.php
     * @return $this
     */
    public function decodePath()
    {
        array_walk($this->pathSegments, function(&$val) {
            $val = rawurldecode($val);
        });
        return $this;
    }

    /**
     * Calls rawurlencode on each path segment, leaving the seperator intact
     *
     * @link http://php.net/manual/en/function.rawurlencode.php
     * @return $this
     */
    public function encodePath()
    {
        array_walk($this->pathSegments, function(&$val) {
            $val = rawurlencode($val);
        });
        return $this;
    }

    /**
     * Create a Url instance from an object.
     *
     * @param object $value
     * @return $this
     */
    function fromObject($value)
    {
        $this->clear();
        $this->orignalValue = $this->returnValue  = (string) (method_exists($value, '__toString') ? $value : null);
        return $this->parse();
    }

    /**
     * Create a Url instance from a string.
     *
     * @param string $value
     * @return $this
     */
    function fromString($value)
    {
        $this->clear();
        $this->orignalValue = $this->returnValue  = (string) $value;
        return $this->parse();
    }

    /**
     * Create a Url instance from a resource.
     *
     * @param resource $value
     * @return $this
     */
    function fromResource($value)
    {
        $this->clear();
        $this->orignalValue = $this->returnValue  = stream_get_contents($value);
        return $this->parse();
    }

    /**
     * Empties the values of this Url
     *
     * @return $this
     */
    protected function clear()
    {
        $this->scheme       = null;
        $this->host         = null;
        $this->port         = null;
        $this->user         = null;
        $this->pass         = null;
        $this->pathSegments = [];
        $this->parameters   = [];
        $this->fragment     = null;
        $this->orignalValue = $this->returnValue  = '';
        return $this->parse();
    }

    /**
     * Get the resulting string
     * @return string
     */
    public function get()
    {
        return $this->toString();
    }

    /**
     * Whether a path segment exists
     *
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean <b>TRUE</b> on success or <b>FALSE</b> on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     */
    public function offsetExists($offset)
    {
        return isset($this->pathSegments[$offset]);
    }

    /**
     * Retrieve path segment
     *
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param int $offset <p>
     * The path segment offset to retrieve.
     * </p>
     * @return string|null The value of the path segement at the give offset or null if not set.
     */
    public function offsetGet($offset)
    {
        return isset($this->pathSegments[$offset]) ? $this->pathSegments[$offset] : null;
    }

    /**
     * Set a path segment
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param int $offset <p>
     * The path segment offset to assign the value to.
     * </p>
     * @param string $value <p>
     * The value to set.
     * </p>
     * @return void No value is returned.
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->pathSegments[] = $value;
        } else {
            $this->pathSegments[$offset] = $value;
        }
    }

    /**
     * Unsets a path segment
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The path segement offset to unset.
     * </p>
     * @return void No value is returned.
     */
    public function offsetUnset($offset)
    {
        unset($this->pathSegments[$offset]);
    }

    /**
     * Get a path segement<br>

     * @param int|string $segment <p>The index of the segement to get, "first" or "last".<br>
     * $url = Url::Create("http://example.com/zero/one/two");<br>
     * echo $url->getSement("first"); // Returns zero<br>
     * echo $url->getSement(0); // Returns zero<br>
     * echo $url->getSement(1); // Returns one<br>
     * echo $url->getSement(2); // Returns two<br>
     * echo $url->getSement("last"); // Returns two<br></p>
     *
     * @return string|false The value of the segement at the given index or false if not set
     */
    public function getSegment($segment = 0)
    {
        $result = null;
        if ($segment == "first") {
            $result = current($this->pathSegments);
        } else if ($segment == "last") {
            $result = end($this->pathSegments);
        } else if (isset($this->pathSegments[$segment])) {
            $result = $this->pathSegments[$segment];
        }
        return $result;
    }

    /**
     * Get the URL of current request.
     *
     * @param bool $includeQuery <p>Include the query string in the result</p>
     * @return Url The URL of the current request or cli if called from the command line
     */
    public function current($includeQuery = true)
    {
        $url = "";
        if (!$this->serverVars && php_sapi_name() == 'cli') {
            $url = "cli";
        } else {
            $url .= $this->currentScheme().$this->currentHost();
            $port = $this->currentPort();
            if (!in_array($port, [ false, 80])) {
                $url .= ':'.$port;
            }
            $url .= $this->currentPath();
            if ($includeQuery) {
                $query = $this->currentQuery();
                if ($query) {
                    $url .= '?'.$query;
                }
            }
        }
        return static::create($url);
    }

    /**
     * Get the Query string of current request.
     *
     * @return string|null The current requests query string to null if not set
     */
    public function currentQuery()
    {
        return $this->serverVar('QUERY_STRING');
    }

    /**
     * Get the scheme of the current request. E.g http/https
     *
     * @return string
     */
    public function currentScheme()
    {
        return 'http'.(String::create($this->serverVar('HTTPS'))->toBool() ? 's' : '').'://';
    }

    /**
     * Get the host of the current request.
     *
     * @return string
     */
    public function currentHost()
    {
        return $this->serverVar('HTTP_HOST');
    }

    /**
     * Get the port of the current request.
     *
     * @return string
     */
    public function currentPort()
    {
        return $this->serverVar('SERVER_PORT');
    }

    /**
     * Get the path of the current request.
     *
     * @return string
     */
    public function currentPath()
    {
        $requestURI = $this->serverVar('REQUEST_URI');
        $sciptName  = $this->serverVar('SCRIPT_NAME');
        return rtrim(parse_url($requestURI? : $sciptName, PHP_URL_PATH), '/');
    }

    /**
     * Make a request to the URL and retieve the result as a string
     *
     * @param bool $forwardCookie <p>If true, the current request cookie will be forwarded in the request</p>
     * @param type $contextOptions  [optional] <p>
     * Must be an associative array in the format
     * $arr['parameter'] = $value.
     * Refer to context parameters for
     * a listing of standard stream parameters.
     * </p>
     * @link http://www.php.net/manual/en/context.php Options that can be passed with $contectOptions
     * @link http://php.net/manual/en/function.file-get-contents.php The internal method used to fetch the url conetents
     * @return type
     */
    public function fetch($forwardCookie = true, $contextOptions = [])
    {
        $cookie = $this->serverVar('HTTP_COOKIE');
        if ($forwardCookie && $cookie !== false) {
            $cookie         = ['http' => ['header' => 'Cookie: '.$cookie."\r\n"]];
            $contextOptions = array_merge($cookie, $contextOptions);
        }
        $context = stream_context_create($contextOptions);
        return file_get_contents((string) $this, false, $context);
    }

    /**
     * Tidies this URL by removing all special characters, except _ and -.<br/>
     * Replaces white spaces with _<br/>
     * Replaces & with "and"<br/>
     *
     * @return \GDM\Framework\Types\Url
     */
    function tidy()
    {
        $regFind    = ['/\s/', '/[^a-zA-Z0-9_\- ]/', '/_+/'];
        $regReplace = ['_', '', '_'];
        array_walk($this->pathSegments,
                   function(&$val) use ($regFind, $regReplace) {
            $val = preg_replace($regFind, $regReplace, str_replace('&', 'and', trim($val)));
        });
        return $this;
    }

    /**
     * Gets the current URL as a string
     *
     * @return string The resulting URL as a string
     */
    public function toString()
    {
        $result = "";
        if ($this->scheme) {
            $result .= $this->scheme.'://';
        }
        if ($this->user) {
            $result .= $this->user;
            if ($this->pass) {
                $result .= $this->user;
            }
            $result .= '@';
        }
        if ($this->host) {
            $result .= $this->host;
        }
        if ($this->port) {
            $result .= ':'.$this->port;
        }
        if ($this->pathSegments) {
            $result .= '/'.implode('/', $this->pathSegments);
        }
        if ($this->parameters) {
            $result .= '?'.http_build_query($this->parameters);
        }
        if ($this->fragment) {
            $result .= '#'.$this->fragment;
        }
        return $result;
    }

    /**
     * Return this url and a \GDM\Framework\Types\String
     *
     * @return String
     */
    public function asString()
    {

        return String::create($this);
    }

    /**
     * Determines if the current URL is https
     *
     * @return bool True if this url starts with https
     */
    public function isHttps()
    {
        return $this->asString()->startsWith("https://");
    }

    /**
     * Determines if the current URL is a full URL or just a path
     *
     * @return bool true if this is a full URL
     */
    public function isFullUrl()
    {
        return !empty($this->scheme);
    }

    /**
     * Reurns filters $_SERVER array.
     * Create to make this call better unit testable
     *
     * @param type $key
     * @return array|null
     */
    private function serverVar($key)
    {
        if (!$this->serverVars) {
            $this->serverVars = filter_input_array(INPUT_SERVER);
        }
        return isset($this->serverVars[$key]) ? $this->serverVars[$key] : null;
    }
}