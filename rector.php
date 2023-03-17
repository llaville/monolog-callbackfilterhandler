<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector;
use Rector\Config\RectorConfig;
use Rector\PHPUnit\Set\PHPUnitSetList;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__ . '/src',
    ]);

    // register a single rule
    //$rectorConfig->rule(InlineConstructorDefaultToPropertyRector::class);

    $rectorConfig->sets([
        SetList::DEAD_CODE,
        LevelSetList::UP_TO_PHP_81,
        PHPUnitSetList::PHPUNIT_100,
    ]);

    // define sets of rules
    //    $rectorConfig->sets([
    //        LevelSetList::UP_TO_PHP_81
    //    ]);

    $rectorConfig->skip([
        \Rector\DeadCode\Rector\If_\RemoveDeadInstanceOfRector::class => [
            __DIR__ . '/src/CallbackFilterHandler.php',
        ],
    ]);
};
