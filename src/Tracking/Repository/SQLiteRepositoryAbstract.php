<?php

namespace Tracking\Repository;

use Exception;

/**
 * @author <fatal.error.27@gmail.com>
 */
abstract class SQLiteRepositoryAbstract
{
    protected const DATABASE_DIRECTORY = __DIR__ . '/Databases';

    public function __construct()
    {
        try {
            $this->exists();
        } catch (Exception $exception) {
            $this->createDirectory();
            $this->createDatabase();
        }
    }

    /**
     * @throws Exception
     */
    private function exists(): void
    {
        if (!file_exists($this->getPath())) {
            throw new Exception('Database not found');
        }
    }

    protected function createDirectory(): void
    {
        if (!is_dir(static::DATABASE_DIRECTORY)) {
            mkdir(static::DATABASE_DIRECTORY);
        }
    }

    /**
     * @return string
     */
    abstract protected function getPath(): string;

    abstract protected function createDatabase(): void;
}
