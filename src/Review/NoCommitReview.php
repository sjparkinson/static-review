<?php

/*
 * This file is part of MainThread\StaticReview.
 *
 * Copyright (c) 2014-2015 Samuel Parkinson <sam.james.parkinson@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see http://github.com/sjparkinson/static-review/blob/master/LICENSE
 */

namespace MainThread\StaticReview\Review;

use MainThread\StaticReview\File\FileInterface;

/**
 * NoCommitReview that looks for the string "nocommit".
 *
 * @author Samuel Parkinson <sam.james.parkinson@gmail.com>
 */
class NoCommitReview extends AbstractReview
{
    /**
     * @inheritdoc
     *
     * @param FileInterface $file
     *
     * @return boolean
     */
    public function supports(FileInterface $file)
    {
        return (substr($file->getMimeType(), 0, 4) === 'text');
    }

    /**
     * @inheritdoc
     *
     * Uses `grep` to look for the string "nocommit" in the given file.
     *
     * @param FileInterface $file
     *
     * @return Result
     */
    public function review(FileInterface $file)
    {
        $this->processBuilder->setArguments([
            'grep',
            '--quiet',
            '--ignore-case',
            '--fixed-strings',
            '"nocommit"',
            $file->getReviewPath()
        ]);

        $process = $this->processBuilder->getProcess();
        $process->run();

        if ($process->isSuccessful()) {
            $this->resultBuilder->setFailed()
                                ->setFile($file)
                                ->setMessage('A "nocommit" string was found.');

            return $this->resultBuilder->getResult();
        }

        return $this->resultBuilder->setPassed()->setFile($file)->getResult();
    }
}
