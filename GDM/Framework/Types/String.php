<?php

namespace GDM\Framework\Types;

/**
 *
 *
 * @author Corey Sewell <corey@gdmedia.tv>
 */
class String extends Scalar {

    protected $falseStrings = ["off", "false", "no", "0", "zero"];
    protected $lineBreaks   = ["\r\n", "\r", "\n"];

    /**
     * Strip non alpha numeric characters form the string
     * @param string $separator [optional] <p>
     * Replace all non alpha numeric characters with this.</p>
     * <p>Default is <b>-</b>
     * </p>
     * @param string $length [optional] <p>
     * Optionally, truncate the string to this length
     * </p>
     * @return self
     */
    function clean($separator = '-', $length = -1) {
        // replace non alphanumeric and non underscore charachters by separator
        $this->replace('/[^a-z0-9]/i', $separator);
        if (!empty($separator)) {
            // replace multiple occurences of separator by one instance
            $this->replace('/' . preg_quote($separator) . '[' . preg_quote($separator) . ']*/', $separator);
        }
        // cut off to maximum length
        if ($length > -1 && $this->length() > $length) {
            $this->truncate($length, '');
        }

        // remove separator from start and end of string
        $this->trim($separator);
        return $this;
    }

    /**
     * Checks if this string object is equal to another.
     * @param string $str2 <p>
     * The string to compare against
     * </p>
     * @param bool $caseSensitive [optional] <p>
     * If true the string comparision is case sensitive
     * </p>
     * @return boolean true if matched false otherwise.
     */
    public function compareTo($str2, $caseSensitive = true) {
        $str2   = $caseSensitive ? $str2 : strtolower($str2);
        $string = $caseSensitive ? $this->returnValue : strtolower($this->returnValue);
        return ($string == $str2);
    }

    /**
     * Checks if this string contains another.
     * @param string $needle <p>
     * The string to compare against
     * </p>
     * @param bool $caseSensitive [optional] <p>
     * If true the string comparision is case sensitive
     * </p>
     * @return boolean true if needle is found in this string.
     */
    public function contains($needle, $caseSensitive = true) {
        $needle = $caseSensitive ? $needle : strtolower($needle);
        $string = $caseSensitive ? $this->returnValue : strtolower($this->returnValue);
        return (!(strpos($string, $needle) === false));
    }

    /**
     * Make a string lowercase
     * @link http://php.net/manual/en/function.strtolower.php
     * @return $this
     */
    public function toLowerCase() {
        $this->returnValue = strtolower($this->returnValue);
        return $this;
    }

    /**
     * Checks if this string is a valid interger
     * @link http://www.php.net/manual/en/filter.filters.validate.php#FILTER_VALIDATE_INT
     * @return $this
     */
    public function isInt() {
        return filter_var($this->returnValue, FILTER_VALIDATE_INT) !== false;
    }

    /**
     * Checks if this string is a valid float
     * @link http://www.php.net/manual/en/filter.filters.validate.php#FILTER_VALIDATE_FLOAT
     * @return $this
     */
    public function isFloat() {
        return filter_var($this->returnValue, FILTER_VALIDATE_FLOAT) !== false;
    }
    
    /**
     * Strip all linebreack characters from this string
     * @param strig $replacement [optional] <p>
     * Optionally, replace linebreaks with the character
     * </p>
     * @return self
     */
    public function stripLineBreak($replacement = '') {
        $this->returnValue = str_replace($this->lineBreaks, $replacement, $this->returnValue);
        return $this;
    }

    /**
     * Strip whitespace (or other characters) from the beginning and end of this string
     * @link http://php.net/manual/en/function.trim.php
     * @param string $characterMask [optional] <p>
     * Optionally, the stripped characters can also be specified using
     * the <i>characterMask</i> parameter.
     * Simply list all characters that you want to be stripped. With
     * .. you can specify a range of characters.
     * </p>
     * @return self
     */
    public function trim($characterMask = " \t\n\r\0\x0B") {
        $this->returnValue = trim($this->returnValue, $characterMask);
        return $this;
    }

