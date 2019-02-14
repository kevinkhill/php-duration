<?php

namespace Khill\Duration;

class Duration
{
    public $days;
    public $hours;
    public $minutes;
    public $seconds;

    public $hoursPerDay;

    private $output;
    private $daysRegex;
    private $hoursRegex;
    private $minutesRegex;
    private $secondsRegex;

    /**
     * Duration constructor.
     *
     * @param int|float|string|null $duration
     */
    public function __construct($duration = null, $hoursPerDay = 24)
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
     * @param  int|float|string|null $duration A string or number, representing a duration
     * @return self|bool returns the Duration object if successful, otherwise false
     */
    public function parse($duration)
    {
        $this->reset();

        if (null === $duration) {
            return false;
        }

        if (is_numeric($duration)) {
            $this->seconds = (float)$duration;

            if ($this->seconds >= 60) {
                $this->minutes = (int)floor($this->seconds / 60);

                // count current precision
                $precision = 0;
                if (($delimiterPos = strpos($this->seconds, '.')) !== false) {
                    $precision = strlen(substr($this->seconds, $delimiterPos + 1));
                }

                $this->seconds = (float)round(($this->seconds - ($this->minutes * 60)), $precision);
            }

            if ($this->minutes >= 60) {
                $this->hours = (int)floor($this->minutes / 60);
                $this->minutes = (int)($this->minutes - ($this->hours * 60));
            }

            if ($this->hours >= $this->hoursPerDay) {
                $this->days = (int)floor($this->hours / $this->hoursPerDay);
                $this->hours = (int)($this->hours - ($this->days * $this->hoursPerDay));
            }

            return $this;
        }

        if (preg_match('/\:/', $duration)) {
            $parts = explode(':', $duration);

            if (count($parts) == 2) {
                $this->minutes = (int)$parts[0];
                $this->seconds = (float)$parts[1];
            } else {
                if (count($parts) == 3) {
                    $this->hours = (int)$parts[0];
                    $this->minutes = (int)$parts[1];
                    $this->seconds = (float)$parts[2];
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
                $this->days += (int)$num[0];
                $this->hours += $num[1] * $this->hoursPerDay;
            }

            if (preg_match($this->hoursRegex, $duration, $matches)) {
                $num = $this->numberBreakdown((float) $matches[1]);
                $this->hours += (int)$num[0];
                $this->minutes += $num[1] * 60;
            }

            if (preg_match($this->minutesRegex, $duration, $matches)) {
                $this->minutes += (int)$matches[1];
            }

            if (preg_match($this->secondsRegex, $duration, $matches)) {
                $this->seconds += (float)$matches[1];
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
     * @param  int|float|string $duration A string or number, representing a duration
     * @param  int|bool $precision Number of decimal digits to round to. If set to false, the number is not rounded.
     * @return int|float
     */
    public function toSeconds($duration = null, $precision = false)
    {
        if (null !== $duration) {
            $this->parse($duration);
        }
        $this->output = ($this->days * $this->hoursPerDay * 60 * 60) + ($this->hours * 60 * 60) + ($this->minutes * 60) + $this->seconds;

        return $precision !== false ? round($this->output, $precision) : $this->output;
    }

    /**
     * Returns the duration as an amount of minutes.
     *
     * For example, one hour and 42 minutes would be "102" minutes
     *
     * @param  int|float|string $duration A string or number, representing a duration
     * @param  int|bool $precision Number of decimal digits to round to. If set to false, the number is not rounded.
     * @return int|float
     */
    public function toMinutes($duration = null, $precision = false)
    {
        if (null !== $duration) {
            $this->parse($duration);
        }

        // backward compatibility, true = round to integer
        if ($precision === true) {
            $precision = 0;
        }

        $this->output = ($this->days * $this->hoursPerDay * 60 * 60) + ($this->hours * 60 * 60) + ($this->minutes * 60) + $this->seconds;
        $result = intval($this->output()) / 60;

        return $precision !== false ? round($result, $precision) : $result;
    }

    /**
     * Returns the duration as a colon formatted string
     *
     * For example, one hour and 42 minutes would be "1:43"
     * With $zeroFill to true :
     *   - 42 minutes would be "0:42:00"
     *   - 28 seconds would be "0:00:28"
     *
     * @param  int|float|string|null $duration A string or number, representing a duration
     * @param  bool $zeroFill A boolean, to force zero-fill result or not (see example)
     * @return string
     */
    public function formatted($duration = null, $zeroFill = false)
    {
        if (null !== $duration) {
            $this->parse($duration);
        }

        $hours = $this->hours + ($this->days * $this->hoursPerDay);

        if ($this->seconds > 0) {
            if ($this->seconds < 10 && ($this->minutes > 0 || $hours > 0 || $zeroFill)) {
                $this->output .= '0' . $this->seconds;
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
                $this->output = '0' . $this->minutes . ':' . $this->output;
            } else {
                $this->output = $this->minutes . ':' . $this->output;
            }
        } else {
            if ($hours > 0 || $zeroFill) {
                $this->output = '00' . ':' . $this->output;
            }
        }

        if ($hours > 0) {
            $this->output = $hours . ':' . $this->output;
        } else {
            if ($zeroFill) {
                $this->output = '0' . ':' . $this->output;
            }
        }

        return $this->output();
    }

    /**
     * Returns the duration as a human-readable string.
     *
     * For example, one hour and 42 minutes would be "1h 42m"
     *
     * @param  int|float|string $duration A string or number, representing a duration
     * @return string
     */
    public function humanize($duration = null)
    {
        if (null !== $duration) {
            $this->parse($duration);
        }

        if ($this->seconds > 0 || ($this->seconds === 0.0 && $this->minutes === 0 && $this->hours === 0 && $this->days === 0)) {
            $this->output .= $this->seconds . 's';
        }

        if ($this->minutes > 0) {
            $this->output = $this->minutes . 'm ' . $this->output;
        }

        if ($this->hours > 0) {
            $this->output = $this->hours . 'h ' . $this->output;
        }

        if ($this->days > 0) {
            $this->output = $this->days . 'd ' . $this->output;
        }

        return trim($this->output());
    }


    private function numberBreakdown($number, $returnUnsigned = false)
    {
        $negative = 1;

        if ($number < 0) {
            $negative = -1;
            $number *= -1;
        }

        if ($returnUnsigned) {
            return array(
                floor($number),
                ($number - floor($number))
            );
        }

        return array(
            floor($number) * $negative,
            ($number - floor($number)) * $negative
        );
    }


    /**
     * Resets the Duration object by clearing the output and values.
     *
     * @access private
     * @return void
     */
    private function reset()
    {
        $this->output = '';
        $this->seconds = 0.0;
        $this->minutes = 0;
        $this->hours = 0;
        $this->days = 0;
    }

    /**
     * Returns the output of the Duration object and resets.
     *
     * @access private
     * @return string
     */
    private function output()
    {
        $out = $this->output;

        $this->reset();

        return $out;
    }
}

