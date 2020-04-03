<?php

namespace Tracking\Usecase\Working;

use Tracking\Dtos\WorkingDayDto;

/**
 * @author  <fatal.error.27@gmail.com>
 */
class GetWorkingDayResponse
{
    /** @var int */
    public $code;

    /** @var WorkingDayDto */
    public $workingDay;

    /**
     * @param int $code
     */
    public function __construct(int $code)
    {
        $this->code = $code;
    }

    /**
     * @param WorkingDayDto $dto
     */
    public function setWorkingDay(WorkingDayDto $dto): void
    {
        $this->workingDay = $dto;
    }
}
