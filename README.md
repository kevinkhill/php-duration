# PHP-Duration
#### Convert durations between colon formatted time, human-readable time and seconds
[![Total Downloads](https://img.shields.io/packagist/dt/khill/php-duration.svg?style=plastic)](https://packagist.org/packages/khill/php-duration)
[![License](https://img.shields.io/packagist/l/khill/php-duration.svg?style=plastic)](http://opensource.org/licenses/MIT)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%205.3-8892BF.svg?style=plastic)](https://php.net/)
[![PayPal](https://img.shields.io/badge/paypal-donate-yellow.svg?style=plastic)](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=FLP6MYY3PYSFQ)

[![Current Release](https://img.shields.io/github/release/kevinkhill/php-duration.svg?style=plastic)](https://github.com/kevinkhill/php-duration/releases)
[![Build Status](https://img.shields.io/travis/kevinkhill/php-duration/master.svg?style=plastic)](https://travis-ci.org/kevinkhill/php-duration)
[![Coverage Status](https://img.shields.io/coveralls/kevinkhill/php-duration/master.svg?style=plastic)](https://coveralls.io/r/kevinkhill/php-duration?branch=master)

This package was created with a very specific goal in mind, to enable an easy way for users to input how long something took, as a duration of time.

The library can accept either in colon separated format, like 2:43 for 2 minutes and 43 seconds
OR
written as human readable or abbreviated time, such as 6m21s for 6 minutes and 21 seconds.

Both can be converted into seconds and minutes with precision for easy storage into a database.

Seconds, colon separated, abbreviated, all three can be parsed and interchanged.
 - supports hours, minutes, and seconds (with microseconds)
 - humanized input supports any form of the words "hour", "minute", "seconds"
   - Example, you could input 1h4m2s or 4 Hr. 32 Min.


# Install
```bash
composer require khill/php-duration:~1.0
```


# Usage
```php
use Khill\Duration\Duration;


$duration = new Duration('7:31');

echo $duration->humanize();  // 7m 31s
echo $duration->formatted(); // 7:31
echo $duration->toSeconds(); // 451
echo $duration->toMinutes(); // 7.5166
echo $duration->toMinutes(null, 0); // 8
echo $duration->toMinutes(null, 2); // 7.52
```

```php
$duration = new Duration('1h 2m 5s');

echo $duration->humanize();  // 1h 2m 5s
echo $duration->formatted(); // 1:02:05
echo $duration->toSeconds(); // 3725
echo $duration->toMinutes(); // 62.0833
echo $duration->toMinutes(null, 0); // 62
```

```php
// Configured for 6 hours per day
$duration = new Duration('1.5d 1.5h 2m 5s', 6);

echo $duration->humanize();  // 1d 4h 32m 5s
echo $duration->formatted(); // 10:32:05
echo $duration->toSeconds(); // 37925
echo $duration->toMinutes(); // 632.083333333
echo $duration->toMinutes(null, 0); // 632
```

```php
$duration = new Duration('4293');

echo $duration->humanize();  // 1h 11m 33s
echo $duration->formatted(); // 1:11:33
echo $duration->toSeconds(); // 4293
echo $duration->toMinutes(); // 71.55
echo $duration->toMinutes(null, 0); // 72
```

# Note
You do not have to create a new object for each conversion, you can also pass any of the three forms into any of the methods to get the immediate output.
```php
$duration = new Duration;

echo $duration->humanize('1h 2m 5s');  // 1h 2m 5s
echo $duration->formatted('1h 2m 5s'); // 1:02:05
echo $duration->toSeconds('1h 2m 5s'); // 3725
echo $duration->toMinutes('1h 2m 5s'); // 62.0833
echo $duration->toMinutes('1h 2m 5s', true); // 62
```