    /**
     * Strip whitespace (or other characters) from the beginning and end of a string
     * Strip whitespace (or other characters) from the beginning of a string
     * @link http://php.net/manual/en/function.ltrim.php
     * @param string $characterMask [optional] <p>
     * You can also specify the characters you want to strip, by means of the
     * <i>characterMask</i> parameter.
     * Simply list all characters that you want to be stripped. With
     * .. you can specify a range of characters.
     * </p>
     * <b>ltrim</b> will strip these characters:
     * " " (ASCII 32
     * (0x20)), an ordinary space.
     * "\t" (ASCII 9
     * (0x09)), a tab.
     * "\n" (ASCII 10
     * (0x0A)), a new line (line feed).
     * "\r" (ASCII 13
     * (0x0D)), a carriage return.
     * "\0" (ASCII 0
     * (0x00)), the NUL-byte.
     * "\x0B" (ASCII 11
     * (0x0B)), a vertical tab.
     *
     * @return self
     */
    public function leftTrim($characterMask = " \t\n\r\0\x0B") {
        $this->returnValue = ltrim($this->returnValue, $characterMask);
        return $this;
    }

    /**
     * Strip whitespace (or other characters) from the end of a string
     * @link http://php.net/manual/en/function.rtrim.php
     * @param string $characterMask [optional] <p>
     * You can also specify the characters you want to strip, by means
     * of the <i>characterMask</i> parameter.
     * Simply list all characters that you want to be stripped. With
     * .. you can specify a range of characters.
     * </p>
     *
     * @return self
     */
    public function rightTrim($characterMask = " \t\n\r\0\x0B") {
        $this->returnValue = rtrim($this->returnValue, $characterMask);
        return $this;
    }

    /**
     * Insert a seperator between every non continuous uppercase character
     * @param string $seperator [optional] <p>
     * The seperator string.
     * </p>
     *
     * @return self
     */
    public function splitByCaps($seperator = ' ') {
        $this->returnValue = preg_replace('/(?<!^)((?<![[:upper:]])[[:upper:]]|[[:upper:]](?![[:upper:]]))/', $seperator . '$1', $this->returnValue);
        return $this;
    }

    /**
     * Uppercase the first character of each word in a string
     * @link http://php.net/manual/en/function.ucwords.php
     * @return string
     */
    public function upperCaseWords() {
        $this->returnValue = ucwords($this->returnValue);
        return $this;
    }

    /**
     * Checks whether a string starts with $needle
     * @param string $needle
     * @return bool
     */
    public function startsWith($needle) {
        return strncmp($this->returnValue, $needle, strlen($needle)) == 0;
    }

    /**
     * Checks whether a string ends with $needle
     * @param string $needle
     * @return bool
     */
    public function endsWith($needle) {
        return substr($this->returnValue, -strlen($needle)) === $needle;
    }

    /**
     * Valid if the string is an email address
     * @param string $checkDNS [optional] <p>
     * If true, this method will use checkdnsrr and validate if the domain part of the email address is a valid domain.
     * </p>
     * @return bool
     */
    public function isEmail($checkDNS = true) {
        $isValid = filter_var($this->returnValue, FILTER_VALIDATE_EMAIL) !== false;
        if ($isValid && $checkDNS) {
            $domain  = substr(strrchr($this->returnValue, "@"), 1);
            // domain not found in DNS
            $isValid = checkdnsrr($domain, "MX") && checkdnsrr($domain, "A");
        }
        return $isValid;
    }

