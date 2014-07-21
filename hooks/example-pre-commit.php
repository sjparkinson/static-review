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
 * @see http://github.com/sjparkinson/static-review/blob/master/LICENSE
 */
$autoload = function($base) {
    require_once (file_exists($base . '/vendor/autoload.php'))
        ? $base . '/vendor/autoload.php'
        : realpath($base . '/../../autoload.php');
};

$autoload(realpath(__DIR__ . '/../'));

// Reference the required classes.
use StaticReview\StaticReview;
use StaticReview\Helper;
use StaticReview\Reporter\Reporter;

// Reference the reviews you want to use.
use StaticReview\Review\General\LineEndingsReview;

$reporter = new Reporter();
$review   = new StaticReview($reporter);

// Add any checks to the StaticReview instance, supports a fluent interface.
$review->addCheck(new LineEndingsReview());

// Review the staged files.
$review->review(Helper::getGitStagedFiles());

// Check if any issues were found.
// Exit with a non-zero to block the commit.
($reporter->hasIssues()) ? exit(1) : exit(0);
