<?php


namespace xZeroMCPE\Zeroify\Configuration;


class TimeConfiguration
{
    public int $time;
    public int $originalTime; // Stores the original time, which can be used to reset it

    public function __construct(int $time) {
        $this->time = $time;
        $this->originalTime = $time;
    }

    /**
     * @return int
     */
    public function getTime(): int
    {
        return $this->time;
    }

    /**
     * @param int $time
     */
    public function setTime(int $time): void
    {
        $this->time = $time;
    }

    public function reset() {
        $this->time = $this->originalTime;
    }

    public function secondsToTime(int $seconds) : string{

        $from     = new \DateTime('@0');
        $to       = new \DateTime("@$seconds");
        $interval = $from->diff($to);
        $str      = '';

        $parts = [
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        ];

        $includedParts = 0;
        $requiredParts = null; //edited

        foreach ($parts as $key => $text) {
            if ($requiredParts && $includedParts >= $requiredParts) {
                break;
            }

            $currentPart = $interval->{$key};

            if (empty($currentPart)) {
                continue;
            }

            if (!empty($str)) {
                $str .= ', ';
            }

            $str .= sprintf('%d %s', $currentPart, $text);

            if ($currentPart > 1) {
                // handle plural
                $str .= 's';
            }

            $includedParts++;
        }

        return $str;
    }

    public function getTimeFormatted(bool $simple = false, bool$withHour = false) : string {
        if(!$simple) {
            return $this->secondsToTime($this->getTime());
        } else {
            if($withHour) {
                return gmdate("H:i:s", $this->getTime());
            }
            return gmdate("i:s", $this->getTime());
        }
    }

    public function subtract($number = 1) : TimeConfiguration {
        $this->time -= $number;
        return $this;
    }

    public function add($number = 1) : TimeConfiguration {
        $this->time += $number;
        return $this;
    }

    public function isFinished() : bool {
        return $this->getTime() <= 0;
    }
}