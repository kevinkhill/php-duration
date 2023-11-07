<?php

declare(strict_types=1);

namespace Khill\Duration;

class Duration
{
    public int|null|float $days;

    public int|null|float $hours;

    public int|null|float $minutes;

    public int|null|float $seconds;

    public ?int $hoursPerDay;

    private string|int|float $output;

    private string $daysRegex;

    private string $hoursRegex;

    private string $minutesRegex;

    private string $secondsRegex;

    /**
     * Duration constructor.
     */
    public function __construct(float|int|string $duration = null, int $hoursPerDay = 24)
    {
        $this->reset();

        $this->daysRegex = '/([0-9\.]+)\s?(?:d|D)/';
        $this->hoursRegex = '/([0-9\.]+)\s?(?:h|H)/';
        $this->minutesRegex = '/([0-9]{1,2})\s?(?:m|M)/';
        $this->secondsRegex = '/([0-9]{1,2}(\.\d+)?)\s?(?:s|S)/';

        $this->hoursPerDay = $hoursPerDay;

        if (null !== $duration) {
            $this->parse($duration);
        }
    }

    /**
     * Attempt to parse one of the forms of duration.
     *
     * @param  float|int|string|null  $duration A string or number, representing a duration
     * @return self|bool returns the Duration object if successful, otherwise false
     */
    public function parse(float|int|string|null $duration): bool|Duration|static
    {
        $this->reset();

        if (null === $duration) {
            return false;
        }

        if (is_numeric($duration)) {
            $this->seconds = (float) $duration;

            if ($this->seconds >= 60) {
                $this->minutes = (int) floor($this->seconds / 60);

                // count current precision
                $precision = 0;
                if (($delimiterPos = strpos((string) $this->seconds, '.')) !== false) {
                    $precision = strlen(substr((string) $this->seconds, $delimiterPos + 1));
                }

                $this->seconds = (float) round(($this->seconds - ($this->minutes * 60)), $precision);
            }

            if ($this->minutes >= 60) {
                $this->hours = (int) floor($this->minutes / 60);
                $this->minutes = (int) ($this->minutes - ($this->hours * 60));
            }

            if ($this->hours >= $this->hoursPerDay) {
                $this->days = (int) floor($this->hours / $this->hoursPerDay);
                $this->hours = (int) ($this->hours - ($this->days * $this->hoursPerDay));
            }

            return $this;
        }

        if (preg_match('/\:/', $duration)) {
            $parts = explode(':', $duration);

            if (2 == count($parts)) {
                $this->minutes = (int) $parts[0];
                $this->seconds = (float) $parts[1];
            } else {
                if (3 == count($parts)) {
                    $this->hours = (int) $parts[0];
                    $this->minutes = (int) $parts[1];
                    $this->seconds = (float) $parts[2];
                }
            }

            return $this;
        }

        if (preg_match($this->daysRegex, $duration) ||
            preg_match($this->hoursRegex, $duration) ||
            preg_match($this->minutesRegex, $duration) ||
            preg_match($this->secondsRegex, $duration)) {
            if (preg_match($this->daysRegex, $duration, $matches)) {
                $num = $this->numberBreakdown((float) $matches[1]);
                $this->days += (int) $num[0];
                $this->hours += $num[1] * $this->hoursPerDay;
            }

            if (preg_match($this->hoursRegex, $duration, $matches)) {
                $num = $this->numberBreakdown((float) $matches[1]);
                $this->hours += (int) $num[0];
                $this->minutes += $num[1] * 60;
            }

            if (preg_match($this->minutesRegex, $duration, $matches)) {
                $this->minutes += (int) $matches[1];
            }

            if (preg_match($this->secondsRegex, $duration, $matches)) {
                $this->seconds += (float) $matches[1];
            }

            return $this;
        }

        return false;
    }

    /**
     * Returns the duration as an amount of seconds.
     *
     * For example, one hour and 42 minutes would be "6120"
     *
     * @param  float|int|string|null  $duration A string or number, representing a duration
     * @param  bool|int  $precision Number of decimal digits to round to. If set to false, the number is not rounded.
     */
    public function toSeconds(float|int|string $duration = null, bool|int $precision = false): float|int|string|null
    {
        if (null !== $duration) {
            $this->parse($duration);
        }
        $this->output = ($this->days * $this->hoursPerDay * 60 * 60) + ($this->hours * 60 * 60) + ($this->minutes * 60) + $this->seconds;

        return false !== $precision ? round($this->output, $precision) : $this->output;
    }

