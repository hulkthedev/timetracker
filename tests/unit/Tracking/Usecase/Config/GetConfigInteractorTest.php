<?php

namespace Tracking\Usecase\Config;

use PHPUnit\Framework\TestCase;
use Tracking\Repository\ConfigInMemoryExceptionRepository;
use Tracking\Repository\ConfigInMemoryRepository;
use Tracking\Usecase\ResultCodes;

/**
 * @author Alexej Beirith <alexej.beirith@arvato.com>
 */
class GetConfigInteractorTest extends TestCase
{
    public function testExecute_ExpectConfigDto(): void
    {
        $repository = new ConfigInMemoryRepository();
        $interactor = new GetConfigInteractor($repository);

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
        $repository = new ConfigInMemoryExceptionRepository($exception);
        $interactor = new GetConfigInteractor($repository);

        $response = $interactor->execute();
        TestCase::assertEquals($expectedErrorCode, $response->code);
    }
}
