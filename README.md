Static-Review
=============

[![Latest Stable Version](http://img.shields.io/packagist/v/sjparkinson/static-review.svg?style=flat)][packagist]
[![Build Status](http://img.shields.io/travis/sjparkinson/static-review/master.svg?style=flat)][travis]
[![Minimum PHP Version](http://img.shields.io/badge/php-%3E%3D%205.4-8892BF.svg?style=flat)][php]

An extendable framework for version control hooks.

![StaticReview Success Demo](http://i.imgur.com/8G3uORp.gif)

[travis]:    https://travis-ci.org/sjparkinson/static-review
[packagist]: https://packagist.org/packages/sjparkinson/static-review
[php]:       https://php.net/

## Usage

For a [composer][composer] managed project you can simply run the following ...

```bash
$ composer require sjparkinson/static-review
```

Hooks can then be installed like so ...

```bash
$ vendor/bin/static-review.php hook:install vendor/sjparkinson/static-review/hooks/example-pre-commit.php .git/hooks/pre-commit
```

Otherwise, if you don't use composer ...

```bash
$ git clone https://github.com/sjparkinson/static-review.git
$ cd static-review/
$ composer install --no-dev --optimize-autoloader
$ bin/static-review.php hook:install hooks/example-pre-commit.php ~/.../.git/hooks/pre-commit
```

[composer]: https://getcomposer.org/

## Example Hook

Below is a basic hook that you can extend upon.

```php
#!/usr/bin/env php
<?php

include __DIR__ . '/../../../autoload.php';

// Reference the required classes.
use StaticReview\StaticReview;
[...]

$reporter = new Reporter();
$review   = new StaticReview($reporter);

// Add any reviews to the StaticReview instance, supports a fluent interface.
$review->addReview(new LineEndingsReview());

$git = new GitVersionControl();

// Review the staged files.
$review->review($git->getStagedFiles());

// Check if any issues were found.
// Exit with a non-zero status to block the commit.
($reporter->hasIssues()) ? exit(1) : exit(0);
```

## Example Review

```php
class NoCommitTagReview extends AbstractReview
{
    // Review any text based file.
    public function canReview(FileInterface $file)
    {
        $mime = $file->getMimeType();

        // check to see if the mime-type starts with 'text'
        return (substr($mime, 0, 4) === 'text');
    }

    // Checks if the file contains `NOCOMMIT`.
    public function review(ReporterInterface $reporter, FileInterface $file)
    {
        $cmd = sprintf('grep --fixed-strings --ignore-case --quiet "NOCOMMIT" %s', $file->getFullPath());

        $process = $this->getProcess($cmd);
        $process->run();

        if ($process->isSuccessful()) {
            $message = 'A NOCOMMIT tag was found';
            $reporter->error($message, $this, $file);
        }
    }
}
```

## Unit Tests

See [vagrantup.com][vagrant] and [phpunit.de][phpunit].

```bash
$ git clone https://github.com/sjparkinson/static-review.git
$ cd static-review/
$ vagrant up
$ vagrant ssh
...
$ cd /srv
$ composer update
$ composer run-script test
```

[vagrant]: https://www.vagrantup.com
[phpunit]: http://phpunit.de

## Licence

The content of this library is released under the [MIT License][license] by [Samuel Parkinson][twitter].

[license]: https://github.com/sjparkinson/static-review/blob/master/LICENSE
[twitter]: https://twitter.com/samparkinson_
