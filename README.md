StaticReview
============

[![Build Status](https://travis-ci.org/sjparkinson/static-review.svg?branch=master)](https://travis-ci.org/sjparkinson/static-review)
[![Latest Stable Version](https://poser.pugx.org/sjparkinson/static-review/v/stable.svg)](https://packagist.org/packages/sjparkinson/static-review)
[![License](https://poser.pugx.org/sjparkinson/static-review/license.svg)](https://packagist.org/packages/sjparkinson/static-review)

A modular pre-commit hook framework for static analysis of modified files.

### Successful Commit

![StaticReview Success Demo](http://i.imgur.com/2hicIEK.gif)

### Blocked Commit

![StaticReview Blocked Demo](http://i.imgur.com/oE66ZUC.gif)

## Usage

Here `~/project/` should be the path to your project.

```bash
git clone https://github.com/sjparkinson/static-review.git StaticReview

cd StaticReview/

composer install --no-dev --optimize-autoloader

ln -s ~/.../StaticReview/src/Hooks/php-pre-commit.php ~/project/.git/hooks/pre-commit

chmod a+x ~/project/.git/hooks/pre-commit
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

$review = new StaticReview($reporter);

// Add any checks to the StaticReview instance, supports a fluent interface.
$review->addCheck(new PhpLintCheck());

// Generate a FileCollection.
$files = Helper::getStagedFiles();

$review->review($files);

// Check if any matching issues were found.
if ($reporter->hasIssues()) {

    exit(1);

}

// Exit with zero to allow the commit.
exit(0);
```

### Example Check

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

## Tests

```bash
git clone https://github.com/sjparkinson/static-review.git StaticReview

cd StaticReview/

composer install --optimize-autoloader

vendor/bin/phpunit --bootstrap vendor/autoload.php tests
```

## License

The content of this library is released under the **MIT License** by **Samuel Parkinson**.

You can find a copy of this license in [`LICENSE`][license] or at http://opensource.org/licenses/mit.

[license]: /LICENSE
