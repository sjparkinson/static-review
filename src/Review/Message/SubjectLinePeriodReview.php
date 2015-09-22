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
 * Rule 4: Do not end the subject line with a period
 *
 * <http://chris.beams.io/posts/git-commit/#end>
 */
class SubjectLinePeriodReview extends AbstractMessageReview
{
    public function review(ReporterInterface $reporter, ReviewableInterface $commit)
    {
        if (substr($commit->getSubject(), -1) === '.') {
            $message = 'Subject line must not end with a period';
            $reporter->error($message, $this, $commit);
        }
    }
}
