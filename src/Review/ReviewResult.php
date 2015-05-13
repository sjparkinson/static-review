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
 * ReviewResult class.
 *
 * @author Samuel Parkinson <sam.james.parkinson@gmail.com>
 */
class ReviewResult
{
    const STATUS_PASSED = 1;

    const STATUS_FAILED = 2;

    /**
     * Creates a new instance of the ReviewResult class.
     *
     * @param integer         $status
     * @param FileInterface   $file
     * @param ReviewInterface $review
     * @param string          $message
     */
    public function __construct($status, FileInterface $file, ReviewInterface $review, $message = null)
    {
        $this->status = $status;
        $this->file = $file;
        $this->review = $review;
        $this->message = $message;
    }

    /**
     * Gets the results FileInterface.
     *
     * @return FileInterface
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Gets the results ReviewInterface.
     *
     * @return ReviewInterface
     */
    public function getReview()
    {
        return $this->review;
    }

    /**
     * Gets the results status.
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Gets the results message.
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Overrides the toString method.
     */
    public function __toString()
    {
        return sprintf(
            "%s: %s in %s",
            $this->getReview()->getName(),
            $this->getMessage(),
            $this->getFile()->getRelativePathname()
        );
    }
}
