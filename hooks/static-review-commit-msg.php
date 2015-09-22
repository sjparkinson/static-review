#!/usr/bin/env php
<?php

/*
 * This file is part of StaticReview
 *
 * Copyright (c) 2015 Woody Gilk <@shadowhand>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see http://github.com/sjparkinson/static-review/blob/master/LICENSE
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

if (empty($argv[1]) || !is_file($argv[1])) {
    echo 'WARNING: Skipping commit message check because the Git hook was not ' . PHP_EOL
       . 'passed the commit message file path; normally `.git/COMMIT_EDITMSG`' . PHP_EOL;

    exit(1);
}

// Reference the required classes and the reviews you want to use.
use League\CLImate\CLImate;
use StaticReview\Issue\Issue;
use StaticReview\Reporter\Reporter;
use StaticReview\Review\Message\BodyLineLengthReview;
use StaticReview\Review\Message\SubjectImperativeReview;
use StaticReview\Review\Message\SubjectLineCapitalReview;
use StaticReview\Review\Message\SubjectLineLengthReview;
use StaticReview\Review\Message\SubjectLinePeriodReview;
use StaticReview\Review\Message\WorkInProgressReview;
use StaticReview\StaticReview;
use StaticReview\VersionControl\GitVersionControl;

$reporter = new Reporter();
$climate  = new CLImate();
$git      = new GitVersionControl();

$review   = new StaticReview($reporter);

// Add any reviews to the StaticReview instance, supports a fluent interface.
$review->addReview(new BodyLineLengthReview())
       ->addReview(new SubjectImperativeReview())
       ->addReview(new SubjectLineCapitalReview())
       ->addReview(new SubjectLineLengthReview())
       ->addReview(new SubjectLinePeriodReview())
       ->addReview(new WorkInProgressReview());

// Check the commit message.
$review->message($git->getCommitMessage($argv[1]));

// Check if any matching issues were found.
if ($reporter->hasIssues()) {
    $climate->out('')->out('');

    foreach ($reporter->getIssues() as $issue) {
        $climate->red($issue);
    }

    $climate->out('')->red('✘ Please fix the errors above using: git commit --amend');

    exit(0);

} else {
    $climate->green('✔ That commit looks good!');

    exit(0);
}
