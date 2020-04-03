<?php

namespace Tracking\Usecase\Working;

use Tracking\Usecase\ResultCodes;

/**
 * @author  <fatal.error.27@gmail.com>
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