    /**
     * Truncates a string to a maximum length neatly and adds a truncation marker
     * when this happens. The maximum length of the string returned will be the
     * maxlength of the string - the truncation marker length.
     * It will not truncate the string in the middle of a word. Insead it will
     * truncate to the next blank space.
     * @param string $length <p>
     * The amount of chracters to truncate to ($marker is included in the length).
     * </p>
     * @param string $marker [optional]  <p>
     * Appended to the end of the truncated string. (default = ...)
     * </p>
     * @return bool
     */
    public function neatTruncate($length, $marker = '...') {
        $length = $length - (strlen($marker));
        $len    = $this->length();
        if ($len > $length) {
            $matches           = array();
            preg_match('/(.{' . $length . '}.*?)\b/', $this->returnValue, $matches);
            $result            = end($matches);
            $this->returnValue = rtrim($result) . $marker;
        }
        return $this;
    }

    /**
     * Truncates a string to a maximum length and adds a truncation marker
     * when this happens. The maximum length of the string returned will be the
     * maxlength of the string - the truncation marker length.
     * @param string $length <p>
     * The amount of chracters to truncate to ($marker is included in the length).
     * </p>
     * @param string $marker [optional]  <p>
     * @return self
     */
    public function truncate($length, $marker = '...') {
        if ($this->length() > $length) {
            $this->returnValue = substr($this->returnValue, 0, ($length - strlen($marker))) . $marker;
        }

        return $this;
    }

    /**
     * Get string length
     * @link http://php.net/manual/en/function.strlen.php
     * @return int The length of the <i>string</i> on success,
     * and 0 if the <i>string</i> is empty.
     */
    public function length() {
        return strlen($this->returnValue);
    }

    /**
     * Get the longest word in a sring
     * @return String The longest word in the current string
     */
    public function longestWord() {
        $words = str_word_count($this->returnValue, 1);

        $longestWordLength = 0;
        $longestWord       = "";

        foreach ($words as $word) {
            if (strlen($word) > $longestWordLength) {
                $longestWordLength = strlen($word);
                $longestWord       = $word;
            }
        }

        return self::create($longestWord);
    }

    /**
     * Return part of a string
     * @link http://php.net/manual/en/function.substr.php
     * @param int $start <p>
     * If <i>start</i> is non-negative, the returned string
     * will start at the <i>start</i>'th position in
     * <i>string</i>, counting from zero. For instance,
     * in the string 'abcdef', the character at
     * position 0 is 'a', the
     * character at position 2 is
     * 'c', and so forth.
     * </p>
     * <p>
     * If <i>start</i> is negative, the returned string
     * will start at the <i>start</i>'th character
     * from the end of <i>string</i>.
     * </p>
     * <p>
     * If <i>string</i> is less than or equal to
     * <i>start</i> characters long, <b>FALSE</b> will be returned.
     * </p>
     * <p>
     * Using a negative <i>start</i>
     * <code>
     * $rest = (string)(new String("abcdef"))->subString(-1); // returns "f"
     * $rest = (string)(new String("abcdef"))->subString(-2); // returns "ef"
     * $rest = (string)(new String("abcdef"))->subString(-3, 1); // returns "d"
     * </code>
     * </p>
     * @param int $length [optional] <p>
     * If <i>length</i> is given and is positive, the string
     * returned will contain at most <i>length</i> characters
     * beginning from <i>start</i> (depending on the length of
     * <i>string</i>).
     * </p>
     * <p>
     * If <i>length</i> is given and is negative, then that many
     * characters will be omitted from the end of <i>string</i>
     * (after the start position has been calculated when a
     * <i>start</i> is negative). If
     * <i>start</i> denotes the position of this truncation or
     * beyond, false will be returned.
     * </p>
     * <p>
     * If <i>length</i> is given and is 0,
     * <b>FALSE</b> or <b>NULL</b> an empty string will be returned.
     * </p>
     * <p>
     * If <i>length</i> is omitted, the substring starting from
     * <i>start</i> until the end of the string will be
     * returned.
     * </p>
     * Using a negative <i>length</i>
     * <code>
     *
     * $rest = (string)(new String("abcdef"))->subString(0, -1); // returns "abcde"
     * $rest = (string)(new String("abcdef"))->subString(2, -1); // returns "cde"
     * $rest = (string)(new String("abcdef"))->subString(4, -4); // returns false
     * $rest = (string)(new String("abcdef"))->subString(-3, -1); // returns "de"
     * </code>
     * @return self
     */
    public function subString($start, $length = null) {
        $length            = is_null($length) ? $this->length() : $length;
        $this->returnValue = substr($this->returnValue, $start, $length);
        return $this;
    }

