<?php
/**
 * This file is part of the mimmi20/monolog-callbackfilterhandler package.
 *
 * Copyright (c) 2022-2023, Thomas Mueller <mimmi20@live.de>
 * Copyright (c) 2015-2021, Laurent Laville <pear@laurent-laville.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Mimmi20\Monolog\Handler\Tests;

use Monolog\Handler\TestHandler as BaseTestHandler;
use Monolog\Level;

use function array_keys;
use function count;
use function property_exists;
use function str_contains;

/**
 * Features included in dev-master branch but not yet released as a stable version
 *
 * @see https://github.com/Seldaek/monolog/pull/529
 *
 * And 2 new features not yet proposed
 * @see hasOnlyRecordsThatContains()
 * @see hasOnlyRecordsMatching()
 */
final class TestHandler extends BaseTestHandler
{
    /** @throws void */
    public function hasEmergencyThatContains(string $message): bool
    {
        return $this->hasRecordThatContains($message, Level::Emergency);
    }

    /** @throws void */
    public function hasAlertThatContains(string $message): bool
    {
        return $this->hasRecordThatContains($message, Level::Alert);
    }

    /** @throws void */
    public function hasCriticalThatContains(string $message): bool
    {
        return $this->hasRecordThatContains($message, Level::Critical);
    }

    /** @throws void */
    public function hasErrorThatContains(string $message): bool
    {
        return $this->hasRecordThatContains($message, Level::Error);
    }

    /** @throws void */
    public function hasWarningThatContains(string $message): bool
    {
        return $this->hasRecordThatContains($message, Level::Warning);
    }

    /** @throws void */
    public function hasNoticeThatContains(string $message): bool
    {
        return $this->hasRecordThatContains($message, Level::Notice);
    }

    /** @throws void */
    public function hasInfoThatContains(string $message): bool
    {
        return $this->hasRecordThatContains($message, Level::Info);
    }

    /** @throws void */
    public function hasDebugThatContains(string $message): bool
    {
        return $this->hasRecordThatContains($message, Level::Debug);
    }

    /** @throws void */
    public function hasRecordThatContains(string $message, Level $level): bool
    {
        if (!isset($this->recordsByLevel[$level->value])) {
            return false;
        }

        foreach ($this->recordsByLevel[$level->value] as $rec) {
            if (str_contains($rec->message, $message)) {
                return true;
            }
        }

        return false;
    }

    /**
     * new feature not yet proposed
     *
     * @throws void
     */
    public function hasOnlyRecordsThatContains(string $message, Level $level): bool
    {
        $levels = array_keys($this->recordsByLevel);

        if (count($levels) !== 1) {
            return false;
        }

        return $this->hasRecordThatContains($message, $level);
    }

    // new feature not yet proposed

    /**
     * new feature not yet proposed
     *
     * @param array<string, mixed> $pattern
     *
     * @throws void
     */
    public function hasOnlyRecordsMatching(array $pattern): bool
    {
        foreach ($this->records as $record) {
            foreach (array_keys($pattern) as $key) {
                if (!property_exists($record, $key)) {
                    return false;
                }

                if ($record->{$key} !== $pattern[$key]) {
                    return false;
                }
            }
        }

        return true;
    }
}
