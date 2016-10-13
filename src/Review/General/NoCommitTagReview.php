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

namespace StaticReview\Review\General;

use StaticReview\File\FileInterface;
use StaticReview\Reporter\ReporterInterface;
use StaticReview\Review\AbstractFileReview;
use StaticReview\Review\ReviewableInterface;

class NoCommitTagReview extends AbstractFileReview
{
    /**
     * Review any text based file.
     *
     * @link http://stackoverflow.com/a/632786
     *
     * @param  FileInterface $file
     * @return bool
     */
    public function canReviewFile(FileInterface $file)
    {
        $mime = $file->getMimeType();

        // check to see if the mime-type starts with 'text'
        return (substr($mime, 0, 4) === 'text');
    }

    /**
     * Checks if the file contains `NOCOMMIT`.
     *
     * @link http://stackoverflow.com/a/4749368
     */
    public function review(ReporterInterface $reporter, ReviewableInterface $file)
    {
        $cmd = sprintf('grep --fixed-strings --ignore-case --quiet "NOCOMMIT" %s', $file->getFullPath());

        $process = $this->getProcess($cmd);
        $process->run();

        if ($process->isSuccessful()) {
            $message = 'A NOCOMMIT tag was found';
            $reporter->error($message, $this, $file);
        }
    }
}
