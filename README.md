# CallbackFilterHandler for Monolog

This library is a Fork of [llaville/monolog-callbackfilterhandler](https://github.com/llaville/monolog-callbackfilterhandler) with updates for Monolog 3.

[![Latest Stable Version](https://poser.pugx.org/mimmi20/monolog-callbackfilterhandler/v/stable?format=flat-square)](https://packagist.org/packages/mimmi20/monolog-callbackfilterhandler)
[![Latest Unstable Version](https://poser.pugx.org/mimmi20/monolog-callbackfilterhandler/v/unstable?format=flat-square)](https://packagist.org/packages/mimmi20/monolog-callbackfilterhandler)
[![License](https://poser.pugx.org/mimmi20/monolog-callbackfilterhandler/license?format=flat-square)](https://packagist.org/packages/mimmi20/monolog-callbackfilterhandler)

## Code Status

[![codecov](https://codecov.io/gh/mimmi20/monolog-callbackfilterhandler/branch/master/graph/badge.svg)](https://codecov.io/gh/mimmi20/monolog-callbackfilterhandler)
[![Average time to resolve an issue](http://isitmaintained.com/badge/resolution/mimmi20/monolog-callbackfilterhandler.svg)](http://isitmaintained.com/project/mimmi20/monolog-callbackfilterhandler "Average time to resolve an issue")
[![Percentage of issues still open](http://isitmaintained.com/badge/open/mimmi20/monolog-callbackfilterhandler.svg)](http://isitmaintained.com/project/mimmi20/monolog-callbackfilterhandler "Percentage of issues still open")
[![Mutation testing badge](https://img.shields.io/endpoint?style=flat&url=https%3A%2F%2Fbadge-api.stryker-mutator.io%2Fgithub.com%2Fmimmi20%2Fmonolog-callbackfilterhandler%2Fmaster)](https://dashboard.stryker-mutator.io/reports/github.com/mimmi20/monolog-callbackfilterhandler/master)

## Requirements

This handler works with PHP 8.1 or above

## Installation

Run

```shell
composer require mimmi20/monolog-callbackfilterhandler
```

## Features

* This handler obey first to basic Monolog rules as `handler level` and `bubble`.
* Then, in second time, logs are filtered by rules defined in one or more callback functions.

Main difference with [FilterHandler](https://github.com/Seldaek/monolog/blob/master/src/Monolog/Handler/FilterHandler.php)
included in standard Monolog distribution since version 1.8.0

* `FilterHandler` can just filter records and only allow those of a given list of levels through to the wrapped handler.
* `CallbackFilterHandler` may filter records to the wrapped handler, on each standard record elements including extra data and logging context.

## Example

Here is a basic setup to log all events to a file and most important to another one (or notify by mail).
See [examples/basic.php](https://github.com/mimmi20/monolog-callbackfilterhandler/blob/master/examples/basic.php) file.

## License

This handler is licensed under the BSD-3-clauses License - see the [LICENSE](https://github.com/mimmi20/monolog-callbackfilterhandler/blob/master/LICENSE) file for details
