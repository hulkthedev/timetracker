<?php

namespace Tracking\Usecase\Working;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Tracking\Repository\ConfigInMemoryRepository;
use Tracking\Repository\WorkingTimeInMemoryExceptionRepository;
use Tracking\Repository\WorkingTimeInMemoryRepository;
use Tracking\Usecase\ResultCodes;

/**
 * @author Alexej Beirith <alexej.beirith@arvato.com>
 */
class GetWorkingDayInteractorTest extends TestCase
{
    public function testExecute_ExpectCode_CODE_SUCCESS(): void
    {
        $interactor = new GetWorkingDayInteractor(
            new WorkingTimeInMemoryRepository(),
            new ConfigInMemoryRepository()
        );

        $request = new Request(['date' => '13.10.2018']);
        $response = $interactor->execute($request);

        TestCase::assertEquals(ResultCodes::CODE_SUCCESS, $response->code);
        TestCase::assertObjectHasAttribute('id', $response->workingDay);
        TestCase::assertObjectHasAttribute('weekday', $response->workingDay);
        TestCase::assertObjectHasAttribute('date', $response->workingDay);
        TestCase::assertObjectHasAttribute('workingStart', $response->workingDay);
        TestCase::assertObjectHasAttribute('workingEnd', $response->workingDay);
        TestCase::assertObjectHasAttribute('workingMode', $response->workingDay);
        TestCase::assertObjectHasAttribute('timeDifference', $response->workingDay);
        TestCase::assertObjectHasAttribute('timeDifferenceIsBalanced', $response->workingDay);
    }

    public function testExecute_ExpectInvalidArgumentException(): void
    {
        $interactor = new GetWorkingDayInteractor(
            new WorkingTimeInMemoryRepository(),
            new ConfigInMemoryRepository()
        );

        $request = new Request([]);
        $response = $interactor->execute($request);

        TestCase::assertEquals(ResultCodes::CODE_ERROR_INVALID_ARGUMENTS, $response->code);
        TestCase::assertNull($response->workingDay);
    }

    /**
     * @return array
     */
    public function exceptionDataProvider(): array
    {
        return [
            [new \PDOException(), ResultCodes::CODE_ERROR_DATABASE_NO_ENTRY_FOUND],
            [new \Exception('', ResultCodes::CODE_ERROR_UNKNOWN), ResultCodes::CODE_ERROR_UNKNOWN]
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
        $interactor = new GetWorkingDayInteractor(
            new WorkingTimeInMemoryExceptionRepository($exception),
            new ConfigInMemoryRepository()
        );

        $request = new Request(['date' => '13.10.2018']);
        $response = $interactor->execute($request);

        TestCase::assertEquals($expectedErrorCode, $response->code);
        TestCase::assertNull($response->workingDay);
    }
}
