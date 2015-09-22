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
 * Rule 3: Capitalize the subject line
 *
 * <http://chris.beams.io/posts/git-commit/#capitalize>
 */
class SubjectLineCapitalReview extends AbstractMessageReview
{
    public function review(ReporterInterface $reporter, ReviewableInterface $commit)
    {
        if (!preg_match('/^[A-Z]/u', $commit->getSubject())) {
            $message = 'Subject line must begin with a capital letter';
            $reporter->error($message, $this, $commit);
        }
    }
}
