<?php

namespace Tracking\Repository;

use PDOException;
use SQLite3;
use Tracking\Dtos\WorkingDayDto;
use Tracking\Dtos\WorkingWeekDto;
use Tracking\Services\TimeCalculationService;

/**
 * @author <fatal.error.27@gmail.com>
 */
class WorkingTimeSQLiteRepository extends SQLiteRepositoryAbstract implements WorkingTimeRepository
{
    const DEFAULT_EMPTY_TIME = '00:00';

    /**
     * @return string
     */
    protected function getPath(): string
    {
        $year = date('Y');
        return static::DATABASE_DIRECTORY . "/{$year}_workingTimes.db";
    }

    protected function createDatabase(): void
    {
        $sql = new SQLite3($this->getPath());
        $sql->exec('CREATE TABLE IF NOT EXISTS WorkingTime(
              id                        INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
              weekNr                    INTEGER NOT NULL,
              weekday                   CHAR(15) NOT NULL,
              date                      DATE NOT NULL,
              workingStart              TIMESTAMP NOT NULL,
              workingEnd                TIMESTAMP NOT NULL,
              timeDifference            TIMESTAMP NOT NULL,
              timeDifferenceIsBalanced  INTEGER NOT NULL,
              workingMode               INTEGER NOT NULL
            )
        ');

        $sql->exec('CREATE UNIQUE INDEX idx_date_unique ON WorkingTime (date)');
        $sql->close();
    }

    /**
     * @return array
     */
    private function getAll(): array
    {
        $sql = new SQLite3($this->getPath(), SQLITE3_OPEN_READONLY);

        $stmt = $sql->prepare('SELECT * FROM WorkingTime');
        $result = @$stmt->execute();

        $entries = [];
        while($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $entries[] = $row;
        }

        $stmt->close();
        $sql->close();

        return $entries;
    }

    /**
     * @return WorkingWeekDto[]
     */
    public function getWorkingTimeList(): array
    {
        $entries = $this->getAll();
        return $this->mapEntriesToDto($entries);
    }

    /**
     * @param array $day
     * @return WorkingWeekDto[]
     * @throws PDOException
     */
    public function startWorking(array $day): array
    {
        $sql = new SQLite3($this->getPath(), SQLITE3_OPEN_READWRITE);

        $stmt = $sql->prepare('INSERT INTO WorkingTime (weekNr, weekday, date, workingStart, workingEnd, timeDifference, timeDifferenceIsBalanced, workingMode) VALUES (:weekNr, :weekday, :date, :workingStart, :workingEnd, :timeDifference, 0, :workingMode)');
        $stmt->bindValue(':weekNr', $day['weekNr'], SQLITE3_INTEGER);
        $stmt->bindValue(':weekday', $day['weekday'], SQLITE3_TEXT);
        $stmt->bindValue(':date', $day['date']);
        $stmt->bindValue(':workingStart', $day['workingStart']);
        $stmt->bindValue(':workingEnd', self::DEFAULT_EMPTY_TIME);
        $stmt->bindValue(':timeDifference', self::DEFAULT_EMPTY_TIME);
        $stmt->bindValue(':workingMode', WorkingModes::WORKING_MODE_DEFAULT, SQLITE3_INTEGER);

        if (false === @$stmt->execute()) {
            throw new PDOException('Double entry');
        }

        $stmt->close();
        $sql->close();

        return $this->getWorkingTimeList();
    }

    /**
     * @param array $day
     * @return WorkingWeekDto[]
     * @throws PDOException
     */
    public function endWorking(array $day): array
    {
        $sql = new SQLite3($this->getPath(), SQLITE3_OPEN_READWRITE);

        $stmt = $sql->prepare('UPDATE WorkingTime SET workingEnd = :workingEnd, timeDifference = :timeDifference, timeDifferenceIsBalanced = :timeDifferenceIsBalanced WHERE date = :date');
        $stmt->bindValue(':date', $day['date']);
        $stmt->bindValue(':workingEnd', $day['workingEnd']);
        $stmt->bindValue(':timeDifference', $day['timeDifference']);
        $stmt->bindValue(':timeDifferenceIsBalanced', $day['timeDifferenceIsBalanced'], SQLITE3_INTEGER);

        if (false === @$stmt->execute()) {
            throw new PDOException('No entry found');
        }

        $stmt->close();
        $sql->close();

        return $this->getWorkingTimeList();
    }

    /**
     * @param array $days
     * @param int   $mode
     * @return array
     * @throws PDOException
     */
    public function setNonWorkingNote(array $days, int $mode): array
    {
        $sql = new SQLite3($this->getPath(), SQLITE3_OPEN_READWRITE);

        foreach ($days as $day) {
            $stmt = $sql->prepare('INSERT INTO WorkingTime (weekNr, weekday, date, workingStart, workingEnd, timeDifference, timeDifferenceIsBalanced, workingMode) VALUES (:weekNr, :weekday, :date, :workingStart, :workingEnd, :timeDifference, 0, :workingMode)');
            $stmt->bindValue(':weekNr', $day['weekNr'], SQLITE3_INTEGER);
            $stmt->bindValue(':weekday', $day['weekday'], SQLITE3_TEXT);
            $stmt->bindValue(':date', $day['date']);
            $stmt->bindValue(':workingStart', self::DEFAULT_EMPTY_TIME);
            $stmt->bindValue(':workingEnd', self::DEFAULT_EMPTY_TIME);
            $stmt->bindValue(':timeDifference', self::DEFAULT_EMPTY_TIME);
            $stmt->bindValue(':workingMode', $mode, SQLITE3_INTEGER);

            if (false === @$stmt->execute()) {
                throw new PDOException('Double entry');
            }

            $stmt->close();
        }

        $sql->close();
        return $this->getWorkingTimeList();
    }

