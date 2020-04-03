<?php

namespace Tracking\Usecase\TimeAccount;

use Tracking\Dtos\TimeAccountDto;
use Tracking\Repository\ConfigRepository;
use Tracking\Repository\TimeAccountRepository;
use Tracking\Services\TimeCalculationService;
use Tracking\Usecase\ResultCodes;

/**
 * @author <fatal.error.27@gmail.com>
 */
class GetTimeAccountInteractor
{
    /** @var TimeAccountRepository */
    private $timeAccountRepository;

    /** @var ConfigRepository */
    private $configRepository;

    /**
     * @param TimeAccountRepository $timeAccountRepository
     * @param ConfigRepository $configRepository
     */
    public function __construct(TimeAccountRepository $timeAccountRepository, ConfigRepository $configRepository)
    {
        $this->timeAccountRepository = $timeAccountRepository;
        $this->configRepository = $configRepository;
    }

    /**
     * @return TimeAccountBasicResponse
     */
    public function execute(): TimeAccountBasicResponse
    {
        try {
            $timeAccountDto = $this->timeAccountRepository->getAll();

            $calculatedTimeAccountDto = $this->calculateTimeAccount($timeAccountDto);
            return new TimeAccountBasicResponse(ResultCodes::CODE_SUCCESS, $calculatedTimeAccountDto);
        } catch (\PDOException $exception) {
            return new TimeAccountBasicResponse(ResultCodes::CODE_ERROR_DATABASE_NO_ENTRY_FOUND);
        } catch (\Exception $exception) {
            return new TimeAccountBasicResponse($exception->getCode());
        }
    }

    /**
     * @param TimeAccountDto $timeAccountDto
     * @return TimeAccountDto
     */
    private function calculateTimeAccount(TimeAccountDto $timeAccountDto): TimeAccountDto
    {
        $config = $this->configRepository->getAll();

        $timeCalculationService = new TimeCalculationService();
        $timeCalculationService->add($config->TIME_ACCOUNT_BALANCE);

        foreach ($timeAccountDto->overtimeDto as $overtimeDto) {
            $timeCalculationService->sub($overtimeDto->overtime);
        }

        $timeAccountDto->overtimeLeft = $timeCalculationService->getFormattedResult();
        $timeAccountDto->overtimeIsBalanced = $timeCalculationService->getBalanceStatus();

        return $timeAccountDto;
    }
}
