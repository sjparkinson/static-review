# static-review

__This package is abbandoned :rotating_light:.__

See [GrumPHP](https://github.com/phpro/grumphp) for a maintained alternative.

---

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

### Global Installation and Usage

The hooks can also be used for any project if you install `static-review` globally:

```bash
$ composer g require sjparkinson/static-review
```

Then, just install the hooks as you would normally but reference the global
installation path:

```bash
$ static-review.php hook:install ~/.composer/vendor/sjparkinson/static-review/hooks/static-review-commit-msg.php .git/hooks/commit-msg
```

This assumes you have set up [global composer paths][global-composer].

[global-composer]: https://getcomposer.org/doc/03-cli.md#global

## Example Hooks

Static Review can be used for both files and commit message review. Below are
basic hooks for each.

### For Files

```php
#!/usr/bin/env php
<?php

include __DIR__ . '/../../../autoload.php';

// Reference the required classes.
use StaticReview\StaticReview;
use StaticReview\Review\General\LineEndingsReview;
[...]

$reporter = new Reporter();
$review   = new StaticReview($reporter);

// Add any reviews to the StaticReview instance, supports a fluent interface.
$review->addReview(new LineEndingsReview());

$git = new GitVersionControl();

// Review the staged files.
$review->files($git->getStagedFiles());

// Check if any issues were found.
// Exit with a non-zero status to block the commit.
($reporter->hasIssues()) ? exit(1) : exit(0);
```

### For Commit Messages

```php
#!/usr/bin/env php
<?php

include __DIR__ . '/../../../autoload.php';

// Reference the required classes.
use StaticReview\StaticReview;
use StaticReview\Review\Message\BodyLineLengthReview;
[...]

$reporter = new Reporter();
$review   = new StaticReview($reporter);

// Add any reviews to the StaticReview instance, supports a fluent interface.
$review->addReview(new BodyLineLengthReview());

$git = new GitVersionControl();

// Review the current commit message.
// The hook is passed the file holding the commit message as the first argument.
$review->message($git->getCommitMessage($argv[1]));

// Check if any issues were found.
// Exit with a non-zero status to block the commit.
($reporter->hasIssues()) ? exit(1) : exit(0);
```

## Example Review For Files

```php
class NoCommitTagReview extends AbstractFileReview
{
    // Review any text based file.
    public function canReviewFile(FileInterface $file)
    {
        $mime = $file->getMimeType();

        // check to see if the mime-type starts with 'text'
        return (substr($mime, 0, 4) === 'text');
    }

    // Checks if the file contains `NOCOMMIT`.
    public function review(ReporterInterface $reporter, ReviewableInterface $file)
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

## Example Review For Messages

```php
class WorkInProgressReview extends AbstractMessageReview
{
    // Check if the commit message contains "wip"
    public function review(ReporterInterface $reporter, ReviewableInterface $commit)
    {
        $fulltext = $commit->getSubject() . PHP_EOL . $commit->getBody();

        if (preg_match('/\bwip\b/i', $fulltext)) {
            $message = 'Do not commit WIP to shared branches';
            $reporter->error($message, $this, $commit);
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
$ composer test
```

[vagrant]: https://www.vagrantup.com
[phpunit]: http://phpunit.de

## Licence

The content of this library is released under the [MIT License][license] by [Samuel Parkinson][twitter].

[license]: https://github.com/sjparkinson/static-review/blob/master/LICENSE
[twitter]: https://twitter.com/samparkinson_