    /**
     * Find the position of the first occurrence of a substring in a string
     * @link http://php.net/manual/en/function.strpos.php
     * @param mixed $needle <p>
     * If <i>needle</i> is not a string, it is converted
     * to an integer and applied as the ordinal value of a character.
     * </p>
     * @param int $offset [optional] <p>
     * If specified, search will start this number of characters counted from
     * the beginning of the string. Unlike <b>strrpos</b> and
     * <b>strripos</b>, the offset cannot be negative.
     * </p>
     * @return mixed the position of where the needle exists relative to the beginning of
     * the <i>haystack</i> string (independent of offset).
     * Also note that string positions start at 0, and not 1.
     * </p>
     * <p>
     * Returns <b>FALSE</b> if the needle was not found.
     */
    public function indexOf($needle, $offset = 0) {
        return strpos($this->returnValue, $needle, $offset);
    }

    /**
     * Escapes all double quotes ( eg " becomes \\" )
     *
     * @return self
     */
    public function escapeDblQuotes() {
        $this->returnValue = str_replace('"', '\\"', $this->returnValue);
        return $this;
    }

    /**
     * Removes all not numerical characters from the strine
     *
     * @return self
     */
    public function stripNonNums() {
        return $this->replace('#\D*?(\d+(\.\d+)?)\D*#', '$1');
    }

    /**
     * Perform a regular expression search and replace
     * @link http://php.net/manual/en/function.preg-replace.php
     * @param mixed $pattern <p>
     * The pattern to search for. It can be either a string or an array with
     * strings.
     * </p>
     * <p>
     * Several PCRE modifiers
     * are also available, including the deprecated 'e'
     * (PREG_REPLACE_EVAL), which is specific to this function.
     * </p>
     * @param mixed $replacement <p>
     * The string or an array with strings to replace. If this parameter is a
     * string and the <i>pattern</i> parameter is an array,
     * all patterns will be replaced by that string. If both
     * <i>pattern</i> and <i>replacement</i>
     * parameters are arrays, each <i>pattern</i> will be
     * replaced by the <i>replacement</i> counterpart. If
     * there are fewer elements in the <i>replacement</i>
     * array than in the <i>pattern</i> array, any extra
     * <i>pattern</i>s will be replaced by an empty string.
     * </p>
     * <p>
     * <i>replacement</i> may contain references of the form
     * \\n or (since PHP 4.0.4)
     * $n, with the latter form
     * being the preferred one. Every such reference will be replaced by the text
     * captured by the n'th parenthesized pattern.
     * n can be from 0 to 99, and
     * \\0 or $0 refers to the text matched
     * by the whole pattern. Opening parentheses are counted from left to right
     * (starting from 1) to obtain the number of the capturing subpattern.
     * To use backslash in replacement, it must be doubled
     * ("\\\\" PHP string).
     * </p>
     * <p>
     * When working with a replacement pattern where a backreference is
     * immediately followed by another number (i.e.: placing a literal number
     * immediately after a matched pattern), you cannot use the familiar
     * \\1 notation for your backreference.
     * \\11, for example, would confuse
     * <b>replace</b> since it does not know whether you
     * want the \\1 backreference followed by a literal
     * 1, or the \\11 backreference
     * followed by nothing. In this case the solution is to use
     * \${1}1. This creates an isolated
     * $1 backreference, leaving the 1
     * as a literal.
     * </p>
     * <p>
     * When using the deprecated e modifier, this function escapes
     * some characters (namely ', ",
     * \ and NULL) in the strings that replace the
     * backreferences. This is done to ensure that no syntax errors arise
     * from backreference usage with either single or double quotes (e.g.
     * 'strlen(\'$1\')+strlen("$2")'). Make sure you are
     * aware of PHP's string
     * syntax to know exactly how the interpreted string will look.
     * </p>
     * @param int $limit [optional] <p>
     * The maximum possible replacements for each pattern in each
     * <i>subject</i> string. Defaults to
     * -1 (no limit).
     * </p>
     * @param int $count [optional] <p>
     * If specified, this variable will be filled with the number of
     * replacements done.
     * </p>
     *
     * @return self
     */
    public function replace($pattern, $replacement, $limit = -1, &$count = null) {
        $this->returnValue = preg_replace($pattern, $replacement, $this->returnValue, $limit, $count);
        return $this;
    }

