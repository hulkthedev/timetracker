<?php

namespace Tracking\Usecase\Recalculation;

use PHPUnit\Framework\TestCase;
use Tracking\Repository\ConfigInMemoryExceptionRepository;
use Tracking\Repository\ConfigInMemoryRepository;
use Tracking\Repository\WorkingTimeInMemoryRepository;
use Tracking\Usecase\ResultCodes;

/**
 * @author Alexej Beirith <alexej.beirith@arvato.com>
 */
class RecalculateTimeDifferenceInteractorTest extends TestCase
{
    public function testExecute_ExpectRightCalculation(): void
    {
        $interactor = new RecalculateTimeDifferenceInteractor(
            new WorkingTimeInMemoryRepository(),
            new ConfigInMemoryRepository()
        );

        $response = $interactor->execute();
        TestCase::assertEquals(ResultCodes::CODE_SUCCESS, $response->code);
    }

    /**
     * @return array
     */
    public function exceptionDataProvider(): array
    {
        return [
            [new \PDOException(), ResultCodes::CODE_ERROR_DATABASE_NO_ENTRY_FOUND],
            [new \Exception('', ResultCodes::CODE_ERROR_CAN_NOT_RECALCULATION_TIME_DIFFERENCE), ResultCodes::CODE_ERROR_CAN_NOT_RECALCULATION_TIME_DIFFERENCE]
        ];
    }

    /**
     * @dataProvider exceptionDataProvider
     *
     * @param \Throwable    $exception
     * @param int           $expectedErrorCode
     */
    public function testExceptionHandling(\Throwable $exception, int $expectedErrorCode): void
    {
        $interactor = new RecalculateTimeDifferenceInteractor(
            new WorkingTimeInMemoryRepository(),
            new ConfigInMemoryExceptionRepository($exception)
        );

        $response = $interactor->execute();
        TestCase::assertEquals($expectedErrorCode, $response->code);
    }
}
