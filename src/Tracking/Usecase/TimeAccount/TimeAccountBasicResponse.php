<?php

namespace Tracking\Usecase\TimeAccount;

use Tracking\Dtos\TimeAccountDto;

/**
 * @author <fatal.error.27@gmail.com>
 */
class TimeAccountBasicResponse
{
    /** @var int */
    public $code;

    /** @var TimeAccountDto */
    public $timeAccount;

    /**
     * @param int $code
     * @param TimeAccountDto|null $dto
     */
    public function __construct(int $code, TimeAccountDto $dto = null)
    {
        $this->code = $code;
        $this->timeAccount = $dto ?? new TimeAccountDto();
    }
}
