<?php

namespace Bartlett\Tests\Monolog\Handler;

use Monolog\Logger;
use Monolog\Handler\TestHandler as BaseTestHandler;

/**
 * Features included in dev-master branch but not yet released as a stable version
 * @see
 *
 * And 2 new features not yet proposed
 */
class TestHandler extends BaseTestHandler
{
    public function hasEmergencyThatContains($message)
    {
        return $this->hasRecordThatContains($message, Logger::EMERGENCY);
    }

    public function hasAlertThatContains($message)
    {
        return $this->hasRecordThatContains($message, Logger::ALERT);
    }

    public function hasCriticalThatContains($message)
    {
        return $this->hasRecordThatContains($message, Logger::CRITICAL);
    }

    public function hasErrorThatContains($message)
    {
        return $this->hasRecordThatContains($message, Logger::ERROR);
    }

    public function hasWarningThatContains($message)
    {
        return $this->hasRecordThatContains($message, Logger::WARNING);
    }

    public function hasNoticeThatContains($message)
    {
        return $this->hasRecordThatContains($message, Logger::NOTICE);
    }

    public function hasInfoThatContains($message)
    {
        return $this->hasRecordThatContains($message, Logger::INFO);
    }

    public function hasDebugThatContains($message)
    {
        return $this->hasRecordThatContains($message, Logger::DEBUG);
    }

    public function hasRecordThatContains($message, $level)
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
    public function hasOnlyRecordsThatContains($message, $level)
    {
        $levels = array_keys($this->recordsByLevel);

        if (count($levels) !== 1) {
            return false;
        }

        return $this->hasRecordThatContains($message, $level);
    }

    // new feature not yet proposed
    public function hasOnlyRecordsMatching($pattern)
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
