<?php

namespace GDM\Framework\Types;

class Date extends \DateTime implements Interfaces\ConvertibleInterface
{

    use Traits\ScalarTrait;
    public static $mysqlFormat = "Y-m-d H:i:s";

    /**
     *
     * @var Settings\DateSettings
     */
    public $settings = null;

    /**
     *
     * @param type $time [optional] <p>A date/time string. Valid formats are explained in Date and Time Formats.<br>
     * Or a DateTime instance
     * Enter NULL here to obtain the current time when using the $timezone parameter.</p>
     * @param Settings\DateSettings $settings [optional] <p>A DateSettings object representing the formats to use.
     *
     * Note:
     * The $timezone parameter and the current timezone are ignored when the $time parameter either is a UNIX timestamp (e.g. @946684800) or specifies a timezone (e.g. 2010-01-28T15:00:00+02:00).</p>
     * @link http://www.php.net/manual/en/datetime.formats.php Date and Time Formats
     * @link http://www.php.net/manual/en/class.datetimezone.php
     */
    public function __construct($time = "now", Settings\DateSettings $settings = null)
    {
        if ($time instanceof \DateTime) {
            $time = $time->format(self::ATOM);
        }
        $this->settings = is_null($settings) ? new Settings\DateSettings() : $settings;
        parent::__construct($time, $this->settings->timeZone);
    }

    /**
     * Return date formatted as a short date according to the current DateSettings or DefaultDateSettings
     *
     * @return string
     * @see Settings\DefaultDateSettings
     * @see Settings\DateSettings
     */
    public function toShortDate()
    {
        return $this->format($this->settings->dateFromat);
    }

    /**
     * Return date formatted as a short date time according to the current DateSettings or DefaultDateSettings
     *
     * @return string
     * @see Settings\DefaultDateSettings
     * @see Settings\DateSettings
     */
    public function toShortDateTime()
    {
        return $this->format($this->settings->dateTimeFromat);
    }

    /**
     * Return date formatted as a long date according to the current DateSettings or DefaultDateSettings
     *
     * @return string
     * @see Settings\DefaultDateSettings
     * @see Settings\DateSettings
     */
    public function toLongDate()
    {
        return $this->format($this->settings->longDateFormat);
    }

    /**
     * Return date formatted as a short date time according to the current DateSettings or DefaultDateSettings
     *
     * @return string
     * @see Settings\DefaultDateSettings
     * @see Settings\DateSettings
     */
    public function toLongDateTime()
    {
        return $this->format($this->settings->longDateTimeFormat);
    }

    /**
     * Return date formatted as a mysql date string
     *
     * @return string
     */
    function toMysqlDate()
    {
        return $this->format(static::$mysqlFormat);
    }

    /**
     * Determine if the given date is before this date
     * 
     * @param \DateTime $toCheck
     * @return bool
     */
    public function isBefore(\DateTime $toCheck)
    {
        return $this->diff($toCheck)->invert == 0;
    }

    /**
     * Determine if the given date is after this date
     * 
     * @param \DateTime $toCheck
     * @return bool
     */
    public function isAfter(\DateTime $toCheck)
    {
        return $this->diff($toCheck)->invert == 1;
    }

    /**
     * Clear settings and returns them to DefaultDateSettings
     * Resets the date to "now"
     *
     */
    protected function clear()
    {
        $this->settings = new Settings\DateSettings();
        $this->setTimestamp(time());
    }

    /**
     * Return date formatted as a short date time according to the current DateSettings or DefaultDateSettings
     *
     * @return string
     * @see Settings\DefaultDateSettings
     * @see Settings\DateSettings
     */
    public function get()
    {
        return $this->toShortDateTime();
    }

    /**
     * Rounds the current time up to the nearest $step minute interval.
     * E.g. if $step=15, time will be rounded up to 15/30/45/00 
     * 
     * @param int $step - The interval step to roung to
     * @return \GDM\Framework\Types\Date
     */
    public function roundMinutes($step=15, $direction = null)
    {

        $function = "round";
        if ($direction && preg_match("/.*up.*/i", $direction)) {
            $function = "ceil";
        } else if ($direction && preg_match("/.*down.*/i", $direction)) {
            $function = "floor";
        }
        $stepSeconds    = $step * 60;
        $nowSeconds     = $this->getTimestamp();
        $roundedSeconds = $function($nowSeconds / ($stepSeconds)) * ($stepSeconds);
        $this->setTimestamp($roundedSeconds);
        return $this;
    }

