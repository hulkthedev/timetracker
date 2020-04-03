<?php

namespace Tracking\Services;

use PHPUnit\Framework\TestCase;
use Tracking\Dtos\ConfigDtoStub;

/**
 * @author  <fatal.error.27@gmail.com>
 */
class TimeDifferenceCalculationServiceTest extends TestCase
{
    /**
     * @return array
     */
    public function calculationDataProvider(): array
    {
        return [
            ['13.06.2018', '10:00', '18:00', '00:30', -1],
            ['13.06.2018', '10:00', '18:30', '00:00',  0],
            ['13.06.2018', '10:00', '19:00', '00:30',  1]
        ];
    }

    /**
     * @dataProvider calculationDataProvider
     * @param string    $date
     * @param string    $startTime
     * @param string    $endTime
     * @param string    $difference
     * @param int       $status
     */
    public function testCalculation(
        string $date,
        string $startTime,
        string $endTime,
        string $difference,
        int $status
    ): void {
        $config = new ConfigDtoStub();
        $calculationService = new TimeDifferenceCalculationService($config);

        $result = $calculationService->calculate($date, $startTime, $endTime);
        TestCase::assertSame($result, [
            'date' => $date,
            'endTime' => $endTime,
            'startTime' => $startTime,
            'difference' => $difference,
            'isBalanced' => $status,
        ]);
    }
}
