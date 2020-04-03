<?php

namespace Tracking\Services;

use PHPUnit\Framework\TestCase;
use Tracking\Dtos\DefaultWorkingDayDtoStub;
use Tracking\Dtos\HolidayWorkingDayDtoStub;
use Tracking\Dtos\OvertimeWorkingDayDtoStub;
use Tracking\Dtos\SickWorkingDayDtoStub;
use Tracking\Dtos\VacationWorkingDayDtoStub;
use Tracking\Dtos\WorkingWeekDto;

/**
 * @author  <fatal.error.27@gmail.com>
 */
class AnalyticsCalculationServiceTest extends TestCase
{
    /** @var AnalyticsCalculationService */
    private $analyticsCalculationService;

    protected function setUp(): void
    {
        $week1 = new WorkingWeekDto();
        $week1->workingDays[] = new DefaultWorkingDayDtoStub();
        $week1->workingDays[] = new HolidayWorkingDayDtoStub();
        $week1->workingDays[] = new OvertimeWorkingDayDtoStub();
        $week1->workingDays[] = new SickWorkingDayDtoStub();
        $week1->workingDays[] = new VacationWorkingDayDtoStub();

        $week2 = clone $week1;
        $week3 = clone $week1;

        $this->analyticsCalculationService = new AnalyticsCalculationService([$week1, $week2, $week3]);
    }

    public function testWorkingDaysCounter(): void
    {
        TestCase::assertEquals(3, $this->analyticsCalculationService->countWorkingDays());
    }

    public function testSickDayCounter(): void
    {
        TestCase::assertEquals(3, $this->analyticsCalculationService->countSickDays());
    }

    public function testVacationDaysCounter(): void
    {
        TestCase::assertEquals(3, $this->analyticsCalculationService->countVacationDays());
    }

    public function testOvertimeDaysCounter(): void
    {
        TestCase::assertEquals(3, $this->analyticsCalculationService->countOvertimeDays());
    }

    public function testHolidaysCounter(): void
    {
        TestCase::assertEquals(3, $this->analyticsCalculationService->countHolidays());
    }
}
