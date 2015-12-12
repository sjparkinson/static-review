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

class ComposerSecurityReview extends AbstractReview
{
    /**
     * Check the composer.lock file for security issues.
     *
     * @param ReviewableInterface $file
     *
     * @return bool
     */
    public function canReview(ReviewableInterface $file)
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
     * @param ReporterInterface   $reporter
     * @param ReviewableInterface $file
     */
    public function review(ReporterInterface $reporter, ReviewableInterface $file)
    {
        $executable = 'vendor/bin/security-checker';

        $cmd = sprintf('%s security:check %s', $executable, $file->getFullPath());

        $process = $this->getProcess($cmd);
        $process->run();

        if (!$process->isSuccessful()) {
            $message = 'The composer project dependencies contain known vulnerabilities';
            $reporter->error($message, $this, $file);
        }
    }
}
