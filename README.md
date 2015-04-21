Static-Review
=============

[![Latest Stable Version](http://img.shields.io/packagist/v/sjparkinson/static-review.svg?style=flat)][packagist]
[![Build Status](http://img.shields.io/travis/sjparkinson/static-review/master.svg?style=flat)][travis]
[![Minimum PHP Version](http://img.shields.io/badge/php-~5.4-8892BF.svg?style=flat)][php]

An extendible framework for version control hooks.

[travis]:    https://travis-ci.org/sjparkinson/static-review
[packagist]: https://packagist.org/packages/sjparkinson/static-review
[php]:       https://php.net/

## Installation

For a [composer][composer] managed project simply run the following ...

```bash
$ composer require sjparkinson/static-review
```

[composer]: https://getcomposer.org/

## Usage

```bash
$ vendor/bin/static-review
```

## Tests

```bash
$ git clone https://github.com/sjparkinson/static-review.git
$ cd static-review/
$ composer install
$ composer run-script test
```

## Licence

The content of this library is released under the [MIT License][license] by [Samuel Parkinson][twitter].

[license]: https://github.com/sjparkinson/static-review/blob/master/LICENSE
[twitter]: https://twitter.com/samparkinson_
