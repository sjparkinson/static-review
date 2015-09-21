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

namespace StaticReview\Reporter;

use StaticReview\Review\ReviewInterface;
use StaticReview\Review\ReviewableInterface;

interface ReporterInterface
{
    public function report($level, $message, ReviewInterface $review, ReviewableInterface $subject);

    public function info($message, ReviewInterface $review, ReviewableInterface $subject);

    public function warning($message, ReviewInterface $review, ReviewableInterface $subject);

    public function error($message, ReviewInterface $review, ReviewableInterface $subject);

    public function hasIssues();

    public function getIssues();
}