    /**
     * @param string $date
     * @return WorkingDayDto
     * @throws PDOException
     */
    public function getWorkingDayByDate(string $date): WorkingDayDto
    {
        $sql = new SQLite3($this->getPath(), SQLITE3_OPEN_READONLY);

        $stmt = $sql->prepare('SELECT * FROM WorkingTime WHERE date = :date');
        $stmt->bindValue(':date', $date);

        if (false === $workingDay = @$stmt->execute()->fetchArray(SQLITE3_ASSOC)) {
            throw new PDOException('No Entry found');
        }

        $stmt->close();
        $sql->close();

        return $this->mapWorkingDayToDto($workingDay);
    }

    /**
     * @param array $day
     * @throws PDOException
     */
    public function updateTimeDifferenceByDate(array $day): void
    {
        $sql = new SQLite3($this->getPath(), SQLITE3_OPEN_READWRITE);

        $stmt = $sql->prepare('UPDATE WorkingTime SET timeDifference = :timeDifference, timeDifferenceIsBalanced = :timeDifferenceIsBalanced WHERE date = :date');
        $stmt->bindValue(':date', $day['date']);
        $stmt->bindValue(':timeDifference', $day['difference']);
        $stmt->bindValue(':timeDifferenceIsBalanced', $day['isBalanced'], SQLITE3_INTEGER);

        if (false === @$stmt->execute()) {
            throw new PDOException('No entry found');
        }

        $stmt->close();
        $sql->close();
    }

    /**
     * @param array $day
     * @return array
     * @throws PDOException
     */
    public function updateWorkingDayByDate(array $day): array
    {
        $sql = new SQLite3($this->getPath(), SQLITE3_OPEN_READWRITE);

        $stmt = $sql->prepare('UPDATE WorkingTime SET workingStart = :workingStart, workingEnd = :workingEnd, timeDifference = :timeDifference, timeDifferenceIsBalanced = :timeDifferenceIsBalanced WHERE date = :date');
        $stmt->bindValue(':date', $day['date']);
        $stmt->bindValue(':workingStart', $day['workingStart']);
        $stmt->bindValue(':workingEnd', $day['workingEnd']);
        $stmt->bindValue(':timeDifference', $day['timeDifference']);
        $stmt->bindValue(':timeDifferenceIsBalanced', $day['timeDifferenceIsBalanced'], SQLITE3_INTEGER);

        if (false === @$stmt->execute()) {
            throw new PDOException('No entry found');
        }

        $stmt->close();
        $sql->close();

        return $this->getWorkingTimeList();
    }

    /**
     * @param array $entries
     * @return WorkingWeekDto[]
     */
    private function mapEntriesToDto(array $entries): array
    {
        $workingList = [];
        $workingDaysByWeek = [];

        foreach ($entries as $index => $workingDay) {
            $week = $workingDay['weekNr'];
            $workingDaysByWeek[$week][] = $workingDay;
        }

        /** @var array $workingWeek */
        foreach ($workingDaysByWeek as $weekNumber => $workingWeek) {
            $timeCalculationService = new TimeCalculationService();

            $workingWeekDto = new WorkingWeekDto();
            $workingWeekDto->weekNr = $weekNumber;

            foreach ($workingWeek as $index => $workingDay) {
                $workingDayDto = $this->mapWorkingDayToDto($workingDay);

                /**
                 * calculate week balance
                 */
                switch ($workingDayDto->timeDifferenceIsBalanced) {
                    case 1:
                        $timeCalculationService->add($workingDay['timeDifference']);
                        break;
                    case -1:
                        $timeCalculationService->sub($workingDay['timeDifference']);
                        break;
                    default:
                        break;
                }

                $workingWeekDto->workingDays[] = $workingDayDto;
            }

            $workingWeekDto->difference = $timeCalculationService->getFormattedResult();
            $workingWeekDto->differenceIsBalanced = $timeCalculationService->getBalanceStatus();

            $workingList[] = $workingWeekDto;
        }

        return $workingList;
    }

    /**
     * @param array $day
     * @return WorkingDayDto
     */
    private function mapWorkingDayToDto(array $day): WorkingDayDto
    {
        $dto = new WorkingDayDto();
        $dto->id = $day['id'];
        $dto->weekday = $day['weekday'];
        $dto->date = $day['date'];
        $dto->workingStart = $day['workingStart'];
        $dto->workingEnd = $day['workingEnd'];
        $dto->timeDifference = $day['timeDifference'];
        $dto->timeDifferenceIsBalanced = $day['timeDifferenceIsBalanced'];
        $dto->workingMode = $day['workingMode'];

        return $dto;
    }
}