    /**
     * Rounds the current time up to the next $step minute interval.
     * E.g. if $step=15, time will be rounded up to 15/30/45/00 
     * 
     * @param int $step - The interval step to roung to
     * @return \GDM\Framework\Types\Date
     */
    public function roundMinutesUp($step = 15)
    {
//        $secondsStep = $step * 60;
//        $now         = $this->getTimestamp();
//        $diff        = $now % ($secondsStep);
//        $this->setTimestamp($now + ($secondsStep - $diff));
        return $this->roundMinutes($step, "up");
    }

    /**
     * Rounds the current time down to the next $step minute interval.
     * E.g. if $step=15, time will be rounded up to 15/30/45/00 
     * 
     * @param int $step - The interval step to roung to
     * @return \GDM\Framework\Types\Date
     */
    public function roundMinutesDown($step = 15)
    {
//        $secondsStep = $step * 60;
//        $now         = $this->getTimestamp();
//        $diff        = $now % ($secondsStep);
//        $this->setTimestamp($now - ($secondsStep - $diff));
//        return $this;
        return $this->roundMinutes($step, "down");
    }

    /**
     * Create an instance from a short date or short date time formatted string
     *
     * @param string $date <p>Date to create instance from</p>
     *
     * @return Date
     * @see Settings\DefaultDateSettings
     */
    static function fromDate($date = false)
    {
        $format = count(explode(" ", $date)) > 1 ? Settings\DefaultDateSettings::$defaultDateTimeFromat : Settings\DefaultDateSettings::$defaultDateFromat;
        return ($date) ? static::createFromFormat($format, $date) : new self();
    }

    /**
     * Create an instance from a mysql date formatted string
     *
     * @param string $date
     * @return Date
     */
    static function fromMySqlDate($date, $settings = null)
    {
        return static::createFromFormat(self::$mysqlFormat, $date, $settings);
    }

    /**
     * Format a timestamp
     *
     * @param type $timestamp [optional] <p>Timestamp to be formatted, defaults to now</p>
     * @param string $format <p>The format that the passed in timestamp should be in. Defaults to DefaultDateSettings::$defaultDateTimeFromat</p>
     * @return string
     * @see Settings\DefaultDateSettings
     */
    static function formatTimestamp($timestamp = 'now', $format = false)
    {
        $dateObj = static::createFromTimestamp($timestamp);
        return $dateObj->format($format ? : Settings\DefaultDateSettings::$defaultDateTimeFromat);
    }

    /**
     * Gets the Unix timestamp from a given mysql date
     * @param type $mysqlDate <p>MySql date to convert to a timestamp</p>
     * @return int <p> The unix timestamp
     */
    static function mysqlToTimestamp($mysqlDate)
    {
        return static::fromMySqlDate($mysqlDate)->getTimestamp();
    }

    /**
     * Converts a default date time string to MySql format
     * @param string $dateTime <p>Date time to convert</p>
     * @return string MySql formatted date
     * @see Settings\DefaultDateSettings
     */
    static function dateTimeToMysql($dateTime = null)
    {
        return static::createFromFormat(Settings\DefaultDateSettings::$defaultDateTimeFromat, $dateTime)->toMysqlDate();
    }

    /**
     * Converts a MySql formatted date to a default date time string
     * @param type $mysqlDate <p>Mysql date to convert</p>
     * @return string Default date time string
     * @see Settings\DefaultDateSettings
     */
    static function mysqlToDateTime($mysqlDate = false)
    {
        return static::createFromFormat(static::$mysqlFormat, $mysqlDate)->format(Settings\DefaultDateSettings::$defaultDateTimeFromat);
    }

    /**
     * Compare the difference in days between to dates.
     * Dates are formatted as dateTime strings
     *
     * @param string $startDate The start date
     * @param string $endDate The end date
     * @param Settings\DateSettings $settings [optional] <p>A DateSettings object representing the formats to use.</p>
     * @return int|false Nmber of day difference or false on failure
     */
    static function getDaysDiff($startDate, $endDate, Settings\DateSettings $settings = null)
    {
        $format = $settings ? $settings->dateFromat : Settings\DefaultDateSettings::$defaultDateFromat;
        $start  = self::createFromFormat($format, $startDate);
        $end    = self::createFromFormat($format, $endDate);
        return $start && $end ? $start->diff($end)->format('%r%d') : false;
    }

    /**
     * Round a unix timestamp to the beggining of the day
     *
     * @param int $timestamp Time stamp to round
     * @return int Rounded time stamp
     */
    static function roundToDay($timestamp)
    {
        return self::createFromTimestamp($timestamp)->setTime(0, 0)->getTimestamp();
    }

