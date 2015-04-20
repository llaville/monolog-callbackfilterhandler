<?php

namespace Bartlett\Tests\Monolog\Handler;

use Bartlett\Monolog\Handler\CallbackFilterHandler;

use Monolog\Logger;
//use Monolog\Handler\TestHandler;

class CallbackFilterHandlerTest extends TestCase
{
    /**
     * Filter events on standard log level (without restriction).
     *
     * @covers Bartlett\Monolog\Handler\CallbackFilterHandler::isHandling
     * @dataProvider provideSuiteRecords
     */
    public function testIsHandling()
    {
        $record  = $this->formatRecord(func_get_args());
        $filters = array();
        $test    = new TestHandler();
        $handler = new CallbackFilterHandler($test, $filters);

        $this->assertTrue($handler->isHandling($record));
    }

    /**
     * Filter events on standard log level (greater or equal than WARNING).
     *
     * @covers Bartlett\Monolog\Handler\CallbackFilterHandler::isHandling
     * @dataProvider provideSuiteRecords
     */
    public function testIsHandlingLevel()
    {
        $record  = $this->formatRecord(func_get_args());
        $filters = array();
        $testlvl = Logger::WARNING;
        $test    = new TestHandler($testlvl);
        $handler = new CallbackFilterHandler($test, $filters);

        if ($record['level'] >= $testlvl) {
            $this->assertTrue($handler->isHandling($record));
        } else {
            $this->assertFalse($handler->isHandling($record));
        }
    }

    /**
     * Filter events only on levels needed (INFO and NOTICE).
     *
     * @covers Bartlett\Monolog\Handler\CallbackFilterHandler::handle
     * @dataProvider provideSuiteRecords
     */
    public function testHandleProcessOnlyNeededLevels()
    {
        $record  = $this->formatRecord(func_get_args());
        $filters = array(
            function ($record) {
                if ($record['level'] == Logger::INFO) {
                    return true;
                }
                if ($record['level'] == Logger::NOTICE) {
                    return true;
                }
                return false;
            }
        );
        $test    = new TestHandler();
        $handler = new CallbackFilterHandler($test, $filters);
        $handler->handle($record);

        $hasMethod = 'has' . ucfirst(strtolower($record['level_name']));

        if (in_array($record['level'], array(Logger::INFO, Logger::NOTICE))) {
            $this->assertTrue($test->{$hasMethod}($record, $record['level']));
        } else {
            $this->assertFalse($test->{$hasMethod}($record, $record['level']));
        }
    }

    /**
     * Filter events that matches all rules defined in filters.
     *
     * @covers Bartlett\Monolog\Handler\CallbackFilterHandler::handle
     * @dataProvider provideSuiteRecords
     */
    public function testHandleProcessAllMatchingRules()
    {
        $record  = $this->formatRecord(func_get_args());
        $filters = array(
            function ($record) {
                return ($record['level'] == Logger::NOTICE);
            },
            function ($record) {
                return (preg_match('/^sample of/', $record['message']) === 1);
            }
        );
        $test    = new TestHandler();
        $handler = new CallbackFilterHandler($test, $filters);
        $handler->handle($record);

        if ($record['level'] === Logger::NOTICE) {
            $this->assertTrue($test->hasNoticeThatContains($record['message']));
        } else {
            $this->assertFalse($test->hasNoticeThatContains($record['message']));
        }
    }

    /**
     * Filter events on batch mode.
     *
     * @covers Bartlett\Monolog\Handler\CallbackFilterHandler::handleBatch
     */
    public function testHandleBatch()
    {
        $filters = array(
            function ($record) {
                return ($record['level'] == Logger::INFO);
            },
            function ($record) {
                return (preg_match('/information/', $record['message']) === 1);
            }
        );
        $records = $this->getMultipleRecords();
        $test    = new TestHandler();
        $handler = new CallbackFilterHandler($test, $filters);
        $handler->handleBatch($records);

        $this->assertTrue($test->hasOnlyRecordsThatContains('information', Logger::INFO));
    }

    /**
     * @covers Bartlett\Monolog\Handler\CallbackFilterHandler::handle
     * @covers Bartlett\Monolog\Handler\CallbackFilterHandler::pushProcessor
     */
    public function testHandleUsesProcessors()
    {
        $filters = array(
            function ($record) {
                if ($record['level'] == Logger::DEBUG) {
                    return true;
                }
                if ($record['level'] == Logger::WARNING) {
                    return true;
                }
                return false;
            }
        );

        $test    = new TestHandler();
        $handler = new CallbackFilterHandler($test, $filters);
        $handler->pushProcessor(
            function ($record) {
                $record['extra']['foo'] = true;

                return $record;
            }
        );
        $handler->handle($this->getRecord(Logger::WARNING));
        $handler->handle($this->getRecord(Logger::ERROR));

        $this->assertTrue(
            $test->hasOnlyRecordsMatching(
                array(
                    'extra' => array('foo' => true),
                    'level' => Logger::WARNING
                )
            )
        );
    }

    /**
     * Filter events matching bubble feature.
     *
     * @covers Bartlett\Monolog\Handler\CallbackFilterHandler::handle
     * @dataProvider provideSuiteBubbleRecords
     */
    public function testHandleRespectsBubble()
    {
        $record  = $this->formatRecord(func_get_args());
        $filters = array(
            function ($record) {
                if ($record['level'] == Logger::INFO) {
                    return true;
                }
                if ($record['level'] == Logger::NOTICE) {
                    return true;
                }
                return false;
            }
        );
        $test = new TestHandler();

        foreach (array(false, true) as $bubble) {
            $handler = new CallbackFilterHandler($test, $filters, $bubble);

            if ($record['level'] == Logger::NOTICE && $bubble === false) {
                $this->assertTrue($handler->handle($record));
            } else {
                $this->assertFalse($handler->handle($record));
            }
        }
    }

    /**
     * Bad filter configuration.
     *
     * @expectedException \RuntimeException
     */
    public function testHandleWithBadFilterThrowsException()
    {
        $filters = array(false);
        $test    = new TestHandler();
        $handler = new CallbackFilterHandler($test, $filters);
    }
}
