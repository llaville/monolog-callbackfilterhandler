<?php
/**
 * This basic example will :
 * - log all events to a file called "my_logger.log"
 * - notify only (fake) error that have a 'exception' contextual data by mail
 * - WARNING: for demo, the NativeMailerHandler was replaced by a StreamHandler
 *
 * @author   Laurent Laville <pear@laurent-laville.org>
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD License
 * @since    Example available since Release 1.0.0
 */

$baseDir   = dirname(__DIR__);
$vendorDir = $baseDir . '/vendor';

$loader = require_once $vendorDir . '/autoload.php';
$loader->addPsr4('Bartlett\\', $baseDir . '/src/Bartlett');

use Bartlett\Monolog\Handler\CallbackFilterHandler;

use Monolog\Logger;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\NativeMailerHandler;
use Monolog\Handler\StreamHandler;

// Create the logger
$logger = new Logger('my_logger');

// Create filter rules
$filters = array(
    function ($record) {
        if (!array_key_exists('exception', $record['context'])) {
            return false;
        }
        return (preg_match('/fake error/', $record['message']) === 1);
    }
);

// Create some handlers
$stream = new RotatingFileHandler(__DIR__ . DIRECTORY_SEPARATOR . 'my_logger.log');
$stream->setFilenameFormat('{filename}-{date}', 'Ymd');

//$mailer = new NativeMailerHandler('user@example.org', 'dear user', 'receiver@example.org');
$mailer = new StreamHandler(__DIR__ . DIRECTORY_SEPARATOR . 'notifications.log', Logger::ERROR);

// add handlers to the logger
$logger->pushHandler($stream);
$logger->pushHandler(new CallbackFilterHandler($mailer, $filters));

// You can now use your logger
$logger->addInfo('My logger is now ready');

$logger->addError('A fake error has occured. Will be logged to file BUT NOT notified by mail.');

try {
    throw new \RuntimeException();

} catch (\Exception $e) {
    $logger->addCritical(
        'A fake error has occured. Will be logged to file AND notified by mail.',
        array('exception' => (string) $e)
    );
}
