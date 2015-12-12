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

namespace StaticReview\Review\Composer;

use StaticReview\Reporter\ReporterInterface;
use StaticReview\Review\AbstractReview;
use StaticReview\Review\ReviewableInterface;

class ComposerLintReview extends AbstractReview
{
    /**
     * Lint only the composer.json file.
     *
     * @param ReviewableInterface $file
     *
     * @return bool
     */
    public function canReview(ReviewableInterface $file)
    {
        // only if the filename is "composer.json"
        return $file->getFileName() === 'composer.json';
    }

    /**
     * Check the composer.json file is valid.
     *
     * @param ReporterInterface   $reporter
     * @param ReviewableInterface $file
     */
    public function review(ReporterInterface $reporter, ReviewableInterface $file)
    {
        $cmd = sprintf('composer validate %s', $file->getFullPath());

        $process = $this->getProcess($cmd);
        $process->run();

        if (!$process->isSuccessful()) {
            $message = 'The composer configuration is not valid';
            $reporter->error($message, $this, $file);
        }
    }
}
