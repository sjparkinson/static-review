StaticReview
============

[![Latest Stable Version](https://poser.pugx.org/sjparkinson/static-review/v/stable.svg)][packagist]
[![Build Status](https://travis-ci.org/sjparkinson/static-review.svg?branch=master)][travis]
[![Minimum PHP Version](http://img.shields.io/badge/php-%3E%3D%205.4-8892BF.svg)][php]

An extendable framework for version control hooks.

![StaticReview Success Demo](http://i.imgur.com/8G3uORp.gif)

[travis]:    https://travis-ci.org/sjparkinson/static-review
[packagist]: https://packagist.org/packages/sjparkinson/static-review
[php]:       https://php.net/

## Usage

Within a [composer][composer] managed project you can simply do the following...

```bash
$ composer require sjparkinson/static-review:~1.0
```

You can then install a hooks to your project like so...

```bash
$ vendor/bin/static-review.php hook:install vendor/sjparkinson/static-review/hooks/example-pre-commit.php .git/hooks/pre-commit
```

Otherwise, if you don't use composer...

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
require_once file_exists(__DIR__ . '/../vendor/autoload.php')
   ? __DIR__ . '/../vendor/autoload.php'
   : __DIR__ . '/../../../autoload.php';

// Reference the required classes.
use StaticReview\StaticReview;
[...]

$reporter = new Reporter();
$review   = new StaticReview($reporter);

// Add any reviews to the StaticReview instance, supports a fluent interface.
$review->addReview(new LineEndingsReview);

$git = VersionControlFactory::build(VersionControlFactory::SYSTEM_GIT);

// Review the staged files.
$review->review($git->getStagedFiles());

// Check if any issues were found.
// Exit with a non-zero to block the commit.
($reporter->hasIssues()) ? exit(1) : exit(0);
```

## Example Check

```php
<?php
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
$ composer install --dev --optimize-autoloader
$ vendor/bin/phpunit
```

[vagrant]: https://www.vagrantup.com
[phpunit]: http://phpunit.de

## Licence

The content of this library is released under the [MIT License][licence] by [Samuel Parkinson][twitter].

[licence]: https://github.com/sjparkinson/static-review/blob/master/LICENCE.md
[twitter]: https://twitter.com/samparkinson_