    /**
     * Returns the duration as an amount of minutes.
     *
     * For example, one hour and 42 minutes would be "102" minutes
     *
     * @param  float|int|string|null  $duration A string or number, representing a duration
     * @param  bool|int  $precision Number of decimal digits to round to. If set to false, the number is not rounded.
     */
    public function toMinutes(float|int|string $duration = null, bool|int $precision = false): float|int
    {
        if (null !== $duration) {
            $this->parse($duration);
        }

        // backward compatibility, true = round to integer
        if (true === $precision) {
            $precision = 0;
        }

        $this->output = ($this->days * $this->hoursPerDay * 60 * 60) + ($this->hours * 60 * 60) + ($this->minutes * 60) + $this->seconds;
        $result = intval($this->output()) / 60;

        return false !== $precision ? round($result, $precision) : $result;
    }

    /**
     * Returns the duration as a colon formatted string
     *
     * For example, one hour and 42 minutes would be "1:43"
     * With $zeroFill to true :
     *   - 42 minutes would be "0:42:00"
     *   - 28 seconds would be "0:00:28"
     *
     * @param  float|int|string|null  $duration A string or number, representing a duration
     * @param  bool  $zeroFill A boolean, to force zero-fill result or not (see example)
     * @return string
     */
    public function formatted(float|int|string $duration = null, bool $zeroFill = false): int|string
    {
        if (null !== $duration) {
            $this->parse($duration);
        }

        $hours = $this->hours + ($this->days * $this->hoursPerDay);

        if ($this->seconds > 0) {
            if ($this->seconds < 10 && ($this->minutes > 0 || $hours > 0 || $zeroFill)) {
                $this->output .= '0'.$this->seconds;
            } else {
                $this->output .= $this->seconds;
            }
        } else {
            if ($this->minutes > 0 || $hours > 0 || $zeroFill) {
                $this->output = '00';
            } else {
                $this->output = '0';
            }
        }

        if ($this->minutes > 0) {
            if ($this->minutes <= 9 && ($hours > 0 || $zeroFill)) {
                $this->output = '0'.$this->minutes.':'.$this->output;
            } else {
                $this->output = $this->minutes.':'.$this->output;
            }
        } else {
            if ($hours > 0 || $zeroFill) {
                $this->output = '00'.':'.$this->output;
            }
        }

        if ($hours > 0) {
            $this->output = $hours.':'.$this->output;
        } else {
            if ($zeroFill) {
                $this->output = '0'.':'.$this->output;
            }
        }

        return $this->output();
    }

    /**
     * Returns the duration as a human-readable string.
     *
     * For example, one hour and 42 minutes would be "1h 42m"
     *
     * @param  int|float|string  $duration A string or number, representing a duration
     */
    public function humanize(float|int|string $duration = null): string
    {
        if (null !== $duration) {
            $this->parse($duration);
        }

        if ($this->seconds > 0 || (0.0 === $this->seconds && 0 === $this->minutes && 0 === $this->hours && 0 === $this->days)) {
            $this->output .= $this->seconds.'s';
        }

        if ($this->minutes > 0) {
            $this->output = $this->minutes.'m '.$this->output;
        }

        if ($this->hours > 0) {
            $this->output = $this->hours.'h '.$this->output;
        }

        if ($this->days > 0) {
            $this->output = $this->days.'d '.$this->output;
        }

        return trim($this->output());
    }

    /**
     * @return array<int, int|float>
     */
    private function numberBreakdown(float $number): array
    {
        $negative = 1;

        if ($number < 0) {
            $negative = -1;
            $number *= -1;
        }

        return [
            floor($number) * $negative,
            ($number - floor($number)) * $negative,
        ];
    }

    /**
     * Resets the Duration object by clearing the output and values.
     */
    private function reset(): void
    {
        $this->output = '';
        $this->seconds = 0.0;
        $this->minutes = 0;
        $this->hours = 0;
        $this->days = 0;
    }

    /**
     * Returns the output of the Duration object and resets.
     */
    private function output(): int|string|float
    {
        $out = $this->output;

        $this->reset();

        return $out;
    }
}
