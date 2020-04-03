<?php

namespace Tracking\Usecase\Working;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Tracking\Repository\ConfigInMemoryRepository;
use Tracking\Repository\WorkingModes;
use Tracking\Repository\WorkingTimeInMemoryExceptionRepository;
use Tracking\Repository\WorkingTimeInMemoryRepository;
use Tracking\Usecase\ResultCodes;

/**
 * @author  <fatal.error.27@gmail.com>
 */
class StartWorkingInteractorTest extends TestCase
{
    /**
     * @return array
     */
    public function workingModeDataProvider(): array
    {
        $dayTimeParams = [
            'workingDate' => '11.10.2018',
            'workingTime' => '10:00'
        ];

        $dayParams = [
            'from' => '11.10.2018',
            'to' => '12.10.2018'
        ];

        return [
            [WorkingModes::WORKING_MODE_DEFAULT, $dayTimeParams],
            [WorkingModes::WORKING_MODE_SICK, $dayParams],
            [WorkingModes::WORKING_MODE_VACATION, $dayParams],
            [WorkingModes::WORKING_MODE_HOLIDAY, $dayParams],
            [WorkingModes::WORKING_MODE_OVERTIME, $dayParams],
        ];
    }

    /**
     * @dataProvider workingModeDataProvider
     * @param int $workingMode
     * @param array $params
     */
    public function testExecute_ExpectCode_CODE_SUCCESS(int $workingMode, array $params): void
    {
        $interactor = new StartWorkingInteractor(
            new WorkingTimeInMemoryRepository(),
            new ConfigInMemoryRepository()
        );

        $request = new Request(array_merge(['workingMode' => $workingMode], $params));
        $response = $interactor->execute($request);

        TestCase::assertEquals(ResultCodes::CODE_SUCCESS, $response->code);
        TestCase::assertCount(5, $response->list);
        TestCase::assertCount(3, $response->statistics);
    }

    /**
     * @dataProvider workingModeDataProvider
     * @param int $workingMode
     */
    public function testExecute_ExpectInvalidArgumentException(int $workingMode): void
    {
        $interactor = new StartWorkingInteractor(
            new WorkingTimeInMemoryRepository(),
            new ConfigInMemoryRepository()
        );

        $request = new Request(['workingMode' => $workingMode]);
        $response = $interactor->execute($request);

        TestCase::assertEquals(ResultCodes::CODE_ERROR_INVALID_ARGUMENTS, $response->code);
        TestCase::assertEmpty($response->list);
        TestCase::assertEmpty($response->statistics);
    }

    /**
     * @return array
     */
    public function exceptionDataProvider(): array
    {
        return [
            [new \PDOException(), ResultCodes::CODE_ERROR_DATABASE_DOUBLE_ENTRY],
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
        $interactor = new StartWorkingInteractor(
            new WorkingTimeInMemoryExceptionRepository($exception),
            new ConfigInMemoryRepository()
        );

        $request = new Request([
            'workingMode' => WorkingModes::WORKING_MODE_DEFAULT,
            'workingDate' => '11.10.2018',
            'workingTime' => '10:00'
        ]);

        $response = $interactor->execute($request);

        TestCase::assertEquals($expectedErrorCode, $response->code);
        TestCase::assertEmpty($response->list);
        TestCase::assertEmpty($response->statistics);
    }
}
