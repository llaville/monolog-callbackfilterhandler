# CallbackFilterHandler for Monolog

| Stable |
|:------:|
| [![Latest Stable Version](https://img.shields.io/packagist/v/bartlett/monolog-callbackfilterhandler)](https://packagist.org/packages/bartlett/monolog-callbackfilterhandler) |
| [![Minimum PHP Version)](https://img.shields.io/packagist/php-v/bartlett/monolog-callbackfilterhandler)](https://php.net/) |
| [![Branch Master](https://img.shields.io/badge/branch-master-blue)](https://github.com/llaville/monolog-callbackfilterhandler) |
| [![Tests](https://github.com/llaville/php-compatinfo-db/workflows/Tests/badge.svg)](https://github.com/llaville/monolog-callbackfilterhandler/actions) |


## Requirements

This handler works with PHP 7.2 or above, use release 1.0.0 for PHP 5.3+ support

## Installation

The recommended way to install this library is [through composer](http://getcomposer.org).
If you don't know yet what is composer, have a look [on introduction](http://getcomposer.org/doc/00-intro.md).

```bash
composer require bartlett/monolog-callbackfilterhandler
```

## Support

- release 1.0.0 is not maintained except for bug report, and will accept only Monolog v1.
- release 2.0.0 is the current active version, and accept only Monolog v2

## Documentation

- release 1.0.0 is fully documented at <http://php5.laurent-laville.org/callbackfilterhandler/>

## Features

* This handler obey first to basic Monolog rules as `handler level` and `bubble`.
* Then, in second time, logs are filtered by rules defined in one or more callback functions.

Main difference with [FilterHandler](https://github.com/Seldaek/monolog/blob/master/src/Monolog/Handler/FilterHandler.php)
included in standard Monolog distribution since version 1.8.0

* `FilterHandler` can just filter records and only allow those of a given list of levels through to the wrapped handler.
* `CallbackFilterHandler` may filter records to the wrapped handler, on each standard record elements including extra data and logging context.

## Example

Here is a basic setup to log all events to a file and most important to another one (or notify by mail).
See [examples/basic.php](https://github.com/llaville/monolog-callbackfilterhandler/blob/master/examples/basic.php) file.

## Authors

* Laurent Laville (Lead Developer)
* Christophe Coevoet (suggested the code base on discussion of Monolog
[PR#411](https://github.com/Seldaek/monolog/pull/411#issuecomment-53413159))

## License

This handler is licensed under the BSD-3-clauses License - see the [LICENSE](https://github.com/llaville/monolog-callbackfilterhandler/blob/master/LICENSE) file for details