    /**
     * Replace all occurrences of the search string with the replacement string
     * @link http://php.net/manual/en/function.str-replace.php
     * @param mixed $search <p>
     * The value being searched for, otherwise known as the needle.
     * An array may be used to designate multiple needles.
     * </p>
     * @param mixed $replace <p>
     * The replacement value that replaces found <i>search</i>
     * values. An array may be used to designate multiple replacements.
     * </p>
     * @param int $count [optional] <p>
     * If passed, this will be set to the number of replacements performed.
     * </p>
     * @return self
     */
    public function simpleReplace($search, $replace, &$count = null) {
        $this->returnValue = str_replace($search, $replace, $this->returnValue, $count);
        return $this;
    }

    /**
     *
     */

    /**
     * Removes all non alpha numberical characters
     *
     * @param string $replacement the string to replace non alpha numeric characters
     * @return self
     */
    public function stripSpecialChars($replacement) {
        $this->returnValue = preg_replace('/[^A-Za-z0-9]/', $replacement, $this->returnValue);
        $this->returnValue = preg_replace('/ +/', $replacement, $this->returnValue);
        return $this;
    }

    /**
     * Split a string by string
     * @link http://php.net/manual/en/function.explode.php
     * @param string $delimiter <p>
     * The boundary string.
     * </p>
     * @param int $limit [optional] <p>
     * If <i>limit</i> is set and positive, the returned array will contain
     * a maximum of <i>limit</i> elements with the last
     * element containing the rest of <i>string</i>.
     * </p>
     * <p>
     * If the <i>limit</i> parameter is negative, all components
     * except the last -<i>limit</i> are returned.
     * </p>
     * <p>
     * If the <i>limit</i> parameter is zero, then this is treated as 1.
     * </p>
     * @return array an array of strings
     * created by splitting the <i>string</i> parameter on
     * boundaries formed by the <i>delimiter</i>.
     * </p>
     * <p>
     * If <i>delimiter</i> is an empty string (""),
     * <b>explode</b> will return <b>FALSE</b>.
     * If <i>delimiter</i> contains a value that is not
     * contained in <i>string</i> and a negative
     * <i>limit</i> is used, then an empty array will be
     * returned, otherwise an array containing
     * <i>string</i> will be returned.
     */
    public function split($delimiter, $limit = null) {
        return is_null($limit) ? explode($delimiter, $this->returnValue) : explode($delimiter, $this->returnValue, $limit);
    }

    /**
     * Make a string uppercase
     * @link http://php.net/manual/en/function.strtoupper.php
     * @return self
     */
    public function toUpperCase() {
        $this->returnValue = strtoupper($this->returnValue);

        return $this;
    }

    /**
     * Converts this string to a boolean.
     * @return self
     */
    public function toBool() {
        return (!is_null($this->returnValue)) &&
                (!empty($this->returnValue)) &&
                (!in_array(strtolower($this->returnValue), $this->falseStrings));
    }

