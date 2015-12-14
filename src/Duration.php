<?php namespace Khill\Duration;

class Duration {

    public $hours;
    public $minutes;
    public $seconds;

    private $timeStr;
    private $output;
    private $hoursRegex;
    private $minutesRegex;
    private $secondsRegex;

    public function __construct($timeStr=null)
    {
        $this->hours   = 0;
        $this->minutes = 0;
        $this->seconds = 0;

        $this->output       = '';
        $this->hoursRegex   = '/([0-9]{1,2})\s?(?:h|H)/';
        $this->minutesRegex = '/([0-9]{1,2})\s?(?:m|M)/';
        $this->secondsRegex = '/([0-9]{1,2})\s?(?:s|S)/';

        if (! is_null($timeStr)) {
            $this->parse($timeStr);
        }
    }

    public function parse($timeStr)
    {
        $this->reset();
        $this->timeStr = $timeStr;

        if (is_numeric($this->timeStr)) {
            $this->seconds = (int) $this->timeStr;

            if ($this->seconds >= 60) {
                $this->minutes = (int) floor($this->seconds / 60);
                $this->seconds = (int) ($this->seconds - ($this->minutes * 60));
            }

            if ($this->minutes >= 60) {
                $this->hours   = (int) floor($this->minutes / 60);
                $this->minutes = (int) ($this->minutes - ($this->hours * 60));
            }

            return $this;
        } else if (preg_match('/\:/', $this->timeStr)) {
            $parts = explode(':', $this->timeStr);

            if (count($parts) == 2) {
                $this->minutes = (int) $parts[0];
                $this->seconds = (int) $parts[1];
            } else if (count($parts) == 3) {
                $this->hours   = (int) $parts[0];
                $this->minutes = (int) $parts[1];
                $this->seconds = (int) $parts[2];
            }

            return $this;
        } else if (preg_match($this->hoursRegex, $this->timeStr) ||
                   preg_match($this->minutesRegex, $this->timeStr) ||
                   preg_match($this->secondsRegex, $this->timeStr))
        {
            if (preg_match($this->hoursRegex, $this->timeStr, $matches)) {
                $this->hours = (int) $matches[1];
            }

            if (preg_match($this->minutesRegex, $this->timeStr, $matches)) {
                $this->minutes = (int) $matches[1];
            }

            if (preg_match($this->secondsRegex, $this->timeStr, $matches)) {
                $this->seconds = (int) $matches[1];
            }

            return $this;
        } else {
            return false;
        }
    }

    private function reset()
    {
        $this->output  = '';
        $this->seconds = 0;
        $this->minutes = 0;
        $this->hours   = 0;
    }

    private function output()
    {
        $out = $this->output;

        $this->reset();

        return $out;
    }

    public function toSeconds($timeStr)
    {
        if (! is_null($timeStr)) {
            $this->parse($timeStr);
        }

        $this->output = ($this->hours * 60 * 60) + ($this->minutes * 60) + $this->seconds;

        return $this->output();
    }

    public function formatted($timeStr)
    {
        if (! is_null($timeStr)) {
            $this->parse($timeStr);
        }

        if ($this->seconds > 0)  {
            if ($this->seconds < 9 && ($this->minutes > 0 || $this->hours > 0)) {
                $this->output .= '0' . $this->seconds;
            } else {
                $this->output .= $this->seconds;
            }
        } else {
            if ($this->minutes > 0 || $this->hours > 0) {
                $this->output = '00';
            }
        }

        if ($this->minutes > 0) {
            if ($this->minutes < 9 && $this->hours > 0) {
                $this->output = '0' . $this->minutes . ':' . $this->output;
            } else {
                $this->output = $this->minutes . ':' . $this->output;
            }
        } else {
            if ($this->hours > 0) {
                $this->output = '00' . ':' . $this->output;
            }
        }

        if ($this->hours > 0) {
            $this->output = $this->hours . ':' . $this->output;
        }

        return $this->output();
    }

    public function humanize($timeStr = null)
    {
        if (! is_null($timeStr)) {
            $this->parse($timeStr);
        }

        if ($this->seconds > 0) {
            $this->output .= $this->seconds . 's';
        }

        if ($this->minutes > 0) {
            $this->output = $this->minutes . 'm ' . $this->output;
        }

        if ($this->hours > 0) {
            $this->output = $this->hours . 'h ' . $this->output;
        }

        return trim($this->output());
    }

}

