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
$autoload = function($base) {
    require_once file_exists($base . '/vendor/autoload.php')
        ? $base . '/vendor/autoload.php'
        : $base . '/../../autoload.php';
};

$autoload(realpath(__DIR__ . '/../'));

// Reference the required classes.
use StaticReview\StaticReview;
use StaticReview\Helper;
use StaticReview\Reporter\Reporter;
use StaticReview\VersionControl\VersionControlFactory;

// Reference the reviews you want to use.
use StaticReview\Review\General\LineEndingsReview;

$reporter = new Reporter();
$review   = new StaticReview($reporter);

// Add any reviews to the StaticReview instance, supports a fluent interface.
$review->addReview(new LineEndingsReview);

$git = VersionControlFactory::build(VersionControlFactory::SYSTEM_GIT);

// Review the staged files.
$review->review($git->getStagedFiles());

echo PHP_EOL;

// Check if any issues were found.
// Exit with a non-zero to block the commit.
($reporter->hasIssues()) ? exit(1) : exit(0);
