<?php

namespace Tracking\Repository;

use Throwable;
use Tracking\Dtos\WorkingDayDto;

/**
 * @author Alexej Beirith <alexej.beirith@arvato.com>
 */
class WorkingTimeInMemoryExceptionRepository extends Spy implements WorkingTimeRepository
{
    /** @var Throwable */
    private $throwable;

    /**
     * @param Throwable $throwable
     */
    public function __construct(Throwable $throwable)
    {
        $this->throwable = $throwable;
    }

    /**
     * @inheritdoc
     */
    public function getWorkingTimeList(): array
    {
        $this->logRequest(__FUNCTION__, func_get_args());
        throw $this->throwable;
    }

    /**
     * @inheritdoc
     */
    public function startWorking(array $day): array
    {
        $this->logRequest(__FUNCTION__, func_get_args());
        throw $this->throwable;
    }

    /**
     * @inheritdoc
     */
    public function endWorking(array $day): array
    {
        $this->logRequest(__FUNCTION__, func_get_args());
        throw $this->throwable;
    }

    /**
     * @inheritdoc
     */
    public function setNonWorkingNote(array $days, int $mode): array
    {
        $this->logRequest(__FUNCTION__, func_get_args());
        throw $this->throwable;
    }

    /**
     * @inheritdoc
     */
    public function getWorkingDayByDate(string $date): WorkingDayDto
    {
        $this->logRequest(__FUNCTION__, func_get_args());
        throw $this->throwable;
    }

    /**
     * @inheritdoc
     */
    public function updateWorkingDayByDate(array $day): array
    {
        $this->logRequest(__FUNCTION__, func_get_args());
        throw $this->throwable;
    }

    /**
     * @inheritdoc
     */
    public function updateTimeDifferenceByDate(array $day): void
    {
        $this->logRequest(__FUNCTION__, func_get_args());
        throw $this->throwable;
    }
}
