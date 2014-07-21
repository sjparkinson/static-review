StaticReview
============

[![Latest Stable Version](https://poser.pugx.org/sjparkinson/static-review/v/stable.svg)][packagist]
[![Build Status](https://travis-ci.org/sjparkinson/static-review.svg?branch=master)][travis]
![Minimum PHP Version](http://img.shields.io/badge/php-%3E%3D%205.4-8892BF.svg)

A version control hook framework built in PHP.

![StaticReview Success Demo](http://i.imgur.com/2hicIEK.gif)

[travis]:      https://travis-ci.org/sjparkinson/static-review
[packagist]:   https://packagist.org/packages/sjparkinson/static-review
[codeclimate]: https://codeclimate.com/github/sjparkinson/static-review

## Usage

Within a [composer][composer] managed project you can simply do the following.

```bash
$ composer require sjparkinson/static-review:~1.1

$ vendor/bin/static-review --hook example-pre-commit
```

Otherwise...

```bash
$ git clone https://github.com/sjparkinson/static-review.git
$ cd static-review/
$ composer install --no-dev --optimize-autoloader
$ ln -s ~/.../static-review/hooks/php-pre-commit.php ~/project/.git/hooks/pre-commit
$ chmod a+x ~/project/.git/hooks/pre-commit
```

[composer]: https://getcomposer.org/

## Example Hook

Below is a basic hook that you can extend upon.

```php
#!/usr/bin/env php
<?php
// StaticReview/hooks/example-pre-commit.php
$autoload = function($base) {
   require_once (file_exists($base . '/vendor/autoload.php'))
       ? $base . '/vendor/autoload.php'
       : realpath($base . '/../../autoload.php');
};

$autoload(realpath(__DIR__ . '/../'));

// Reference the required classes.
use StaticReview\StaticReview;
[...]

$reporter = new Reporter();
$review   = new StaticReview($reporter);

// Add any checks to the StaticReview instance, supports a fluent interface.
$review->addCheck(new LineEndingsReview());

// Review the staged files.
$review->review(Helper::getGitStagedFiles());

// Check if any issues were found.
// Exit with a non-zero to block the commit.
($reporter->hasIssues()) ? exit(1) : exit(0);
```

## Example Check

```php
<?php
class NoCommitTagReview extends AbstractReview
{
    /**
     * Review any text based file.
     *
     * @link http://stackoverflow.com/a/632786
     *
     * @param FileInterface $file
     * @return bool
     */
    public function canReview(FileInterface $file)
    {
        // return mime type ala mimetype extension
        $finfo = finfo_open(FILEINFO_MIME);

        //check to see if the mime-type starts with 'text'
        return substr(finfo_file($finfo, $file->getFileLocation()), 0, 4) == 'text';
    }

    /**
     * Checks if the file contains `NOCOMMIT`.
     *
     * @link http://stackoverflow.com/a/4749368
     */
    public function review(ReporterInterface $reporter, FileInterface $file)
    {
        $cmd = sprintf('grep -Fq "NOCOMMIT" %s', $file->getFileLocation());

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

The content of this library is released under the **MIT License** by **Samuel Parkinson**.

You can find a copy of this licence in [`LICENCE`][licence] or at http://opensource.org/licenses/mit.

[licence]: https://github.com/sjparkinson/static-review/blob/master/LICENCE
