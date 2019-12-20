<?php

namespace Tracking\Usecase\Config;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Tracking\Repository\ConfigInMemoryExceptionRepository;
use Tracking\Repository\ConfigInMemoryRepository;
use Tracking\Usecase\ResultCodes;

/**
 * @author Alexej Beirith <alexej.beirith@arvato.com>
 */
class UpdateConfigInteractorTest extends TestCase
{
    public function testExecute_ExpectConfigDto(): void
    {
        $repository = new ConfigInMemoryRepository();
        $interactor = new UpdateConfigInteractor($repository);

        $request = new Request([
            'breakTimePerDay1' => '00:30',
            'breakTimePerDay2' => '00:00',
            'workingTimePerDay' => '08:00',
            'vacationDaysPerYear' => 30
        ]);

        $response = $interactor->execute($request);
        TestCase::assertEquals(ResultCodes::CODE_SUCCESS, $response->code);
    }

    /**
     * @return array
     */
    public function exceptionDataProvider(): array
    {
        return [
            [new \PDOException(), ResultCodes::CODE_ERROR_CAN_NOT_UPDATE_CONFIG],
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
        $interactor = new UpdateConfigInteractor($repository);

        $request = new Request([
            'breakTimePerDay1' => '00:30',
            'breakTimePerDay2' => '00:00',
            'workingTimePerDay' => '08:00',
            'vacationDaysPerYear' => 30
        ]);

        $response = $interactor->execute($request);
        TestCase::assertEquals($expectedErrorCode, $response->code);
    }

    /**
     * @return array
     */
    public function requestValidationParameter(): array
    {
        return [
            [new Request(['breakTimePerDay1' => 1, 'breakTimePerDay2' => 2, 'workingTimePerDay' => 3, 'vacationDaysPerYear' => 4]), ResultCodes::CODE_SUCCESS],
            [new Request(['breakTimePerDay1' => 1, 'breakTimePerDay2' => 2, 'workingTimePerDay' => 3]), ResultCodes::CODE_ERROR_INVALID_ARGUMENTS],
            [new Request(['breakTimePerDay1' => 1, 'breakTimePerDay2' => 2]), ResultCodes::CODE_ERROR_INVALID_ARGUMENTS],
            [new Request(['breakTimePerDay1' => 1]), ResultCodes::CODE_ERROR_INVALID_ARGUMENTS]
        ];
    }

    /**
     * @dataProvider requestValidationParameter
     *
     * @param Request   $request
     * @param int       $expectedErrorCode
     */
    public function testExecute_ExpectFailedValidation(Request $request, int $expectedErrorCode): void
    {
        $repository = new ConfigInMemoryRepository();
        $interactor = new UpdateConfigInteractor($repository);

        $response = $interactor->execute($request);
        TestCase::assertEquals($expectedErrorCode, $response->code);
    }
}
