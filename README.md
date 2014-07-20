StaticReview
============

[![Latest Stable Version](https://poser.pugx.org/sjparkinson/static-review/v/stable.svg)][packagist]
[![Build Status](https://travis-ci.org/sjparkinson/static-review.svg?branch=master)][travis]
[![Code Climate](http://img.shields.io/codeclimate/github/sjparkinson/static-review.svg)][codeclimate]
![Minimum PHP Version](http://img.shields.io/badge/php-%3E%3D%205.4-8892BF.svg)]

A modular pre-commit hook framework for static analysis of modified files.

![StaticReview Success Demo](http://i.imgur.com/2hicIEK.gif)

[travis]:      https://travis-ci.org/sjparkinson/static-review
[packagist]:   https://packagist.org/packages/sjparkinson/static-review
[codeclimate]: https://codeclimate.com/github/sjparkinson/static-review

## Usage

Here `~/project/` should be the path to your project.

Ensure when you create the symlink that you **do not** use relative paths.

```bash
$ git clone https://github.com/sjparkinson/static-review.git StaticReview

$ cd StaticReview/

$ composer install --no-dev --optimize-autoloader

$ ln -s ~/.../StaticReview/src/Hooks/php-pre-commit.php ~/project/.git/hooks/pre-commit

$ chmod a+x ~/project/.git/hooks/pre-commit
```

## Example Hook

Below is a basic hook that you can extend upon.

```php
#!/usr/bin/env php
<?php
// Autoload method that resolves the symlink.
$autoload = function() {
    $base = pathinfo(realpath(__FILE__), PATHINFO_DIRNAME);
    require_once strstr($base, 'src/Hooks', true) . 'vendor/autoload.php';
};

$autoload();

// Reference the required classes.
use StaticReview\StaticReview;
[...]

$reporter = new Reporter();
$review   = new StaticReview($reporter);

// Add any checks to the StaticReview instance, supports a fluent interface.
$review->addCheck(new PhpLintReview());

// Review the staged files.
$review->review(Helper::getGitStagedFiles());

// Check if any issues were found.
if ($reporter->hasIssues()) {
    // Exit with a non-zero to block the commit.
    exit(1);
}

// Exit with zero to allow the commit.
exit(0);
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
$ git clone https://github.com/sjparkinson/static-review.git StaticReview

$ cd StaticReview/

$ composer install --dev --optimize-autoloader

$ vendor/bin/phpunit --bootstrap vendor/autoload.php --coverage-text
```

## Licence

The content of this library is released under the **MIT License** by **Samuel Parkinson**.

You can find a copy of this licence in [`LICENCE`][licence] or at http://opensource.org/licenses/mit.

[licence]: https://github.com/sjparkinson/static-review/blob/master/LICENCE
