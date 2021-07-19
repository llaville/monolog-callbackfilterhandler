<?php declare(strict_types=1);

namespace Bartlett\Monolog\Handler\Tests;

use Monolog\Logger;
use Monolog\Handler\TestHandler as BaseTestHandler;

use function array_key_exists;
use function array_keys;
use function count;
use function strpos;

/**
 * Features included in dev-master branch but not yet released as a stable version
 * @see https://github.com/Seldaek/monolog/pull/529
 *
 * And 2 new features not yet proposed
 * @see hasOnlyRecordsThatContains()
 * @see hasOnlyRecordsMatching()
 */
class TestHandler extends BaseTestHandler
{
    public function hasEmergencyThatContains($message): bool
    {
        return $this->hasRecordThatContains($message, Logger::EMERGENCY);
    }

    public function hasAlertThatContains($message): bool
    {
        return $this->hasRecordThatContains($message, Logger::ALERT);
    }

    public function hasCriticalThatContains($message): bool
    {
        return $this->hasRecordThatContains($message, Logger::CRITICAL);
    }

    public function hasErrorThatContains($message): bool
    {
        return $this->hasRecordThatContains($message, Logger::ERROR);
    }

    public function hasWarningThatContains($message): bool
    {
        return $this->hasRecordThatContains($message, Logger::WARNING);
    }

    public function hasNoticeThatContains($message): bool
    {
        return $this->hasRecordThatContains($message, Logger::NOTICE);
    }

    public function hasInfoThatContains($message): bool
    {
        return $this->hasRecordThatContains($message, Logger::INFO);
    }

    public function hasDebugThatContains($message): bool
    {
        return $this->hasRecordThatContains($message, Logger::DEBUG);
    }

    public function hasRecordThatContains(string $message, $level): bool
    {
        if (!isset($this->recordsByLevel[$level])) {
            return false;
        }

        foreach ($this->recordsByLevel[$level] as $rec) {
            if (strpos($rec['message'], $message) !== false) {
                return true;
            }
        }

        return false;
    }

    // new feature not yet proposed
    public function hasOnlyRecordsThatContains($message, $level): bool
    {
        $levels = array_keys($this->recordsByLevel);

        if (count($levels) !== 1) {
            return false;
        }

        return $this->hasRecordThatContains($message, $level);
    }

    // new feature not yet proposed
    public function hasOnlyRecordsMatching($pattern): bool
    {
        foreach ($this->records as $record) {
            foreach (array_keys($pattern) as $key) {
                if (!array_key_exists($key, $record)) {
                    return false;
                }
                if ($record[$key] !== $pattern[$key]) {
                    return false;
                }
            }
        }
        return true;
    }
}
