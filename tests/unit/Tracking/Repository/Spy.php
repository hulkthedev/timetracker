<?php

namespace Tracking\Repository;

/**
 * @author <fatal.error.27@gmail.com>
 */
class Spy
{
    /** @var array */
    private $logger = [];

    /**
     * @param string $functionName
     * @param mixed $args
     */
    protected function logRequest(string $functionName, $args = 'not set'): void
    {
        if (isset($this->logger[$functionName])) {
            $this->logger[$functionName][] = $args;
        } else {
            $this->logger[$functionName] = $args;
        }
    }

    /**
     * @param string $functionName
     * @return array
     */
    public function getLog(string $functionName = ''): array
    {
        return !empty($functionName) && !empty($this->logger[$functionName])
            ? reset($this->logger[$functionName])
            : $this->logger;
    }
}
