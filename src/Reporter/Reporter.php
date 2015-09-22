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

use StaticReview\Collection\IssueCollection;
use StaticReview\Issue\Issue;
use StaticReview\Review\ReviewInterface;
use StaticReview\Review\ReviewableInterface;

class Reporter implements ReporterInterface
{
    protected $issues;

    /**
     * Initializes a new instance of the Reporter class.
     *
     * @param  IssueCollection $issues
     * @return Reporter
     */
    public function __construct()
    {
        $this->issues = new IssueCollection();
    }

    public function progress($current, $total)
    {
        echo sprintf("Reviewing %d of %d.\r", $current, $total);
    }

    /**
     * Reports an Issue raised by a Review.
     *
     * @param  int                 $level
     * @param  string              $message
     * @param  ReviewInterface     $review
     * @param  ReviewableInterface $subject
     * @return Reporter
     */
    public function report($level, $message, ReviewInterface $review, ReviewableInterface $subject)
    {
        $issue = new Issue($level, $message, $review, $subject);

        $this->issues->append($issue);

        return $this;
    }

    /**
     * Reports an Info Issue raised by a Review.
     *
     * @param  string              $message
     * @param  ReviewInterface     $review
     * @param  ReviewableInterface $subject
     * @return Reporter
     */
    public function info($message, ReviewInterface $review, ReviewableInterface $subject)
    {
        $this->report(Issue::LEVEL_INFO, $message, $review, $subject);

        return $this;
    }

    /**
     * Reports an Warning Issue raised by a Review.
     *
     * @param  string              $message
     * @param  ReviewInterface     $review
     * @param  ReviewableInterface $subject
     * @return Reporter
     */
    public function warning($message, ReviewInterface $review, ReviewableInterface $subject)
    {
        $this->report(Issue::LEVEL_WARNING, $message, $review, $subject);

        return $this;
    }

    /**
     * Reports an Error Issue raised by a Review.
     *
     * @param  string              $message
     * @param  ReviewInterface     $review
     * @param  ReviewableInterface $subject
     * @return Reporter
     */
    public function error($message, ReviewInterface $review, ReviewableInterface $subject)
    {
        $this->report(Issue::LEVEL_ERROR, $message, $review, $subject);

        return $this;
    }

    /**
     * Checks if the reporter has revieved any Issues.
     *
     * @return IssueCollection
     */
    public function hasIssues()
    {
        return (count($this->issues) > 0);
    }

    /**
     * Gets the reporters IssueCollection.
     *
     * @return IssueCollection
     */
    public function getIssues()
    {
        return $this->issues;
    }
}
