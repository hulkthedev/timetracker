<?php

namespace Tracking\Repository;

use Throwable;
use Tracking\Dtos\ConfigDto;

/**
 * @author  Alexej Beirith <alexej.beirith@arvato.com>
 */
class ConfigInMemoryExceptionRepository extends Spy implements ConfigRepository
{
    /** @var Throwable */
    private $throwable;

    /**
     * @param Throwable $throwable
     */
    public function __construct(Throwable $throwable)
    {
        $this->throwable = $throwable;
    }

    /**
     * @inheritdoc
     */
    public function getAll(): ConfigDto
    {
        $this->logRequest(__FUNCTION__, func_get_args());
        throw $this->throwable;
    }

    /**
     * @inheritdoc
     */
    public function update(array $data): ConfigDto
    {
        $this->logRequest(__FUNCTION__, func_get_args());
        throw $this->throwable;
    }
}
