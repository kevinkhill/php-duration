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
            array(1, '1 s'),
            array(1, '1 sec'),
            array(3, '3S'),
            array(7, '7 S'),
            array(51, '51seconds'),
            array(4, '4 Sec.'),
            array(15, '15 SEcONDs'),
            array(1, '1.0 s'),
            array(1.5689, '1.5689 S'),
            array(1.00342, '1.00342 S'),
        );
    }

    /**
     * @dataProvider secondsSampleData
     */
    public function testGettingValueFromSecondSuffixes($expectedSeconds, $secStr)
    {
        $this->d->parse($secStr);
        $this->assertEquals($expectedSeconds, $this->d->seconds);
    }

    public function minutesSampleData()
    {
        return array(
            array(1, '1 m'),
            array(4, '4 min'),
            array(6, '6M'),
            array(14, '14 Ms'),
            array(31, '31 minutes'),
            array(9, '9Min.'),
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
            array(1, '1 h'),
            array(1, '1 hr'),
            array(1, '1H'),
            array(24, '24 Hrs'),
            array(3, '3hours'),
            array(6, '6HoUr'),
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
            array(1, '1 d'),
            array(1, '1 D'),
            array(1, '1D'),
            array(24, '24 ds'),
            array(3, '3days'),
            array(6, '6DaY'),
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
        $this->assertEquals('4', $this->d->formatted(4));
        $this->assertEquals('9', $this->d->formatted(9));
        $this->assertEquals('42', $this->d->formatted(42));
        $this->assertEquals('1:02', $this->d->formatted(62));
        $this->assertEquals('1:09', $this->d->formatted(69));
        $this->assertEquals('1:42', $this->d->formatted(102));
        $this->assertEquals('10:47', $this->d->formatted(647));
        $this->assertEquals('1:00:00', $this->d->formatted(3600));
        $this->assertEquals('1:00:01', $this->d->formatted(3601));
        $this->assertEquals('1:00:11', $this->d->formatted(3611));
        $this->assertEquals('1:01:00', $this->d->formatted(3660));
        $this->assertEquals('1:01:14', $this->d->formatted(3674));
        $this->assertEquals('1:04:25', $this->d->formatted(3865));
        $this->assertEquals('1:09:09', $this->d->formatted(4149));

        // microseconds
        $this->assertEquals('4.987', $this->d->formatted(4.987));
        $this->assertEquals('9.123', $this->d->formatted(9.123));
        $this->assertEquals('42.672', $this->d->formatted(42.672));
        $this->assertEquals('1:02.23', $this->d->formatted(62.23));
        $this->assertEquals('1:09.9', $this->d->formatted(69.9));
        $this->assertEquals('1:42.62394', $this->d->formatted(102.62394));
        $this->assertEquals('10:47.5', $this->d->formatted(647.5));
        $this->assertEquals('1:00:00.954', $this->d->formatted(3600.954));
        $this->assertEquals('1:00:01.5123', $this->d->formatted(3601.5123));
        $this->assertEquals('1:00:11.0412368456', $this->d->formatted(3611.0412368456));
        $this->assertEquals('1:01:00.56945', $this->d->formatted(3660.56945));
        $this->assertEquals('1:01:14.3', $this->d->formatted(3674.3));
        $this->assertEquals('1:04:25.0005598', $this->d->formatted(3865.0005598));
        $this->assertEquals('1:09:09.123', $this->d->formatted(4149.123));
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

        // microseconds
        $this->assertEquals('0:00:04.542', $this->d->formatted(4.542, true));
        $this->assertEquals('1:09:09.0987', $this->d->formatted(4149.0987, true));
    }

    public function testConvertingFormattedStringsToSeconds()
    {
        $this->assertEquals(4, $this->d->toSeconds('4'));
        $this->assertEquals(9, $this->d->toSeconds('9'));
        $this->assertEquals(42, $this->d->toSeconds('42'));
        $this->assertEquals(62, $this->d->toSeconds('1:02'));
        $this->assertEquals(69, $this->d->toSeconds('1:09'));
        $this->assertEquals(102, $this->d->toSeconds('1:42'));
        $this->assertEquals(647, $this->d->toSeconds('10:47'));
        $this->assertEquals(3600, $this->d->toSeconds('1:00:00'));
        $this->assertEquals(3601, $this->d->toSeconds('1:00:01'));
        $this->assertEquals(3611, $this->d->toSeconds('1:00:11'));
        $this->assertEquals(3660, $this->d->toSeconds('1:01:00'));
        $this->assertEquals(3674, $this->d->toSeconds('1:01:14'));
        $this->assertEquals(3865, $this->d->toSeconds('1:04:25'));
        $this->assertEquals(4149, $this->d->toSeconds('1:09:09'));

        // microseconds
        $this->assertEquals(4.6, $this->d->toSeconds('4.6'));
        $this->assertEquals(9.5, $this->d->toSeconds('9.5'));
        $this->assertEquals(42.1, $this->d->toSeconds('42.1'));
        $this->assertEquals(62.96, $this->d->toSeconds('1:02.96'));
        $this->assertEquals(69.23, $this->d->toSeconds('1:09.23'));
        $this->assertEquals(102.55, $this->d->toSeconds('1:42.55'));
        $this->assertEquals(647.999, $this->d->toSeconds('10:47.999'));
        $this->assertEquals(3600.9987, $this->d->toSeconds('1:00:00.9987'));
        $this->assertEquals(3601.000111, $this->d->toSeconds('1:00:01.000111'));
        $this->assertEquals(3611.0999, $this->d->toSeconds('1:00:11.0999'));
        $this->assertEquals(3660.500001, $this->d->toSeconds('1:01:00.500001'));
        $this->assertEquals(3674.00001, $this->d->toSeconds('1:01:14.00001'));
        $this->assertEquals(3865.499999, $this->d->toSeconds('1:04:25.499999'));
        $this->assertEquals(4149.499999, $this->d->toSeconds('1:09:09.499999'));

        // precision
        $this->assertEquals(5, $this->d->toSeconds('4.6', 0));
        $this->assertEquals(10, $this->d->toSeconds('9.5', 0));
        $this->assertEquals(42.1, $this->d->toSeconds('42.1', 1));
        $this->assertEquals(63, $this->d->toSeconds('1:02.96', 1));
        $this->assertEquals(69.23, $this->d->toSeconds('1:09.23'));
        $this->assertEquals(102.55, $this->d->toSeconds('1:42.55', 2));
        $this->assertEquals(648, $this->d->toSeconds('10:47.999', 2));
        $this->assertEquals(3601, $this->d->toSeconds('1:00:00.9987', 2));
        $this->assertEquals(3601, $this->d->toSeconds('1:00:01.000111', 3));
        $this->assertEquals(3611.0999, $this->d->toSeconds('1:00:11.0999', 4));
        $this->assertEquals(3660.5, $this->d->toSeconds('1:01:00.500001', 2));
        $this->assertEquals(3674, $this->d->toSeconds('1:01:14.00001', 2));
        $this->assertEquals(3865.5, $this->d->toSeconds('1:04:25.499999', 3));
        $this->assertEquals(4149.499997, $this->d->toSeconds('1:09:09.4999971', 6));
    }

    public function testConvertingFormattedStringsToMinutes()
    {
        $this->assertEquals(4 / 60, $this->d->toMinutes('4'));
        $this->assertEquals(9 / 60, $this->d->toMinutes('9'));
        $this->assertEquals(42 / 60, $this->d->toMinutes('42'));
        $this->assertEquals(62 / 60, $this->d->toMinutes('1:02'));
        $this->assertEquals(69 / 60, $this->d->toMinutes('1:09'));
        $this->assertEquals(102 / 60, $this->d->toMinutes('1:42'));
        $this->assertEquals(647 / 60, $this->d->toMinutes('10:47'));
        $this->assertEquals(3600 / 60, $this->d->toMinutes('1:00:00'));
        $this->assertEquals(3601 / 60, $this->d->toMinutes('1:00:01'));
        $this->assertEquals(3611 / 60, $this->d->toMinutes('1:00:11'));
        $this->assertEquals(3660 / 60, $this->d->toMinutes('1:01:00'));
        $this->assertEquals(3674 / 60, $this->d->toMinutes('1:01:14'));
        $this->assertEquals(3865 / 60, $this->d->toMinutes('1:04:25'));
        $this->assertEquals(4149 / 60, $this->d->toMinutes('1:09:09'));

        // to integer - BC
        $this->assertEquals(0, $this->d->toMinutes('4', true));
        $this->assertEquals(0, $this->d->toMinutes('9', true));
        $this->assertEquals(1, $this->d->toMinutes('42', true));
        $this->assertEquals(1, $this->d->toMinutes('1:02', true));
        $this->assertEquals(1, $this->d->toMinutes('1:09', true));
        $this->assertEquals(2, $this->d->toMinutes('1:42', true));
        $this->assertEquals(11, $this->d->toMinutes('10:47', true));
        $this->assertEquals(60, $this->d->toMinutes('1:00:00', true));
        $this->assertEquals(60, $this->d->toMinutes('1:00:01', true));
        $this->assertEquals(60, $this->d->toMinutes('1:00:11', true));
        $this->assertEquals(61, $this->d->toMinutes('1:01:00', true));
        $this->assertEquals(61, $this->d->toMinutes('1:01:14', true));
        $this->assertEquals(64, $this->d->toMinutes('1:04:25', true));
        $this->assertEquals(65, $this->d->toMinutes('1:04:55', true));
        $this->assertEquals(69, $this->d->toMinutes('1:09:09', true));

        // precision
        $this->assertEquals(0, $this->d->toMinutes('4', 0));
        $this->assertEquals(0, $this->d->toMinutes('9', 0));
        $this->assertEquals(1, $this->d->toMinutes('42', 0));
        $this->assertEquals(1, $this->d->toMinutes('1:02', 0));
        $this->assertEquals(1, $this->d->toMinutes('1:09', 0));
        $this->assertEquals(2, $this->d->toMinutes('1:42', 0));
        $this->assertEquals(11, $this->d->toMinutes('10:47', 0));
        $this->assertEquals(60, $this->d->toMinutes('1:00:00', 0));
        $this->assertEquals(60, $this->d->toMinutes('1:00:01', 0));
        $this->assertEquals(60, $this->d->toMinutes('1:00:11', 0));
        $this->assertEquals(61, $this->d->toMinutes('1:01:00', 0));
        $this->assertEquals(61, $this->d->toMinutes('1:01:14', 0));
        $this->assertEquals(64, $this->d->toMinutes('1:04:25', 0));
        $this->assertEquals(65, $this->d->toMinutes('1:04:55', 0));
        $this->assertEquals(69, $this->d->toMinutes('1:09:09', 0));

        $this->assertEquals(0.1, $this->d->toMinutes('4', 1));
        $this->assertEquals(0.15, $this->d->toMinutes('9', 2));
        $this->assertEquals(0.7, $this->d->toMinutes('42', 3));
        $this->assertEquals(1, $this->d->toMinutes('1:02', 1));
        $this->assertEquals(1.15, $this->d->toMinutes('1:09', 2));
        $this->assertEquals(1.7, $this->d->toMinutes('1:42', 3));
        $this->assertEquals(10.8, $this->d->toMinutes('10:47', 1));
        $this->assertEquals(60, $this->d->toMinutes('1:00:00', 2));
        $this->assertEquals(60.017, $this->d->toMinutes('1:00:01', 3));
        $this->assertEquals(60.2, $this->d->toMinutes('1:00:11', 1));
        $this->assertEquals(61, $this->d->toMinutes('1:01:00', 2));
        $this->assertEquals(61.233, $this->d->toMinutes('1:01:14', 3));
        $this->assertEquals(64.42, $this->d->toMinutes('1:04:25', 2));
        $this->assertEquals(64.92, $this->d->toMinutes('1:04:55', 2));
        $this->assertEquals(69.15, $this->d->toMinutes('1:09:09', 2));
    }

    public function testConvertSecondsToHumanizedString()
    {
        $this->assertEquals('4s', $this->d->humanize(4));
        $this->assertEquals('42s', $this->d->humanize(42));
        $this->assertEquals('1m 2s', $this->d->humanize(62));
        $this->assertEquals('1m 42s', $this->d->humanize(102));
        $this->assertEquals('10m 47s', $this->d->humanize(647));
        $this->assertEquals('1h', $this->d->humanize(3600));
        $this->assertEquals('1h 5s', $this->d->humanize(3605));
        $this->assertEquals('1h 1m', $this->d->humanize(3660));
        $this->assertEquals('1h 1m 5s', $this->d->humanize(3665));
        $this->assertEquals('3d', $this->d->humanize(259200));
        $this->assertEquals('2d 11h 30m', $this->d->humanize(214200));

        $this->assertEquals('4.0596s', $this->d->humanize(4.0596));
        $this->assertEquals('2d 11h 30m 0.9542s', $this->d->humanize(214200.9542));

    }

    /**
     * @depends testGettingValueFromSecondSuffixes
     * @depends testGettingValueFromMinuteSuffixes
     * @depends testGettingValueFromHourSuffixes
     */
    public function testConvertHumanizedStringToSeconds()
    {
        $this->assertEquals(4, $this->d->toSeconds('4s'));
        $this->assertEquals(42, $this->d->toSeconds('42s'));
        $this->assertEquals(72, $this->d->toSeconds('1m 12s'));
        $this->assertEquals(102, $this->d->toSeconds('1m 42s'));
        $this->assertEquals(647, $this->d->toSeconds('10m 47s'));
        $this->assertEquals(3600, $this->d->toSeconds('1h'));
        $this->assertEquals(3605, $this->d->toSeconds('1h 5s'));
        $this->assertEquals(3660, $this->d->toSeconds('1h 1m'));
        $this->assertEquals(3665, $this->d->toSeconds('1h 1m 5s'));
        $this->assertEquals(86400, $this->d->toSeconds('1d'));
        $this->assertEquals(214200, $this->d->toSeconds('2d 11h 30m'));
        $this->assertEquals(214214, $this->d->toSeconds('2d 11h 30m 14s'));
    }

    public function testConvertHumanizedStringToSeconds7HourDay()
    {
        $d = new Duration(null, 7);
        $this->assertEquals(25200, $d->toSeconds('1d'));
        $this->assertEquals(91800, $d->toSeconds('2d 11h 30m'));
    }

}
