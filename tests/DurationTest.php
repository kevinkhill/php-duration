<?php

declare(strict_types=1);

//declare(strict_types=1);

use Khill\Duration\Duration;

use function PHPUnit\Framework\assertEquals;

beforeEach(fn () => $this->duration = new Duration());

dataset('seconds_sample_data', [
    [false, null],
    [false, ' '],
    [0, '0 s'],
    [1, '1 s'],
    [1, '1 sec'],
    [3, '3S'],
    [7, '7 S'],
    [51, '51seconds'],
    [4, '4 Sec.'],
    [15, '15 SEcONDs'],
    [1, '1.0 s'],
    [1.5689, '1.5689 S'],
    [1.00342, '1.00342 S'],
]);

dataset('minutes_sample_data', [
    [0, '0m'],
    [1, '1 m'],
    [4, '4 min'],
    [6, '6M'],
    [14, '14 Ms'],
    [31, '31 minutes'],
    [9, '9Min.'],
    [11, '11 MINUTE'],
]);

dataset('hours_sample_data', [
    [0, '0h'],
    [1, '1 h'],
    [1, '1 hr'],
    [1, '1H'],
    [24, '24 Hrs'],
    [3, '3hours'],
    [6, '6HoUr'],
    [14, '14 HOURs'],
    [36, '36h'],
]);

dataset('days_sample_data', [
    [0, '0d'],
    [1, '1 d'],
    [1, '1 D'],
    [1, '1D'],
    [24, '24 ds'],
    [3, '3days'],
    [6, '6DaY'],
    [14, '14 DAYs'],
]);

test('Getting Value From Second Suffixes', function ($expectedSeconds, $secStr) {
    $this->duration->parse($secStr);
    assertEquals($expectedSeconds, $this->duration->seconds);
})
    ->with('seconds_sample_data');

test('Getting Value From Minute Suffixes', function ($intVal, $minStr) {
    $this->duration->parse($minStr);
    assertEquals($intVal, $this->duration->minutes);
})
    ->with('minutes_sample_data');

test('Getting Value From Hour Suffixes', function ($intVal, $hrStr) {
    $this->duration->parse($hrStr);
    assertEquals($intVal, $this->duration->hours);
})
    ->with('hours_sample_data');

test('Getting Value From Day Suffixes', function ($intVal, $dayStr) {
    $this->duration->parse($dayStr);
    assertEquals($intVal, $this->duration->days);
})
    ->with('days_sample_data');

test('Converting Seconds To Formatted String', function () {
    assertEquals('0', $this->duration->formatted(0));
    assertEquals('4', $this->duration->formatted(4));
    assertEquals('9', $this->duration->formatted(9));
    assertEquals('42', $this->duration->formatted(42));
    assertEquals('1:02', $this->duration->formatted(62));
    assertEquals('1:09', $this->duration->formatted(69));
    assertEquals('1:42', $this->duration->formatted(102));
    assertEquals('10:47', $this->duration->formatted(647));
    assertEquals('1:00:00', $this->duration->formatted(3600));
    assertEquals('1:00:01', $this->duration->formatted(3601));
    assertEquals('1:00:11', $this->duration->formatted(3611));
    assertEquals('1:01:00', $this->duration->formatted(3660));
    assertEquals('1:01:14', $this->duration->formatted(3674));
    assertEquals('1:04:25', $this->duration->formatted(3865));
    assertEquals('1:09:09', $this->duration->formatted(4149));

    // microseconds
    assertEquals('0', $this->duration->formatted(0.0));
    assertEquals('4.987', $this->duration->formatted(4.987));
    assertEquals('9.123', $this->duration->formatted(9.123));
    assertEquals('42.672', $this->duration->formatted(42.672));
    assertEquals('1:02.23', $this->duration->formatted(62.23));
    assertEquals('1:09.9', $this->duration->formatted(69.9));
    assertEquals('1:42.62394', $this->duration->formatted(102.62394));
    assertEquals('10:47.5', $this->duration->formatted(647.5));
    assertEquals('1:00:00.954', $this->duration->formatted(3600.954));
    assertEquals('1:00:01.5123', $this->duration->formatted(3601.5123));
    assertEquals('1:00:11.0412368456', $this->duration->formatted(3611.0412368456));
    assertEquals('1:01:00.56945', $this->duration->formatted(3660.56945));
    assertEquals('1:01:14.3', $this->duration->formatted(3674.3));
    assertEquals('1:04:25.0005598', $this->duration->formatted(3865.0005598));
    assertEquals('1:09:09.123', $this->duration->formatted(4149.123));
});