    /**
     * Factory method to create an Date instance
     *
     * @param type $time [optional] <p>A date/time string. Valid formats are explained in Date and Time Formats.<br>
     * Or a DateTime instance
     * Enter NULL here to obtain the current time when using the $timezone parameter.</p>
     * @param Settings\DateSettings $settings [optional] <p>A DateSettings object representing the formats to use.
     *
     * Note:
     * The $timezone parameter and the current timezone are ignored when the $time parameter either is a UNIX timestamp (e.g. @946684800) or specifies a timezone (e.g. 2010-01-28T15:00:00+02:00).</p>
     * @link http://www.php.net/manual/en/datetime.formats.php Date and Time Formats
     * @link http://www.php.net/manual/en/class.datetimezone.php
     */
    static function create($time = "now", Settings\DateSettings $settings = null)
    {
        return new self($time, $settings);
    }

    /**
     *
     * @param string $format <p>The format that the passed in string should be in.</p>
     * @param string $string <p>String representing the time.</p>
     * @param Settings\DateSettings $settings [optional] <p>A DateSettings object representing the formats to use.
     * @return Date|bool new Date instance or FALSE on failure.
     * @link http://www.php.net/manual/en/datetime.createfromformat.php
     */
    static function createFromFormat($format, $string, $settings = null)
    {
        $settings = is_null($settings) ? new Settings\DateSettings() : $settings;
        $result   = parent::createFromFormat($format, $string, $settings->timeZone);
        return $result ? new Date($result->format(static::RFC850), $settings) : false;
    }

    /**
     * Create an instance from a timestamp
     *
     * @param type $timestamp [optional] <p>Timestamp to be formatted, defaults to now</p>
     * @param Settings\DateSettings $settings [optional] <p>A DateSettings object representing the formats to use.
     * @return date
     * @see Settings\DefaultDateSettings
     */
    static function createFromTimestamp($timestamp, $settings = null)
    {
        $settings = is_null($settings) ? new Settings\DateSettings() : $settings;
        $dateObj  = new Date('now', $settings);
        if (is_long($timestamp)) {
            $dateObj = new Date('@'.$timestamp);
        }
        return $dateObj;
    }

    /**
     * Determin if a timezone is in daylight savings
     *
     * @param \DateTimeZone|string $timeZone <p>Can be a string name of a time zone or \DateTimeZone instace</p>
     * @return bool True if the given time zone is currently in daylight savings
     * @link http://www.php.net/manual/en/timezones.php Valid time zones
     * @link http://www.php.net/manual/en/class.datetimezone.php
     */
    static function isTimezoneInDST($timeZone)
    {
        $tz    = is_string($timeZone) ? new \DateTimeZone($timeZone) : $timeZone;
        $trans = $tz->getTransitions();
        return ((count($trans) && $trans[count($trans) - 1]['ts'] > time()));
    }

    /**
     * Returns and array of all the days in a week
     *
     * @param string $startFrom [optional]<p>Day to start from. eg Monday will return an array starting witht the first element as Monday and the last as Sunday
     * @param type $format [optional]<p>Format to return the days as. eg l will return full names eg Monday, N will return 1 for Monday 7 for Sunday, D will return Mon to Sun</p>
     * @return array <p>The array containing all the days of the week in the specified format</p>
     * @link http://www.php.net/manual/en/datetime.formats.php Date and Time Formats
     */
    static function daysOfTheWeek($startFrom = 'Monday', $format = 'l')
    {
        $result      = [];
        $start       = new self($startFrom);
        $interval    = \DateInterval::createFromDateString('1 day');
        $recurrences = 6;
        foreach (new \DatePeriod($start, $interval, $recurrences) as $date) {
            $result[] = $date->format($format);
        }
        return $result;
    }

    /**
     * Returns and array of all the months in a year
     *
     * @param type $format [optional]<p>Format to return the days as. eg F will return full textual representation of each month, such as January or March, M will return short textual representation of each month, three letters Jan through Dec</p>
     * @return array <p>The array containing all the months of the year in the specified format</p>
     * @link http://www.php.net/manual/en/datetime.formats.php Date and Time Formats
     */
    static function monthsOfTheYear($format = "F")
    {
        $result      = [];
        $start       = new self('January this year');
        $interval    = new \DateInterval('P1M');
        $recurrences = 11;
        foreach (new \DatePeriod($start, $interval, $recurrences) as $date) {
            $result[] = $date->format($format);
        }
        return $result;
    }
}