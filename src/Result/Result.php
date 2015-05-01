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

namespace MainThread\StaticReview\Result;

use MainThread\StaticReview\File\FileInterface;
use MainThread\StaticReview\Review\ReviewInterface;

/**
 * ResultCollector class.
 *
 * @author Samuel Parkinson <sam.james.parkinson@gmail.com>
 */
class Result
{
    const STATUS_PASSED = 1;

    const STATUS_FAILED = 2;

    public function __construct($status, FileInterface $file, ReviewInterface $review, $message = null)
    {
        $this->status = $status;
        $this->file = $file;
        $this->review = $review;
        $this->message = $message;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function getReview()
    {
        return $this->review;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getMessage()
    {
        return $this->message;
    }
}