test('Converting Seconds To Formatted String Zero Filled', function () {
    assertEquals('0:00:00', $this->duration->formatted(0, true));
    assertEquals('0:00:04', $this->duration->formatted(4, true));
    assertEquals('0:00:09', $this->duration->formatted(9, true));
    assertEquals('0:00:42', $this->duration->formatted(42, true));
    assertEquals('0:01:02', $this->duration->formatted(62, true));
    assertEquals('0:01:09', $this->duration->formatted(69, true));
    assertEquals('0:01:42', $this->duration->formatted(102, true));
    assertEquals('0:10:47', $this->duration->formatted(647, true));
    assertEquals('1:00:00', $this->duration->formatted(3600, true));
    assertEquals('1:00:01', $this->duration->formatted(3601, true));
    assertEquals('1:00:11', $this->duration->formatted(3611, true));
    assertEquals('1:01:00', $this->duration->formatted(3660, true));
    assertEquals('1:01:14', $this->duration->formatted(3674, true));
    assertEquals('1:04:25', $this->duration->formatted(3865, true));
    assertEquals('1:09:09', $this->duration->formatted(4149, true));

    // microseconds
    assertEquals('0:00:04.542', $this->duration->formatted(4.542, true));
    assertEquals('1:09:09.0987', $this->duration->formatted(4149.0987, true));
});

test('Converting Formatted Strings To Seconds', function () {
    assertEquals(0, $this->duration->toSeconds('0'));
    assertEquals(4, $this->duration->toSeconds('4'));
    assertEquals(9, $this->duration->toSeconds('9'));
    assertEquals(42, $this->duration->toSeconds('42'));
    assertEquals(62, $this->duration->toSeconds('1:02'));
    assertEquals(69, $this->duration->toSeconds('1:09'));
    assertEquals(102, $this->duration->toSeconds('1:42'));
    assertEquals(647, $this->duration->toSeconds('10:47'));
    assertEquals(3600, $this->duration->toSeconds('1:00:00'));
    assertEquals(3601, $this->duration->toSeconds('1:00:01'));
    assertEquals(3611, $this->duration->toSeconds('1:00:11'));
    assertEquals(3660, $this->duration->toSeconds('1:01:00'));
    assertEquals(3674, $this->duration->toSeconds('1:01:14'));
    assertEquals(3865, $this->duration->toSeconds('1:04:25'));
    assertEquals(4149, $this->duration->toSeconds('1:09:09'));

    // microseconds
    assertEquals(4.6, $this->duration->toSeconds('4.6'));
    assertEquals(9.5, $this->duration->toSeconds('9.5'));
    assertEquals(42.1, $this->duration->toSeconds('42.1'));
    assertEquals(62.96, $this->duration->toSeconds('1:02.96'));
    assertEquals(69.23, $this->duration->toSeconds('1:09.23'));
    assertEquals(102.55, $this->duration->toSeconds('1:42.55'));
    assertEquals(647.999, $this->duration->toSeconds('10:47.999'));
    assertEquals(3600.9987, $this->duration->toSeconds('1:00:00.9987'));
    assertEquals(3601.000111, $this->duration->toSeconds('1:00:01.000111'));
    assertEquals(3611.0999, $this->duration->toSeconds('1:00:11.0999'));
    assertEquals(3660.500001, $this->duration->toSeconds('1:01:00.500001'));
    assertEquals(3674.00001, $this->duration->toSeconds('1:01:14.00001'));
    assertEquals(3865.499999, $this->duration->toSeconds('1:04:25.499999'));
    assertEquals(4149.499999, $this->duration->toSeconds('1:09:09.499999'));

    // precision
    assertEquals(0, $this->duration->toSeconds('0', 0));
    assertEquals(5, $this->duration->toSeconds('4.6', 0));
    assertEquals(10, $this->duration->toSeconds('9.5', 0));
    assertEquals(42.1, $this->duration->toSeconds('42.1', 1));
    assertEquals(63, $this->duration->toSeconds('1:02.96', 1));
    assertEquals(69.23, $this->duration->toSeconds('1:09.23'));
    assertEquals(102.55, $this->duration->toSeconds('1:42.55', 2));
    assertEquals(648, $this->duration->toSeconds('10:47.999', 2));
    assertEquals(3601, $this->duration->toSeconds('1:00:00.9987', 2));
    assertEquals(3601, $this->duration->toSeconds('1:00:01.000111', 3));
    assertEquals(3611.0999, $this->duration->toSeconds('1:00:11.0999', 4));
    assertEquals(3660.5, $this->duration->toSeconds('1:01:00.500001', 2));
    assertEquals(3674, $this->duration->toSeconds('1:01:14.00001', 2));
    assertEquals(3865.5, $this->duration->toSeconds('1:04:25.499999', 3));
    assertEquals(4149.499997, $this->duration->toSeconds('1:09:09.4999971', 6));
});

