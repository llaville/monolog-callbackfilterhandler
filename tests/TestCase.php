<?php

namespace Bartlett\Tests\Monolog\Handler;

use Monolog\Logger;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * @return array Record
     */
    protected function getRecord($level = Logger::WARNING, $message = 'test', $context = array())
    {
        return array(
            'message' => $message,
            'context' => $context,
            'level' => $level,
            'level_name' => Logger::getLevelName($level),
            'channel' => 'test',
            'datetime' => \DateTime::createFromFormat('U.u', sprintf('%.6F', microtime(true))),
            'extra' => array(),
        );
    }

    /**
     * @return array
     */
    protected function getMultipleRecords()
    {
        return array(
            $this->getRecord(Logger::DEBUG, 'debug message 1'),
            $this->getRecord(Logger::DEBUG, 'debug message 2'),
            $this->getRecord(Logger::INFO, 'information'),
            $this->getRecord(Logger::WARNING, 'warning'),
            $this->getRecord(Logger::ERROR, 'error')
        );
    }

    /**
     * Format a record with all keys and values provided ($args).
     *
     * @param array $args Values of a log record
     *
     * @return array
     */
    protected function formatRecord($args)
    {
        $values = $args;
        $keys   = array(
            'message',
            'context',
            'level',
            'level_name',
            'channel',
            'datetime',
            'extra',
        );
        return array_combine($keys, $args);
    }

    /**
     * Data provider that produce a suite of records in level order.
     *
     * @return array
     * @see CallbackFilterHandlerTest::testIsHandling()
     * @see CallbackFilterHandlerTest::testIsHandlingLevel()
     * @see CallbackFilterHandlerTest::testHandleProcessOnlyNeededLevels()
     * @see CallbackFilterHandlerTest::testHandleProcessAllMatchingRules()
     */
    public function provideSuiteRecords()
    {
        $dataset = array();

        foreach (Logger::getLevels() as $level_name => $level_code) {
            $dataset[] = $this->getRecord($level_code, sprintf('sample of %s message', $level_name));
        }
        return $dataset;
    }

    /**
     * Data provider that produce a suite of records for bubble respect.
     *
     * @return array
     * @see CallbackFilterHandlerTest::testHandleRespectsBubble()
     */
    public function provideSuiteBubbleRecords()
    {
        return array(
            $this->getRecord(Logger::NOTICE),
            $this->getRecord(Logger::WARNING),
        );
    }
}
