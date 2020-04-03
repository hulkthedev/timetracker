<?php

namespace Tracking\Services;

/**
 * @author <fatal.error.27@gmail.com>
 */
class TimeCalculationService
{
    const MINUTES_PER_HOUR = 60;

    /** @var int */
    private $minutes = 0;

    /**
     * @param string $time
     * @return TimeCalculationService
     */
    public function add(string $time): TimeCalculationService
    {
        $minutesAndHours = explode(':', $time);

        $minutesTotal  = (int)$minutesAndHours[1];
        $minutesTotal += (int)$minutesAndHours[0] * self::MINUTES_PER_HOUR;

        $this->minutes += $minutesTotal;
        return $this;
    }

    /**
     * @param string $time
     * @return TimeCalculationService
     */
    public function sub(string $time): TimeCalculationService
    {
        $minutesAndHours = explode(':', $time);

        $minutesTotal  = (int)$minutesAndHours[1];
        $minutesTotal += (int)$minutesAndHours[0] * self::MINUTES_PER_HOUR;

        $this->minutes -= $minutesTotal;
        return $this;
    }

    /**
     * @return TimeCalculationService
     */
    public function reset(): TimeCalculationService
    {
        $this->minutes = 0;
        return $this;
    }

    /**
     * @return int
     */
    public function getMinutes(): int
    {
        return abs($this->minutes);
    }

    /**
     * @return int
     */
    public function getBalanceStatus(): int
    {
        if ($this->minutes === 0) {
            return 0;
        }

        if ($this->minutes > 0) {
            return 1;
        }

        return -1;
    }

    /**
     * @return string
     */
    public function getFormattedResult(): string
    {
        $hours = floor(abs($this->minutes) / self::MINUTES_PER_HOUR);
        $minutes = (abs($this->minutes) % self::MINUTES_PER_HOUR);

        $hours = str_pad($hours, 2, '0', STR_PAD_LEFT);
        $minutes = str_pad($minutes, 2, '0', STR_PAD_LEFT);

        return sprintf('%s:%s', $hours, $minutes);
    }

    /**
     * @param string $time
     * @return int
     */
    public function getMinutesFromTime(string $time): int
    {
        $this->reset();
        $this->add($time);

        $minutes = $this->getMinutes();
        $this->reset();

        return $minutes;
    }

    /**
     * @param int $minutes
     * @return string
     */
    public function getTimeFromMinutes(int $minutes): string
    {
        $this->reset();
        $this->minutes = $minutes;

        $time = $this->getFormattedResult();
        $this->reset();

        return $time;
    }
}
