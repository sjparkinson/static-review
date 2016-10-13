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

namespace StaticReview\Review\Composer;

use StaticReview\File\FileInterface;
use StaticReview\Reporter\ReporterInterface;
use StaticReview\Review\AbstractFileReview;
use StaticReview\Review\ReviewableInterface;

class ComposerSecurityReview extends AbstractFileReview
{
    /**
     * Check the composer.lock file for security issues.
     *
     * @param  FileInterface $file
     * @return bool
     */
    public function canReviewFile(FileInterface $file)
    {
        if ($file->getFileName() === 'composer.lock') {
            return true;
        }

        return false;
    }

    /**
     * Check the composer.lock file doesn't contain dependencies
     * with known security vulnerabilities.
     *
     * @param ReporterInterface $reporter
     * @param FileInterface     $file
     */
    public function review(ReporterInterface $reporter, ReviewableInterface $file)
    {
        $executable = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, 'vendor/bin/security-checker');

        $cmd = sprintf('%s security:check %s', $executable, $file->getFullPath());

        $process = $this->getProcess($cmd);
        $process->run();

        if (! $process->isSuccessful()) {
            $message = 'The composer project dependencies contain known vulnerabilities';
            $reporter->error($message, $this, $file);
        }
    }
}
