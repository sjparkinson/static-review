static-review
=============

[![Latest Stable Version](http://img.shields.io/packagist/v/sjparkinson/static-review.svg?style=flat)][packagist]
[![Build Status](http://img.shields.io/travis/sjparkinson/static-review/master.svg?style=flat)][travis]
[![Minimum PHP Version](http://img.shields.io/badge/php-~5.5-8892BF.svg?style=flat)][php]

An extendible framework for version control hooks.

[travis]:    https://travis-ci.org/sjparkinson/static-review
[packagist]: https://packagist.org/packages/sjparkinson/static-review
[php]:       https://php.net/

## Installation

Using [composer][composer] you can install the tool [globally](https://getcomposer.org/doc/03-cli.md#global) with the following ...

```bash
$ composer global require sjparkinson/static-review
```

[composer]: https://getcomposer.org/

Or you can install the application as a phar:

```bash
$ curl -sS https://raw.githubusercontent.com/sjparkinson/static-review/5.0/static-review.phar > static-review.phar
$ chmod a+x static-review.phar && mv static-review.phar /usr/local/bin/static-review
```

## Usage

From the command line:

```bash
$ static-review [--config="..."] [path]
```

Or in `.git/hooks/pre-commit`:

```sh
#!/bin/bash
static-review --config ../../static-review.yml ../../src/
```

## Tests

```bash
$ git clone https://github.com/sjparkinson/static-review.git
$ cd static-review/
$ composer install
$ composer test
```

## License

The content of this library is released under the [MIT License][license] by [Samuel Parkinson][twitter].

[license]: https://github.com/sjparkinson/static-review/blob/master/LICENSE
[twitter]: https://twitter.com/samparkinson_
