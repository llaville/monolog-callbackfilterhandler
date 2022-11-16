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

/**
 * Callback Filter Handler for Monolog.
 */

namespace Mimmi20\Monolog\Handler;

use Closure;
use Monolog\Handler\AbstractHandler;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\ProcessableHandlerInterface;
use Monolog\Handler\ProcessableHandlerTrait;
use Monolog\Level;
use Monolog\LogRecord;
use Monolog\ResettableInterface;
use Psr\Log\LogLevel;
use RuntimeException;

use function count;
use function json_encode;

/**
 * Monolog handler wrapper that filters records based on a list of callback functions.
 */
final class CallbackFilterHandler extends AbstractHandler implements ProcessableHandlerInterface
{
    use ProcessableHandlerTrait;

    /**
     * Filters Closure to restrict log records.
     *
     * @var Closure[]
     * @phpstan-var array<int|string, (Closure(LogRecord, int|string|Level): bool)>
     */
    protected array $filters;

    /**
     * @param Closure|HandlerInterface $handler handler or factory Closure($record, $this)
     * @param Closure[]                $filters A list of filters to apply
     * @param int|Level|string         $level   The minimum logging level at which this handler will be triggered
     * @param bool                     $bubble  Whether the messages that are handled can bubble up the stack or not
     * @phpstan-param (Closure(LogRecord|null, HandlerInterface): HandlerInterface)|HandlerInterface $handler
     * @phpstan-param value-of<Level::VALUES>|value-of<Level::NAMES>|Level|LogLevel::* $level
     *
     * @throws RuntimeException
     */
    public function __construct(
        private Closure | HandlerInterface $handler,
        array $filters,
        int | string | Level $level = Level::Debug,
        bool $bubble = true,
    ) {
        parent::__construct($level, $bubble);

        $this->filters = [];

        foreach ($filters as $filter) {
            if (!$filter instanceof Closure) {
                throw new RuntimeException(
                    'The given filter (' . json_encode($filter) . ') is not a Closure',
                );
            }

            $this->filters[] = $filter;
        }
    }

    /** @throws void */
    public function isHandling(LogRecord $record): bool
    {
        if (!parent::isHandling($record)) {
            return false;
        }

        if (isset($record->message)) {
            // when record is fulfilled, try each filter
            foreach ($this->filters as $filter) {
                if (!$filter($record, $this->level)) {
                    return false;
                }
            }
        }

        return true;
    }

    /** @throws RuntimeException */
    public function handle(LogRecord $record): bool
    {
        // The same logic as in FilterHandler

        if (!$this->isHandling($record)) {
            return false;
        }

        $record = $this->processRecord($record);

        $this->getHandler($record)->handle($record);

        return false === $this->bubble;
    }

    /**
     * @param LogRecord[] $records
     *
     * @throws RuntimeException
     */
    public function handleBatch(array $records): void
    {
        // The same logic as in FilterHandler
        $filtered = [];

        foreach ($records as $record) {
            if (!$this->isHandling($record)) {
                continue;
            }

            $filtered[] = $record;
        }

        if (0 >= count($filtered)) {
            return;
        }

        $this->getHandler($filtered[count($filtered) - 1])->handleBatch($filtered);
    }

    /**
     * Return the nested handler
     *
     * If the handler was provided as a factory, this will trigger the handler's instantiation.
     *
     * @throws RuntimeException
     */
    public function getHandler(LogRecord | null $record = null): HandlerInterface
    {
        // The same logic as in FingersCrossedHandler

        if (!$this->handler instanceof HandlerInterface) {
            $handler = ($this->handler)($record, $this);

            if (!$handler instanceof HandlerInterface) {
                throw new RuntimeException('The factory Closure should return a HandlerInterface');
            }

            $this->handler = $handler;
        }

        return $this->handler;
    }

    /** @throws RuntimeException */
    public function reset(): void
    {
        $this->resetProcessors();

        $handler = $this->getHandler();

        if (!($handler instanceof ResettableInterface)) {
            return;
        }

        $handler->reset();
    }
}
