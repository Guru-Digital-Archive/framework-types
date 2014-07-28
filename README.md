PHP Object orientated types
===============

A collection of helper classes that attempt to make dealing with PHP types a bit more Object Orientated
Inspired by https://github.com/alquerci/php-types-autoboxing

##Example usage
###String

```PHP
$string = "Here is a string that we will use for some examples        ";

// Implements __toString, so object instances can be used as plain old strings
$echoMe = GDM\Framework\Types\String::create($string);
echo $echoMe; // Outputs "Here is a string that we will use for some examples"

// Simple example
$truncateMe = GDM\Framework\Types\String::create($string);
echo $truncateMe->neatTruncate(10); // Outputs "Here is...";

// Most methods are Chainable
$chainMe = GDM\Framework\Types\String::create($string);
echo $chainMe->leftTrim("H")->upperCaseWords()->replace('/String/', 'Sentence'); // Outputs "Ere Is A Sentence That We Will Use For Some Examples "
```

###Date

```PHP
$date = "01/01/2014 14:35";

// Set global defaults
\GDM\Framework\Types\Settings\DefaultDateSettings::$defaultTimeZone = 'Pacific/Auckland';
\GDM\Framework\Types\Settings\DefaultDateSettings::$defaultDateFromat = 'd/m/Y';
\GDM\Framework\Types\Settings\DefaultDateSettings::$defaultDateTimeFromat = 'd/m/Y H:i';
\GDM\Framework\Types\Settings\DefaultDateSettings::$defaultLongDateFormat = 'l jS \of F Y';
\GDM\Framework\Types\Settings\DefaultDateSettings::$defaultLongDateTimeFormat = 'l jS \of F Y h:i:s A';
// Or pass a settings object in
$settings = GDM\Framework\Types\Settings\DateSettings::create('Pacific/Auckland', 'd/m/Y', 'd/m/Y H:i', 'l jS \of F Y', 'l jS \of F Y', 'l jS \of F Y h:i:s A');

// Implements __toString, so object instances can be used as plain old strings
$echoMe = GDM\Framework\Types\Date::create($date, $settings);
echo $echoMe; // Outputs "01/01/2014 14:35"

// Long fromat
echo $echoMe->toLongDateTime(); // Outputs "Wednesday 1st of January 2014";

// Helpers
echo GDM\Framework\Types\Date::getDaysDiff("01/01/2014", "21/01/2014");  // Outputs "20"

foreach (GDM\Framework\Types\Date::daysOfTheWeek() as $day) {
            echo $day . "<br/>";
}// Outputs Monday
//      Tuesday
//      Wednesday
//      Thursday
//      Friday
//      Saturday
//      Sunday
```

###URL

```PHP
$urlString = "http://www.example.com/path/to/file?foo=bar";

// Implements __toString, so object instances can be used as plain old strings
$echoMe = GDM\Framework\Types\Url::create($urlString);
echo $echoMe; // Outputs "http://www.example.com/path/to/file?bar=foo"

// Url parameters are readable as propertie
echo $echoMe->foo; // Outputs "bar";

// You can also set properties
$echoMe->bar = "some-new-value";
echo $echoMe; // Outputs "http://www.example.com/path/to/file?foo=bar&bar=some-new-value";

// Path segements are array accessable
echo $echoMe[1];  // Outputs "to"

$echoMe[3] = "somewhere";
echo $echoMe; // Outputs "http://www.example.com/path/to/file/somewhere?foo=bar&bar=some-new-value";
```

##To do
* .... write a better readme?
* Add more types