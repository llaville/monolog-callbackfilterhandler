<?php
/**
 * This file is part of the mimmi20/monolog-callbackfilterhandler package.
 *
 * Copyright (c) 2022, Thomas Mueller <mimmi20@live.de>
 * Copyright (c) 2015-2021, Laurent Laville <pear@laurent-laville.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Mimmi20\Monolog\Handler\Tests;

use DateTimeImmutable;
use Monolog\Level;
use Monolog\Logger;
use Monolog\LogRecord;
use Psr\Log\InvalidArgumentException;
use Psr\Log\LogLevel;

use function sprintf;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * Data provider that produce a suite of records in level order.
     *
     * @see CallbackFilterHandlerTest::testIsHandling()
     * @see CallbackFilterHandlerTest::testIsHandlingLevel()
     * @see CallbackFilterHandlerTest::testHandleProcessOnlyNeededLevels()
     * @see CallbackFilterHandlerTest::testHandleProcessAllMatchingRules()
     *
     * @return LogRecord[][]
     *
     * @throws InvalidArgumentException
     */
    public function provideSuiteRecords(): array
    {
        $dataset = [];

        foreach (Level::VALUES as $levelCode) {
            $level = Level::fromValue($levelCode);

            $dataset[] = [$this->getRecord($level, sprintf('sample of %s message', $level->getName()))];
        }

        return $dataset;
    }

    /**
     * Data provider that produce a suite of records for bubble respect.
     *
     * @see CallbackFilterHandlerTest::testHandleRespectsBubble()
     *
     * @return LogRecord[][]
     *
     * @throws InvalidArgumentException
     */
    public function provideSuiteBubbleRecords(): array
    {
        return [
            [$this->getRecord(Level::Notice)],
            [$this->getRecord()],
        ];
    }

    /**
     * @param array<mixed> $context
     * @phpstan-param value-of<Level::VALUES>|value-of<Level::NAMES>|Level|LogLevel::* $level
     *
     * @throws InvalidArgumentException
     */
    protected function getRecord(int | string | Level $level = Level::Warning, string $message = 'test', array $context = [], string $channel = 'test'): LogRecord
    {
        return new LogRecord(
            datetime: new DateTimeImmutable('now'),
            channel: $channel,
            level: Logger::toMonologLevel($level),
            message: $message,
            context: $context,
            extra: [],
        );
    }

    /**
     * @return LogRecord[]
     *
     * @throws InvalidArgumentException
     */
    protected function getMultipleRecords(): array
    {
        return [
            $this->getRecord(Level::Debug, 'debug message 1'),
            $this->getRecord(Level::Debug, 'debug message 2'),
            $this->getRecord(Level::Info, 'information'),
            $this->getRecord(Level::Warning, 'warning'),
            $this->getRecord(Level::Error, 'error'),
        ];
    }
}
