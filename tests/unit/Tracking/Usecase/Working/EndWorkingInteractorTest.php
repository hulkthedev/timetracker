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
class EndWorkingInteractorTest extends TestCase
{
    public function testExecute_ExpectCode_CODE_SUCCESS(): void
    {
        $repository = new WorkingTimeInMemoryRepository();
        $interactor = new EndWorkingInteractor($repository, new ConfigInMemoryRepository());

        $request = $this->createRequest();
        $response = $interactor->execute($request);

        TestCase::assertEquals(ResultCodes::CODE_SUCCESS, $response->code);
        TestCase::assertCount(5, $response->list);
        TestCase::assertCount(3, $response->statistics);

        $transmittedData = $repository->getLog('endWorking');
        TestCase::assertEquals('11.06.2018', $transmittedData['date']);
        TestCase::assertEquals('10:00', $transmittedData['workingStart']);
        TestCase::assertEquals('18:00', $transmittedData['workingEnd']);
        TestCase::assertEquals('00:30', $transmittedData['timeDifference']);
        TestCase::assertEquals(-1, $transmittedData['timeDifferenceIsBalanced']);
    }

    /**
     * @return array
     */
    public function requestValidationParameter(): array
    {
        return [
            [new Request(['workingDate' => 1, 'workingTime' => 2]), ResultCodes::CODE_SUCCESS],
            [new Request(['workingDate' => 1]), ResultCodes::CODE_ERROR_INVALID_ARGUMENTS],
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
        $interactor = new EndWorkingInteractor(
            new WorkingTimeInMemoryRepository(),
            new ConfigInMemoryRepository()
        );

        $response = $interactor->execute($request);

        TestCase::assertEquals($expectedErrorCode, $response->code);
        TestCase::assertEmpty($response->list);
        TestCase::assertEmpty($response->statistics);
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
        $interactor = new EndWorkingInteractor(
            new WorkingTimeInMemoryExceptionRepository($exception),
            new ConfigInMemoryRepository()
        );

        $request = $this->createRequest();
        $response = $interactor->execute($request);

        TestCase::assertEquals($expectedErrorCode, $response->code);
        TestCase::assertEmpty($response->list);
        TestCase::assertEmpty($response->statistics);
    }

    /**
     * @return Request
     */
    private function createRequest(): Request
    {
        return new Request([
            'workingDate' => '15.10.2018',
            'workingTime' => '18:00'
        ]);
    }
}