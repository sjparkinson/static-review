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

class WorkInProgressReview extends AbstractMessageReview
{
    public function review(ReporterInterface $reporter, ReviewableInterface $commit)
    {
        $fulltext = $commit->getSubject() . PHP_EOL . $commit->getBody();

        if (preg_match('/\bwip\b/i', $fulltext)) {
            $message = 'Do not commit WIP to shared branches';
            $reporter->error($message, $this, $commit);
        }
    }
}
