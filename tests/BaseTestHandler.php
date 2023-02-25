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

use Monolog\Handler\Handler;
use Monolog\LogRecord;

/**
 * Features included in dev-master branch but not yet released as a stable version
 *
 * @see https://github.com/Seldaek/monolog/pull/529
 *
 * And 2 new features not yet proposed
 * @see hasOnlyRecordsThatContains()
 * @see hasOnlyRecordsMatching()
 */
abstract class BaseTestHandler extends Handler
{
    /**
     * @throws void
     *
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
     */
    public function isHandling(LogRecord $record): bool
    {
        return false;
    }

    /**
     * @throws void
     *
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
     */
    public function handle(LogRecord $record): bool
    {
        return false;
    }

    /** @throws void */
    public function reset(): void
    {
        // do nothing
    }
}
