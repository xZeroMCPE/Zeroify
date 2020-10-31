<?php


namespace xZeroMCPE\Zeroify\Configuration;


class TimeConfiguration
{
    public int $time;

    public function __construct(int $time) {
        $this->time = $time;
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

    public function getTimeFormatted($withHour = false) : string {
        if($withHour) {
            return gmdate("H:i:s", $this->getTime());
        }
        return gmdate("i:s", $this->getTime());
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