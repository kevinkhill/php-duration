<?php

use Khill\Duration\Duration;

class DurationTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->d = new Duration;
    }

    public function secondsSampleData()
    {
        return array(
            array( 1, '1 s'),
            array( 1, '1 sec'),
            array( 3, '3S'),
            array( 7, '7 S'),
            array(51, '51seconds'),
            array( 4, '4 Sec.'),
            array(15, '15 SEcONDs')
        );
    }

    /**
     * @dataProvider secondsSampleData
     */
    public function testGettingValueFromSecondSuffixes($intVal, $secStr)
    {
        $this->d->parse($secStr);
        $this->assertEquals($intVal, $this->d->seconds);
    }

    public function minutesSampleData()
    {
        return array(
            array( 1, '1 m'),
            array( 4, '4 min'),
            array( 6, '6M'),
            array(14, '14 Ms'),
            array(31, '31 minutes'),
            array( 9, '9Min.'),
            array(11, '11 MINUTE')
        );
    }

    /**
     * @dataProvider minutesSampleData
     */
    public function testGettingValueFromMinuteSuffixes($intVal, $minStr)
    {
        $this->d->parse($minStr);
        $this->assertEquals($intVal, $this->d->minutes);
    }

    public function hoursSampleData()
    {
        return array(
            array( 1, '1 h'),
            array( 1, '1 hr'),
            array( 1, '1H'),
            array(24, '24 Hrs'),
            array( 3, '3hours'),
            array( 6, '6HoUr'),
            array(14, '14 HOURs'),
            array(36, '36h')
        );
    }

    /**
     * @dataProvider hoursSampleData
     */
    public function testGettingValueFromHourSuffixes($intVal, $hrStr)
    {
        $this->d->parse($hrStr);
        $this->assertEquals($intVal, $this->d->hours);
    }

    public function daysSampleData()
    {
        return array(
            array( 1, '1 d'),
            array( 1, '1 D'),
            array( 1, '1D'),
            array(24, '24 ds'),
            array( 3, '3days'),
            array( 6, '6DaY'),
            array(14, '14 DAYs')
        );
    }

    /**
     * @dataProvider daysSampleData
     */
    public function testGettingValueFromDaySuffixes($intVal, $dayStr)
    {
        $this->d->parse($dayStr);
        $this->assertEquals($intVal, $this->d->days);
    }


    public function testConvertingSecondsToFormattedString()
    {
        $this->assertEquals('4',       $this->d->formatted(4));
        $this->assertEquals('9',       $this->d->formatted(9));
        $this->assertEquals('42',      $this->d->formatted(42));
        $this->assertEquals('1:02',    $this->d->formatted(62));
        $this->assertEquals('1:09',    $this->d->formatted(69));
        $this->assertEquals('1:42',    $this->d->formatted(102));
        $this->assertEquals('10:47',   $this->d->formatted(647));
        $this->assertEquals('1:00:00', $this->d->formatted(3600));
        $this->assertEquals('1:00:01', $this->d->formatted(3601));
        $this->assertEquals('1:00:11', $this->d->formatted(3611));
        $this->assertEquals('1:01:00', $this->d->formatted(3660));
        $this->assertEquals('1:01:14', $this->d->formatted(3674));
        $this->assertEquals('1:04:25', $this->d->formatted(3865));
        $this->assertEquals('1:09:09', $this->d->formatted(4149));
    }

    public function testConvertingSecondsToFormattedStringZeroFilled()
    {
        $this->assertEquals('0:00:04', $this->d->formatted(4, true));
        $this->assertEquals('0:00:09', $this->d->formatted(9, true));
        $this->assertEquals('0:00:42', $this->d->formatted(42, true));
        $this->assertEquals('0:01:02', $this->d->formatted(62, true));
        $this->assertEquals('0:01:09', $this->d->formatted(69, true));
        $this->assertEquals('0:01:42', $this->d->formatted(102, true));
        $this->assertEquals('0:10:47', $this->d->formatted(647, true));
        $this->assertEquals('1:00:00', $this->d->formatted(3600, true));
        $this->assertEquals('1:00:01', $this->d->formatted(3601, true));
        $this->assertEquals('1:00:11', $this->d->formatted(3611, true));
        $this->assertEquals('1:01:00', $this->d->formatted(3660, true));
        $this->assertEquals('1:01:14', $this->d->formatted(3674, true));
        $this->assertEquals('1:04:25', $this->d->formatted(3865, true));
        $this->assertEquals('1:09:09', $this->d->formatted(4149, true));
    }

    public function testConvertingFormattedStringsToSeconds()
    {
        $this->assertEquals(4,    $this->d->toSeconds('4'));
        $this->assertEquals(9,    $this->d->toSeconds('9'));
        $this->assertEquals(42,   $this->d->toSeconds('42'));
        $this->assertEquals(62,   $this->d->toSeconds('1:02'));
        $this->assertEquals(69,   $this->d->toSeconds('1:09'));
        $this->assertEquals(102,  $this->d->toSeconds('1:42'));
        $this->assertEquals(647,  $this->d->toSeconds('10:47'));
        $this->assertEquals(3600, $this->d->toSeconds('1:00:00'));
        $this->assertEquals(3601, $this->d->toSeconds('1:00:01'));
        $this->assertEquals(3611, $this->d->toSeconds('1:00:11'));
        $this->assertEquals(3660, $this->d->toSeconds('1:01:00'));
        $this->assertEquals(3674, $this->d->toSeconds('1:01:14'));
        $this->assertEquals(3865, $this->d->toSeconds('1:04:25'));
        $this->assertEquals(4149, $this->d->toSeconds('1:09:09'));
    }

    public function testConvertingFormattedStringsToMinutes()
    {
        $this->assertEquals(4/60,    $this->d->toMinutes('4'));
        $this->assertEquals(9/60,    $this->d->toMinutes('9'));
        $this->assertEquals(42/60,   $this->d->toMinutes('42'));
        $this->assertEquals(62/60,   $this->d->toMinutes('1:02'));
        $this->assertEquals(69/60,   $this->d->toMinutes('1:09'));
        $this->assertEquals(102/60,  $this->d->toMinutes('1:42'));
        $this->assertEquals(647/60,  $this->d->toMinutes('10:47'));
        $this->assertEquals(3600/60, $this->d->toMinutes('1:00:00'));
        $this->assertEquals(3601/60, $this->d->toMinutes('1:00:01'));
        $this->assertEquals(3611/60, $this->d->toMinutes('1:00:11'));
        $this->assertEquals(3660/60, $this->d->toMinutes('1:01:00'));
        $this->assertEquals(3674/60, $this->d->toMinutes('1:01:14'));
        $this->assertEquals(3865/60, $this->d->toMinutes('1:04:25'));
        $this->assertEquals(4149/60, $this->d->toMinutes('1:09:09'));

        $this->assertEquals(0,  $this->d->toMinutes('4', true));
        $this->assertEquals(0,  $this->d->toMinutes('9', true));
        $this->assertEquals(1,  $this->d->toMinutes('42', true));
        $this->assertEquals(1,  $this->d->toMinutes('1:02', true));
        $this->assertEquals(1,  $this->d->toMinutes('1:09', true));
        $this->assertEquals(2,  $this->d->toMinutes('1:42', true));
        $this->assertEquals(11, $this->d->toMinutes('10:47', true));
        $this->assertEquals(60, $this->d->toMinutes('1:00:00', true));
        $this->assertEquals(60, $this->d->toMinutes('1:00:01', true));
        $this->assertEquals(60, $this->d->toMinutes('1:00:11', true));
        $this->assertEquals(61, $this->d->toMinutes('1:01:00', true));
        $this->assertEquals(61, $this->d->toMinutes('1:01:14', true));
        $this->assertEquals(64, $this->d->toMinutes('1:04:25', true));
        $this->assertEquals(65, $this->d->toMinutes('1:04:55', true));
        $this->assertEquals(69, $this->d->toMinutes('1:09:09', true));
    }

    public function testConvertSecondsToHumanizedString()
    {
        $this->assertEquals('4s',         $this->d->humanize(4));
        $this->assertEquals('42s',        $this->d->humanize(42));
        $this->assertEquals('1m 2s',      $this->d->humanize(62));
        $this->assertEquals('1m 42s',     $this->d->humanize(102));
        $this->assertEquals('10m 47s',    $this->d->humanize(647));
        $this->assertEquals('1h',         $this->d->humanize(3600));
        $this->assertEquals('1h 5s',      $this->d->humanize(3605));
        $this->assertEquals('1h 1m',      $this->d->humanize(3660));
        $this->assertEquals('1h 1m 5s',   $this->d->humanize(3665));
        $this->assertEquals('3d',         $this->d->humanize(259200));
        $this->assertEquals('2d 11h 30m', $this->d->humanize(214200));

    }

    /**
     * @depends testGettingValueFromSecondSuffixes
     * @depends testGettingValueFromMinuteSuffixes
     * @depends testGettingValueFromHourSuffixes
     */
    public function testConvertHumanizedStringToSeconds()
    {
        $this->assertEquals(4,      $this->d->toSeconds('4s'));
        $this->assertEquals(42,     $this->d->toSeconds('42s'));
        $this->assertEquals(72,     $this->d->toSeconds('1m 12s'));
        $this->assertEquals(102,    $this->d->toSeconds('1m 42s'));
        $this->assertEquals(647,    $this->d->toSeconds('10m 47s'));
        $this->assertEquals(3600,   $this->d->toSeconds('1h'));
        $this->assertEquals(3605,   $this->d->toSeconds('1h 5s'));
        $this->assertEquals(3660,   $this->d->toSeconds('1h 1m'));
        $this->assertEquals(3665,   $this->d->toSeconds('1h 1m 5s'));
        $this->assertEquals(86400,  $this->d->toSeconds('1d'));
        $this->assertEquals(214200, $this->d->toSeconds('2d 11h 30m'));
        $this->assertEquals(214214, $this->d->toSeconds('2d 11h 30m 14s'));
    }

    public function testConvertHumanizedStringToSeconds7HourDay()
    {
        $d = new Duration(null, 7);
        $this->assertEquals(25200,  $d->toSeconds('1d'));
        $this->assertEquals(91800, $d->toSeconds('2d 11h 30m'));
    }

}
