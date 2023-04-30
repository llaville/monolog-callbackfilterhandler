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

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\If_\RemoveAlwaysTrueIfConditionRector;
use Rector\DeadCode\Rector\If_\RemoveDeadInstanceOfRector;
use Rector\DeadCode\Rector\StaticCall\RemoveParentCallWithoutParentRector;
use Rector\Php71\Rector\FuncCall\CountOnNullRector;
use Rector\Php80\Rector\FunctionLike\UnionTypesRector;
use Rector\Php81\Rector\Array_\FirstClassCallableRector;
use Rector\Php81\Rector\FuncCall\NullToStrictStringFuncCallArgRector;
use Rector\PHPUnit\Set\PHPUnitSetList;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ]);

    // register a single rule
    // $rectorConfig->rule(\Rector\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector\InlineConstructorDefaultToPropertyRector::class);

    $rectorConfig->sets([
        SetList::DEAD_CODE,
        LevelSetList::UP_TO_PHP_81,
        PHPUnitSetList::PHPUNIT_100,
    ]);

    $rectorConfig->skip(
        [
            UnionTypesRector::class,
            NullToStrictStringFuncCallArgRector::class,
            RemoveDeadInstanceOfRector::class,
            FirstClassCallableRector::class,
            RemoveAlwaysTrueIfConditionRector::class,
            RemoveParentCallWithoutParentRector::class,
            CountOnNullRector::class,
        ],
    );
};
