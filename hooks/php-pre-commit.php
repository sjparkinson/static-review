#!/usr/bin/env php
<?php
/*
 * This file is part of StaticReview
 *
 * Copyright (c) 2014 Samuel Parkinson <@samparkinson_>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see http://github.com/sjparkinson/static-review/blob/master/LICENSE.md
 */
$included = include file_exists(__DIR__ . '/../vendor/autoload.php')
    ? __DIR__ . '/../vendor/autoload.php'
    : __DIR__ . '/../../../autoload.php';

if (! $included) {
    echo 'You must set up the project dependencies, run the following commands:' . PHP_EOL
         . 'curl -sS https://getcomposer.org/installer | php' . PHP_EOL
         . 'php composer.phar install' . PHP_EOL;

    exit(1);
}

use StaticReview\StaticReview;
use StaticReview\Helper;
use StaticReview\Reporter\Reporter;
use StaticReview\VersionControl\GitVersionControl;

// Reference the reviews you want to use.
use StaticReview\Review\General\LineEndingsReview;
use StaticReview\Review\General\NoCommitTagReview;
use StaticReview\Review\PHP\PhpLeadingLineReview;
use StaticReview\Review\PHP\PhpLintReview;
use StaticReview\Review\Composer\ComposerLintReview;

$reporter = new Reporter();

$review = new StaticReview($reporter);

// Add any reviews to the StaticReview instance, supports a fluent interface.
$review->addReview(new LineEndingsReview)
       ->addReview(new PhpLeadingLineReview)
       ->addReview(new NoCommitTagReview)
       ->addReview(new PhpLintReview)
       ->addReview(new ComposerLintReview);

$git = new GitVersionControl();

// Review the staged files.
$review->review($git->getStagedFiles());

// Check if any matching issues were found.
if ($reporter->hasIssues()) {

    echo PHP_EOL . PHP_EOL;

    foreach($reporter->getIssues() as $issue) {
        echo Helper::getColourString($issue, $issue->getColour()) . PHP_EOL;
    }

    echo PHP_EOL . Helper::getColourString('✘ Please fix the errors above.', 'red') . PHP_EOL;

    exit(1);

} else {

    echo PHP_EOL;

    echo Helper::getColourString('✔ Looking good. ', 'green');
    echo Helper::getColourString('Have you tested everything?', 'gray') . PHP_EOL;

    // Exit with zero to allow the commit.
    exit(0);

}
