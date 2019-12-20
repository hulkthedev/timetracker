<?php

namespace Tracking\Usecase\Working;

use Tracking\Usecase\ResultCodes;

/**
 * @author  Alexej Beirith <alexej.beirith@arvato.com>
 */
class GetWorkingListInteractor extends WorkingBasic
{
    /**
     * @return WorkingBasicResponse
     */
    public function execute(): WorkingBasicResponse
    {
        try {
            $dtoList = $this->workingTimeRepository->getWorkingTimeList();
            return $this->getSuccessfullyResponse($dtoList);
        } catch (\PDOException $exception) {
            return new WorkingBasicResponse(ResultCodes::CODE_ERROR_DATABASE_NO_ENTRY_FOUND);
        } catch (\Exception $exception) {
            return new WorkingBasicResponse($exception->getCode());
        }
    }
}
