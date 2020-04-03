<?php

namespace Tracking\Usecase\Recalculation;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Tracking\Repository\ConfigInMemoryExceptionRepository;
use Tracking\Repository\ConfigInMemoryRepository;
use Tracking\Usecase\ResultCodes;

/**
 * @author <fatal.error.27@gmail.com>
 */
class RecalculateTimeDifferenceInRealTimeInteractorTest extends TestCase
{
    public function testExecute_ExpectRightCalculation(): void
    {
        $repository = new ConfigInMemoryRepository();
        $interactor = new RecalculateTimeDifferenceInRealTimeInteractor($repository);

        $request = $this->createRequest();
        $response = $interactor->execute($request);

        TestCase::assertEquals(ResultCodes::CODE_SUCCESS, $response->code);
        TestCase::assertEquals('04:00', $response->difference);
        TestCase::assertEquals(-1, $response->differenceIsBalanced);
    }

    public function testExecute_ExpectDefaultExceptionHandling(): void
    {
        $repository = new ConfigInMemoryExceptionRepository(new \Exception('UnitTest'));
        $interactor = new RecalculateTimeDifferenceInRealTimeInteractor($repository);

        $request = $this->createRequest();
        $response = $interactor->execute($request);

        TestCase::assertEquals(ResultCodes::CODE_ERROR_CAN_NOT_RECALCULATION_TIME_DIFFERENCE, $response->code);
        TestCase::assertNull($response->difference);
        TestCase::assertNull($response->differenceIsBalanced);
    }

    /**
     * @return Request
     */
    private function createRequest(): Request
    {
        return new Request([
            'workingDate' => '13.06.2018',
            'workingStartTime' => '08:00',
            'workingEndTime' => '12:00'
        ]);
    }
}
