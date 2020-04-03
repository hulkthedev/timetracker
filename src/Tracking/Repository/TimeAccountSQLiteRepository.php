<?php

namespace Tracking\Repository;

use PDOException;
use SQLite3;
use Tracking\Dtos\OvertimeDto;
use Tracking\Dtos\TimeAccountDto;

/**
 * @author <fatal.error.27@gmail.com>
 */
class TimeAccountSQLiteRepository extends SQLiteRepositoryAbstract implements TimeAccountRepository
{
    /**
     * @return string
     */
    protected function getPath(): string
    {
        $year = date('Y');
        return static::DATABASE_DIRECTORY . "/{$year}_timeAccount.db";
    }

    protected function createDatabase(): void
    {
        $sql = new SQLite3($this->getPath());
        $sql->exec('CREATE TABLE IF NOT EXISTS TimeAccount(
              id                  INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
              workingDayId        INTEGER NOT NULL,
              overtime            TIMESTAMP NOT NULL,
              FOREIGN KEY(workingDayId) REFERENCES WorkingTime(id)
            )
        ');

        $sql->exec('CREATE UNIQUE INDEX idx_key_unique ON TimeAccount (workingDayId)');
        $sql->close();
    }

    /**
     * @return TimeAccountDto
     */
    public function getAll(): TimeAccountDto
    {
        $sql = new SQLite3($this->getPath(), SQLITE3_OPEN_READONLY);

        $stmt = $sql->prepare('SELECT * FROM TimeAccount');
        $result = @$stmt->execute();

        $entries = [];
        while($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $entries[] = $row;
        }

        $stmt->close();
        $sql->close();

        return $this->mapEntriesToDto($entries);
    }

    /**
     * @param array $data
     * @param int $id
     * @return TimeAccountDto
     * @throws PDOException
     */
    public function add(array $data, int $id): TimeAccountDto
    {
        $sql = new SQLite3($this->getPath(), SQLITE3_OPEN_READWRITE);

        $stmt = $sql->prepare('INSERT INTO TimeAccount (workingDayId, overtime) VALUES (:workingDayId, :overtime)');
        $stmt->bindValue(':workingDayId', $id, SQLITE3_INTEGER);
        $stmt->bindValue(':overtime', $data['overtime']);

        if (false === @$stmt->execute()) {
            throw new PDOException('Double entry');
        }

        $stmt->close();
        $sql->close();

        return $this->getAll();
    }

    /**
     * @param array $entries
     * @return TimeAccountDto
     */
    private function mapEntriesToDto(array $entries): TimeAccountDto
    {
        $timeAccountDto = new TimeAccountDto();

        foreach ($entries as $entry) {
            $overtimeDto = new OvertimeDto();
            $overtimeDto->id = $entry['id'];
            $overtimeDto->overtime = $entry['overtime'];
            $overtimeDto->workingDayId = $entry['workingDayId'];

            $timeAccountDto->overtimeDto[] = $overtimeDto;
        }

        return $timeAccountDto;
    }
}
