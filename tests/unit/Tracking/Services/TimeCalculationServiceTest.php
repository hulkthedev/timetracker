<?php

namespace Tracking\Services;

use PHPUnit\Framework\TestCase;

/**
 * @author  Alexej Beirith <alexej.beirith@arvato.com>
 */
class TimeCalculationServiceTest extends TestCase
{
    /** @var TimeCalculationService */
    private $timeCalculationService;

    protected function setUp(): void
    {
        $this->timeCalculationService = new TimeCalculationService();
    }

    public function testGetMinutesFromTime(): void
    {
        TestCase::assertEquals(1, $this->timeCalculationService->getMinutesFromTime('00:01'));
        TestCase::assertEquals(65, $this->timeCalculationService->getMinutesFromTime('01:05'));
    }

    public function testGetTimeFromMinutes(): void
    {
        TestCase::assertEquals('01:05', $this->timeCalculationService->getTimeFromMinutes(65));
        TestCase::assertEquals('00:01', $this->timeCalculationService->getTimeFromMinutes(1));
    }

    public function testTimeCalculation_ExpectPositiveBalanceStatus(): void
    {
        $this->timeCalculationService->add('01:00')
            ->add('00:05')
            ->add('01:55');

        TestCase::assertEquals('03:00', $this->timeCalculationService->getFormattedResult());
        TestCase::assertEquals(1, $this->timeCalculationService->getBalanceStatus());
        TestCase::assertEquals(180, $this->timeCalculationService->getMinutes());
    }

    public function testTimeCalculation_ExpectNegativeBalanceStatus(): void
    {
        $this->timeCalculationService->sub('01:00')
            ->sub('00:05')
            ->sub('01:55');

        TestCase::assertEquals('03:00', $this->timeCalculationService->getFormattedResult());
        TestCase::assertEquals(-1, $this->timeCalculationService->getBalanceStatus());
        TestCase::assertEquals(180, $this->timeCalculationService->getMinutes());
    }

    public function testTimeCalculation_ExpectNeutralBalanceStatus(): void
    {
        $this->timeCalculationService->add('01:05')
            ->sub('01:05');

        TestCase::assertEquals('00:00', $this->timeCalculationService->getFormattedResult());
        TestCase::assertEquals(0, $this->timeCalculationService->getBalanceStatus());
        TestCase::assertEquals(0, $this->timeCalculationService->getMinutes());
    }
}
