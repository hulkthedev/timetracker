<?php

namespace Tracking\Repository;

use Tracking\Dtos\DefaultWorkingDayDtoStub;
use Tracking\Dtos\WorkingDayDto;
use Tracking\Dtos\WorkingWeekFactory;

/**
 * @author Alexej Beirith <alexej.beirith@arvato.com>
 */
class WorkingTimeInMemoryRepository extends Spy implements WorkingTimeRepository
{
    /**
     * @inheritdoc
     */
    public function getWorkingTimeList(): array
    {
        $this->logRequest(__FUNCTION__, func_get_args());
        return WorkingWeekFactory::factory();
    }

    /**
     * @inheritdoc
     */
    public function startWorking(array $day): array
    {
        $this->logRequest(__FUNCTION__, func_get_args());
        return WorkingWeekFactory::factory();
    }

    /**
     * @inheritdoc
     */
    public function endWorking(array $day): array
    {
        $this->logRequest(__FUNCTION__, func_get_args());
        return WorkingWeekFactory::factory();
    }

    /**
     * @inheritdoc
     */
    public function setNonWorkingNote(array $days, int $mode): array
    {
        $this->logRequest(__FUNCTION__, func_get_args());
        return WorkingWeekFactory::factory();
    }

    /**
     * @inheritdoc
     */
    public function getWorkingDayByDate(string $date): WorkingDayDto
    {
        $this->logRequest(__FUNCTION__, func_get_args());
        return new DefaultWorkingDayDtoStub();
    }

    /**
     * @inheritdoc
     */
    public function updateWorkingDayByDate(array $day): array
    {
        $this->logRequest(__FUNCTION__, func_get_args());
        return WorkingWeekFactory::factory();
    }

    /**
     * @inheritdoc
     */
    public function updateTimeDifferenceByDate(array $day): void
    {
        $this->logRequest(__FUNCTION__, func_get_args());
    }
}
