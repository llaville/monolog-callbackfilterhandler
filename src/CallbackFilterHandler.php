<?php declare(strict_types=1);
/**
 * Callback Filter Handler for Monolog.
 *
 * @category Logging
 * @package  monolog-callbackfilterhandler
 * @author   Laurent Laville <pear@laurent-laville.org>
 * @author   Christophe Coevoet
 * @license  http://www.opensource.org/licenses/bsd-license.php  BSD License
 */

namespace Bartlett\Monolog\Handler;

use Monolog\Handler\AbstractHandler;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\ProcessableHandlerInterface;
use Monolog\Logger;

use RuntimeException;
use function array_shift;
use function array_unshift;
use function is_callable;
use function json_encode;

/**
 * Monolog handler wrapper that filters records based on a list of callback functions.
 *
 * @category Logging
 * @package  monolog-callbackfilterhandler
 * @author   Laurent Laville <pear@laurent-laville.org>
 * @author   Christophe Coevoet
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD License
 * @since    Class available since Release 1.0.0
 */
class CallbackFilterHandler extends AbstractHandler implements ProcessableHandlerInterface
{
    /**
     * Handler or factory callable($record, $this)
     *
     * @var callable|HandlerInterface
     */
    protected $handler;

    /**
     * @var int
     */
    protected $handlerLevel;

    /**
     * Filters callable to restrict log records.
     *
     * @var callable[]
     */
    protected $filters;

    /**
     * Changes to apply on log records, by a stack of callable
     *
     * @var callable[]
     */
    protected $processors;

    /**
     * @param callable|HandlerInterface $handler Handler or factory callable($record, $this).
     * @param callable[]                $filters A list of filters to apply
     * @param int|string                $level   The minimum logging level at which this handler will be triggered
     * @param boolean                   $bubble  Whether the messages that are handled can bubble up the stack or not
     */
    public function __construct($handler, array $filters, $level= Logger::DEBUG, bool $bubble = true)
    {
        if (!$handler instanceof HandlerInterface) {
            if (!is_callable($handler)) {
                throw new RuntimeException(
                    "The given handler (" . json_encode($handler)
                    . ") is not a callable nor a Monolog\\Handler\\HandlerInterface object"
                );
            }
        }

        $this->handlerLevel = $level;
        parent::__construct($level, $bubble);

        $this->handler = $handler;
        $this->filters = [];
        $this->processors = [];

        foreach ($filters as $filter) {
            if (!is_callable($filter)) {
                throw new RuntimeException(
                    "The given filter (" . json_encode($filter)
                    . ") is not a callable object"
                );
            }
            $this->filters[] = $filter;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function pushProcessor(callable $callback): HandlerInterface
    {
        array_unshift($this->processors, $callback);
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function popProcessor(): callable
    {
        return array_shift($this->processors);
    }

    /**
     * {@inheritdoc}
     */
    public function isHandling(array $record): bool
    {
        if ($record['level'] < $this->handlerLevel) {
            return false;
        }

        if (isset($record['message'])) {
            // when record is full filled, try each filter
            foreach ($this->filters as $filter) {
                if (!$filter($record, $this->handlerLevel)) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(array $record): bool
    {
        if (!$this->isHandling($record)) {
            return false;
        }

        // The same logic as in FingersCrossedHandler
        if (!$this->handler instanceof HandlerInterface) {
            $this->handler = ($this->handler)($record, $this);
            if (!$this->handler instanceof HandlerInterface) {
                throw new RuntimeException("The factory callable should return a HandlerInterface");
            }
        }

        if ($this->processors) {
            foreach ($this->processors as $processor) {
                $record = ($processor)($record);
            }
        }

        $this->handler->handle($record);

        return false === $this->bubble;
    }

    /**
     * {@inheritdoc}
     */
    public function handleBatch(array $records): void
    {
        $filtered = [];
        foreach ($records as $record) {
            if ($this->isHandling($record)) {
                $filtered[] = $record;
            }
        }

        $this->handler->handleBatch($filtered);
    }
}
