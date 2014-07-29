StaticReview
============

[![Latest Stable Version](https://poser.pugx.org/sjparkinson/static-review/v/stable.svg)][packagist]
[![Build Status](https://travis-ci.org/sjparkinson/static-review.svg?branch=master)][travis]
[![Test Coverage](http://img.shields.io/codeclimate/coverage/github/sjparkinson/static-review.svg)][codeclimate]
[![Minimum PHP Version](http://img.shields.io/badge/php-%3E%3D%205.4-8892BF.svg)][php]

An extendable framework for version control hooks.

![StaticReview Success Demo]i(http://i.imgur.com/8G3uORp.gif)

[travis]:      https://travis-ci.org/sjparkinson/static-review
[packagist]:   https://packagist.org/packages/sjparkinson/static-review
[codeclimate]: https://codeclimate.com/github/sjparkinson/static-review
[php]:         https://php.net/

## Usage

Within a [composer][composer] managed project you can simply do the following...

```bash
$ composer require sjparkinson/static-review:~1.1
$ vendor/bin/static-review.php list hook
```

Then you can add one of the hooks to your project like so...

```bash
$ vendor/bin/static-review.php hook:install example-pre-commit .git/hooks/pre-commit
```

Or if you just want to run a hook...

```bash
$ vendor/bin/static-review.php hook:run example-pre-commit
```

Otherwise, if you don't use composer...

```bash
$ git clone https://github.com/sjparkinson/static-review.git
$ cd static-review/
$ composer install --no-dev --optimize-autoloader
$ bin/static-review.php hook:install example-pre-commit ~/.../.git/hooks/pre-commit
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
        // return mime type ala mimetype extension
        $finfo = finfo_open(FILEINFO_MIME);

        //check to see if the mime-type starts with 'text'
        return substr(finfo_file($finfo, $file->getFullPath()), 0, 4) == 'text';
    }

    // Checks if the file contains `NOCOMMIT`.
    public function review(ReporterInterface $reporter, FileInterface $file)
    {
        $cmd = sprintf('grep -Fq "NOCOMMIT" %s', $file->getFullPath());

        $process = $this->getProcess($cmd);
        $process->run();

        if ($process->isSuccessful()) {
            $message = 'NOCOMMIT tag found';
            $reporter->error($message, $this, $file);
        }
    }
}
```

## Unit Tests

```bash
$ git clone https://github.com/sjparkinson/static-review.git
$ cd static-review/
$ composer install --dev --optimize-autoloader
$ vendor/bin/phpunit
```

## Licence

The content of this library is released under the [MIT License][licence] by [Samuel Parkinson][twitter].

[licence]: https://github.com/sjparkinson/static-review/blob/master/LICENCE.md
[twitter]: https://twitter.com/samparkinson_
