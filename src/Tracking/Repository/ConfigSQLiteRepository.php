<?php

namespace Tracking\Repository;

use PDOException;
use SQLite3;
use Tracking\Dtos\ConfigDto;

/**
 * @author  Alexej Beirith <alexej.beirith@arvato.com>
 */
class ConfigSQLiteRepository extends SQLiteRepositoryAbstract implements ConfigRepository
{
    const DEFAULTS = [
        'WORKING_TIME_PER_DAY'              => '07:42',
        'BREAKING_TIME_PER_DAY_1'           => '00:30',
        'BREAKING_TIME_PER_DAY_2'           => '00:00',
        'VACATION_DAYS_PER_YEAR'            => 28,
        'TIME_ACCOUNT_BALANCE  '            => '00:00',
        'TIME_ACCOUNT_BALANCE_IS_BALANCED'  => 0,
    ];

    /**
     * @return string
     */
    protected function getPath(): string
    {
        return static::DATABASE_DIRECTORY . '/config.db';
    }

    protected function createDatabase(): void
    {
        $sql = new SQLite3($this->getPath());
        $sql->exec('CREATE TABLE IF NOT EXISTS Config(
              const CHAR(100) NOT NULL,
              value TEXT NOT NULL
            )
        ');

        $sql->exec('CREATE UNIQUE INDEX idx_key_unique ON Config (const)');
        $sql->close();

        $this->fillWithDefaultValues();
    }

    /**
     * @throws PDOException
     */
    private function fillWithDefaultValues(): void
    {
        $sql = new SQLite3($this->getPath(), SQLITE3_OPEN_READWRITE);

        foreach (static::DEFAULTS as $const => $value) {
            $stmt = $sql->prepare('INSERT INTO Config (const, value) VALUES (:const, :value)');
            $stmt->bindValue(':const', $const);
            $stmt->bindValue(':value', $value);

            if (false === @$stmt->execute()) {
                throw new PDOException('Double entry');
            }

            $stmt->close();
        }

        $sql->close();
    }

    /**
     * @param array $data
     *
     * @return ConfigDto
     *
     * @throws PDOException
     */
    public function update(array $data): ConfigDto
    {
        $sql = new SQLite3($this->getPath(), SQLITE3_OPEN_READWRITE);

        foreach ($data as $const => $value) {
            $stmt = $sql->prepare('UPDATE Config SET value = :value WHERE const = :const');
            $stmt->bindValue(':const', $const);
            $stmt->bindValue(':value', $value);

            if (false === @$stmt->execute()) {
                throw new PDOException('Can not update config');
            }

            $stmt->close();
        }

        $sql->close();
        return $this->getAll();
    }

    /**
     * @return ConfigDto
     *
     * @throws PDOException
     */
    public function getAll(): ConfigDto
    {
        $sql = new SQLite3($this->getPath(), SQLITE3_OPEN_READONLY);

        $stmt = $sql->prepare('SELECT * FROM Config');
        $result = @$stmt->execute();

        if (false === $result) {
            throw new PDOException('No Entries found');
        }

        $entries = [];
        while($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $entries[$row['const']] = $row['value'];
        }

        $stmt->close();
        $sql->close();

        return $this->mapConfigToDto($entries);
    }

    /**
     * @param array $config
     *
     * @return ConfigDto
     */
    private function mapConfigToDto(array $config): ConfigDto
    {
        $dto = new ConfigDto();

        foreach ($config as $key => $value) {
            if (!is_numeric($value)) {
                $dto->$key = $value;
            } else {
                $dto->$key = (int)$value;
            }
        }

        return $dto;
    }
}
