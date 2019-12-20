<?php

namespace Tracking\Usecase\Working;

use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;
use Tracking\Usecase\ResultCodes;
use Tracking\Usecase\Working\WorkingBasic;

/**
 * @author  Alexej Beirith <alexej.beirith@arvato.com>
 */
class GetWorkingDayInteractor extends WorkingBasic
{
    /**
     * @param Request $request
     *
     * @return GetWorkingDayResponse
     */
    public function execute(Request $request): GetWorkingDayResponse
    {
        try {
            $this->validateRequest($request);

            $workingDay = $this->workingTimeRepository->getWorkingDayByDate($request->get('date'));

            $response = new GetWorkingDayResponse(ResultCodes::CODE_SUCCESS);
            $response->setWorkingDay($workingDay);

            return $response;
        } catch (InvalidArgumentException $exception) {
            return new GetWorkingDayResponse(ResultCodes::CODE_ERROR_INVALID_ARGUMENTS);
        } catch (\PDOException $exception) {
            return new GetWorkingDayResponse(ResultCodes::CODE_ERROR_DATABASE_NO_ENTRY_FOUND);
        } catch (\Exception $exception) {
            return new GetWorkingDayResponse($exception->getCode());
        }
    }

    /**
     * @param Request $request
     *
     * @throws InvalidArgumentException
     */
    private function validateRequest(Request $request): void
    {
        if (null == $request->get('date')) {
            throw new InvalidArgumentException('Required parameters are missing');
        }
    }
}
