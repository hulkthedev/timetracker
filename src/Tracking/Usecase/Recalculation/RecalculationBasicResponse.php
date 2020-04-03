<?php

namespace Tracking\Usecase\Recalculation;

/**
 * @author <fatal.error.27@gmail.com>
 */
class RecalculationBasicResponse
{
    /** @var int */
    public $code;

    /** @var string */
    public $difference;

    /** @var int */
    public $differenceIsBalanced;

    /**
     * @param int   $code
     */
    public function __construct(int $code)
    {
        $this->code = $code;
    }

    /**
     * @param string $difference
     */
    public function setDifference(string $difference): void
    {
        $this->difference = $difference;
    }

    /**
     * @param int $isBalanced
     */
    public function setIsDifferenceBalanced(int $isBalanced): void
    {
        $this->differenceIsBalanced = $isBalanced;
    }
}
