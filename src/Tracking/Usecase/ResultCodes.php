<?php

namespace Tracking\Usecase;

/**
 * @codeCoverageIgnore
 * @author Alexej Beirith <alexej.beirith@arvato.com>
 */
class ResultCodes
{
    const CODE_SUCCESS = 0;

    /** Working Time */
    const CODE_ERROR_INVALID_ARGUMENTS = -1;
    const CODE_ERROR_DATABASE_DOUBLE_ENTRY = -2;
    const CODE_ERROR_DATABASE_NO_ENTRY_FOUND = -3;

    /** Recalculation */
    const CODE_ERROR_CAN_NOT_RECALCULATION_TIME_DIFFERENCE = -4;

    /** Config */
    const CODE_ERROR_CAN_NOT_UPDATE_CONFIG = -5;

    /** Unknown */
    const CODE_ERROR_UNKNOWN = -99;
}
