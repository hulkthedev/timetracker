<?php

namespace Tracking\Usecase\Working;

use PHPUnit\Framework\TestCase;
use Tracking\Repository\ConfigInMemoryRepository;
use Tracking\Repository\WorkingTimeInMemoryExceptionRepository;
use Tracking\Repository\WorkingTimeInMemoryRepository;
use Tracking\Usecase\ResultCodes;

/**
 * @author  <fatal.error.27@gmail.com>
 */
class GetWorkingListInteractorTest extends TestCase
{
    public function testExecute_ExpectCode_CODE_SUCCESS(): void
    {
        $interactor = new GetWorkingListInteractor(
            new WorkingTimeInMemoryRepository(),
            new ConfigInMemoryRepository()
        );

        $response = $interactor->execute();

        TestCase::assertEquals(ResultCodes::CODE_SUCCESS, $response->code);
        TestCase::assertCount(5, $response->list);
        TestCase::assertCount(3, $response->statistics);
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
     * @param \Throwable $exception
     * @param int $expectedErrorCode
     */
    public function testExceptionHandling(\Throwable $exception, int $expectedErrorCode): void
    {
        $interactor = new GetWorkingListInteractor(
            new WorkingTimeInMemoryExceptionRepository($exception),
            new ConfigInMemoryRepository()
        );

        $response = $interactor->execute();

        TestCase::assertEquals($expectedErrorCode, $response->code);
        TestCase::assertEmpty($response->list);
        TestCase::assertEmpty($response->statistics);
    }
}
