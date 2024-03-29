# Changelog

All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/),
using the [Keep a CHANGELOG](http://keepachangelog.com) principles.

## [Unreleased]

## [2.1.0] - 2022-07-11

<!-- MARKDOWN-RELEASE:START -->
### Added

- [PR#7](https://github.com/llaville/monolog-callbackfilterhandler/pull/7) : Allow LogLevel constants as HandlerLevel (thanks to @mimmi20)
<!-- MARKDOWN-RELEASE:END -->

## [2.0.0] - 2021-07-20

New major version to accept only Monolog v2

## Added

* a CHANGELOG to keep changes of this project
* [EditorConfig](https://editorconfig.org/) file
* PHPStan configuration file

## Changed

* README page project is now in Markdown format rather than AsciiDoc
* Directory structure `src/` simplified to follow [PSR-4](https://www.php-fig.org/psr/psr-4/) standard
* the handler class constructor has now a new argument `$level` the minimum logging level at which this handler will be triggered.
Do not consider using anymore the level of `$handler` first argument of class constructor
* [Allow Monolog 2.x](https://github.com/llaville/monolog-callbackfilterhandler/issues/5)

## 1.0.0 - 2015-04-21

First version of this handler for Monolog v1 that filters records based on a list of callback functions

[unreleased]: https://github.com/llaville/monolog-callbackfilterhandler/compare/2.1.0...HEAD
[2.1.0]: https://github.com/llaville/monolog-callbackfilterhandler/compare/2.0.0...2.1.0
[2.0.0]: https://github.com/llaville/monolog-callbackfilterhandler/compare/1.0.0...2.0.0
