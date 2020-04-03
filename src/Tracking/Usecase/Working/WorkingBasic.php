<?php

namespace Tracking\Usecase\Working;

use Tracking\Dtos\WorkingWeekDto;
use Tracking\Repository\ConfigRepository;
use Tracking\Repository\WorkingTimeRepository;
use Tracking\Services\AnalyticsCalculationService;
use Tracking\Usecase\ResultCodes;

/**
 * @author  <fatal.error.27@gmail.com>
 */
class WorkingBasic
{
    const DEFAULT_TIME_FORMAT = 'H:i';
    const DEFAULT_DATE_FORMAT = 'd.m.Y';

    /** @var WorkingTimeRepository */
    protected $workingTimeRepository;

    /** @var ConfigRepository */
    protected $configRepository;

    /**
     * @param WorkingTimeRepository $workingTimeRepository
     * @param ConfigRepository $configRepository
     */
    public function __construct(WorkingTimeRepository $workingTimeRepository, ConfigRepository $configRepository)
    {
        $this->workingTimeRepository = $workingTimeRepository;
        $this->configRepository = $configRepository;
    }

    /**
     * @param WorkingWeekDto[] $dtoList
     * @return WorkingBasicResponse
     */
    protected function getSuccessfullyResponse(array $dtoList): WorkingBasicResponse
    {
        $analyticCalculationService = new AnalyticsCalculationService($dtoList);

        $response = new WorkingBasicResponse(ResultCodes::CODE_SUCCESS);
        $response->setList($dtoList)
            ->addToStatistics('totalSickDays', $analyticCalculationService->countSickDays())
            ->addToStatistics('totalVacationDays', $analyticCalculationService->countVacationDays())
            ->addToStatistics('totalOvertimeDays', $analyticCalculationService->countOvertimeDays());

        return $response;
    }
}
