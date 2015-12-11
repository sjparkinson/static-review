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

namespace StaticReview\Review;

use StaticReview\Commit\CommitMessageInterface;
use StaticReview\File\FileInterface;
use Symfony\Component\Process\Process;

abstract class AbstractMessageReview extends AbstractReview
{
    protected function canReviewFile(FileInterface $file)
    {
        return false;
    }

    protected function canReviewMessage(CommitMessageInterface $message)
    {
        return true;
    }
}
