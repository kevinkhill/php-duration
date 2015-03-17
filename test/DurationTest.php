<?php

use Khill\Duration\Duration;

class DurationTest extends PHPUnit_Framework_TestCase {

    public function setUp()
    {
        parent::setUp();

        $this->d = new Duration;
    }

    public function secondsSampleData()
    {
        return [
            [ 1, '1 s'],
            [ 1, '1 sec'],
            [ 3, '3S'],
            [ 7, '7 S'],
            [51, '51seconds'],
            [ 4, '4 Sec.'],
            [15, '15 SEcONDs']
        ];
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
        return [
            [ 1, '1 m'],
            [ 4, '4 min'],
            [ 6, '6M'],
            [14, '14 Ms'],
            [31, '31 minutes'],
            [ 9, '9Min.'],
            [11, '11 MINUTE']
        ];
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
        return [
            [ 1, '1 h'],
            [ 1, '1 hr'],
            [ 1, '1H'],
            [24, '24 Hrs'],
            [ 3, '3hours'],
            [ 6, '6HoUr'],
            [14, '14 HOURs']
        ];
    }

    /**
     * @dataProvider hoursSampleData
     */
    public function testGettingValueFromHourSuffixes($intVal, $hrStr)
    {
        $this->d->parse($hrStr);
        $this->assertEquals($intVal, $this->d->hours);
    }

    public function testConvertingSecondsToFormattedString()
    {
        $this->assertEquals('4',       $this->d->formatted(4));
        $this->assertEquals('42',      $this->d->formatted(42));
        $this->assertEquals('1:02',    $this->d->formatted(62));
        $this->assertEquals('1:42',    $this->d->formatted(102));
        $this->assertEquals('10:47',   $this->d->formatted(647));
        $this->assertEquals('1:00:00', $this->d->formatted(3600));
        $this->assertEquals('1:00:01', $this->d->formatted(3601));
        $this->assertEquals('1:00:11', $this->d->formatted(3611));
        $this->assertEquals('1:01:00', $this->d->formatted(3660));
        $this->assertEquals('1:01:14', $this->d->formatted(3674));
        $this->assertEquals('1:04:25', $this->d->formatted(3865));
    }

    public function testConvertingFormattedStringsToSeconds()
    {
        $this->assertEquals(4,    $this->d->toSeconds('4'));
        $this->assertEquals(42,   $this->d->toSeconds('42'));
        $this->assertEquals(62,   $this->d->toSeconds('1:02'));
        $this->assertEquals(102,  $this->d->toSeconds('1:42'));
        $this->assertEquals(647,  $this->d->toSeconds('10:47'));
        $this->assertEquals(3600, $this->d->toSeconds('1:00:00'));
        $this->assertEquals(3601, $this->d->toSeconds('1:00:01'));
        $this->assertEquals(3611, $this->d->toSeconds('1:00:11'));
        $this->assertEquals(3660, $this->d->toSeconds('1:01:00'));
        $this->assertEquals(3674, $this->d->toSeconds('1:01:14'));
        $this->assertEquals(3865, $this->d->toSeconds('1:04:25'));
    }

    public function testConvertSecondsToHumanizedString()
    {
        $this->assertEquals('4s',       $this->d->humanize(4));
        $this->assertEquals('42s',      $this->d->humanize(42));
        $this->assertEquals('1m 2s',    $this->d->humanize(62));
        $this->assertEquals('1m 42s',   $this->d->humanize(102));
        $this->assertEquals('10m 47s',  $this->d->humanize(647));
        $this->assertEquals('1h',       $this->d->humanize(3600));
        $this->assertEquals('1h 5s',    $this->d->humanize(3605));
        $this->assertEquals('1h 1m',    $this->d->humanize(3660));
        $this->assertEquals('1h 1m 5s', $this->d->humanize(3665));
    }

    /**
     * @depends testGettingValueFromSecondSuffixes
     * @depends testGettingValueFromMinuteSuffixes
     * @depends testGettingValueFromHourSuffixes
     */
    public function testConvertHumanizedStringToSeconds()
    {
        $this->assertEquals(4,    $this->d->toSeconds('4s'));
        $this->assertEquals(42,   $this->d->toSeconds('42s'));
        $this->assertEquals(72,   $this->d->toSeconds('1m 12s'));
        $this->assertEquals(102,  $this->d->toSeconds('1m 42s'));
        $this->assertEquals(647,  $this->d->toSeconds('10m 47s'));
        $this->assertEquals(3600, $this->d->toSeconds('1h'));
        $this->assertEquals(3605, $this->d->toSeconds('1h 5s'));
        $this->assertEquals(3660, $this->d->toSeconds('1h 1m'));
        $this->assertEquals(3665, $this->d->toSeconds('1h 1m 5s'));
    }

}