test('Converting Formatted Strings To Minutes', function () {
    assertEquals(0, $this->duration->toMinutes('0'));
    assertEquals(4 / 60, $this->duration->toMinutes('4'));
    assertEquals(9 / 60, $this->duration->toMinutes('9'));
    assertEquals(42 / 60, $this->duration->toMinutes('42'));
    assertEquals(62 / 60, $this->duration->toMinutes('1:02'));
    assertEquals(69 / 60, $this->duration->toMinutes('1:09'));
    assertEquals(102 / 60, $this->duration->toMinutes('1:42'));
    assertEquals(647 / 60, $this->duration->toMinutes('10:47'));
    assertEquals(3600 / 60, $this->duration->toMinutes('1:00:00'));
    assertEquals(3601 / 60, $this->duration->toMinutes('1:00:01'));
    assertEquals(3611 / 60, $this->duration->toMinutes('1:00:11'));
    assertEquals(3660 / 60, $this->duration->toMinutes('1:01:00'));
    assertEquals(3674 / 60, $this->duration->toMinutes('1:01:14'));
    assertEquals(3865 / 60, $this->duration->toMinutes('1:04:25'));
    assertEquals(4149 / 60, $this->duration->toMinutes('1:09:09'));

    // to integer - BC
    assertEquals(0, $this->duration->toMinutes('0', true));
    assertEquals(0, $this->duration->toMinutes('4', true));
    assertEquals(0, $this->duration->toMinutes('9', true));
    assertEquals(1, $this->duration->toMinutes('42', true));
    assertEquals(1, $this->duration->toMinutes('1:02', true));
    assertEquals(1, $this->duration->toMinutes('1:09', true));
    assertEquals(2, $this->duration->toMinutes('1:42', true));
    assertEquals(11, $this->duration->toMinutes('10:47', true));
    assertEquals(60, $this->duration->toMinutes('1:00:00', true));
    assertEquals(60, $this->duration->toMinutes('1:00:01', true));
    assertEquals(60, $this->duration->toMinutes('1:00:11', true));
    assertEquals(61, $this->duration->toMinutes('1:01:00', true));
    assertEquals(61, $this->duration->toMinutes('1:01:14', true));
    assertEquals(64, $this->duration->toMinutes('1:04:25', true));
    assertEquals(65, $this->duration->toMinutes('1:04:55', true));
    assertEquals(69, $this->duration->toMinutes('1:09:09', true));

    // precision
    assertEquals(0, $this->duration->toMinutes('0', 0));
    assertEquals(0, $this->duration->toMinutes('4', 0));
    assertEquals(0, $this->duration->toMinutes('9', 0));
    assertEquals(1, $this->duration->toMinutes('42', 0));
    assertEquals(1, $this->duration->toMinutes('1:02', 0));
    assertEquals(1, $this->duration->toMinutes('1:09', 0));
    assertEquals(2, $this->duration->toMinutes('1:42', 0));
    assertEquals(11, $this->duration->toMinutes('10:47', 0));
    assertEquals(60, $this->duration->toMinutes('1:00:00', 0));
    assertEquals(60, $this->duration->toMinutes('1:00:01', 0));
    assertEquals(60, $this->duration->toMinutes('1:00:11', 0));
    assertEquals(61, $this->duration->toMinutes('1:01:00', 0));
    assertEquals(61, $this->duration->toMinutes('1:01:14', 0));
    assertEquals(64, $this->duration->toMinutes('1:04:25', 0));
    assertEquals(65, $this->duration->toMinutes('1:04:55', 0));
    assertEquals(69, $this->duration->toMinutes('1:09:09', 0));

    assertEquals(0, $this->duration->toMinutes('0', 1));
    assertEquals(0.1, $this->duration->toMinutes('4', 1));
    assertEquals(0.15, $this->duration->toMinutes('9', 2));
    assertEquals(0.7, $this->duration->toMinutes('42', 3));
    assertEquals(1, $this->duration->toMinutes('1:02', 1));
    assertEquals(1.15, $this->duration->toMinutes('1:09', 2));
    assertEquals(1.7, $this->duration->toMinutes('1:42', 3));
    assertEquals(10.8, $this->duration->toMinutes('10:47', 1));
    assertEquals(60, $this->duration->toMinutes('1:00:00', 2));
    assertEquals(60.017, $this->duration->toMinutes('1:00:01', 3));
    assertEquals(60.2, $this->duration->toMinutes('1:00:11', 1));
    assertEquals(61, $this->duration->toMinutes('1:01:00', 2));
    assertEquals(61.233, $this->duration->toMinutes('1:01:14', 3));
    assertEquals(64.42, $this->duration->toMinutes('1:04:25', 2));
    assertEquals(64.92, $this->duration->toMinutes('1:04:55', 2));
    assertEquals(69.15, $this->duration->toMinutes('1:09:09', 2));
});

