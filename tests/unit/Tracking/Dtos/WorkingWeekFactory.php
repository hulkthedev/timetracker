<?php

namespace Tracking\Dtos;

/**
 * @author  <fatal.error.27@gmail.com>
 */
class WorkingWeekFactory
{
    /**
     * @param int $weeks
     * @return array
     */
    public static function factory(int $weeks = 5): array
    {
        $weeksDtos = [];

        for ($i = 1; $i <= $weeks; $i++) {
            $weekDto = new WorkingWeekDto();
            $weekDto->weekNr = $i;
            $weekDto->difference = '00:00';
            $weekDto->differenceIsBalanced = 0;
            $weekDto->workingDays[] = static::createWorkingDay();

            $weeksDtos[] = $weekDto;
        }

        return $weeksDtos;
    }

    /**
     * @return WorkingDayDto
     */
    private static function createWorkingDay(): WorkingDayDto
    {
        switch (random_int(1, 10)) {
            case 7:
                return new VacationWorkingDayDtoStub();
            case 8:
                return new SickWorkingDayDtoStub();
            case 9:
                return new HolidayWorkingDayDtoStub();
            case 10:
                return new OvertimeWorkingDayDtoStub();
            default:
                return new DefaultWorkingDayDtoStub();
        }
    }
}