    /**
     * Returns the string in reverse order.
     * @return self
     */
    public function reverse() {
        $this->returnValue = implode(array_reverse(preg_split('//', $this->returnValue, -1)));
        return $this;
    }

    /**
     * Returns an MD5 hash of the string.
     * @return string The md5 hash.
     */
    public function md5() {
        return md5($this->returnValue);
    }

    /**
     * Wrap this string in a comment block
     *
     * @param string $mime <p>The mime type of comment type<br>
     * e.g. <br>
     * "text/javascript" will be wrapped in &#47;** <i>string</i> **&#47;<br>
     * "text/html" will be wrapped in &lt;!-- <i>string</i> --&gt;<br><br>
     * Current support mimetypes are text/html, text/javascript, text/css and application/x-javascript </p>
     * @return self
     */
    public function toComment($mime) {
        $commentStart = "<!--";
        $commentEnd   = "-->";
        switch ($mime) {
            case "text/javascript":
            case "text/css":
            case "application/x-javascript":
                $commentStart = "/**";
                $commentEnd   = "**/";
                break;
            default:
                break;
        }
        $this->returnValue = $commentStart . " " . $this->returnValue . " " . $commentEnd;
        return $this;
    }

    /**
     * Generates a VALID RFC 4211 COMPLIANT Universally Unique IDentifier (UUID) 4.
     * @see http://www.php.net/manual/en/function.uniqid.php#94959
     * @return static
     */
    public static function createUUID() {
        return self::create(
                        sprintf(
                                '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                                // 32 bits for "time_low"
                                mt_rand(0, 0xffff), mt_rand(0, 0xffff),
                                // 16 bits for "time_mid"
                                mt_rand(0, 0xffff),
                                // 16 bits for "time_hi_and_version",
                                // four most significant bits holds version number 4
                                mt_rand(0, 0x0fff) | 0x4000,
                                // 16 bits, 8 bits for "clk_seq_hi_res",
                                // 8 bits for "clk_seq_low",
                                // two most significant bits holds zero and one for variant DCE1.1
                                mt_rand(0, 0x3fff) | 0x8000,
                                // 48 bits for "node"
                                mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
                        )
        );
    }

    /**
     * Generatesa random string
     * @param int $length [optional] <p>The length of the random string to generate
     * </p>
     * @return self
     */
    public static function createRandomAlphaNumeric($length = 8) {
        $chars  = array("abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ", "123456789");
        $count  = array((strlen($chars[0]) - 1), (strlen($chars[1]) - 1));
        $prefix = "";
        for ($i = 0; $i < $length; $i++) {
            $type = mt_rand(0, 1);
            $prefix .= substr($chars[$type], mt_rand(0, $count[$type]), 1);
        }
        return self::create($prefix);
    }

    function fromObject($value) {
        $this->clear();
        $this->orignalValue = $this->returnValue  = (string) (method_exists($value, "__toString") ? $value : get_class($value));
        return $this;
    }

    function fromBool($value) {
        $this->clear();
        $this->orignalValue = $this->returnValue  = $value ? "true" : "false";
        return $this;
    }

    function fromInt($value) {
        $this->clear();
        $this->orignalValue = $this->returnValue  = (string) $value;
        return $this;
    }

    function fromDouble($value) {
        $this->clear();
        $this->orignalValue = $this->returnValue  = (string) $value;
        return $this;
    }

    function fromString($value) {
        $this->clear();
        $this->orignalValue = $this->returnValue  = (string) $value;
        return $this;
    }

    function fromResource($value) {
        $this->clear();
        $this->orignalValue = $this->returnValue  = stream_get_contents($value);
        return $this;
    }

    protected function clear() {
        $this->orignalValue = $this->returnValue  = "";
        return $this;
    }

    /**
     * Get the resulting string
     * @return string
     */
    public function get() {
        return $this->returnValue;
    }

}