test('Convert Seconds To Humanized String', function () {
    assertEquals('0s', $this->duration->humanize(0));
    assertEquals('4s', $this->duration->humanize(4));
    assertEquals('42s', $this->duration->humanize(42));
    assertEquals('1m 2s', $this->duration->humanize(62));
    assertEquals('1m 42s', $this->duration->humanize(102));
    assertEquals('10m 47s', $this->duration->humanize(647));
    assertEquals('1h', $this->duration->humanize(3600));
    assertEquals('1h 5s', $this->duration->humanize(3605));
    assertEquals('1h 1m', $this->duration->humanize(3660));
    assertEquals('1h 1m 5s', $this->duration->humanize(3665));
    assertEquals('3d', $this->duration->humanize(259200));
    assertEquals('2d 11h 30m', $this->duration->humanize(214200));

    assertEquals('4.0596s', $this->duration->humanize(4.0596));
    assertEquals('2d 11h 30m 0.9542s', $this->duration->humanize(214200.9542));

});

test('Convert Humanized String To Seconds', function () {
    assertEquals(0, $this->duration->toSeconds('0s'));
    assertEquals(4, $this->duration->toSeconds('4s'));
    assertEquals(42, $this->duration->toSeconds('42s'));
    assertEquals(72, $this->duration->toSeconds('1m 12s'));
    assertEquals(102, $this->duration->toSeconds('1m 42s'));
    assertEquals(647, $this->duration->toSeconds('10m 47s'));
    assertEquals(3600, $this->duration->toSeconds('1h'));
    assertEquals(3605, $this->duration->toSeconds('1h 5s'));
    assertEquals(3660, $this->duration->toSeconds('1h 1m'));
    assertEquals(3665, $this->duration->toSeconds('1h 1m 5s'));
    assertEquals(86400, $this->duration->toSeconds('1d'));
    assertEquals(214200, $this->duration->toSeconds('2d 11h 30m'));
    assertEquals(214214, $this->duration->toSeconds('2d 11h 30m 14s'));
})
    ->depends(
        'Getting Value From Second Suffixes',
        'Getting Value From Minute Suffixes',
        'Getting Value From Hour Suffixes'
    );

test('Convert Humanized String To Seconds 7 Hour Day', function () {
    $d = new Duration(null, 7);

    assertEquals(0, $d->toSeconds('0d'));
    assertEquals(25200, $d->toSeconds('1d'));
    assertEquals(91800, $d->toSeconds('2d 11h 30m'));
});

test('Support Decimals', function () {
    $d = new Duration(null, 6);

    assertEquals(0, $d->toMinutes('0d'));
    assertEquals(6 * 60, $d->toMinutes('1d'));
    assertEquals((6 + 3) * 60, $d->toMinutes('1.5d'));
    assertEquals(60, $d->toMinutes('1h'));
    assertEquals(60 + 30, $d->toMinutes('1.5h'));
    assertEquals((12 * 60) + 60 + 30, $d->toMinutes('2d 1.5h'));
});

test('Convert Humanized With Support Decimals', function () {
    $t = '1.5d 1.5h 2m 5s';

    assertEquals('1d 4h 32m 5s', (new Duration($t, 6))->humanize(), "Test humanize with: {$t}");
    assertEquals('10:32:05', (new Duration($t, 6))->formatted(), "Test formatted with: {$t}");
    assertEquals(37925, (new Duration($t, 6))->toSeconds(), "Test toSeconds with: {$t}");
    assertEquals(37925 / 60, (new Duration($t, 6))->toMinutes(), "Test toMinutes with: {$t}");
    assertEquals(632, (new Duration($t, 6))->toMinutes(null, 0), "Test toMinutes with: {$t}");
});
