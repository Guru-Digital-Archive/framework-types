<?php

namespace GDM\Framework\Types\Settings;

class DateSettings
{
    /**
     *
     * @var \DateTimeZone
     */
    public $timeZone           = 'Pacific/Auckland';
    public $dateFromat         = 'd/m/Y';
    public $dateTimeFromat     = 'd/m/Y H:i';
    public $longDateFormat     = 'l jS \of F Y';
    public $longDateTimeFormat = 'l jS \of F Y h:i:s A';

    /**
     *
     * @param \DateTimeZone|string $timeZone <p>Can be a string name of a time zone or \DateTimeZone instace</p>
     * @param string $dateFromat <p>String date format accepted by date() for short dates eg d/m/y.</p>
     * @param string $dateTimeFromat <p>String date format accepted by date() for short date time eg d/m/y H:i.</p>
     * @param string $longDateFormat <p>String date format accepted by date() for short date time eg l jS \of F Y.</p>
     * @param string $longDateTimeFormat <p>String date format accepted by date() for short date time eg l jS \of F Y h:i:s A.</p>
     *
     * @link http://www.php.net/manual/en/timezones.php Valid time zones
     * @link http://www.php.net/manual/en/class.datetimezone.php
     * @link http://www.php.net/manual/en/function.date.php
     */
    public function __construct($timeZone = null, $dateFromat = null, $dateTimeFromat = null, $longDateFormat = null, $longDateTimeFormat = null)
    {
        $this->timeZone           = $timeZone? : DefaultDateSettings::$defaultTimeZone;
        $this->dateFromat         = $dateFromat? : DefaultDateSettings::$defaultDateFromat;
        $this->dateTimeFromat     = $dateTimeFromat? : DefaultDateSettings::$defaultDateTimeFromat;
        $this->longDateFormat     = $longDateFormat? : DefaultDateSettings::$defaultLongDateFormat;
        $this->longDateTimeFormat = $longDateTimeFormat? : DefaultDateSettings::$defaultLongDateTimeFormat;
        if (is_string($this->timeZone)) {
            $this->timeZone = new \DateTimeZone($this->timeZone);
        }
    }

    /**
     *
     * @param \DateTimeZone|string $timeZone <p>Can be a string name of a time zone or \DateTimeZone instace</p>
     * @param string $dateFromat <p>String date format accepted by date() for short dates eg d/m/y.</p>
     * @param string $dateTimeFromat <p>String date format accepted by date() for short date time eg d/m/y H:i.</p>
     * @param string $longDateFormat <p>String date format accepted by date() for short date time eg l jS \of F Y.</p>
     * @param string $longDateTimeFormat <p>String date format accepted by date() for short date time eg l jS \of F Y h:i:s A.</p>
     *
     * @link http://www.php.net/manual/en/timezones.php Valid time zones
     * @link http://www.php.net/manual/en/class.datetimezone.php
     * @link http://www.php.net/manual/en/function.date.php
     *
     * @return DateSettings
     */
    public static function create($timeZone = null, $dateFromat = null, $dateTimeFromat = null, $longDateFormat = null, $longDateTimeFormat = null)
    {
        return new self($timeZone, $dateFromat, $dateTimeFromat, $longDateFormat, $longDateTimeFormat);
    }

    /**
     *
     * @return \DateTimeZone
     */
    public function getTimeZone()
    {
        return $this->timeZone;
    }

    public function getDateTimeFromat()
    {
        return $this->dateTimeFromat;
    }

    public function getLongDateFormat()
    {
        return $this->longDateFormat;
    }

    public function getLongDateTimeFormat()
    {
        return $this->longDateTimeFormat;
    }

    /**
     *
     * @param \DateTimeZone|string $timeZone <p>Set the time zone, Can be a string name of a time zone or \DateTimeZone instace</p>
     * @return \GDM\Framework\Types\Settings\DateSettings
     */
    public function setTimeZone($timeZone)
    {
        if (is_string($timeZone)) {
            $timeZone = new \DateTimeZone($this->timeZone);
        }
        $this->timeZone = $timeZone;
        return $this;
    }

    /**
     *
     * @param string $dateFromat <p>Set the string date format accepted by date() for short dates eg d/m/y.</p>
     * @return \GDM\Framework\Types\Settings\DateSettings
     */
    public function setDateFromat($dateFromat)
    {
        $this->dateFromat = $dateFromat;
        return $this;
    }

    /**
     *
     * @param type $dateTimeFromat <p>String date format accepted by date() for short date time eg d/m/y H:i.</p>
     * @return \GDM\Framework\Types\Settings\DateSettings
     */
    public function setDateTimeFromat($dateTimeFromat)
    {
        $this->dateTimeFromat = $dateTimeFromat;
        return $this;
    }

    /**
     *
     * @param type $longDateFormat <p>String date format accepted by date() for short date time eg l jS \of F Y.</p>
     * @return \GDM\Framework\Types\Settings\DateSettings
     */
    public function setLongDateFormat($longDateFormat)
    {
        $this->longDateFormat = $longDateFormat;
        return $this;
    }

    /**
     *
     * @param type $longDateTimeFormat <p>String date format accepted by date() for short date time eg l jS \of F Y h:i:s A.</p>
     * @return \GDM\Framework\Types\Settings\DateSettings
     */
    public function setLongDateTimeFormat($longDateTimeFormat)
    {
        $this->longDateTimeFormat = $longDateTimeFormat;
        return $this;
    }
}