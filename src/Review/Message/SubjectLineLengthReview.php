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

namespace StaticReview\Review\Message;

use StaticReview\Reporter\ReporterInterface;
use StaticReview\Review\AbstractMessageReview;
use StaticReview\Review\ReviewableInterface;

/**
 * Rule 2: Limit the subject line to 50 characters
 *
 * <http://chris.beams.io/posts/git-commit/#limit-50>
 */
class SubjectLineLengthReview extends AbstractMessageReview
{
    /**
     * @var integer Allowed length limit.
     */
    protected $maximum = 50;

    public function setMaximumLength($length)
    {
        $this->maximum = $length;
    }

    public function getMaximumLength()
    {
        return $this->maximum;
    }

    public function review(ReporterInterface $reporter, ReviewableInterface $commit)
    {
        if (strlen($commit->getSubject()) > $this->getMaximumLength()) {
            $message = sprintf(
                'Subject line is greater than %d characters',
                $this->getMaximumLength()
            );
            $reporter->error($message, $this, $commit);
        }
    }
}
